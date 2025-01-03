<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Modul_model extends CI_Model {
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function getModul(){
        $data = $this->db->get('modul');
        return $data->result_array();
    }
}