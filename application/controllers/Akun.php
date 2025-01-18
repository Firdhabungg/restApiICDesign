<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Akun extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('akun_model', 'akun');
        $this->load->library('form_validation');
    }
    // Get based username
    public function index_get()
    {
        $username = $this->get('username');

        if ($username === null) {
            $this->response([
                'status' => false,
                'message' => 'Username is required'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $data = $this->akun->getAkunByUsername($username);

        if ($data) { //jika benar 
            $this->response($data, REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    // Login
    function index_post(){
        $username = $this->post('username', TRUE);
        $password = $this->post('password', TRUE);

        $data = $this->akun->login($username, $password);
        if($data){
                $output = [
                'success' => true,
                'message' => 'Login berhasil',
                'data' => $data
            ];
            $this->response($output, REST_Controller::HTTP_OK);
        }else {
            $output = [
                'success' => false,
                'message' => 'Login gagal, Cek username dan password anda',
                'data' => null
            ];
            $this->response($output, REST_Controller::HTTP_OK);
            $this->output->_display();
            exit();
        }
    }
    // Register
    public function register_post() {
        // Set aturan validasi
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]|max_length[20]|is_unique[akun.username]', [
            'required' => 'Username wajib diisi',
            'is_unique' => 'Username sudah digunakan',
            'min_length' => 'Username minimal 4 karakter',
            'max_length' => 'Username maksimal 20 karakter'
        ]);
        
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[50]', [
            'required' => 'Password wajib diisi',
            'min_length' => 'Password minimal 4 karakter'
        ]);
        
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|max_length[50]', [
            'required' => 'Nama lengkap wajib diisi',
            'max_length' => 'Nama lengkap maksimal 50 karakter'
        ]);
        
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[akun.email]', [
            'required' => 'Email wajib diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah terdaftar'
        ]);

        // Jalankan validasi
        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->response([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Ambil dan bersihkan data input
        $data = [
            'username' => $this->security->xss_clean($this->post('username')),
            // 'password' => password_hash($this->post('password'), PASSWORD_DEFAULT),
            'password' => $this->post('password'),
            'nama_lengkap' => $this->security->xss_clean($this->post('nama_lengkap')),
            'email' => $this->security->xss_clean($this->post('email'))
        ];

        try {
            // Kirim ke model akun
            $result = $this->akun->create_akun($data);

            if ($result) {
                // Hapus password dari response
                unset($data['password']);
                
                $this->response([
                    'success' => true,
                    'message' => 'Registrasi berhasil',
                    'data' => $data
                ], REST_Controller::HTTP_CREATED);
            }
        } catch (Exception $e) {
            log_message('error', 'Registration Error: ' . $e->getMessage());
            $this->response([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server',
                'data' => null
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
}