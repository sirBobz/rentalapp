<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Login;

/**
 * SuiteSearch represents the model behind the search form about `app\models\Suite`.
 */
class UserSearch extends Login
{
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['emailaddress'], 'safe']
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Login::find()->select(['id', 'datecreated', 'emailaddress', 'status']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        //$this->rentalstatus = $params['RentalAccountSearch']['rentalstatus'];
        
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status
        ]);
        
        $query->andFilterWhere(['like', 'emailaddress', $this->emailaddress]);
        
        return $dataProvider;
    }
}
