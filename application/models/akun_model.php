<?php
defined("BASEPATH") OR exit("No direct script access allowed");

class Akun_model extends CI_Model {
    private $table = "akun";
    
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $data = $this->db->get('akun');
        return $data->row_array();
    }
    
    public function getAkunByUsername($username) {
        // $this->db->where('username', $username);
        // $query = $this->db->get('akun');
        // return $query->row_array();
        
        // singkat:
        return $this->db->get_where('akun', ['username' => $username])->row_array();
    }

    public function create_akun($data) {
        try {
            $insert_data = [
                'username' => $data['username'],
                // 'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'password' => $data['password'],
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
            ];
            
            $this->db->insert($this->table, $insert_data);
            return $this->db->affected_rows() > 0;
        } catch (Exception $e) {
            log_message('error', 'Error creating user: ' . $e->getMessage());
            return false;
        }
    }

    public function check_username_exists($username) {
        return $this->db->where('username', $username)
                        ->get($this->table)
                        ->num_rows() > 0;
    }
}