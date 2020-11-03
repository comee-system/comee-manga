<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		//メニューの展開変数
		$this->open_menu = "menu-open";

		//ページクラス読み込み
		$this->load->library('pagination');
		//現状ページの指定
		$this->basePath = "/Admin/Users/list/";
		//一覧表示件数
		$this->limit = 50;
		$this->load->model("User");
		$this->load->model("Payment");
		$this->load->model("Manga_model");
		//ヘルパー
		$this->load->helper("page");
		//ユーザ型
		global $g_array_type;
		$this->g_array_type = $g_array_type;
		//口座タイプ
		global $g_array_account;
		$this->g_array_account = $g_array_account;
	}
	/**
	 * ユーザデータ一覧表示
	 */
	public function list($pg=0)
	{
		log_message('debug', 'ユーザデータ一覧表示');
		//ユーザデータ取得
		$total = $this->User->___getUserLists();
		$where['limit'] = $this->limit;
		$where['offset'] = sprintf("%d",$this->limit*$pg);
		$userlist = $this->User->___getUserLists($where);
		//ページネイションの指定
		$config = pagitemp($this,$total);
		$this->pagination->initialize($config);

		//viewに渡すようの変数
		$data['users_open'] = $this->open_menu;
		$data['pager'     ] = $this->pagination->create_links();
		$data['userlist'  ] = $userlist;
		$data['g_array_type'  ] = $this->g_array_type;
		$data[ 'message' ] = $this->session->flashdata('message');

		$this->load->view('header');
		$this->load->view('/admin/index',$data);
		$this->load->view('footer');
	}
	/*************
	 * ユーザデータ登録
	 */
	public function regist($id=""){

		$data = [];
		$data[ 'error' ] = [];
		$data[ 'D_IMAGE'    ] = D_IMAGE;
		$data[ 'email' ] = $this->input->post("email");
		$data[ 'password' ] = $this->input->post("password");
		$data[ 'userid' ] = $this->input->post("userid");
		$data[ 'username' ] = $this->input->post("username");
		$data[ 'usernamekana' ] = $this->input->post("usernamekana");
		$data[ 'birth_y' ] = $this->input->post("birth_y");
		$data[ 'birth_m' ] = $this->input->post("birth_m");
		$data[ 'birth_d' ] = $this->input->post("birth_d");
		$data[ 'agelimit' ] = $this->input->post("agelimit");
		$data[ 'type' ] = $this->input->post("type");
		$data[ 'message' ] = $this->session->flashdata('message');
		$data[ 'iconimage' ] = $this->session->flashdata('filepath');
		$data['id'] = $id;
		//更新用
		if($id > 0 && !$this->input->post('regist') ){
			$where=[];
			$where['id']=$id;
			$rlt = $this->db->get_where('user',$where)->result();
			$list = [];
			foreach($rlt[0] as $key=>$val){
				$data[$key]=$val;
			}
			//パスワードは空欄
			$data['password'] = "";
			//生年月日入れ替え
			$b = explode("-",$data['birth']);
			$data['birth_y'] = $b[0];
			$data['birth_m'] = $b[1];
			$data['birth_d'] = $b[2];
		}
		if($this->input->post('regist')){
			//登録処理
			if($id > 0 ){
				log_message('debug', 'ユーザデータ更新');
			}else{
				log_message('debug', 'ユーザデータ登録');
			}
			$data[ 'error' ] = $this->User->__error();
			//log_message('debug', $data[ 'error' ]);

			if(!$data[ 'error' ]){
				//登録処理
				$set=[];
				if($this->input->post("password")){
					$set['password'] = password_hash($this->input->post("password"), PASSWORD_DEFAULT);
				}
				$set[ 'email' ] = $this->input->post("email");
				$set[ 'userid' ] = $this->input->post("userid");
				$set[ 'username' ] = $this->input->post("username");
				$set[ 'usernamekana' ] = $this->input->post("usernamekana");
				$set[ 'birth' ] = sprintf("%04d-%02d-%02d"
							,$this->input->post("birth_y")
							,$this->input->post("birth_m")
							,$this->input->post("birth_d")
							);
				$set[ 'agelimit' ] = $this->input->post("agelimit");
				$filepath = basename($this->input->post("filepath"));
				if($filepath){
					$set['iconimage'] = $filepath;
				  }

				if(!$id) $set[ 'regist_ts' ] = date("Y-m-d H:i:s");
				if($id){
					$where['id'] = $id;
					$rlt = $this->db->update('user', $set,$where);
					//アイコン画像アップロードテスト
					$uid = $id;
					$this->Manga_model->__createImageIcon($uid);
				}else{
					$rlt = $this->db->insert('user', $set);
					$insertid = $this->db->insert_id();
					$this->Manga_model->__createImageIcon($insertid);
				}
				
				

				if($rlt){
					if($id > 0){
						log_message('debug', 'ユーザデータ更新成功['.$set['userid'].']');
						$this->session->set_flashdata('message', 'ユーザデータの更新に成功しました。');
						header("Location:/Admin/Users/regist/".$id);
					}else{
						log_message('debug', 'ユーザデータ登録成功['.$set['userid'].']');
						$this->session->set_flashdata('message', 'ユーザデータの登録に成功しました。');
						header("Location:/Admin/Users/regist/");
					}
					exit();
				}else{
					log_message('error', 'ユーザデータ登録失敗['.$set['userid'].']');
					$data[ 'error' ] = "登録に失敗しました。";
				}
				
			}
		}
		$data['users_open'] = $this->open_menu;
		$this->load->view('header');
		$this->load->view('/admin/regist',$data);
		$this->load->view('footer');

	}
	/**********************
	 * ユーザーデータ更新
	 */
	public function delete($id){

		if($this->User->__delete($id)){
			$this->session->set_flashdata('message', 'ユーザデータの削除に成功しました。');
			log_message('debug', 'ユーザデータ削除成功');
		}else{
			$this->session->set_flashdata('message', 'ユーザデータの削除に失敗しました。');
		}
		header("Location:/Admin/Users/list/");
		exit();
		
	}
	/*******************
	 * 支払いユーザ情報登録
	 */
	public function payment($uid){
		//支払いデータ取得
		$rlt = $this->Payment->__getUserPayment($uid);
		$result = $rlt[0];

		//登録処理
		if($this->input->post("regist" )){
			if($this->Payment->__setUserPayment()){
				$this->session->set_flashdata('message', '支払いデータの更新に成功しました。');
				log_message('debug', 'ユーザデータ更新成功');
				header("Location:/Admin/Users/list/");

				exit();
			}
			foreach($this->input->post() as $key=>$value){
				$result->$key=$value;
			}
		}

		if(count($rlt) < 1){
			log_message('debug', '支払いデータの取得に失敗しました。');
			show_404();
			exit();
		}
		
		$data = [];
		$data['error'] = [];
		$data['message'] = "";
		$data['user_id'] = $uid;
		$data['g_array_account'] = $this->g_array_account;
		$data['payment'] = $result;
		$data['users_open'] = $this->open_menu;
		
		$data['error'] = $this->Payment->error;
		$this->load->view('header');
		$this->load->view('/admin/payment',$data);
		$this->load->view('footer');
	}
	
}
