<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProveedorMaterial;

/**
 * ProveedorMaterialSearch represents the model behind the search form of `app\models\ProveedorMaterial`.
 */
class ProveedorMaterialSearch extends ProveedorMaterial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'material_id', 'proveedor_id', 'stock_min', 'stock_act','nombre', 'precio'], 'safe'],
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
    //Devuelve la id de la tabla seleccionada
    public function getIdFromName($data){
        if($data != ""){
            return $data->id;
        }
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
        $query = ProveedorMaterial::find();

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

        //Filtrar stock actual
        $stock_act = $this->stock_act;
        
        switch ($stock_act) {
            case "LightCoral":
                //No hay suficiente stock
                $query->where('(stock_act = 0) or (stock_act < stock_min) or ((stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) <= 30))');
                break;
            case "Gold":
                //Queda poco stock
                $query->where('(stock_act <> 0) or ((stock_act = stock_min) or  (stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) <= 60))');
                break;
            case "LightGreen":
                //Suficiente stock
                $query->where('(stock_act <> 0) or (stock_act > stock_min) and ((((stock_act - stock_min) * 100) / stock_act) > 60)');
                break;
        }
        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'material_id', $this->getIdFromName($this->material)])
                ->andFilterWhere(['like', 'proveedor_id', $this->getIdFromName($this->proveedor)])
                ->andFilterWhere(['like', 'stock_min', $this->stock_min])
                ->andFilterWhere(['like', 'precio', $this->precio]);

        return $dataProvider;
    }
}
