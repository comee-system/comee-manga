<?php

class User extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
    }

    /******************
     * ログインチェックの実行
     */
    function loginCheck(){
      $where = [
        'email'=>$this->input->post("email"),
        'status'=>1,
        'type'=>1
      ];
      $query = $this->db->get_where("user",$where);
      $result = $query->result();
      $password = "";
      if(isset( $result[0]->password )){
        $password = $result[0]->password;
      }
  
      if (password_verify($this->input->post('password'), $password)) {
        //ログインデータの保存
        $this->__setLogin($result);
        return true;
      }
      
      return false;
    }

    /**********************
     * ログインデータの保存
     */
    public  function __setLogin($data){

      $data = array(
          "id"=>$data[0]->id,
          'language' => "ja",
          'login' => "on"
      );
      $this->session->set_userdata($data);

    }
    /********************
     * ログアウト
     */
    public function logout(){
        $data = array(
          "id"=>"",
          'language' => "",
          'login' => ""
        );
        $this->session->set_userdata($data);
    }
    /*****************
     * ユーザーデータ取得
     */
    public function ___getUserData($id){
      $where = [];
      $where[ 'id' ] = $id;
      $query = $this->db->get_where("User",$where);
      $result = $query->result();
      return $result[0];
    }
    /****************************
     * ユーザデータ取得
     */
    public function ___getUserLists($limits=[]){


      $limit = $offset = "";
      if(isset($limits['limit'])) $limit = $limits['limit'];
      if(isset($limits['offset'])) $offset = $limits['offset'];
      $where = [
        'status'=>1
      ];
      if($limit && $offset){
        $query = $this->db->get_where("view_user_manga",$where, $limit, $offset);
      }else{
        $query = $this->db->get_where("view_user_manga",$where);      
      }
      $result = $query->result();
      
      return $result;
    }

    /**********************
     * ユーザ登録エラーチェック
     */
    public function __error(){
      $id = $this->input->post("id");
      $email = $this->input->post("email");
      $return = []; 
      if(!$email){
        $return[] = "メールアドレスが入力されていません。";
      }else{
        $pattern = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/";
        if(!preg_match($pattern, $email )){
          $return[] = "メールアドレスに誤りがあります。";
        }
      }

      $password = $this->input->post("password");
      if($id > 0 && strlen($password) == 0){
        //idかつパスワードが空欄時はチェックを行わない
        $password = "";
      }else{

        if(!$password){
          $return[] = "パスワードが入力されていません。";
        }else{
          if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
            $return[] = "パスワードは半角英数字のみとなります。";
          }
          if(strlen($password) < 8 ){
            $return[] = "パスワードは8文字以上で入力してください。";
          }

        }
      }
      
      $userid = $this->input->post("userid");
      
      if(!$userid){
        $return[] = "ユーザIDが入力されていません。";
      }else{
        if (!preg_match("/^[a-zA-Z0-9]+$/", $userid)) {
          $return[] = "ユーザIDは半角英数字のみとなります。";
        }
        if(strlen($userid) < 4 ){
          $return[] = "ユーザIDは4文字以上で入力してください。";
        }
        if(!$this->__checkUserID($id)){
          $return[] = "ユーザIDが重複しています。";
        }
        
      }
      $username = $this->input->post("username");
      if(!$username){
        $return[] = "ユーザネームが入力されていません。";
      }
      $usernamekana = $this->input->post("usernamekana");
      if(!$usernamekana){
        $return[] = "フリガナが入力されていません。";
      }
      $y = sprintf("%d",$this->input->post("birth_y"));
      $m = sprintf("%d",$this->input->post("birth_m"));
      $d = sprintf("%d",$this->input->post("birth_d"));
      if(!checkdate($m,$d,$y)){
        $return[] = "生年月日に誤りがあります。";
      }


      return $return;
    }
    /************
     * ユーザID重複確認
     */
    public function __checkUserID($id){
      $where = [];
      $where[ 'status' ] = 1;
      $where[ 'userid' ] = $this->input->post("userid");
      $this->db->select(["id","userid"]);
      $query = $this->db->get_where('user',$where)->result();
      
      //idがあるときにデータの重複時に自身のIDのときはtrueを返す
      if($id > 0 && $id == $query[0]->id){
        return true;
      }else
      if(count($query) < 1){
        return true;
      }
        return false;
    }
    /************
     * ユーザ削除確認
     */
    public function __delete($id){
      $where = [];
      $where[ 'status' ] = 1;
      $where[ 'id' ] = $id;
      $this->db->select(["id","userid"]);
      $query = $this->db->get_where('user',$where)->result();
      //idがあるときにデータの重複時に自身のIDのときはtrueを返す
      if($id == $query[0]->id){
        $set=[];
        $set[ 'status' ] = 0;
        $this->db->update('user', $set,$where);
        return true;
      }
      return false;
    }
  }