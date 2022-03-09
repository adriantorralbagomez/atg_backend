<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Caja;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class CajaSearch extends Caja
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['orden_id','sector_id','tipocaja_id','etiqueta_id','estado'], 'safe'],
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
        $query = Caja::find();

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
       
        $query->andFilterWhere(['like', 'orden_id', $this->orden])
                ->andFilterWhere(['like', 'sector_id', $this->getIdFromName($this->sector)])
                ->andFilterWhere(['like', 'tipocaja_id', $this->getIdFromName($this->tipocaja)])
                ->andFilterWhere(['like', 'estado', $this->estado])
                ->andFilterWhere(['like', 'etiqueta_id', $this->etiqueta]);

        return $dataProvider;
    }
}
