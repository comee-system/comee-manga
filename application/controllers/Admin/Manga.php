<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manga extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("User");
		$this->load->model("Genre");
		$this->load->model("Tag");
		$this->load->model("manga_model");
		$this->load->library('pagination');

		//ヘルパー
		$this->load->helper("page");
		//現状ページの指定
		$this->basePath = "/Admin/Manga/list/";

		//一覧表示件数
		$this->limit = 30;

	}
	public function list($pg=0)
	{
		
		//ジャンル情報取得
		$genre = $this->Genre->__getGenreMaster();

		$manga_name = $this->input->post("manga_name");
		$username = $this->input->post("username");
		$genre_name = $this->input->post("genre_name");
		$tag_name = $this->input->post("tag_name");

		$where = [];
		$where[ 'manga_name' ] = $manga_name;
		$where[ 'username' ] = $username;
		$where[ 'genre_name' ] = $genre_name;
		$where[ 'tag_name' ] = $tag_name;
		//データ取得
		$total = $this->manga_model->__getList($where);
		$where['limit'] = $this->limit;
		$where['offset'] = sprintf("%d",$this->limit*$pg);
				
		$list = $this->manga_model->__getList($where);
		//ページネイションの指定
		$config = pagitemp($this,$total);
		$this->pagination->initialize($config);


		$data['manga_open'] = "menu-open";
		$data['pager'     ] = $this->pagination->create_links();
		$data['list'      ] = $list;
		$data['message'   ] = "";
		$data['genre'     ] = $genre;
		$data['manga_name'  ] = $manga_name;
		$data['username'    ] = $username;
		$data['genre_name'  ] = $genre_name;
		$data['tag_name'    ] = $tag_name;
		$this->load->view('header');
		$this->load->view('/admin/manga/list',$data);
		$this->load->view('footer');
	}
	/**************************
	 * 削除
	 */
	public function delete($id){
		if($id > 0){
			//存在確認
			if($this->manga_model->__getMangaData($id)){
				$this->manga_model->__editMangaStatus($id);
				log_message('debug', '['.date('Y/m/d H:i:s').']漫画データ削除成功');
				$this->session->set_flashdata('success', '連載データの削除に成功しました。');

			}else{
				log_message('error', '['.date('Y/m/d H:i:s').']漫画データ削除失敗');

			}

		}
		header("Location:/Admin/Manga/list/");
		exit();
	}
	/***********
	 * 連載登録
	 */
	public function regist($manga_id=""){
		global $g_array_sale;
		global $g_array_disp;
		global $g_array_manga_type;
		global $g_array_ageflag;

		//連載登録
		if($this->input->post("regist")){
			//トランザクション開始
			$this->db->trans_start();
			
			//漫画情報登録
			$flg = $this->manga_model->__setManga($manga_id);
			
			if(!$flg){
				//エラーのため戻る
				log_message('debug', '['.date('Y/m/d H:i:s').']連載データ登録失敗。ロールバック実施');
				$this->db->trans_rollback();
			}else{
				//ジャンルの登録
				$lastid = $this->manga_model->lastid;

				if($this->Genre->__setGenreData($lastid)){
					//タグの登録
					$this->Tag->__setTagData($lastid);
				}else{
					//ジャンル登録失敗
					log_message('debug', '['.date('Y/m/d H:i:s').']ジャンル登録失敗。ロールバック実施');
					$this->db->trans_rollback();
				}
				$this->db->trans_complete();

				//更新完了
				if($manga_id >0){
					log_message('debug', '['.date('Y/m/d H:i:s').']連載データ更新成功('.$manga_id.')。コミット');
					$this->session->set_flashdata('success', '連載データの更新に成功しました。');
					header("Location:/Admin/Manga/regist/".$manga_id);
				}else{
					log_message('debug', '['.date('Y/m/d H:i:s').']連載データ登録成功。コミット');
					$this->session->set_flashdata('success', '連載データの登録に成功しました。');
					header("Location:/Admin/Manga/regist");
				}
				
				exit();
			}
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

		//漫画情報取得
		$manga = "";
		$genre = [];
		$tag = [];
		if($manga_id){
			//漫画データ
			$manga = $this->manga_model->__getMangaData($manga_id);
			//ジャンル情報取得
			$genre = $this->Genre->__getGenreData($manga_id);
			//タグ情報取得
			$tag = $this->Tag->__getTagData($manga_id);

		}

		$data  = [];
		//タグ表示用js
		$data['id'] = $manga_id;
		$data['tagJS'] = "on";
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->manga_model->error;
		$data['manga_open'] = "menu-open";
		$data[ 'g_array_sale' ] = $g_array_sale;
		$data[ 'g_array_disp' ] = $g_array_disp;
		$data[ 'g_array_manga_type' ] = $g_array_manga_type;
		$data[ 'g_array_ageflag' ] = $g_array_ageflag;
		//ユーザ情報取得
		$user = $this->User->___getUserLists();
		$data['user'] = $user;
		//ジャンルマスターデータ取得
		$genremaster = $this->Genre->__getGenreMaster();
		$data['genremaster'] = $genremaster;
		
		//変数挿入
		$data[ 'D_IMAGE'    ] = D_IMAGE;
		$data[ 'user_id'    ] = $this->___valiable($manga,"user_id");
		$data[ 'manga_name' ] = $this->___valiable($manga,"manga_name");
		$data[ 'manga_kana' ] = $this->___valiable($manga,"manga_kana");
		$data[ 'message' ] = $this->___valiable($manga,"message");
		$data[ 'sale' ] = $this->___valiable($manga,"sale");
		$data[ 'price' ] = $this->___valiable($manga,"price");
		$data[ 'announce_text' ] = $this->___valiable($manga,"announce_text");
		$data[ 'filepath' ] = $this->___valiable($manga,"filepath","announce_image");
		$data[ 'display' ] = $this->___valiable($manga,"display");
		$data[ 'term_flag' ] = $this->___valiable($manga,"term_flag");
		$data[ 'term_start' ] = $this->___valiable($manga,"term_start");
		$data[ 'term_end' ] = $this->___valiable($manga,"term_end");

		if(!empty($this->input->post('genre'))){
			$data[ 'check_genre'   ] = $this->input->post("genre");			
		}else{
			$data[ 'check_genre'   ] = $genre;
		}
		
		if(!empty($this->input->post('tag'))){
			$data[ 'check_tag' ] = $this->input->post("tag");
		}else{
			$data[ 'check_tag' ] = $tag;
		}

		$data[ 'type' ] = $this->___valiable($manga,"type");
		$data[ 'age_flag' ] = $this->___valiable($manga,"age_flag");



		$this->load->view('header');
		$this->load->view('/admin/manga/regist',$data);
		$this->load->view('footer');
	}
	/*******************
	 * 変数代入
	 */
	public function ___valiable($manga,$key,$postkey = ""){
		$ret = "";
		if(!$postkey){
			$postkey = $key;
		}

		if( !empty($this->input->post($key)) ){ $ret = $this->input->post($key);}
		elseif(!empty($manga[0]->$postkey)){ $ret = $manga[0]->$postkey;}
		return $ret;
	}
}
