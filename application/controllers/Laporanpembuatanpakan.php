<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanpembuatanpakan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Ikan');
        $this->load->model('Karyawan');
        $this->load->model('Pembuatan_pakan');
        $this->load->model('Tebar_history');
        $this->load->model('Sampling');
        $this->load->model('Grading');
        $this->load->model('Penjualan');
    }
    private $data;


    public function initialization()
    {
        $dt_from = new DateTime('first day of this month');
        $dt_from->setTimezone(new DateTimeZone('GMT+7'));

        $dt_to = new DateTime('last day of this month');
        $dt_to->setTimezone(new DateTimeZone('GMT+7'));

        $this->data["date_from"] = $dt_from->format("Y-m-d");
        $this->data["date_to"] = $dt_to->format("Y-m-d");
    }


    public function check_role(){
        if(isset($_SESSION['hash'], $_SESSION['hash'])){
            $double_login = $this->Karyawan->check_double_login($_SESSION['id'], $_SESSION['hash']);
            $newdata = array(
                'role_id'  => $_SESSION['role_id'],
                'uname'     => $_SESSION['uname'],
                'id' => $_SESSION['id']
            );
            $this->session->set_tempdata($newdata, NULL, $this->Timeout->get_time()->minute * 60);
            if($double_login){
                session_destroy();
                redirect('Login');
            }
        } else {
            session_destroy();
            redirect('Login');
        }
    }


    public function index()
    {
        $this->check_role();
        $this->initialization();
        $this->load->view('laporan_pem_pakan', $this->data);
    }


    public function search(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('date_from', 'Dari Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('date_to', 'Sampai Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->data['date_from'] = $this->input->post('date_from');
        $this->data['date_to'] = $this->input->post('date_to');
        $this->data['history'] = [];
        if ($this->form_validation->run() != FALSE)
        {
            $this->data['pemberian_pakan'] = $this->Pembuatan_pakan->get_report($this->data['date_from'], (string)$this->data['date_to'] . " 23:59:59");
            for($i=0; $i<sizeof($this->data['pemberian_pakan']) ; $i++){
                $list = $this->Pembuatan_pakan->get_bahan_penolong($this->data["pemberian_pakan"][$i]["id"]);
                $this->data['history'][$i] = [];
                for($j=0; $j<sizeof($list); $j++){
                    $this->data['history'][$i][$j] = $list[$j];
                }
            }
        }
//        print_r($this->data["history"]);
        $this->data["state"] = "create";
        $this->load->view('laporan_pem_pakan_pdf', $this->data);
    }
}
