<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\OrdenPedidoinfo;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class OrdenPedidoinfoSearch extends OrdenPedidoinfo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id','pedidoinfo_id','variedad_id','cantidad'], 'safe'],
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
        $query = OrdenPedidoinfo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 10 ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
       
        //'orden_id','pedidoinfo_id','variedad_id','cantidad'

        $query->andFilterWhere(['like', 'orden_id', $this->orden])
                ->andFilterWhere(['like', 'pedidoinfo_id', $this->pedidoinfo])
                ->andFilterWhere(['like', 'variedad_id', $this->variedad])
                ->andFilterWhere(['like', 'cantidad', $this->cantidad]);

        return $dataProvider;
    }
}
