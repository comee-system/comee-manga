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
	/********************
	 * zipファイル展開
	 */
	public function zip(){
		//zipファイルかどうか拡張子チェック
		$zip_file_name = $_FILES['upfile']['name'];
		$zip_error = $_FILES['upfile']['error'];
		$zip_extension = substr($zip_file_name, strrpos($zip_file_name, '.') + 1);
		if($zip_extension !== 'zip'){
			//set_error_msg('upfile', 'CSVファイルを選択してください');
			return 0;
		}
		$dir = D_IMAGE_TEMP;
		$zip_name = md5(uniqid(rand(), true)) . '_' . time();
		$zip_file = $zip_name . '.zip';
		if( move_uploaded_file($_FILES['upfile']['tmp_name'], $dir . $zip_file) ){

			$zip = new ZipArchive;
			if($zip->open($dir.$zip_file) === TRUE){
				$zip->extractTo($dir . $zip_name);
				$zip->close();

				//zipファイルの削除
				unlink($dir.$zip_file);
	
				//展開されたフォルダ内にあるディレクトリを検索
				$dirs = scandir($dir . $zip_name . '/');
	
				//ディレクトリを決め打ち(0->'.', 1->'..'になる)
				$tmp_dir = $dir . $zip_name . '/*';
				$lists = [];
				foreach(glob($tmp_dir) as $file){
					//ファイルのコピー
					$exp = explode(".",$file);
					$copyfile = $dir.uniqid().".".$exp[1];
					copy($file, $copyfile);
					$lists[] = basename($copyfile);
				}
				
				echo json_encode($lists);
			}else{
				echo '解凍エラー';
			}

		}
		exit();
		
	}
	/********************
	 * ファイルアップロード用まとめて
	 */
	public function fileAll(){
		if(count($_FILES) > 0){
			$files  = $_FILES[ 'files' ];
			$data = [];
			$i=0;
			foreach($files[ 'name' ] as $key=>$values){
				$data[$i]['name'] = $values;
				$data[$i]['type'] = $files[ 'type' ][$key];
				$data[$i]['tmp_name'] = $files[ 'tmp_name' ][$key];
				$data[$i]['error'] = $files[ 'error' ][$key];
				$data[$i]['size'] = $files[ 'size' ][$key];
				$i++;
			}
			
			//ファイルを添付ファイルにアップロード
			if(count($data)){
				$lists = [];
				foreach($data as $key=>$values){
					$name  = $values[ 'name' ];
					$type  = $values[ 'type' ];
					$tmp   = $values[ 'tmp_name' ];
					$error = $values[ 'error' ];
					$size  = $values[ 'size' ];

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
							//echo $filename;
							$lists[] = $filename;
						}else{
							echo 3;

						}
					}
				}
				echo json_encode($lists);
				exit();
			}
		}
		exit();
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
