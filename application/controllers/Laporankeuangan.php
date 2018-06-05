<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporankeuangan extends CI_Controller {
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
        $this->load->model('Laporan_keuangan');
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
        $this->load->view('laporankeuangan', $this->data);
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
            $this->data['detail'] = $this->Laporan_keuangan->show_all($this->data['date_from'], $this->data['date_to']);
        }
        $this->data["state"] = "create";
        $this->load->view('laporankeuangan_pdf', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama jenis ikan', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['name'] = $this->input->post('tname');
        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->Ikan->cek_kembar($this->data['name'], $this->data['id']) == false){
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Nama ikan kembar</div>";
                } else {
                    $result = $this->Ikan->update($this->data['name'], $this->data['id'], $_SESSION['id']);
                    if($result == 1){
                        redirect('Masterikan');
                    }else{
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('laporankeuangan', $this->data);
        } else {
            redirect('Masterikan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Ikan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                redirect('Masterikan');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('laporankeuangan', $this->data);
        } else {
            redirect('Masterikan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Ikan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Ikan->get_count_all()]);

    }
}
