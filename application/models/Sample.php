<?php

class Sample extends CI_Model {

    function __construct(){
      parent::__construct();
      $this->load->database();
    }

    function getData($page){
      $query = $this->db->get("sample");

      return $query->result();
    }
  }