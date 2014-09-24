<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Rental;

/**
 * SuiteSearch represents the model behind the search form about `app\models\Suite`.
 */
class RentalAccountSearch extends Rental
{
    public function rules()
    {
        return [
            [['id', 'rentalstatus'], 'integer'],
            //[['amountperperiod', 'depositamount', 'currentbalance'], 'number'],
            [['accountnumber', /*'rentalstatus', 'billingstartdate',*/ 'tenantname', 'unitname'], 'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Rental::find()->select(['rental.id', 'rental.rentalstatus', 'rental.amountperperiod', 
            'rental.depositamount', 'rental.currentbalance', 'rental.accountnumber', 
            'rental.billingstartdate', 'e.name as tenantname', 'u.name as unitname'])
            ->innerJoin('entity e', 'rental.tenantref = e.id')
            ->innerJoin('unit u', 'rental.unitref = u.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->rentalstatus = $params['RentalAccountSearch']['rentalstatus'];
        
        $query->andFilterWhere([
            'id' => $this->id,
            'rentalstatus' => $this->rentalstatus,
            /*'amountperperiod' => $this->amountperperiod,
            'depositamount' => $this->depositamount,
            'currentbalance' => $this->currentbalance*/
        ]);
        
        $query->andFilterWhere(['like', 'rental.accountnumber', $this->accountnumber])
                ->andFilterWhere(['like', 'e.name', $this->tenantname])
                //->andFilterWhere(['like', 'u.name', $this->unitname])
                ;
        

        return $dataProvider;
    }
}
