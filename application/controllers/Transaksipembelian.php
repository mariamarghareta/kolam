<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksipembelian extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Pembelian');
        $this->load->model('Karyawan');
        $this->load->model('Pakan');
        $this->load->model('Obat');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["selected_pakan"] = "";
        $this->data["jumlah_item"] = "";
        $this->data["harga_per_item"] = "";
        $this->data["total_harga"] = "";
        $this->data["isi"] = "";
        $this->data["total_isi"] = "";
        $this->data["keterangan"] = "";
        $this->data["tipe_pembelian"] = "";
        $this->data["tipe_pembelian_before"] = "";
        $this->data["item_id_before"] = "";
        $this->data["nama_lain"] = "";
        $this->data["satuan"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Pembelian->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Pembelian->get_count_all();
        $this->data["arr_pakan"] = ($this->Pakan->get_all());
        $this->data["arr_obat"] = ($this->Obat->show_all_data());
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
        $this->load->view('pembelian', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('pembelian_form', $this->data);
    }


    public function load_data(){
        $this->data['tipe_pembelian'] = $this->uri->segment(3);
        $this->data['tipe_pembelian_before'] = $this->uri->segment(3);
        $this->data['id'] = $this->uri->segment(4);

        $datum = $this->Pembelian->get($this->data['id'], $this->data['tipe_pembelian'])[0];
        if($this->data['tipe_pembelian'] == 'p'){
            $this->data['selected_pakan'] = $datum->pakan_id;
            $this->data["item_id_before"] = $datum->pakan_id;
        } else if($this->data['tipe_pembelian'] == 'o'){
            $this->data['selected_obat'] = $datum->obat_id;
            $this->data["item_id_before"] = $datum->obat_id;
        }  else if($this->data['tipe_pembelian'] == 'l'){
            $this->data['nama_lain'] = $datum->name;
        }
        $this->data["id"] = $datum->id;
        $this->data["jumlah_item"] = $datum->jumlah_item;
        $this->data["harga_per_item"] = $datum->harga_per_item;
        $this->data["total_harga"] = $datum->total_harga;
        $this->data["isi"] = $datum->isi;
        $this->data["total_isi"] = $datum->total_isi;
        $this->data["keterangan"] = $datum->keterangan;
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->data["state"] = "update";
        $this->load->view('pembelian_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->data["state"] = "delete";
        $this->load->view('pembelian_form', $this->data);
    }

    public function load_form_data(){
        $this->data["tipe_pembelian"] = $this->input->post('tipe_pembelian');
        $this->data["selected_obat"] = $this->input->post('cb_obat');
        $this->data["selected_pakan"] = $this->input->post('cb_pakan');


        $this->data["jumlah_item"] = $this->input->post('jumlah_item');
        $this->data["harga_per_item"] = $this->input->post('harga_per_item');
        $this->data["total_harga"] = $this->input->post('total_harga');
        $this->data["isi"] = $this->input->post('isi');
        $this->data["total_isi"] = $this->input->post('total_isi');
        $this->data["keterangan"] = $this->input->post('keterangan');
        $this->data["nama_lain"] = $this->input->post('nama_lain');
        $this->data["id"] = $this->input->post('tid');
        $this->data["tipe_pembelian_before"] = $this->input->post('tipe_pembelian_before');
        $this->data["item_id_before"] = $this->input->post('item_id_before');

        $this->form_validation->set_rules('jumlah_item', 'Jumlah Item', 'required|greater_than[0]', array('required' => '%s harus diisi', 'greater_than' => '%s harus lebih besar dari 0'));
        $this->form_validation->set_rules('harga_per_item', 'Harga per Item', 'required|greater_than[0]', array('required' => '%s harus diisi', 'greater_than' => '%s harus lebih besar dari 0'));
        if($this->data["tipe_pembelian"] != 'l') {
            $this->form_validation->set_rules('isi', 'Isi Item', 'required|greater_than[0]', array('required' => '%s harus diisi', 'greater_than' => '%s harus lebih besar dari 0'));
        }
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();
        if ($this->form_validation->run() != FALSE)
        {
            $this->inserting_data();
        }
        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
        $this->data["state"] = "create";
        $this->load->view('pembelian_form', $this->data);
    }


    public function inserting_data(){
        if($this->data["tipe_pembelian"] == "o"){
            $result = $this->Pembelian->insert_obat($this->data["selected_obat"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], $this->data["isi"], $this->data["total_isi"], $this->data["keterangan"], $_SESSION['id']);
            if($result == 1){
                #tambah stok
                $temp = $this->Obat->tambah_stok($this->data["selected_obat"], $this->data["total_isi"], $_SESSION['id']);
                if($temp){
                    redirect('Transaksipembelian');
                }
            }
        } else if($this->data["tipe_pembelian"] == "p"){
            $result = $this->Pembelian->insert_pakan($this->data["selected_pakan"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], $this->data["isi"], $this->data["total_isi"], $this->data["keterangan"], $_SESSION['id']);
            if($result == 1){
                #tambah stok
                $temp = $this->Pakan->tambah_stok($this->data["selected_pakan"], $this->data["total_isi"], $_SESSION['id']);
                if($temp){
                    redirect('Transaksipembelian');
                }
            }
        } else if($this->data["tipe_pembelian"] == "l"){
            $result = $this->Pembelian->insert_lain($this->data["nama_lain"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], 0, 0, $this->data["keterangan"], $_SESSION['id']);
            if($result == 1){
                redirect('Transaksipembelian');
            }
        }
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();

        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE) {
                if($this->data['tipe_pembelian'] != $this->data['tipe_pembelian_before']){
//                  WARNING BAGIAN INI BELUM SELESAI
                    $this->Pembelian->delete($this->data['id'],  $this->data['tipe_pembelian_before'], $_SESSION['id']);
                    if($this->data['tipe_pembelian_before'] == 'p'){
                        $this->Pakan->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                    } else if($this->data['tipe_pembelian_before'] == 'o'){
                        $this->Obat->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                    }
                    $this->inserting_data();

                } else {
                    if($this->data['tipe_pembelian'] == 'l') {
                        $result = $this->Pembelian->update_lain($this->data["nama_lain"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], $this->data["isi"], $this->data["total_isi"], $this->data["keterangan"], $this->data['id'], $_SESSION['id']);
                        if ($result == 1) {
                            redirect('Transaksipembelian');
                        }
                    } else if($this->data['tipe_pembelian'] == 'o') {
                        $result = $this->Pembelian->update_obat($this->data["selected_obat"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], $this->data["isi"], $this->data["total_isi"], $this->data["keterangan"], $this->data['id'], $_SESSION['id']);
                        if ($result == 1) {
                            if($this->data["selected_obat"] != $this->data["item_id_before"]){
                                $this->Obat->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                            }
                            $this->Obat->update_live_stok($this->data["selected_obat"], $_SESSION['id']);
                            redirect('Transaksipembelian');
                        }
                    } else if($this->data['tipe_pembelian'] == 'p') {
                        $result = $this->Pembelian->update_pakan($this->data["selected_pakan"], $this->data["jumlah_item"], $this->data["harga_per_item"], $this->data["total_harga"], $this->data["isi"], $this->data["total_isi"], $this->data["keterangan"], $this->data['id'], $_SESSION['id']);
                        if ($result == 1) {
                            if($this->data["selected_pakan"] != $this->data["item_id_before"]){
                                $this->Pakan->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                            }
                            $this->Pakan->update_live_stok($this->data["selected_pakan"], $_SESSION['id']);
                            redirect('Transaksipembelian');
                        }
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('pembelian_form', $this->data);
        }else {
            redirect('Transaksipembelian');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();

        if($this->input->post('delete') == "delete") {
            $result = $this->Pembelian->delete($this->data['id'],  $this->data['tipe_pembelian_before'], $_SESSION['id']);
            if($result == 1){
                if($this->data['tipe_pembelian_before'] == 'p'){
                    $this->Pakan->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                } else if($this->data['tipe_pembelian_before'] == 'o'){
                    $this->Obat->update_live_stok($this->data["item_id_before"], $_SESSION['id']);
                }
                redirect('Transaksipembelian');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('pembelian_form', $this->data);
        } else {
            redirect('Transaksipembelian');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Pembelian->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Pembelian->get_count_all()]);

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
