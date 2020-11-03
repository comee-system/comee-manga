<?php

class Expression extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
    }


    /**********************
     * 表現マスターデータの取得
     */
    public  function __getMasterExpresstionData(){

      $where = array(
          "status"=>1
      );
      $query = $this->db->get_where("master_Expression",$where);
      $result = $query->result();
      return $result;
    }



    /***************************
     * 表現内容の登録
     */
    public function __setExpressionData($lastid){
      //登録の前にデータの削除を行う
      $where[ 'manga_detail_id' ] = $lastid;
      if(!$this->db->delete('expression',$where)){
        return false;
      }
      foreach($this->input->post('expression') as $key=>$value){
        $data = [];
        $data['master_expression_id'] = $key;
        $data['manga_detail_id'] = $lastid;
        $data['regist_ts'] = date("Y-m-d H:i:s");
        $flg = $this->db->insert('expression', $data);
        if(!$flg){
          return false;
        }
      }
      return true;
    }
  }