<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Karyawan');
        $this->load->model('Timeout');
    }
    private $data;


    public function index()
    {
        $this->initialization();
        $this->load->view('login', $this->data);
    }


    public function initialization()
    {
        $this->data["err_msg"] = "";
    }

    
    public function check()
    {
        $this->initialization();
        if ($this->input->post('submit')==true) {
            $data['uname']=$this->input->post('uname');
            $data['pass']=$this->input->post('pass');
            $temp=$this->Karyawan->grab_data_login($data['uname'], $data['pass']);
            if ($temp){
                if($temp['role_id']==null){
                    $this->data['err_msg']=
                        "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>
                        Username atau password salah
                        </div>";
                } else {
                    $newdata = array(
                        'role_id'  => $temp['role_id'],
                        'uname'     => $temp['uname'],
                        'id' => $temp['id']
                    );
                    $this->session->set_tempdata($temp, NULL, $this->Timeout->get_time()->minute * 60);
                    redirect('Monitoring');
                }
            }else{
                $this->data['err_msg'] =  "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'> Username atau password salah </div>";
            }
        }
        $this->load->view('login', $this->data);
    }
}
