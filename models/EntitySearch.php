<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Entity;

/**
 * EntitySearch represents the model behind the search form about `app\models\Entity`.
 */
class EntitySearch extends Entity
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'phonenumber', 'emailaddress', 'datecreated', 'type'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Entity::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'datecreated' => $this->datecreated,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phonenumber', $this->phonenumber])
            ->andFilterWhere(['like', 'emailaddress', $this->emailaddress])
            ->andFilterWhere(['like', 'type', $this->type]);

        return $dataProvider;
    }
}
