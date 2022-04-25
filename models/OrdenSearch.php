<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Orden;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class OrdenSearch extends Orden
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lote','variedad_id','finca_id','parcela_id','fecha','cantidad','estado', 'coste'], 'safe'],
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
        $query = Orden::find();

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
        
        $query->andFilterWhere(['like', 'lote', $this->lote])
                ->andFilterWhere(['like', 'variedad_id', $this->getIdFromName($this->variedad)])
                ->andFilterWhere(['like', 'finca_id', $this->getIdFromName($this->finca)])
                ->andFilterWhere(['like', 'parcela_id', $this->parcela])
                ->andFilterWhere(['like', 'fecha', $this->fecha])
                ->andFilterWhere(['like', 'cantidad', $this->cantidad])
                ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
