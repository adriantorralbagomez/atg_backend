<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Parcela;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class ParcelaSearch extends Parcela
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['variedad_id','finca_id','nombre','cant_total','cant_disp'], 'safe'],
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
        $query = Parcela::find();

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
       //'variedad_id','finca_id','nombre','cant_total','cant_disp'
        $query->andFilterWhere(['like', 'variedad_id', $this->getIdFromName($this->variedad)])
                ->andFilterWhere(['like', 'finca_id', $this->getIdFromName($this->finca)])
                ->andFilterWhere(['like', 'nombre', $this->nombre])
                ->andFilterWhere(['like', 'cant_total', $this->cant_total])
                ->andFilterWhere(['like', 'cant_disp', $this->cant_disp]);

        return $dataProvider;
    }
}
