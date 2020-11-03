<?php

class Payment extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
      $this->error = [];
    }

    
    /************
     * ユーザ情報取得
     */
    public function __getUserPayment($uid){
      $where = [];
      $where[ 'userid' ] = $uid;
      $rlt = $this->db->get_where('view_user_payment',$where)->result();
      return $rlt;
    }

    /*******************
     * ユーザ情報登録
     */
    public function __setUserPayment(){
      //存在するときは更新、存在しないときは登録を行う
      //エラーチェック
      if($this->_error()){
        //登録処理
        $this->__setPayment();
        return true;
      }
      return false;
    }
    /************
     * 支払い
     */
    public function __setPayment(){
      $where=[];
      $where[ 'user_id' ] = $this->input->post("user_id");

      $query = $this->db->get_where('user_payment',$where)->result();
      if($query){
        $where=[];
        $where[ 'user_id' ] = $this->input->post("user_id");
        $set['sei'] = $this->input->post("sei");
        $set['mei'] = $this->input->post("mei");
        $set['sei_kana'] = $this->input->post("sei_kana");
        $set['mei_kana'] = $this->input->post("mei_kana");
        $set['post'] = $this->input->post("post");
        $set['address'] = $this->input->post("address");
        $set['tel'] = $this->input->post("tel");
        $set['bankname'] = $this->input->post("bankname");
        $set['shopname'] = $this->input->post("shopname");
        $set['bankcode'] = $this->input->post("bankcode");
        $set['banktype'] = $this->input->post("banktype");
        $set['account_name'] = $this->input->post("account_name");
        $set['account_name_kana'] = $this->input->post("account_name_kana");
        $this->db->update("user_payment",$set,$where);
      }else{
        $set = [];
        $set['user_id'] = $this->input->post("user_id");
        $set['sei'] = $this->input->post("sei");
        $set['mei'] = $this->input->post("mei");
        $set['sei_kana'] = $this->input->post("sei_kana");
        $set['mei_kana'] = $this->input->post("mei_kana");
        $set['post'] = $this->input->post("post");
        $set['address'] = $this->input->post("address");
        $set['tel'] = $this->input->post("tel");
        $set['bankname'] = $this->input->post("bankname");
        $set['shopname'] = $this->input->post("shopname");
        $set['bankcode'] = $this->input->post("bankcode");
        $set['banktype'] = $this->input->post("banktype");
        $set['account_name'] = $this->input->post("account_name");
        $set['account_name_kana'] = $this->input->post("account_name_kana");
        $set['regist_ts'] = date("Y-m-d H:i:s");
        $this->db->insert("user_payment",$set);
      }
    }
    /*************
     * エラーチェック
     */
    public function _error(){
      if(!$this->input->post("sei") || !$this->input->post("mei")){
        $this->error[] = "姓もしくは名が入力されていません。";
      }
      if(!$this->input->post("sei_kana") || !$this->input->post("mei_kana")){
        $this->error[] = "姓かなもしくは名かなが入力されていません。";
      }
      $post = $this->input->post("post");
      $post = preg_replace("/\-/","",$post);
      if(!$this->input->post("post") ){
        $this->error[] = "郵便番号が入力されていません。";
      }else{
        if(!preg_match("/^\d{7}$/",$post)){
          $this->error[] = "郵便番号に不備があります。";
        }
      }
      if(!$this->input->post("address") ){
        $this->error[] = "住所が入力されていません。";
      }
      $tel = preg_replace("/\-/","",$this->input->post("tel"));
      if(!$this->input->post("tel") ){
        $this->error[] = "電話番号が入力されていません。";
      }else{
        if(!preg_match("/^0\d{9,10}$/",$tel)){
          $this->error[] = "電話番号に不備があります。";
        }
      }

      if(
        !$this->input->post("bankname") 
      || !$this->input->post("shopname") 
      || !$this->input->post("bankcode") ){
        $this->error[] = "銀行名/支店名/銀行コードが入力されていません。";
      }

      if(
        !$this->input->post("banktype") 
      || !$this->input->post("account_name") 
      || !$this->input->post("account_name_kana") ){
        $this->error[] = "口座タイプ/口座名義/口座名義かなが入力されていません。";
      }

      if(count($this->error) < 1){
        return true;
      }
      return false;
    
    }
  }