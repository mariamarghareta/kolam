<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembuatanpakan extends CI_Controller {
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
        $this->load->model('Pembuatan_pakan');
        $this->load->model('Karyawan');
        $this->load->model('Obat');
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
        $this->data["arr"] = json_encode($this->Pembuatan_pakan->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Pembuatan_pakan->get_count_all();
        $this->data["tgl_tebar"] = $this->Pembuatan_pakan->get_now();
        $this->data["list_obat"] = json_encode([]);
        $this->data["arr_obat"] = ($this->Obat->show_all_in_stock());
        $this->data["arr_pakan"] = ($this->Pakan->get_all_instock());
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
        $this->data["create_user"] = "";
        $this->data["create_time"] = "";
        $this->data["write_user"] = "";
        $this->data["write_time"] = "";
        $this->data["jumlah_obat"] = "";
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
        $this->load->view('pembuatan_pakan', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_obat"]);
        }
        $this->data["state"] = "create";
        $this->load->view('pembuatan_pakan_form', $this->data);
    }


    public function load_data(){
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "update";
        $datum = $this->Pembuatan_pakan->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["selected_pakan"] = $datum->pakan_id;
        $this->data["selected_pakan_before"] = $datum->pakan_id;
        $this->data["jumlah_pakan"] = $datum->jumlah_pakan;
        $this->data["keterangan"] = $datum->keterangan;
        $this->data["create_user"] = $datum->create_user;
        $this->data["create_time"] = $datum->create_time;
        $this->data["write_user"] = $datum->write_user;
        $this->data["write_time"] = $datum->write_time;

        $arrobat = [];
        $data = $this->Pembuatan_pakan->get_bahan_penolong($this->data['id']);
        for($i=0; $i <sizeof($data); $i ++){
            $idx = sizeof($arrobat);
            $arrobat[$idx]["obat_id"] = $data[$i]["obat_id"];
            $arrobat[$idx]["obat_name"] = $data[$i]["obat_name"];
            $arrobat[$idx]["jumlah"] =  $data[$i]["jumlah"];
            $arrobat[$idx]["satuan"] =  $data[$i]["satuan"];
        }
        $this->session->set_tempdata(["list_obat" => $arrobat], NULL, 5 * 60);
        $this->data["list_obat"] = json_encode($arrobat);
    }

    public function show(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->data["state"] = "show";
        $this->load->view('pembuatan_pakan_form', $this->data);
    }

    public function update(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->load->view('pembuatan_pakan_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->load_data();
        $this->data["state"] = "delete";
        $this->load->view('pembuatan_pakan_form', $this->data);
    }


    public function get_form_data(){
        $this->form_validation->set_rules('jenis_pakan', 'Jenis Pakan', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('jumlah_pakan', 'Jumlah Pakan', 'required|greater_than[-1]', array('required' => '%s harus diisi', 'greater_than' => '%s harus lebih besar dari 0'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data["id"] = $this->input->post("tid");
        $this->data["keterangan"] = $this->input->post("keterangan");
        $this->data["jumlah_pakan"] = $this->input->post("jumlah_pakan");
        $this->data["selected_pakan"] = $this->input->post("jenis_pakan");
        $this->data["selected_pakan_before"] = $this->input->post("selected_pakan_before");
        $this->data["pakan_id"] = $this->input->post("pakan_id");
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();

        if ($this->form_validation->run() != FALSE) {
            $result = $this->Pembuatan_pakan->insert($this->data["selected_pakan"], $this->data["jumlah_pakan"], $this->data["keterangan"], $_SESSION['id']);
            if ($result) {
                #kurangi stok pakan
                $update_pakan = $this->Pakan->kurangi_stok($this->data["selected_pakan"], $this->data["jumlah_pakan"], "gr", $_SESSION['id']);
                if ($update_pakan) {
                    $is_error = False;
                    if (isset($_SESSION['list_obat'])) {
                        $temp = $_SESSION['list_obat'];
                        for ($i = 0; $i < sizeof($temp); $i++) {
                            $res = $this->Pembuatan_pakan->insert_bahan_penolong($result, $temp[$i]["obat_id"], $temp[$i]["jumlah"], $temp[$i]["satuan"], $_SESSION['id']);
                            if ($res <= 0) {
                                $is_error = True;
                            } else {
                                $this->Obat->kurangi_stok($temp[$i]["obat_id"], $temp[$i]["jumlah"], $_SESSION['id']);
                            }
                        }
                    }
                    if (!$is_error) {
                        unset($_SESSION["list_obat"]);
                        redirect('Pembuatanpakan');
                    }
                }
            }
        }
        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
        $this->data["state"] = "create";
        $this->load->view('pembuatan_pakan_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this -> get_form_data();

        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE) {
                $result = $this->Pembuatan_pakan->update($this->data["selected_pakan"], $this->data["jumlah_pakan"], $this->data["keterangan"], $this->data["id"], $_SESSION['id']);
                if($result){
                    if($this->data['selected_pakan']!=$this->data['selected_pakan_before']){
                        $result = $this->Pakan->update_live_stok($this->data["selected_pakan_before"], $_SESSION['id']);
                    }
                    $result = $this->Pakan->update_live_stok($this->data["selected_pakan"], $_SESSION['id']);
                    if($result){
                        $is_error = False;
                        if(isset($_SESSION['list_obat'])){
//                        delete existing bahan penolong
                            $detail = $this->Pembuatan_pakan->get_bahan_penolong($this->data["id"]);
                            $this->Pembuatan_pakan->delete_bahan_penolong($this->data["id"], $_SESSION['id']);
                            for($i=0; $i<sizeof($detail); $i++){
                                $this->Obat->update_live_stok($detail[$i]["obat_id"], $_SESSION['id']);
                            }
                            $temp = $_SESSION['list_obat'];
                            for($i=0; $i <sizeof($temp); $i ++){
                                $res = $this->Pembuatan_pakan->insert_bahan_penolong($this->data["id"], $temp[$i]["obat_id"], $temp[$i]["jumlah"], $temp[$i]["satuan"], $_SESSION['id']);
                                if($res <= 0){
                                    $is_error = True;
                                } else {
                                    $this->Obat->update_live_stok($temp[$i]["obat_id"], $_SESSION['id']);
                                }
                            }
                        }
                        if(!$is_error){
                            unset($_SESSION["list_obat"]);
                            redirect('Pembuatanpakan');
                        }
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('pembuatan_pakan_form', $this->data);
        }else {
            redirect('Pembuatanpakan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this -> get_form_data();
        if($this->input->post('delete') == "delete") {
            $result = $this->Pembuatan_pakan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                $result = $this->Pakan->update_live_stok($this->data["selected_pakan_before"], $_SESSION['id']);
                if($result == 1){
                    $detail = $this->Pembuatan_pakan->get_bahan_penolong($this->data["id"]);
                    $res = $this->Pembuatan_pakan->delete_bahan_penolong($this->data["id"], $_SESSION['id']);
                    for($i=0; $i<sizeof($detail); $i++){
                        $this->Obat->update_live_stok($detail[$i]["obat_id"], $_SESSION['id']);
                    }
                    unset($_SESSION["list_obat"]);
                    redirect('Pembuatanpakan');
                }
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('pembuatan_pakan_form', $this->data);
        } else {
            redirect('Pembuatanpakan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Pembuatan_pakan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Pembuatan_pakan->get_count_all()]);

    }


    public function getKolamInfo(){
        $kolam_id = $this->input->post('kolam_id');
        echo json_encode($this->Kolam->get($kolam_id));
    }


    public function addObatList(){
        $this->check_role();
        $obat_id = $this->input->post('obat_id');
        $obat_name = $this->input->post('obat_name');
        $jumlah = $this->input->post('jumlah');
        $satuan = $this->input->post('satuan');
        $arrobat = [];
        $is_new_obat = True;
        if(isset($_SESSION['list_obat'])){
            $temp = $_SESSION['list_obat'];
            for($i=0; $i <sizeof($temp); $i ++){
                $addValue = 0;
                if($temp[$i]["obat_id"] == $obat_id){
                    $addValue = $jumlah;
                    $is_new_obat = False;
                }
                $idx = sizeof($arrobat);
                $arrobat[$idx]["obat_id"] = $temp[$i]["obat_id"];
                $arrobat[$idx]["obat_name"] = $temp[$i]["obat_name"];
                $arrobat[$idx]["jumlah"] =  $temp[$i]["jumlah"] + $addValue;
                $arrobat[$idx]["satuan"] =  $temp[$i]["satuan"];
            }
        }
        if($is_new_obat){
            $idx = sizeof($arrobat);
            $arrobat[$idx]["obat_id"] = $obat_id;
            $arrobat[$idx]["obat_name"] = $obat_name;
            $arrobat[$idx]["jumlah"] = $jumlah;
            $arrobat[$idx]["satuan"] = $satuan;
        }

        $this->session->set_tempdata(["list_obat" => $arrobat], NULL, 5 * 60);
//        unset($_SESSION["list_obat"]);
        echo json_encode($arrobat);

    }


    public function removeObatList(){
        $this->check_role();
        $obat_id = $this->input->post('obat_id');
        $arrobat = [];
        if(isset($_SESSION['list_obat'])){
            $temp = $_SESSION['list_obat'];
            for($i=0; $i <sizeof($temp); $i ++){
                if($temp[$i]["obat_id"] != $obat_id){
                    $idx = sizeof($arrobat);
                    $arrobat[$idx]["obat_id"] = $temp[$i]["obat_id"];
                    $arrobat[$idx]["obat_name"] = $temp[$i]["obat_name"];
                    $arrobat[$idx]["jumlah"] =  $temp[$i]["jumlah"];
                    $arrobat[$idx]["satuan"] =  $temp[$i]["satuan"];
                }
            }
        }
        $this->session->set_tempdata(["list_obat" => $arrobat], NULL, 5 * 60);
        echo json_encode($arrobat);

    }
}
