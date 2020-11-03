<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fileupload extends MY_Controller {
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
	

	/*******************
	 * ファイルアップロード用
	 * route.phpで指定
	 */
	public function file(){
		if(count($_FILES) == 1){
			$name  = $_FILES[ 'upfile' ][ 'name' ];
			$type  = $_FILES[ 'upfile' ][ 'type' ];
			$tmp   = $_FILES[ 'upfile' ][ 'tmp_name' ];
			$error = $_FILES[ 'upfile' ][ 'error' ];
			$size  = $_FILES[ 'upfile' ][ 'size' ];
			$file_ext = pathinfo($name, PATHINFO_EXTENSION);
			$file_ext = strtolower($file_ext);
			if($file_ext != "jpg" && $file_ext != "gif" && $file_ext != "png"){
				//拡張子エラー
				echo 1;
				exit();
			}
			if($error > 0 ){
				//何らかのエラー
				echo 2;
				exit();
			}
			
			$filename = uniqid().".".$file_ext;
			$tempfiledir = FCPATH."assets/image/temp/";
			if (is_uploaded_file ( $tmp ) ){
				$file = $tempfiledir.$filename;
				if (move_uploaded_file ( $tmp, $file )) {
					//成功
					echo $filename;
				}else{
					echo 3;

				}
			}
		}
		exit();
	}
}
