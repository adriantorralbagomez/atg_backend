<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Material;
use yii\data\ActiveDataProvider;

/**
 * MaterialSearch represents the model behind the search form of `app\models\Material`.
 */
class MaterialSearch extends Material
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tipocaja_id', 'stock_min', 'stock_act','nombre', 'descripcion'], 'safe'],
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
        $query = Material::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        $stock_act = $this->stock_act;
        //CASI TERMINADO
        switch ($stock_act) {
            case "LightCoral":
                //No hay suficiente stock
                $query->where('(stock_act < stock_min) or ((stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) <= 30))');
                break;
            case "Gold":
                //Queda poco stock
                $query->where('(stock_act = stock_min) or  (stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) <= 60)');
                break;
            case "LightGreen":
                //Suficiente stock
                $query->where('(stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) > 80)');
                break;
            
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipocaja_id' => $this->tipocaja_id,
            'stock_min' => $this->stock_min,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion]);

        return $dataProvider;
    }
}
