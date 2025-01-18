<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Modul_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function getModul()
    {
        return $this->db->get('modul')->result_array();
    }
    public function getModulById($id)
    {
        return $this->db->get_where('modul', ['id_modul' => $id])->row_array();
    }
}
