<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporanmonsayur extends CI_Controller {
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
        $this->load->model('Laporan_mon_sayur');
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
        $this->load->view('laporan_mon_sayur', $this->data);
    }


    public function search(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('date_from', 'Dari Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('date_to', 'Sampai Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->data['date_from'] = $this->input->post('date_from');
        $this->data['date_to'] = $this->input->post('date_to');

        if ($this->form_validation->run() != FALSE)
        {
            $this->data['detail'] = $this->Laporan_mon_sayur->show_all($this->data['date_from'], $this->data['date_to']);
        }
        $this->data["state"] = "create";
        $this->load->view('laporan_mon_sayur_pdf', $this->data);
    }
}
