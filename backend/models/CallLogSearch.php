<?php

namespace backend\models;

//use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

class CallLogSearch extends Model
{
    public $cid;
    public $queue_num;
    public $date_call;
    public $main_cid;
    public $main_did;
    public $did;
    public $dstatus;
    public $operator_id;
    
    public function rules()
    {
        // only fields in rules() are searchable
        return [
            //[['id'], 'integer'],
            [['dstatus','operator_id'], 'safe'],
            [['main_cid','main_did','did'], 'safe'],
            [['cid','queue_num','date_call'],'safe']
        ];
    }

    public function search($params)
    {

        $callsQuery = new Query();
        
        $callsQuery->select('c.logc_id,
                             c.cid main_cid,
                             c.did main_did,
                             c.date_call,
                             ci.did,
                             ci.date_call as ci_date_call,
                             ci.date_answer,
                             ci.date_hangup,
                             ci.dial_status,
                             ref.value as dstatus,
                             ci.queue_num,
                             ci.uid,
                             o.operator_name')
                ->from('log_calls c')
                ->leftJoin('log_calls_details ci','c.guid = ci.guid')
                ->leftJoin('ref_commons ref','ref.addition = ci.dial_status and ref.common = \'DIALSTATUS\' ')
                ->leftJoin('ref_operators o','ci.operator_id = o.operator_id');
        
        $dataProvider = new ActiveDataProvider([
            'query' => $callsQuery,
            'pagination' => [
                            'pageSize' => 40,
                        ],
                        'sort' => [
                            'attributes' => [
                                'date_call',
                                'ci_date_call',
                                //'main_cid',
                                //'main_did',
                                ],
                            'defaultOrder' => [
                                'date_call' => SORT_DESC,
                                
                                ]
                            ],
        ]);

        // load the search form data and validate
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        // adjust the query by adding the filters
        /*$callsQuery->andFilterWhere(['ci.cid' => $this->cid]);
        $callsQuery->andFilterWhere(['ci.queue_num' => $this->queue_num]);*/
        
        $callsQuery->andFilterWhere(['like','ci.queue_num', $this->queue_num]);
        $callsQuery->andFilterWhere(['like','c.cid', $this->main_cid]);
        $callsQuery->andFilterWhere(['like','c.date_call', $this->date_call ]);
        $callsQuery->andFilterWhere(['like','c.did', $this->main_did ]);
        $callsQuery->andFilterWhere(['like','ci.did', $this->did ]);
        $callsQuery->andFilterWhere(['ci.dial_status' => $this->dstatus ]);
        $callsQuery->andFilterWhere(['ci.operator_id' => $this->operator_id ]);
        
        return $dataProvider;
    }
}