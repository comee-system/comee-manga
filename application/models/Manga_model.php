<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manga_model extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
      $this->error = [];
    }
    /*******************
     * 漫画ステータス変更
     */
    public function __editMangaStatus($id){

      $where = [];
      $where[ 'id' ] = $id;
      $data['status'] = 0;
      $flg = $this->db->update('manga', $data,$where);
      return $flg;
    }
    /**************************
     * 漫画データ取得
     */
    public function __getMangaData($id){
      $where = array(
          'id' => $id
					,'status' => 1
      );
      $query = $this->db->get_where("manga",$where);
      $result = $query->result();
      return $result;
    }
    /************************
     * 漫画データ全部
     */
    public function ___getMangaDataAll(){
        $where = array(
          'status'=>1
        );
        $this->db->select('id,manga_name,user_id');
        $query = $this->db->get_where("manga",$where);
        $result = $query->result();
        return $result;
    }
    

    /*************************
     * 一覧データ取得
     */
    public function __getList($limits=[]){

      $limit = $offset = "";
      if(isset($limits['limit'])) $limit = $limits['limit'];
      if(isset($limits['offset'])) $offset = $limits['offset'];

      $where =[];

      if(isset($limits[ 'manga_name' ]) && $limits[ 'manga_name' ]) $this->db->like('manga_name', $limits[ 'manga_name' ]);
      if(isset($limits[ 'username' ]) && $limits[ 'username' ]) $this->db->like('username', $limits[ 'username' ]);
      if(isset($limits[ 'genre_name' ]) && $limits[ 'genre_name' ]) $this->db->like('genre_name', $limits[ 'genre_name' ]);
      if(isset($limits[ 'tag_name' ]) && $limits[ 'tag_name' ]) $this->db->like('tag_name', $limits[ 'tag_name' ]);


      if($limit && $offset){
        $this->db->order_by("update_ts DESC");
        $query = $this->db->get_where("view_manga_user",$where, $limit, $offset);
        
      }else{
        $this->db->order_by("update_ts DESC");
        $query = $this->db->get_where("view_manga_user",$where);
      }

      
      $result = $query->result();

      return $result;
    } 

    /**********************
     * 連載の登録
     */
    public  function __setManga($manga_id = ""){

      if(!$this->__error()){

        return false;
      }else{

        $filepath = basename($this->input->post("filepath"));
        $data = array(
          'user_id' => $this->input->post('user_id'),
          'manga_name' => $this->input->post("manga_name"),
          'manga_kana' => $this->input->post("manga_kana"),
          'message' => $this->input->post("message"),
          'sale' => $this->input->post("sale"),
          'price' => $this->input->post("price"),
          'announce_text' => $this->input->post("announce_text"),
          'age_flag' => $this->input->post("age_flag"),
          'display' => $this->input->post("display"),
          'type' => $this->input->post("type"),
          'term_flag' => $this->input->post("term_flag"),
          'term_start' => $this->input->post("term_start"),
          'term_end' => $this->input->post("term_end"),
          'regist_ts'=>date('Y-m-d H:i:s')
        );
        if($filepath){
          $data['announce_image'] = $filepath;
        }

        if($manga_id > 0){
          //更新処理
          $where = [];
          $where[ 'id' ] = $manga_id;
          $flg = $this->db->update('manga', $data,$where);
        }else{
          //登録処理
          $flg = $this->db->insert('manga', $data);
        }
  
        if($flg){
          //更新時はmanga_idを返す
          if($manga_id > 0 ){
            $this->lastid = $manga_id;
          }else{
            $this->lastid = $this->db->insert_id();
          }

          log_message('debug', '['.date('Y/m/d H:i:s').']連載データ登録成功');
          //画像の補正
          $this->__createImage();
          return true;
        }else{
          log_message('debug', '['.date('Y/m/d H:i:s').']連載データ登録失敗');
          return false;
        }        
        
      }
    }
    /**********************
     * 連載詳細の登録
     */
    public function __setMangaDetail($mangadetail_id = ""){

      if($this->__errorDetail()){
        $this->db->trans_start();
        $data = [];
        $data[ 'user_id' ] = $this->input->post('user_id');
        $data[ 'title'   ] = $this->input->post('title');
        $data[ 'caption' ] = $this->input->post('caption');
        $data[ 'detail_age_limit' ] = $this->input->post('detail_age_limit');
        $data[ 'sale_flag' ] = $this->input->post('sale_flag');
        $data[ 'outline_text' ] = $this->input->post('outline_text');
        $data[ 'thumnail_filename' ] = basename($this->input->post('filepath'));
        $data[ 'regist_ts' ] = date("Y-m-d H:i:s");
        

        //更新のときは引数から利用
        if($mangadetail_id){
          $this->db->where('id',$mangadetail_id);
          $this->db->update('manga_detail', $data);
          $mangadetail_id = $mangadetail_id;
        }else{
          $this->db->insert('manga_detail', $data);
          $mangadetail_id = $this->db->insert_id();
        }
        $this->lastid = $mangadetail_id;
        //タグデータ登録
        $flag1 = $this->Tag->__setTagData($mangadetail_id,"detail");
        //表現内容データ登録
        $flag2 = $this->Expression->__setExpressionData($mangadetail_id,"detail");
        //中間テーブル登録
        $flag3 = $this->__setMiddle();
        if(
          $flag1 && 
          $flag2 && 
          $flag3 
        ){
          //サムネイル用画像
          $this->__createImage("detail","thum");
          $this->db->trans_complete();
          return true;

        }
              
      }
      return false;

    }
    /*******************
     * 中間テーブル登録
     */
    public function __setMiddle(){

      //登録の前にデータの削除を行う
      $where[ 'manga_detail_id' ] = $this->lastid;
      if(!$this->db->delete('manga_middle',$where)){
        return false;
      }
      foreach($this->input->post('manga_id') as $value){
        $data = [];
        $data['manga_detail_id'] = $this->lastid;
        $data['manga_id'] = $value;
        $data['regist_ts'] = date("Y-m-d H:i:s");
        $flg = $this->db->insert('manga_middle', $data);
        if(!$flg){
          return false;
        }
      }
      return true;

    }
    /**********************
     * 連載詳細の登録エラーチェック
     */
    public function __errorDetail(){
      $error = "";
      $this->error=[];

      if(empty($this->input->post('title'))){
        $this->error[1] = "連載タイトルが入力されていません。";
        $error = 1;
      }
      if(empty($this->input->post('manga_id'))){
        $this->error[2] = "連載の選択が未選択状態です。";
        $error = 1;
      }
      if(empty($this->input->post('caption'))){
        $this->error[3] = "連載キャプションが未入力です。";
        $error = 1;
      }
      


      if($error){
        return false;
      }
      return true;
    }

    /*************************
     * 漫画詳細一覧
     */


    /***************
     * アイコン用画像の補正
     */
    public function __createImageIcon($uid){
        //ユーザー用のディレクトリ作成
        $userPath = D_IMAGE.$uid."/";
        if(!file_exists($userPath)){
            mkdir($userPath,0755);
        }

        $userPath = D_IMAGE.$uid."/icon/";
        if(!file_exists($userPath)){
            mkdir($userPath,0755);
        }
        //画像リサイズ
        $filepath = base_url().$this->input->post("filepath");
        if(!empty($this->input->post("filepath"))){
          //ファイル情報
          $path_parts = pathinfo($filepath);
          $extension = $path_parts[ 'extension' ];
  
          //JPG用
          if($extension == "jpg" || $extension == "JPG"){
            $this->___resizeJPG($filepath,$userPath,100,"xs");
            $this->___resizeJPG($filepath,$userPath,400,"s");
          }
          //gif用
          if($extension == "gif" || $extension == "GIF"){
            $this->___resizeGIF($filepath,$userPath,100,"xs");
            $this->___resizeGIF($filepath,$userPath,400,"s");
          }
          //png用
          if($extension == "png" || $extension == "PNG"){
            $this->___resizePNG($filepath,$userPath,100,"xs");
            $this->___resizePNG($filepath,$userPath,400,"s");
          }
  
          
          //元ファイル移動
          $basename = basename($filepath);
          copy($filepath, $userPath.$basename);
        }

    }
    /***************
     * 画像の補正
     * $type : 詳細
     * $code : サムネイル
     */
    public function __createImage($type = "",$code = ""){

      //ユーザー用のディレクトリ作成
      $userPath = D_IMAGE.$this->input->post('user_id')."/";
      if(!file_exists($userPath)){
          mkdir($userPath,0755,true);
      }

      if($type == "detail"){
        //漫画詳細
        if($code) $code = $code."/";
        $userPath = D_IMAGE.$this->input->post('user_id')."/detail/".$this->lastid."/".$code;
      }else{
        //告知画像
        $userPath = D_IMAGE.$this->input->post('user_id')."/announce/".$this->lastid."/";
      }
      if(!file_exists($userPath)){
          mkdir($userPath,0755,true);
      }

      //画像リサイズ
      $filepath = base_url().$this->input->post("filepath");
      //ファイルがあるときのみ実行

      if(!empty($this->input->post("filepath"))){
        //ファイル情報
        $path_parts = pathinfo($filepath);
        $extension = $path_parts[ 'extension' ];

        //JPG用
        if($extension == "jpg" || $extension == "JPG"){
          $this->___resizeJPG($filepath,$userPath,100,"xs");
          $this->___resizeJPG($filepath,$userPath,400,"s");
          $this->___resizeJPG($filepath,$userPath,800,"m");
          $this->___resizeJPG($filepath,$userPath,1200,"l");
        }
        //gif用
        if($extension == "gif" || $extension == "GIF"){
          $this->___resizeGIF($filepath,$userPath,100,"xs");
          $this->___resizeGIF($filepath,$userPath,400,"s");
          $this->___resizeGIF($filepath,$userPath,800,"m");
          $this->___resizeGIF($filepath,$userPath,1200,"l");
        }
        //png用
        if($extension == "png" || $extension == "PNG"){
          $this->___resizePNG($filepath,$userPath,100,"xs");
          $this->___resizePNG($filepath,$userPath,400,"s");
          $this->___resizePNG($filepath,$userPath,800,"m");
          $this->___resizePNG($filepath,$userPath,1200,"l");
        }

        
        //元ファイル移動
        $basename = basename($filepath);
        copy($filepath, $userPath.$basename);
      }
    }


    /*********************
     * 連載詳細データ取得
     */
    public function __getMangaDetailOne($id){

      $where[ 'id' ] = $id;
      $query = $this->db->get_where("view_manga_detail_tag_expression",$where);
      $result = $query->result();
      return $result;
    }
    /*************************
     * 連載詳細一覧データ取得
     */
    public function __getMangaDetail($limits=[]){
      

        $limit = $offset = "";
        if(isset($limits['limit'])) $limit = $limits['limit'];
        if(isset($limits['offset'])) $offset = $limits['offset'];
  
        $where =[];
        
        if($this->input->post("title")) $this->db->like('title', $this->input->post("title"));

        if($this->input->post("manga_id") ){
          $this->db->where('FIND_IN_SET('.$this->input->post("manga_id").',manga_id) !=',0);
        }
        
        if($limit){
          $this->db->order_by("update_ts DESC");
          
          $query = $this->db->get_where("view_manga_detail",$where, $limit, $offset);
            
        }else{
          $this->db->order_by("update_ts DESC");
          $query = $this->db->get_where("view_manga_detail",$where);
        }
  
        
        $result = $query->result();
  
        return $result;
        

    }


    /*********************
     * pngをリサイズ
     */
    public function ___resizePNG($filepath,$userPath,$w,$type){
      $basename = basename($filepath);
      $baseImage = imagecreatefrompng($filepath); 
      list($width, $height) = getimagesize($filepath);
      //縦比率
      $h = ($w*$height)/$width;

      $image = imagecreatetruecolor($w, $h);
      imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $w, $h, $width, $height);
      imagepng($image , $userPath.$type.'_'.$basename);
      imagedestroy($image);
    }
    /*********************
     * gifをリサイズ
     */
    public function ___resizeGIF($filepath,$userPath,$w,$type){
      $basename = basename($filepath);
      $baseImage = imagecreatefromgif($filepath); 
      list($width, $height) = getimagesize($filepath);
      //縦比率
      $h = ($w*$height)/$width;

      $image = imagecreatetruecolor($w, $h);
      imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $w, $h, $width, $height);
      imagegif($image , $userPath.$type.'_'.$basename);
      imagedestroy($image);
    }
    /*********************
     * jpgをリサイズ
     */
    public function ___resizeJPG($filepath,$userPath,$w,$type){
      $basename = basename($filepath);
      $baseImage = imagecreatefromjpeg($filepath); 
      list($width, $height) = getimagesize($filepath);
      //縦比率
      $h = ($w*$height)/$width;

      $image = imagecreatetruecolor($w, $h);
      imagecopyresampled($image, $baseImage, 0, 0, 0, 0, $w, $h, $width, $height);
      imagejpeg($image , $userPath.$type.'_'.$basename);
      imagedestroy($image);
    }
    
    /***********
     * エラーチェック
     */
    public function __error(){
      $this->error = [];
      if(strlen($this->input->post('user_id')) < 1){
        $this->error[1] = "ユーザー名を選択してください。";
      }

      if(strlen($this->input->post('manga_name')) < 1){
        $this->error[2] = "連載タイトルが入力されていません。";
      }
      if(strlen($this->input->post('manga_kana')) < 1){
        $this->error[3] = "連載タイトルかなが入力されていません。";
      }
      if(strlen($this->input->post('message')) < 1){
        $this->error[4] = "連載説明が入力されていません。";
      }

      if(strlen($this->input->post('price')) > 0){
        if(!is_numeric($this->input->post('price'))){
          $this->error[5] = "販売価格は数値のみ入力してください。";
        }
        $error = 1;
      }
      if($this->input->post('term_flag')==1 ){
        $term_start = $this->input->post('term_start');
        $term_end = $this->input->post('term_end');
        if(
            $term_start >= $term_end 
            || $this->validateDate($term_start, '%Y/%m/%d')
            || $this->validateDate($term_end, '%Y/%m/%d')
            ){
          $this->error[6] = "表示期間の指定に誤りがあります。";
          $error = 1;
        }
      }


      if(count($this->error) > 0 ){

        return false;
      }
      return true;

    }
    function validateDate($date, $format = 'Y/m/d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

  }