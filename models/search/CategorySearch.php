<?php

namespace app\models\search;

use app\models\Category;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * CategorySearch represents the model behind the search form of `app\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name'], 'string'],
            [['parent_id'], 'integer'],

            [['is_delete'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios(): array
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
        $query = Category::find();

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
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['=', 'parent_id', $this->parent_id]);
        $query->andFilterWhere(['=', 'is_delete', 0]);
        
        return $dataProvider;
    }
}
