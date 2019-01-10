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
        $this->load->model('Monitoring_pakan');
        $this->load->model('Karyawan');
        $this->load->model('Pemberian_pakan');
        $this->load->model('Kolam');
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
        $dt_today = new DateTime();
        $dt_today->setTimezone(new DateTimeZone('GMT+7'));
        $this->data["search_word"] = "";
        $this->data["arr_pakan"] = json_encode($this->Pakan->get_all());
        $this->data["arr_obat"] = json_encode($this->Obat->show_all_data());
        $this->data["arr_monitoring"] = json_encode($this->Monitoring_pakan->monitoring_all());
        $this->data["arr_kolam_kosong"] = json_encode($this->Kolam->get_all_not_occupied_kolam());
        $this->data["total_pakan"] = $this->Pemberian_pakan->sum_all_pakan()[0];
        $this->data["date_filter"] = $this->Pemberian_pakan->sum_all_pakan()[0];
        $this->data["date_filter"] = $dt_today->format("Y-m-d");
    }

    public function print_pakan(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('pemberian_pakan_id');
        $this->data['waktu'] = $this->input->post('waktu');
        $this->data['data_pakan'] = $this->Pemberian_pakan->get($this->data['id'])[0];
        $this->data['hari_ini_date'] = $this->Pemberian_pakan->get_now_date();
        $this->data['hari_ini_time'] = $this->Pemberian_pakan->get_now_time();

        $this->load->view('print_pemberian_pakan', $this->data);
    }

    public function get_monitoring_by_date(){
        $dt = $this->input->post('dt');
        echo json_encode($this->Monitoring_pakan->get_all_monitoring_by_date($dt));
    }
}
