<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Unit;

/**
 * SuiteSearch represents the model behind the search form about `app\models\Suite`.
 */
class UnitSearch extends Unit
{
    public function rules()
    {
        return [
            [['id', 'propertyref'], 'integer'],
            [['name', 'propertyname'], 'safe'],
            [['isavailable'], 'boolean'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Unit::find()->select(['unit.id', 'unit.name', 'unit.isavailable', 'unit.description', 
            'p.name as propertyname'])
                ->innerJoin('property p', 'unit.propertyref = p.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'propertyref' => $this->propertyname,
            'isavailable' => $this->isavailable,
        ]);
        
        $query->andFilterWhere(['like', 'unit.name', $this->name]);
        //print_r($query->all());
        //die();

        return $dataProvider;
    }
}
