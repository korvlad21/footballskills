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
    public $birthday_start;
    public $birthday_end;

    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'position'], 'integer'],
            [['surname', 'name', 'otchestvo', 'birthday_start', 'birthday_end'], 'safe'],
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
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
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
            // 'date' => $this->date,
        ]);
        if ( ! empty( $this->birthday_start ) 
            && ! empty( $this->birthday_end ) 
        )
        {
            $query->andFilterWhere(['and',
            ['>=', 'birthday', $this->birthday_start],
            ['<=', 'birthday',  $this->birthday_end]
            ]);
        }
        
        $query->andFilterWhere(['like', 'surname', $this->surname])
        ->andFilterWhere(['like', 'name', $this->name])
        ->andFilterWhere(['like', 'otchestvo', $this->otchestvo])
            ->andFilterWhere(['=', 'position', $this->position]);

        if (!isset($params['sort']) && empty($params['sort'])) {
            $query->orderBy('id DESC');
        }


        return $dataProvider;
    }
}
