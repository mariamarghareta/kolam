<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stockadj extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Adj');
        $this->load->model('Obat');
        $this->load->model('Pakan');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["stok"] = 0;
        $this->data["tipe_pembelian"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Adj->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["arr_pakan"] = ($this->Pakan->get_all());
        $this->data["arr_obat"] = ($this->Obat->show_all_data());
        $this->data["max_data"] = $this->Adj->get_count_all();
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
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
        $this->load->view('invadj', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('invadj_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();

        $this->data['tipe_pembelian'] = $this->input->post('tipe_pembelian');
        $this->data['selected_pakan'] = $this->input->post('cb_pakan');
        $this->data['selected_obat'] = $this->input->post('cb_obat');
        $this->data['stok'] = $this->input->post('stok');
        if($this->data['tipe_pembelian'] == 'o'){
            $result = $this->Adj->insert_obat($this->data['selected_obat'], $this->data['stok'],  $_SESSION['id']);
            if($result){
                $result = $this->Obat->update_stok($this->data['selected_obat'], $this->data['stok'], $_SESSION['id']);
            }
        } else if($this->data['tipe_pembelian'] == 'p'){
            $result = $this->Adj->insert_pakan($this->data['selected_pakan'], $this->data['stok'],  $_SESSION['id']);
            if($result){
                $result = $this->Pakan->update_stok($this->data['selected_pakan'], $this->data['stok'], $_SESSION['id']);
            }
        }
        if($result == 1){
            redirect('Stockadj');
        }else{
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
        }
        $this->data["state"] = "create";
        $this->load->view('invadj_form', $this->data);
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Adj->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Adj->get_count_all()]);

    }


    public function getSatuan(){
        $tipe = $this->input->post('tipe');
        $id = $this->input->post('id');

        if($tipe == 'o'){
            echo json_encode($this->Obat->get($id));
        } else if ($tipe == 'p'){
            echo json_encode($this->Pakan->get($id));
        }
    }
}
