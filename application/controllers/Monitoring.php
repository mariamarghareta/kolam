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
    }
    private $data;

    public function check_role(){
        if(isset($_SESSION['role_id'])){
            $newdata = array(
                'role_id'  => $_SESSION['role_id'],
                'uname'     => $_SESSION['uname'],
                'id' => $_SESSION['id']
            );
            $this->session->set_tempdata($newdata, NULL, $this->Timeout->get_time()->minute);
        } else {
            session_destroy();
            redirect('Login');
        }
    }


    public function index()
    {
        $this->check_role();
        $this->load->view('monitoring');
    }


    public function logout(){
        session_destroy();
        redirect('Login');
    }
}
