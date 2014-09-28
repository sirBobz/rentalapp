<?php

namespace app\controllers;

use \Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\Payment;
use app\models\PaymentSimulateForm;
use app\services\IReceiveIpnPayment;
use app\services\IProcessPayment;
use app\services\PaymentReceivedConsumer;

class PaymentController extends \yii\web\Controller
{
    protected $ipnService;
    protected $paymentProcessor;

    public function __construct($id, $module, IReceiveIpnPayment $ipnSvc, 
            IProcessPayment $paymentProcessor, $config = array()) {
        $this->ipnService = $ipnSvc;
        $this->paymentProcessor = $paymentProcessor;
        parent::__construct($id, $module, $config);
    }
    
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @permission assignpayment
     */
    public function actionAssign($id)
    {
        $payment = Payment::findOne(['id' => $id, 
            'paymentstatus' => Payment::STATUS_PAYMENT_PENDING_ASSIGNMENT]);
        $tenants = \app\models\Tenant::find()->where(['entitytype' => 'Tenant'])->
        select(['name as value', 'id'])->asArray()->all();
        
        if ($payment == NULL)
            throw new NotFoundHttpException('The payment is not in an assignable state.');
        
        $post = Yii::$app->request->post();
        if(!empty($post))
        {
            $rental = \app\models\Rental::findOne($post['rentalaccount']);
            $rental->creditOnManualPaymentAssignment($payment->amount, $payment->id);
            
            //redirect to success message
        }
        
        return $this->render('assign', [
            'model' => $payment,
            'tenants' => $tenants
        ]);
    }

    /**
     * @permission viewpayment
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @permission reversepayment
     */
    public function actionReverse($id)
    {
        $payment = Payment::findOne(['id' => $id, 
            'paymentstatus' => Payment::STATUS_PAYMENT_PENDING_ASSIGNMENT]);
        
        if ($payment == NULL)
            throw new NotFoundHttpException('The payment is not in a reversible state.');
        
        $post = Yii::$app->request->post();
        if(!empty($post))
        {
            $payment->reverse();
            $payment->save();
            
            //redirect to success message
        }
        
        return $this->render('reverse',[
            'model' => $payment
        ]);
    }

    /**
     * @permission simulatepayment
     */
    public function actionSimulate()
    {
        $model = new PaymentSimulateForm();
        $model->receiptnumber = uniqid();
        $model->paymentdate = date('Y-m-d H:i:s');
        $model->ipnid = 1;
        
        if ($model->load(Yii::$app->request->post())) {
            $payment = $this->ipnService->parse($model->attributes);
            
            $obj = new PaymentReceivedConsumer($this->paymentProcessor);
            $payment->on(\app\models\Payment::EVENT_PAYMENT_COMPOSED, [$obj, 'processEvent']);
            $payment->notifyUponCreation();
        
            $this->ipnService->Acknowledge($payment);
            
        }
        else {
            return $this->render('simulate', [
                'model' => $model
            ]);
        }
                
        //$formData = \Yii::$app->request->queryParams;
        
        
    }

    /**
     * @permission uploadpayment
     */
    public function actionUpload()
    {
        $model = new \app\models\PaymentUploadForm;
        if($model->load(Yii::$app->request->post()))
        {
            $model->file = \yii\web\UploadedFile::getInstance($model, 'file');
            $path = Yii::$app->basePath . '/uploads/'. time() . "_" . $model->file->baseName . "." . 
                    $model->file->extension;
            $model->file->saveAs($path, TRUE);
            
            $obj = new PaymentReceivedConsumer($this->paymentProcessor);
            $skipRow = TRUE;
            $handle = fopen($path, "r");
            while (($data = fgetcsv($handle)) !== FALSE)
            {
                $numCols = count($data);               
                
                //if numcols != 11 SKIP
                if($numCols != 11)
                    continue;
                
                if (substr($data[0], 0, 7) == "Receipt")
                {
                    $skipRow = FALSE;
                }
                if(!$skipRow && (substr($data[0], 0, 7) != "Receipt"))
                {
                    $payment = new \app\models\Payment($data[5], NULL, substr($data[9], 12, strlen($data[9])), $data[1], 
                            substr($data[9], 0, 10), $data[10], $data[0]);
                    $payment->save();
                    
                    $payment->on(\app\models\Payment::EVENT_PAYMENT_COMPOSED, [$obj, 'processEvent']);
                    $payment->notifyUponCreation();
                }
            }
            fclose($handle);
            
            echo "payment uploaded successfully";
        }
        
        return $this->render('upload', ['model' => $model]);
    }
    
    /**
     * @permission paymentforactioning
     */
    public function actionListpendingassignment()
    {
        $query = \app\models\Payment::find();
        $query->andFilterWhere(['paymentstatus' => 1]);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $this->render('listpendingassignment', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
