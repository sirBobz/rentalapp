<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Latepaymentrentallog;

/**
 * LatePaymentRentalLogSearch represents the model behind the search form about app\models\Latepaymentrentallog.
 */
class LatePaymentRentalLogSearch extends Latepaymentrentallog
{
    public function rules()
    {
        return [
            [['amountcharged'], 'number'],
            [['datecreated'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Latepaymentrentallog::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            //return $dataProvider;
        }
        
        $query->andWhere([
            'year' => date('Y'),
            'month' => (date('m') < 10) ? substr(date('m'), 1) : date('m') ,
            
        ]);
        /*$query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phonenumber', $this->phonenumber])
            ->andFilterWhere(['like', 'emailaddress', $this->emailaddress]);*/
        
        return $dataProvider;
    }
}
?>