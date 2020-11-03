<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MangaDetail extends MY_Controller {
	public function __construct()
	{
		
		parent::__construct();
		$this->load->model("User");
		$this->load->model("Genre");
		$this->load->model("Tag");
		$this->load->model("manga_model");
		$this->load->model("Expression");
		$this->load->library('pagination');

		//年齢制限
		global $g_array_ageflag;
		$this->g_array_ageflag = $g_array_ageflag;
		//販売設定_詳細
		global $g_array_sale_detail;
		$this->g_array_sale_detail = $g_array_sale_detail;

		//現状ページの指定
		$this->basePath = "/Admin/MangaDetail/list/";

		//一覧表示件数
		$this->limit = 30;

		//ヘルパー
		$this->load->helper("page");

	}
	/******************
	 * 一覧表示
	 */
	public function list($pg=0){
		$where = [];
		$where[ 'manga_id' ] = $this->input->post("manga_id");
		$total = $this->manga_model->__getMangaDetail($where);
		$where['limit'] = $this->limit;
		$where['offset'] = sprintf("%d",$this->limit*$pg);
		$list = [];
		$list = $this->manga_model->__getMangaDetail($where);
		//var_dump($list);
		//ページネイションの指定
		$config = pagitemp($this,$total);
		$this->pagination->initialize($config);

		//漫画データ
		$manga = $this->manga_model->___getMangaDataAll();

		$message = "";
		$data = [];
		$data['message'] = $message;
		$data['list'] = $list;
		$data['manga_detail_open'] = "menu-open";
		$data['pager'     ] = $this->pagination->create_links();
		$data['manga'     ] = $manga;
		$data[ 'D_IMAGE'    ] = D_IMAGE;
		$this->load->view('header');
		$this->load->view('/admin/mangaDetail/list',$data);
		$this->load->view('footer');
	}


	public function regist($id="")
	{	
		$error = [];
		$success = "";
		if($this->input->post("regist")){
			//登録処理
			//$idがあるときは更新処理
			$this->manga_model->__setMangaDetail($id);
			$error = $this->manga_model->error;
			if(count($error) < 1 ){
				log_message('debug', '['.date('Y/m/d H:i:s').']漫画詳細データ登録成功');
				$this->session->set_flashdata('success', '漫画詳細データ登録成功しました。');
				header("Location:/Admin/MangaDetail/regist/".$id);
				exit();
			}
		}

		//登録データ取得
		$mangadetail = [];
		if($id > 0 ){
			$mangadetail = $this->manga_model->__getMangaDetailOne($id);
			
		}

		//タグ情報取得
		$getTag = $this->input->post("getTag");
		if($getTag == "on"){
			$tag = $this->Tag->__getTagMaster();
			header('Content-type: application/json');
			echo json_encode($tag);
			exit();
		}
		//タグ情報登録
		$ajax = $this->input->post("ajax");
		if($ajax == "on"){
			$this->Tag->__setTag();
			exit();
		}


		$data = [];
		$data['tagJS'] = "on";
		//ユーザー情報取得
		if($id > 0){
			$user = $this->User->___getUserData($mangadetail[0]->user_id);
		}else{
			$user = $this->User->___getUserLists();			
		}

		$data['user'] = $user;
		//表示データ/初期データ
		$data['param'] = $this->__setParam($mangadetail);
		if(!empty($this->input->post('tag'))){
			$data[ 'check_tag'    ] = $this->input->post('tag');
		}elseif(!empty($data[ 'param' ][ 'check_tag' ])){
			$data[ 'check_tag'    ] = $data[ 'param' ][ 'check_tag' ];
		}

		//連載データ
		$manga = $this->manga_model->___getMangaDataAll();
		$data['manga'] = $manga;

		//表現内容
		$expresstiondata = $this->Expression->__getMasterExpresstionData();
		$data['expresstiondata'] = $expresstiondata;

		$data[ 'D_IMAGE'    ] = D_IMAGE;
		if(!empty($mangadetail[0]->user_id)){
			$data[ 'user_id'    ] = $mangadetail[0]->user_id;
			$data[ 'filepath'   ] = $mangadetail[0]->thumnail_filename;
		}else{
			$data[ 'user_id'    ] = "";
			$data[ 'filepath'   ] = "";
		}

		$data[ 'id'         ] = $id;
		
		$data[ 'error'      ] = $error;
		$data[ 'success'    ] = $this->session->flashdata('success');
		

		$data['manga_detail_open'] = "menu-open";
		$data['g_array_ageflag'  ] = $this->g_array_ageflag;
		$data['g_array_sale_detail'  ] = $this->g_array_sale_detail;

		$this->load->view('header');
		$this->load->view('/admin/mangaDetail/regist',$data);
		$this->load->view('footer');
	}
	/**********
	 * パラメータ登録
	 */
	public function __setParam($mangadetail = ""){
		$param = [];
		if(empty($this->input->post()) && !empty($mangadetail[0])){
			
			$param = [];
			if(isset($mangadetail[0])){
				foreach((array)$mangadetail[0] as $key=>$value){
					$param[$key] = $value;
				}
				//漫画IDの配列
				$param['manga_id'] = explode(",",$param[ 'manga_id' ]);
				//tagIDの配列
				$param['check_tag'] = array_flip(explode(",",$param[ 'tag_id' ]));
				//表現内容の配列
				$param['expression'] = $this->__convArray(explode(",",$param[ 'expression_id' ]));
				

			}
		}else{
			$param = $this->input->post();
		}
		return $param;
	}
	/*************
	 * 配列の値をキーにする
	 */
	public function __convArray($array){
		$list = [];
		foreach($array as $key=>$value){
			$list[$value] = $value;
		}
		return $list;
	}
	
}
