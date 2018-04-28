<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastertime extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Karyawan');
    }
    private $data;
    public function index()
    {
        $this->check_role();
        $this->clear();
        $this->show();

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

    public function clear(){
        $this->data['time']=$this->Timeout->get_time()->minute;
        $this->data['msg']="";
    }
    public function show(){
        $this->load->view('mastertimeout', $this->data);
    }
    public function change(){
        $this->check_role();
        $this->clear();
        $this->data['time']= $this->input->post('time');


        $this->form_validation->set_rules('time', 'Waktu', 'required|greater_than[0]', array('required'=>'Harus diisi', 'greater_than' => 'Harus lebih besar dari 0'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if($this->form_validation->run() == TRUE){
            $hasil = $this->Timeout->change_time($this->data['time']);
            if($hasil == 1){
                $this->clear();
                $this->data['msg'] = "<div id='err_msg' class='alert alert-success sldown' style='display:none;'>Update Berhasil</div>";
            }else {
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
            }
        }
        $this->show();
    }
    public function logout(){
        session_destroy();
        redirect('Login');
        //echo "log out";
    }
}
