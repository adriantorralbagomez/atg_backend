<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pedido;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class PedidoSearch extends Pedido
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cliente_id','cantidad','estado','fecha'], 'safe'],
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
        $query = Pedido::find();

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

         $query->andFilterWhere(['like', 'cliente_id', $this->cliente])
                 ->andFilterWhere(['like', 'cantidad', $this->cantidad])
                 ->andFilterWhere(['like', 'estado', $this->estado])
                 ->andFilterWhere(['like', 'fecha', $this->fecha]);

        return $dataProvider;
    }
}
