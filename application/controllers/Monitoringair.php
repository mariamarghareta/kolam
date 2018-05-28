<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoringair extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Monitoring_air');
        $this->load->model('Blok');
        $this->load->model('Kolam');
        $this->load->model('Obat');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["pakan_id"] = "";
        $this->data["tebar_id"] = "";
        $this->data["tinggi_air"] = "";
        $this->data["waktu"] = "";
        $this->data["ph"] = "";
        $this->data["kcr"] = "";
        $this->data["suhu"] = "";
        $this->data["warna_air"] = "";
        $this->data["keterangan"] = "";
        $this->data["selected_blok"] = "";
        $this->data["selected_kolam"] = "";
        $this->data["selected_obat"] = "";
        $this->data["jumlah_obat"] = "";
        $this->data["search_word"] = "";
        $this->data["selected_waktu"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Monitoring_air->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($this->data["arr_blok"][0]["id"]);
        }
        $this->data["arr_obat"] = ($this->Obat->show_all_in_stock());
        $this->data["max_data"] = $this->Monitoring_air->get_count_all();
        $this->data["list_obat"] = json_encode([]);
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
        $this->load->view('monitoring_air', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_obat"]);
        }
        $this->data["state"] = "create";
        $this->load->view('monitoring_air_form', $this->data);
    }


    public function load_data(){
        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_obat"]);
        }
        $datum = $this->Monitoring_air->get($this->data['id'])[0];
        $this->data["tid"] = $datum->id;
        $this->data["pakan_id"] =  $datum->pemberian_pakan_id;
        $this->data["tebar_id"] = $datum->tebar_id;
        $this->data["tinggi_air"] =  $datum->tinggi_air;
        $this->data["selected_waktu"] =  $datum->waktu;
        $this->data["ph"] =  $datum->ph;
        $this->data["kcr"] =  $datum->kcr;
        $this->data["suhu"] =  $datum->suhu;
        $this->data["warna_air"] =  $datum->warna;
        $this->data["keterangan"] =  $datum->keterangan;
        $this->data["selected_blok"] =  $datum->blok_id;
        $this->data["selected_kolam"] =  $datum->kolam_id;
        $this->data["selected_obat"] = "";
        $this->data["jumlah_obat"] = 0;

        $arrobat = [];
        $data = $this->Monitoring_air->get_bahan_penolong($this->data['id']);
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
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "show";
        $this->load_data();
        $this->load->view('monitoring_air_form', $this->data);
    }

    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "update";
        $this->load_data();
        $this->load->view('monitoring_air_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "delete";
        $this->load_data();
        $this->load->view('monitoring_air_form', $this->data);
    }


    public function load_form_data(){
        $this->data["id"] = $this->input->post('tid');
        $this->data["pakan_id"] = $this->input->post('pakan_id');
        $this->data["tebar_id"] = $this->input->post('tebar_id');
        $this->data["tinggi_air"] = $this->input->post('tinggi_air');
        $this->data["selected_waktu"] = $this->input->post('waktu_monitoring');
        $this->data["ph"] = $this->input->post('ph');
        $this->data["kcr"] = $this->input->post('kcr');
        $this->data["suhu"] = $this->input->post('suhu');
        $this->data["warna_air"] = $this->input->post('warna_air');
        $this->data["keterangan"] = $this->input->post('keterangan');
        $this->data["selected_blok"] = $this->input->post('tblok');
        $this->data["selected_kolam"] = $this->input->post('tkolam');
        $this->data["selected_obat"] = $this->input->post('tobat');
        $this->data["jumlah_obat"] = $this->input->post('jumlah_obat');

        $this->form_validation->set_rules('tinggi_air', 'Tinggi Air', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka'));
        $this->form_validation->set_rules('ph', 'PH', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka'));
        $this->form_validation->set_rules('kcr', 'KCR', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka'));
        $this->form_validation->set_rules('suhu', 'Suhu', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka'));
        $this->form_validation->set_rules('warna_air', 'Warna Air', 'required', array('required' => '%s harus diisi', 'numeric' => '%s harus berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();

        if ($this->form_validation->run() != FALSE)
        {
            $result = $this->Monitoring_air->insert($this->data["selected_kolam"], $this->data["tebar_id"], $this->data["pakan_id"],
                $this->data["tinggi_air"], $this->data["selected_waktu"], $this->data["suhu"], $this->data["ph"], $this->data["kcr"], $this->data["warna_air"], $this->data["keterangan"], $_SESSION['id']);
            if($result  > 0){
                $arrobat = [];
                $is_error = False;
                if(isset($_SESSION['list_obat'])){
                    $temp = $_SESSION['list_obat'];
                    for($i=0; $i <sizeof($temp); $i ++){
                        $res = $this->Monitoring_air->insert_bahan_penolong($result, $temp[$i]["obat_id"], $temp[$i]["jumlah"], $_SESSION['id']);
                        if($res <= 0){
                            $is_error = True;
                        } else {
                            $this->Obat->kurangi_stok($temp[$i]["obat_id"], $temp[$i]["jumlah"], $_SESSION['id']);
                        }
                    }
                }
                if(!$is_error){
                    unset($_SESSION["list_obat"]);
                    redirect('Monitoringair');
                }
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
            }
        }
        $this->data["state"] = "create";
        $this->load->view('monitoring_air_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();

        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE) {
                $result = $this->Monitoring_air->update($this->data["selected_kolam"], $this->data["tebar_id"], $this->data["pakan_id"],
                    $this->data["tinggi_air"], $this->data["selected_waktu"], $this->data["suhu"], $this->data["ph"], $this->data["kcr"], $this->data["warna_air"], $this->data["keterangan"], $this->data["id"], $_SESSION['id']);
                if($result  > 0){
                    $arrobat = [];
                    $is_error = False;
                    if(isset($_SESSION['list_obat'])){
//                        delete existing bahan penolong
                        $detail = $this->Monitoring_air->get_bahan_penolong($this->data["id"]);
                        $this->Monitoring_air->delete_bahan_penolong($this->data["id"], $_SESSION['id']);
                        for($i=0; $i<sizeof($detail); $i++){
                            $this->Obat->update_live_stok($detail[$i]["obat_id"], $_SESSION['id']);
                        }
                        $temp = $_SESSION['list_obat'];
                        for($i=0; $i <sizeof($temp); $i ++){
                            $res = $this->Monitoring_air->insert_bahan_penolong($this->data["id"], $temp[$i]["obat_id"], $temp[$i]["jumlah"], $_SESSION['id']);
                            if($res <= 0){
                                $is_error = True;
                            } else {
                                $this->Obat->update_live_stok($temp[$i]["obat_id"], $_SESSION['id']);
                            }
                        }
                    }
                    if(!$is_error){
                        unset($_SESSION["list_obat"]);
                        redirect('Monitoringair');
                    }
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
            $this->data["state"] = "update";
            $this->load->view('monitoring_air_form', $this->data);
        }else {
            redirect('Monitoringair');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();
        if($this->input->post('delete') == "delete") {
            $result = $this->Monitoring_air->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                $detail = $this->Monitoring_air->get_bahan_penolong($this->data["id"]);
                $res = $this->Monitoring_air->delete_bahan_penolong($this->data["id"], $_SESSION['id']);
                for($i=0; $i<sizeof($detail); $i++){
                    $this->Obat->update_live_stok($detail[$i]["obat_id"], $_SESSION['id']);
                }
                unset($_SESSION["list_obat"]);
                redirect('Monitoringair');

            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('monitoring_air_form', $this->data);
        } else {
            redirect('Monitoringair');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Monitoring_air->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Monitoring_air->get_count_all()]);

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
