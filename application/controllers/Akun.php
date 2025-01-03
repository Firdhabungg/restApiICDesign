<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Akun extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('akun_model');
    }
    function index_post(){
        $username = $this->post('username', TRUE);
        $password = $this->post('password', TRUE);

        $data = $this->akun_model->login($username, $password);
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
}