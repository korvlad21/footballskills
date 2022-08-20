<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Player;

/**
 * PlayerSearch represents the model behind the search form of `app\models\Player`.
 */
class PlayerSearch extends Player
{

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'position'], 'integer'],
            [['surname', 'name', 'otchestvo'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Player::find()->where(['is_delete' => 0]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        foreach($this->getAttributes() as $attr => $value){
            $this->$attr = trim($value);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'date' => $this->date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'Player_category_id', $this->Player_category_id])
            ->andFilterWhere(['like', 'text', $this->text]);

        if (!isset($params['sort']) && empty($params['sort'])) {
            $query->orderBy('date DESC');
        }


        return $dataProvider;
    }
}
