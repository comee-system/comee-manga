<?php

class Tag extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
    }


    /**********************
     * タグの登録
     */
    public  function __setTag(){
      
      $data = array(
        'user_id' => $this->input->post('user_id'),
        'name' => $this->input->post("name"),
        'regist_ts'=>date('Y-m-d H:i:s')
      );
      $this->db->insert('master_tag', $data);
      
      return true;
      
    }
    
    /**********************
     * タグマスターの取得
     */
    public  function __getTagMaster(){
      $data = array(
        'user_id' => $this->input->post('user_id'),
        'status' => 1,
      );
      $query = $this->db->get_where('master_tag', $data);
      $result = $query->result();
      return $result;
      
    }
    /***********************
     * タグデータの取得
     */
    public function __getTagData($id){
      $data = array(
        'manga_id' => $id
      );
      $query = $this->db->get_where('tag', $data);
      $result = $query->result();
      $list = [];
      foreach($result as $value){
        $list[$value->master_tag_id]=$value;
      }
      return $list;

    }
    /**********************
     * 連載へタグの登録
     * type:詳細画面から利用：detail
     */
    public  function __setTagData($lastid,$type = ""){
      //登録の前にデータの削除を行う
      if($type == "detail"){
        $where[ 'manga_detail_id' ] = $lastid;
      }else{
        $where[ 'manga_id' ] = $lastid;
      }
      if(!$this->db->delete('tag',$where)){
        return false;
      }
      foreach($this->input->post('tag') as $key=>$value){
        $data = [];
        $data['user_id'] = $this->input->post("user_id");
        $data['master_tag_id'] = $key;
        if($type == "detail"){
          $data['manga_detail_id'] = $lastid;
        }else{
          $data['manga_id'] = $lastid;
        }
        $data['regist_ts'] = date("Y-m-d H:i:s");
        $flg = $this->db->insert('tag', $data);
        if(!$flg){
          return false;
        }
      }
      return true;
      
    }


  }