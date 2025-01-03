<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Akun_model extends CI_Model{
    function __construct(){
        parent::__construct();
        $this->load->database();
    }
    public function login($username, $password){
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $data = $this->db->get('akun');
        return $data->row_array();
    }
}
