<?php

class Genre extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
    }


    /**********************
     * ジャンルマスターデータの取得
     */
    public  function __getGenreMaster(){

      $where = array(
          "status"=>1
      );
      $query = $this->db->get_where("master_genre",$where);
      $result = $query->result();
      return $result;
    }
    /*******************
     * ジャンルデータの取得
     */
    public function __getGenreData($id){
      $where = array(
        "manga_id"=>$id
      );
      $query = $this->db->get_where("genre",$where);
      $result = $query->result();
      $list = [];
      foreach($result as $value){
        $list[$value->master_genre_id] = $value;
      }
      return $list;
    }


    /***************************
     * ジャンルの登録
     */
    public function __setGenreData($lastid){
      //登録の前にデータの削除を行う
      $where[ 'manga_id' ] = $lastid;
      if(!$this->db->delete('genre',$where)){
        return false;
      }
      foreach($this->input->post('genre') as $key=>$value){
        $data = [];
        $data['user_id'] = $this->input->post("user_id");
        $data['master_genre_id'] = $key;
        $data['manga_id'] = $lastid;
        $data['regist_ts'] = date("Y-m-d H:i:s");
        $flg = $this->db->insert('genre', $data);
        if(!$flg){
          return false;
        }
      }
      return true;
    }
  }