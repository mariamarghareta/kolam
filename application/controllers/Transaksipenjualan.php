<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksipenjualan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Penjualan');
        $this->load->model('Karyawan');
        $this->load->model('Mitra');
        $this->load->model('Tebar_history');
        $this->load->model('Blok');
        $this->load->model('Kolam');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["selected_kolam"] = "";
        $this->data["selected_kolam_before"] = "";
        $this->data["selected_blok"] = "";
        $this->data["selected_mitra"] = "";
        $this->data["jumlah"] = "";
        $this->data["harga"] = "";
        $this->data["tgl_tebar"] = "";
        $this->data["total"] = "";
        $this->data["keterangan"] = "";
        $this->data["search_word"] = "";
        $this->data["pemberian_pakan_id"] = "";
        $this->data["cb_tutup"] = "";
        $this->data["tebar_id"] = "";
        $this->data["his_id"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Penjualan->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Penjualan->get_count_all();
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
        $this->data["arr_mitra"] = ($this->Mitra->show_all_data());
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($this->data["arr_blok"][0]["id"]);
        }
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
        $this->load->view('penjualan', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('penjualan_form', $this->data);
    }


    public function load_data($deleted){
        if($deleted == 0){
            $datum = $this->Penjualan->get($this->data['id'])[0];
        } else if($deleted == 1){
            $datum = $this->Penjualan->get_data($this->data['id'])[0];
        }
        $this->data["id"] = $datum->id;
        $this->data["selected_kolam"] = $datum->kolam_id;
        $this->data["selected_kolam_before"] = $datum->kolam_id;
        $this->data["selected_blok"] = $datum->blok_id;
        $this->data["selected_mitra"] = $datum->mitra_bisnis_id;
        $this->data["jumlah"] = $datum->jumlah;
        $this->data["harga"] = $datum->harga;
        $this->data["tgl_tebar"] = $datum->dt;
        $this->data["total"] = $datum->total;
        $this->data["keterangan"] = $datum->keterangan;
        $this->data["pemberian_pakan_id"] = $datum->pemberian_pakan_id;
        $this->data["tebar_id"] = $datum->tebar_id;
        $this->data["cb_tutup"] = $datum->tutup_kolam;
        $datum_his = $this->Tebar_history->get_by_jual($datum->id)[0];
        $this->data["his_id"] = $datum_his->id;
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "update";
        $this->load(0);
        $this->load->view('penjualan_form', $this->data);
    }

    public function data_show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "show";

        $this->load_data(1);
        $this->load->view('penjualan_form', $this->data);
    }

    public function show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "show";

        $this->load_data(0);
        $this->load->view('penjualan_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $this->load_data(0);
        $this->load->view('penjualan_form', $this->data);
    }

    public function get_form_data(){
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric|greater_than[0]', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka', 'greater_than' => '%s harus lebih besar dari 0'));
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka', 'greater_than' => '%s harus lebih besar dari 0'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['selected_blok'] = $this->input->post('tblok');
        $this->data['selected_kolam'] = $this->input->post('tkolam');
        $this->data['selected_kolam_before'] = $this->input->post('selected_kolam_before');
        $this->data['selected_mitra'] = $this->input->post('cb_mitra');
        $this->data['harga'] = $this->input->post('harga');
        $this->data['jumlah'] = $this->input->post('jumlah');
        $this->data['total'] = $this->input->post('total');
        $this->data['keterangan'] = $this->input->post('keterangan');
        $this->data['tebar_id'] = $this->input->post('tebar_id');
        $this->data['pemberian_pakan_id'] = $this->input->post('pemberian_pakan_id');
        $this->data['his_id'] = $this->input->post('his_id');
        $this->data['cb_tutup'] = $this->input->post('cb_tutup');
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();

        if ($this->form_validation->run() != FALSE)
        {
            if($this->data['selected_kolam'] != "" and $this->data['selected_mitra'] != ""){
                $tutup = 0;
                if (isset($_POST['cb_tutup'])){
                    $tutup = 1;
                }
                $result = $this->Penjualan->insert($this->data['selected_mitra'], $this->data['selected_kolam'], $this->data['tebar_id'], $this->data['pemberian_pakan_id'], $this->data['jumlah']
                    , $this->data['harga'], $this->data['total'], $this->data['keterangan'], $tutup, $_SESSION['id']);

                if($result){
                    $tebar_history = $this->Tebar_history->insert($this->data['tebar_id'], 0, 0, "Penjualan Ikan", 0, $this->data['selected_kolam'], $_SESSION['id'], $result);
                    if($tebar_history){
                        if ($tutup == 1){
                            $empty = $this->Kolam->update_pemberian_pakan(0, 0, $this->data['selected_kolam'], $_SESSION['id']);
                            if($empty){
                                $tebar_history = $this->Tebar_history->insert($this->data['tebar_id'], 0, 0, "Tutup Kolam", 0, $this->data['selected_kolam'], $_SESSION['id'], $result);
                                if($tebar_history){
                                    redirect('Transaksipenjualan');
                                }
                            }
                        } else {
                            redirect('Transaksipenjualan');
                        }
                    }
                }
            }
        }
        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
        $this->data["state"] = "create";
        $this->load->view('penjualan_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();

        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->data['selected_kolam'] != $this->data['selected_kolam_before']){
                    $result = $this->Penjualan->update($this->data['selected_mitra'], $this->data['selected_kolam'],  $this->data['tebar_id'], $this->data['pemberian_pakan_id'], $this->data['jumlah'], $this->data['harga'], $this->data['total'], $this->data['keterangan'], $this->data['id'], $_SESSION['id']);
                    if($result){
                        $tebar_history = $this->Tebar_history->update_by_tebar($this->data['selected_kolam'], $this->data['tebar_id'], $this->data['his_id'], $_SESSION['id']);
                        if($tebar_history){
                            redirect('Transaksipenjualan');
                        }
                    }
                } else {
                    $result = $this->Penjualan->update($this->data['selected_mitra'], $this->data['selected_kolam'],  $this->data['tebar_id'], $this->data['pemberian_pakan_id'], $this->data['jumlah'], $this->data['harga'], $this->data['total'], $this->data['keterangan'], $this->data['id'], $_SESSION['id']);
                    if($result){
                        redirect('Transaksipenjualan');
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('penjualan_form', $this->data);
        } else {
            redirect('Transaksipenjualan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();
        if($this->input->post('delete') == "delete") {
            $result = $this->Penjualan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                $history = $this->Tebar_history->delete($this->data['his_id'], $_SESSION['id']);
                if($history){
                    redirect('Transaksipenjualan');
                }
            }
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            $this->data["state"] = "delete";
            $this->load->view('penjualan_form', $this->data);
        } else {
            redirect('Transaksipenjualan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Penjualan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Penjualan->get_count_all()]);

    }
}
