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
    public $stock_act;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tipocaja_id', 'nombre', 'stock_min', 'stock_act', 'descripcion'], 'safe'],
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
        $query = Material::find()->alias('mat');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 10 ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipocaja_id' => $this->tipocaja_id,
        ]);
        $stock_act = $this->stock_act;
        $query->leftjoin("proveedor_material as provmat", "mat.id = provmat.material_id");
        //select sum(provmat.stock_act)
        $sum_stock_act = '(select sum(provmat.stock_act))';
        switch ($stock_act) {
            case "N":
                //No hay suficiente stock
                $query->where('('.$sum_stock_act.' = 0) or (
                    ('.$sum_stock_act.' < mat.stock_min) or (
                        ('.$sum_stock_act.' > mat.stock_min) and (((('.$sum_stock_act.' - mat.stock_min) * 100) / '.$sum_stock_act.') < 30)
                    )
                )');
                break;
            case "P":
                //Queda poco stock
                $query->where('('.$sum_stock_act.' <> 0) and (
                    ('.$sum_stock_act.' = mat.stock_min) or  (
                        ('.$sum_stock_act.' > mat.stock_min) and (((('.$sum_stock_act.' - mat.stock_min) * 100) / '.$sum_stock_act.') BETWEEN 30 AND 60)
                    )
                )');
                break;
            case "S":
                //Suficiente stock
                $query->where('('.$sum_stock_act.' <> 0) and (
                    ('.$sum_stock_act.' > mat.stock_min) and (((('.$sum_stock_act.' - mat.stock_min) * 100) / '.$sum_stock_act.') > 60)
                )');
                break;
        }
        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'stock_min', $this->stock_min]);

        return $dataProvider;
    }
}
