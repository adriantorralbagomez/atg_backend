<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pedidostock;

/**
 * PedidostockSearch represents the model behind the search form of `app\models\Pedidostock`.
 */
class PedidostockSearch extends Pedidostock
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'proveedor_material_id', 'cantidad'], 'integer'],
            [['fecha', 'estado'], 'safe'],
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
        $query = Pedidostock::find();

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

        // grid filtering conditions
        $query->andFilterWhere(['like', 'id', $this->id])
                ->andFilterWhere(['like', 'proveedor_material_id', $this->getIdFromName($this->proveedor_material_id)])
                ->andFilterWhere(['like', 'cantidad', $this->cantidad])
                ->andFilterWhere(['like', 'fecha', $this->fecha])
                ->andFilterWhere(['like', 'estado', $this->estado]);
        return $dataProvider;
    }
}
