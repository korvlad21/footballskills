<?php

namespace app\models\search;

use yii\base\Model;
use app\models\Characteristic;
use yii\data\ActiveDataProvider;
use app\models\CharacteristicPlayer;


/**
 * CharacteristicSearch represents the model behind the search form of `app\models\CharacteristicPlayer`.
 */
class CharacteristicPlayerSearch extends CharacteristicPlayer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'characteristic_id', 'player_id'], 'integer'],
            [['value'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
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
        $query = CharacteristicPlayer::find()->with(['player', 'characteristic']);
            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        foreach($this->getAttributes() as $attr => $value){
            $this->$attr = trim($value);
        }
        $query->andFilterWhere(['=', 'id', $this->id]);
        $query->andFilterWhere(['=', 'characteristic_id', $this->characteristic_id]);

        return $dataProvider;
    }
}
