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
        $query = Material::find();

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
        $materiales = Material::find()->all();
        //$query->join("INNER JOIN", "proveedor_material as provmat", " mat.id = provmat.material_id");
        $stock_total_mat = 0;
        foreach ($materiales as $mat) {
            $stock_total_mat = Material::calc_stock_act($mat["id"]);
            switch ($stock_act) {
                case "LightCoral":
                    //No hay suficiente stock
                    $query->where('('.$stock_total_mat.' = 0) 
                    or ('.$stock_total_mat.' < stock_min) 
                    or (('.$stock_total_mat.' > stock_min) 
                    and (((('.$stock_total_mat.' - stock_min) * 100) / '.$stock_total_mat.') <= 30))');
                    break;
                case "Gold":
                    //Queda poco stock
                    $query->where('('.$stock_total_mat.' <> 0) 
                    and (('.$stock_total_mat.' = stock_min) 
                    or  ('.$stock_total_mat.' > stock_min) 
                    and (((('.$stock_total_mat.' - stock_min) * 100) / '.$stock_total_mat.') <= 60))');
                    break;
                case "LightGreen":
                    //Suficiente stock
                    $query->where('('.$stock_total_mat.' <> 0) 
                    and (('.$stock_total_mat.' > stock_min) 
                    and (((('.$stock_total_mat.' - stock_min) * 100) / '.$stock_total_mat.') > 60))');
                    break;
            }
        }
        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'descripcion', $this->descripcion])
            ->andFilterWhere(['like', 'stock_min', $this->stock_min]);

        return $dataProvider;
    }
}
