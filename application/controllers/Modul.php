<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . "libraries/REST_Controller.php";
class Modul extends REST_Controller {
    function __construct(){
        parent::__construct();
        $this->load->model('modul_model','modul');
    }
    public function index_get() {
        $data = $this->modul->getModul();
        $this->response($data,REST_Controller::HTTP_OK);
    }
}