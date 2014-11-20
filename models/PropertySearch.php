<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Property;

/**
 * PropertySearch represents the model behind the search form about `app\models\Property`.
 */
class PropertySearch extends Property
{
    public function rules()
    {
        return [
            [['id', 'createdbyref', 'destroyedbyref', 'propertyownerref', 'locationref', 'type', 'genre', 'lastpaymentdate', 'rentalperiod'], 'integer'],
            [['name', 'description', 'datecreated', 'datedestroyed', 'code'], 'safe'],
            [['latepaymentcharge', 'lat', 'long'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Property::find();
        if (key_exists('ownerid', $params))
        {
            $query->andFilterWhere(['propertyownerref' => $params['ownerid']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->filterWhere([
            'locationref' => $this->locationref,
            'type' => $this->type,
            'genre' => $this->genre
        ]);
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'code', $this->code]);
            
        return $dataProvider;
    }
}
