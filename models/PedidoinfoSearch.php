<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Pedidoinfo;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class PedidoinfoSearch extends Pedidoinfo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pedido_id','cantidad'], 'safe'],
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
    public function search($params, $where = "")
    {
        $query = Pedidoinfo::find();

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
       
        $query->andFilterWhere(['like', 'pedido_id', $this->pedido])
                ->andFilterWhere(['like', 'cantidad', $this->cantidad]);
        
                if($where){
                    $query->andWhere($where);
                }

        return $dataProvider;
    }
}
