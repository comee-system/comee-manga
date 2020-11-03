<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * ログインページ
 */
class Auth extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
		//URLヘルパーロード
		$this->load->helper('url');
		//モデルファイル読み込み
		$this->load->model("User");
    }
	/*************
	 * ログインページ
	 */
	public function login()
	{
		$errmsg = $this->session->flashdata('errmsg');
		$data["errmsg"] = $errmsg; 
		$this->load->view('/auth/login',$data);
	}
	/***********
	 * ログインチェックを行う
	 */
	public function loginCheck(){
		$signin = $this->input->post("signin");
		if(isset($signin) && $signin == "on"){
			log_message('debug', 'ログイン実行');
			//ログインチェック
			if($this->User->loginCheck()){
				log_message('debug', 'ログイン成功しました');
				redirect(base_url().'Admin/Post/');
				
			}else{
				log_message('error', 'ログインに失敗しました。IDもしくはパスワードに誤りがあります。');
				$this->session->set_flashdata('errmsg','ログインに失敗しました。IDもしくはパスワードに誤りがあります。');
			}
		}else{
			$this->session->set_flashdata('errmsg','ログインに失敗しました。IDもしくはパスワードに誤りがあります。');
		}
		redirect(base_url().'Auth/login/');
	}

	public function logout(){
		log_message('debug', 'ログアウト実行');
		$this->User->logout();
		redirect(base_url().'Auth/login/');
	}
}
