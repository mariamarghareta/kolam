<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoringpakan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Kolam');
        $this->load->model('Blok');
        $this->load->model('Pakan');
        $this->load->model('Monitoring_pakan');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["keterangan"] = "";
        $this->data["jumlah_pakan"] = 0;
        $this->data["selected_pakan"] = 0;
        $this->data["selected_pakan_before"] = 0;
        $this->data["selected_waktu"] = "";
        $this->data["mr"] = 0;
        $this->data["kolam_id"] = 0;
        $this->data["total_ikan"] = 0;
        $this->data["biomass"] = 0;
        $this->data["size"] = 0;
        $this->data["pakan_id"] = 0;
        $this->data["tebar_id"] = 0;
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Monitoring_pakan->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Monitoring_pakan->get_count_all();
        $this->data["tgl_tebar"] = $this->Monitoring_pakan->get_now();
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($this->data["arr_blok"][0]["id"]);
        }
        $this->data["arr_pakan"] = ($this->Pakan->get_all_instock());
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
        $this->load->view('monitoringpakan', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('monitoringpakan_form', $this->data);
    }


    public function load_data(){
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "update";
        $datum = $this->Monitoring_pakan->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["selected_blok"] = $datum->blok_id;
        $this->data["selected_kolam"] = $datum->kolam_id;
        $this->data["kolam_id_before"] = $datum->kolam_id;
        $this->data["selected_waktu"] = $datum->waktu_pakan;
        $this->data["selected_pakan"] = $datum->pakan_id;
        $this->data["selected_pakan_before"] = $datum->pakan_id;
        $this->data["jumlah_pakan"] = $datum->jumlah_pakan;
        $this->data["mr"] = $datum->mr;
        $this->data["keterangan"] = $datum->keterangan;
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->load->view('monitoringpakan_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->data["state"] = "delete";
        $this->load->view('monitoringpakan_form', $this->data);
    }


    public function get_form_data(){
        $this->form_validation->set_rules('jenis_pakan', 'Jenis Pakan', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('jumlah_pakan', 'Jumlah Pakan', 'required|greater_than[0]', array('required' => '%s harus diisi', 'greater_than' => '%s harus lebih besar dari 0'));
        $this->form_validation->set_rules('mr', 'MR', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data["id"] = $this->input->post("tid");
        $this->data["keterangan"] = $this->input->post("keterangan");
        $this->data["jumlah_pakan"] = $this->input->post("jumlah_pakan");
        $this->data["selected_pakan"] = $this->input->post("jenis_pakan");
        $this->data["selected_pakan_before"] = $this->input->post("selected_pakan_before");
        $this->data["selected_waktu"] = $this->input->post("waktu_pakan");
        $this->data["mr"] = $this->input->post("mr");
        $this->data["kolam_id"] = $this->input->post("tkolam");
        $this->data["kolam_id_before"] = $this->input->post("kolam_id_before");
        $this->data["total_ikan"] = $this->input->post("total_ikan");
        $this->data["biomass"] = $this->input->post("biomass");
        $this->data["size"] = $this->input->post("size");
        $this->data["pakan_id"] = $this->input->post("pakan_id");
        $this->data["tebar_id"] = $this->input->post("tebar_id");
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();

        if ($this->form_validation->run() != FALSE) {
            $result = $this->Monitoring_pakan->insert($this->data["kolam_id"], $this->data["tebar_id"], $this->data["pakan_id"], $this->data["selected_waktu"], $this->data["selected_pakan"], $this->data["jumlah_pakan"], $this->data["mr"], $this->data["keterangan"], $_SESSION['id']);
            if ($result == 1) {
                #kurangi stok pakan
                $update_pakan = $this->Pakan->kurangi_stok($this->data["selected_pakan"], $this->data["jumlah_pakan"], $_SESSION['id']);
                if ($update_pakan) {
                    redirect('Monitoringpakan');
                }
            }
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
            $this->data["state"] = "create";
            $this->load->view('monitoringpakan_form', $this->data);
        }
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this -> get_form_data();

        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE) {
                $result = $this->Monitoring_pakan->update($this->data["kolam_id"], $this->data["tebar_id"], $this->data["pakan_id"], $this->data["selected_waktu"], $this->data["selected_pakan"], $this->data["jumlah_pakan"], $this->data["mr"], $this->data["keterangan"], $this->data["id"], $_SESSION['id']);
                if($result){
                    if($this->data['kolam_id']!=$this->data['kolam_id_before']){
                        $result = $this->Pakan->update_live_stok($this->data["selected_pakan_before"], $_SESSION['id']);
                    }
                    $result = $this->Pakan->update_live_stok($this->data["selected_pakan"], $_SESSION['id']);
                    if($result){
                        redirect('Monitoringpakan');
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('monitoringpakan_form', $this->data);
        }else {
            redirect('Monitoringpakan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this -> get_form_data();
        if($this->input->post('delete') == "delete") {
            $result = $this->Monitoring_pakan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                $result = $this->Pakan->update_live_stok($this->data["selected_pakan_before"], $_SESSION['id']);
                if($result == 1){
                    redirect('Monitoringpakan');
                }
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('monitoringpakan_form', $this->data);
        } else {
            redirect('Monitoringpakan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Monitoring_pakan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Monitoring_pakan->get_count_all()]);

    }


    public function getKolamInfo(){
        $kolam_id = $this->input->post('kolam_id');
        echo json_encode($this->Kolam->get($kolam_id));
    }
}