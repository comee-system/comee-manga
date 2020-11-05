<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Manga_detail extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');
    }




    /**********************
     * 画像データテーブルの登録
     * type:詳細画面から利用：detail
     */
    public  function __setImageData($lastid,$type = ""){
      //登録の前にデータの削除を行う
      $where = [];
      $where[ 'manga_detail_id' ] = $lastid;

      if(!$this->db->delete('manga_detail_image',$where)){
        return false;
      }
      

      $mangaDetail = $this->input->post("mangaDetail");
      if(count($mangaDetail)){
        $i=1;
        foreach($mangaDetail as $key=>$value){
          
          $size = filesize("./".$value);
          
          $file = basename($value);
          $data = [];
          $data[ 'manga_detail_id' ] = $lastid;
          $data[ 'filename' ] = $file;
          $data[ 'image_size' ] = $size;
          $data[ 'number' ] = $i;
          $data[ 'regist_ts' ] = date("Y-m-d H:i:s");
          $flg = $this->db->insert('manga_detail_image', $data);
          $i++;
          if(!$flg){
            return false;
          }

        }
      }
      return true;
      
    }
    public function ___getMangaDetail($id = 0){

      if($id < 1){
        return false;
      }
      $where = array(
        'manga_detail_id'=>$id
      );
      $this->db->order_by('number');
      $query = $this->db->get_where("manga_detail_image",$where);
      $result = $query->result();
      return $result;
    }

  }