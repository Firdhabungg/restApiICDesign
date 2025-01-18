<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . "libraries/REST_Controller.php";
class Modul extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('modul_model', 'modul');
    }
    public function index_get()
    {
        $data = $this->modul->getModul();
        $this->response($data, REST_Controller::HTTP_OK);
    }
    public function detail_get()
    {
        // Ambil parameter dari query string
        $id = $this->get('id_modul');

        // Validasi jika parameter tidak ada
        if ($id == null) {
            $this->response([
                'status' => false,
                'message' => 'Parameter id_modul is required'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }
        // Ambil data dari model
        $data = $this->modul->getModulById($id);
        // Jika data ditemukan
        if (!empty($data)) {
            $this->response([
                'status' => true,
                'data' => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
