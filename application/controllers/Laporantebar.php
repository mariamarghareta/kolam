<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporantebar extends CI_Controller {
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
        $this->load->model('Tebar');
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

        $this->data["date_from"] = $dt_from->format("d-m-Y");
        $this->data["date_to"] = $dt_to->format("d-m-Y");
        $this->data["arr_tebar"] = $this->Tebar->get_kode_all();
        $this->data["cb_cari"] = "tgl";
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
        $this->load->view('laporan_tebar', $this->data);
    }


    public function search(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('date_from', 'Dari Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('date_to', 'Sampai Tanggal', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->data['date_from'] = $this->input->post('date_from');
        $this->data['date_to'] = $this->input->post('date_to');
        $this->data['cb_cari'] = $this->input->post('cb_cari');
        $this->data['cb_tebar'] = $this->input->post('cb_tebar');
        $this->data['details'] = [];
        $this->data['history'] = [];
        $this->data['sampling'] = [];
        $this->data['grading'] = [];
        $this->data['jual'] = [];
        if ($this->form_validation->run() != FALSE)
        {
            if($this->data["cb_cari"] == "tgl"){
                $this->data['tebar'] = $this->Tebar->get_report($this->data['date_from'], (string)$this->data['date_to'] . " 23:59:59");
            } else {
                $this->data['tebar'] = $this->Tebar->get_array($this->data['cb_tebar']);
            }
            for($i=0; $i<sizeof($this->data['tebar']) ; $i++){
                $this->data['details'][$i] = [];
                $this->data['details'][$i]["tebar"] = $this->data["tebar"][$i];
                $history = $this->Tebar_history->show_all($this->data["tebar"][$i]["id"]);
                $this->data['history'][$i] = [];
                $this->data['sampling'][$i] = [];
                $this->data['grading'][$i] = [];
                $this->data['jual'][$i] = [];
                for($j=0; $j<sizeof($history); $j++){
                    $this->data['history'][$i][$j] = $history[$j];

                    if ((string)$history[$j]->sampling_id != "0"){
                        $temp = $this->Sampling->get_without_check($history[$j]->sampling_id);
                        if(sizeof($temp) > 0){
                            $temp = $temp[0];
                            $this->data['sampling'][$i][$j] = $temp;
                            $this->data['grading'][$i][$j] = [];
                            $this->data['jual'][$i][$j] = [];
                        }
                    }else if ((string)$history[$j]->grading_id != "0"){
                        $temp = $this->Grading->get_without_check($history[$j]->grading_id);
                        if(sizeof($temp) > 0) {
                            $temp = $temp[0];
                            $this->data['sampling'][$i][$j] = [];
                            $this->data['grading'][$i][$j] = $temp;
                            $this->data['jual'][$i][$j] = [];
                        }
                    }else if ((string)$history[$j]->jual_id != "0"){
                        $temp = $this->Penjualan->get_data($history[$j]->jual_id);
                        if(sizeof($temp) > 0) {
                            $temp = $temp[0];
                            $this->data['sampling'][$i][$j] = [];
                            $this->data['grading'][$i][$j] = [];
                            $this->data['jual'][$i][$j] = $temp;
                        }
                    }
                }
            }
        }
//        print_r($this->data["sampling"]);
        $this->data["state"] = "create";
        $this->load->view('laporan_tebar_pdf', $this->data);
    }
}
