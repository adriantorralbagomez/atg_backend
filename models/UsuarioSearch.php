<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Usuario;
use yii\data\ActiveDataProvider;

/**
 * UsuarioSearch represents the model behind the search form of `app\models\Entradas`.
 */
class UsuarioSearch extends Usuario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'usuario', 'nombre', 'mail_corp', 'mail_per','rol','telefono'], 'safe'],
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
        $query = Usuario::find();

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
       
        //if(!hasrole('A'))
         //   $query->andFilterWhere(['id'=>Yii::$app->user->id]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'usuario', $this->usuario])
            ->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['=', 'rol', $this->rol])
            ->andFilterWhere(['like', 'mail_corp', $this->mail_corp])
            ->andFilterWhere(['like', 'mail_per', $this->mail_per]);

        return $dataProvider;
    }
}
