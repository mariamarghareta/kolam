<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Pakan');
        $this->load->model('Obat');
        $this->load->model('Karyawan');
    }
    private $data;

    public function check_role(){
        if(isset($_SESSION['hash'], $_SESSION['hash'])){
            $double_login = $this->Karyawan->check_double_login($_SESSION['id'], $_SESSION['hash']);
            $newdata = array(
                'role_id'  => $_SESSION['role_id'],
                'uname'     => $_SESSION['uname'],
                'id' => $_SESSION['id']
            );
            $this->session->set_tempdata($newdata, NULL, $this->Timeout->get_time()->minute * 60);
            print $double_login;
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
        $this->load->view('monitoring', $this->data);
    }


    public function logout(){
        session_destroy();
        redirect('Login');
    }


    public function initialization(){
        $data_count = 10;
        $offset = 1;
        $this->data["search_word"] = "";
        $this->data["arr_pakan"] = json_encode($this->Pakan->get_all());
        $this->data["arr_obat"] = json_encode($this->Obat->show_all_data());
    }
}
