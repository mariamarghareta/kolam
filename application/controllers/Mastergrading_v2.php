<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastergrading_v2 extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Tebar');
        $this->load->model('Grading');
        $this->load->model('Blok');
        $this->load->model('Kolam');
        $this->load->model('Tebar_history');
        $this->load->model('Pemberian_pakan');
        $this->load->model('Sampling');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["sampling"] = 0;
        $this->data["sampling_akhir"] = 0;
        $this->data["size"] = 0;
        $this->data["size_tujuan"] = 0;
        $this->data["size_total"] = 0;
        $this->data["biomass"] = 0;
        $this->data["biomass_tujuan"] = 0;
        $this->data["biomass_total"] = 0;
        $this->data["dosis_pakan"] = 0;
        $this->data["total_pakan"] = 0;
        $this->data["total_ikan"] = 0;
        $this->data["total_ikan_tujuan"] = 0;
        $this->data["total_ikan_akhir"] = 0;
        $this->data["fr"] = 0;
        $this->data["sr"] = 0;
        $this->data["pagi"] = 0;
        $this->data["sore"] = 0;
        $this->data["malam"] = 0;
        $this->data["selected_kolam"] = 0;
        $this->data["selected_blok"] = 0;
        $this->data["tangka"] = 1;
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["his_id"] = "";
        $this->data["pakan_id"] = "";
        $this->data["kolam_id"] = "";
        $this->data["sampling_id"] = "";
        $this->data["search_word"] = "";
        $this->data["ukuran"] = "";
        $this->data["selected_kolam_tujuan"] = "";
        $this->data["selected_blok_tujuan"] = "";
        $this->data["total_biomass"] = 0;
        $this->data["total_populasi"] = 0;
        $this->data["sr"] = 0;
        $this->data["pertumbuhan_daging"] = 0;
        $this->data["fcr"] = 0;
        $this->data["adg"] = 0;
        $this->data["total_biomass_before"] = 0;
        $this->data["total_populasi_before"] = 0;
        $this->data["total_pakan_before"] = 0;
        $this->data["biomass_before"] = 0;
        $this->data["total_pakan_monitoring"] = 0;
        $this->data["k_total_ikan"] = 0;
        $this->data["k_biomass"] = 0;
        $this->data["sr_akhir"] = 0;
        $this->data["tebar_id"] = 0;
        $this->data['selected_kolam_txt']="";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Grading->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Tebar->get_count_all();
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($this->data["arr_blok"][0]["id"]);
        }
        $this->data["list_grading"] = json_encode([]);
        $this->data["arr_blok_tujuan"] = ($this->Blok->show_blok_berkolam());

        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
        $this->data["create_user"] = "";
        $this->data["create_time"] = "";
        $this->data["write_user"] = "";
        $this->data["write_time"] = "";
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
        $this->load->view('grading', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_grading"]);
        }
        $this->data["state"] = "create";
        $this->load->view('grading_form_v2', $this->data);
    }


    public function show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_grading"]);
        }
        $this->data["state"] = "show";
        $this->load_get_data(0);
        $this->load->view('grading_form', $this->data);
    }

    public function data_show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_grading"]);
        }
        $this->data["state"] = "show";
        $this->load_get_data(1);
        $this->load->view('grading_form', $this->data);
    }


    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        if(!$this->input->post["tblok"]){
            unset($_SESSION["list_grading"]);
        }
        $this->data["state"] = "delete";
        $this->load_get_data(0);
        $this->load->view('grading_form', $this->data);
    }


    public function load_get_data($param){
        if($param == 0){
            $datum = $this->Grading->get($this->data['id'])[0];
        } else {
            $datum = $this->Grading->get_without_check($this->data['id'])[0];
        }
        $this->data["id"] = $datum->id;
        $this->data["tebar_id"] = $datum->tebar_id;
        $this->data["total_biomass"] = $datum->total_biomass;
        $this->data["total_populasi"] = $datum->total_populasi;
        $this->data["sr_akhir"] = $datum->sr;
        $this->data['pertumbuhan_daging'] = $datum->pertumbuhan_daging;
        $this->data['fcr'] = $datum->fcr;
        $this->data['adg'] = $datum->adg;
        $this->data['selected_blok'] = $datum->blok_id;
        $this->data['selected_kolam'] = $datum->asal_kolam_id;
        $this->data['selected_kolam_txt'] = $datum->blok_name . " " . $datum->kolam_name;
        $this->data['kolam_id'] = $datum->asal_kolam_id;
        $this->data["arr_kolam"] = $this->Kolam->get_kolam_for_grading($this->data['selected_blok'], $this->data["id"]);
        $this->data["create_user"] = $datum->create_user;
        $this->data["create_time"] = $datum->create_time;
        $this->data["write_user"] = $datum->write_user;
        $this->data["write_time"] = $datum->write_time;
        $datum = $this->Pemberian_pakan->get_by_grading($this->data['id']);
        $arritem = [];
        foreach ($datum as $item) {
            $idx = sizeof($arritem);
            $arritem[$idx]["urutan"] = sizeof($arritem);
            $arritem[$idx]["ukuran"] = $item->ukuran;
            $arritem[$idx]["sampling"] =  $item->sampling;;
            $arritem[$idx]["angka"] =  $item->angka;
            $arritem[$idx]["satuan"] =   $item->satuan;
            $arritem[$idx]["biomass"] =  $item->biomass;
            $arritem[$idx]["size"] =  $item->size;
            $arritem[$idx]["total_ikan"] =  $item->total_ikan;
            $arritem[$idx]["fr"] = $item->fr;
            $arritem[$idx]["sr"] =  $item->sr;
            $arritem[$idx]["dosis_pakan"] =  $item->dosis_pakan;
            $arritem[$idx]["total_pakan"] =  $item->total_pakan;
            $arritem[$idx]["pagi"] = $item->pagi;
            $arritem[$idx]["sore"] = $item->sore;
            $arritem[$idx]["malam"] = $item->malam;
            $arritem[$idx]["kolam_tujuan"] = $item->kolam_id;
            $arritem[$idx]["blok_tujuan"] = $item->blok_id;
            $arritem[$idx]["kolam_tujuan_txt"] = $item->kolam_name;
            $arritem[$idx]["blok_tujuan_txt"] = $item->blok_name;

            $arritem[$idx]["sampling_akhir"] =  $item->sampling_akhir;
            $arritem[$idx]["tangka_akhir"] =  $item->angka_akhir;
            $arritem[$idx]["satuan_akhir"] =  $item->satuan_akhir;
            $arritem[$idx]["biomass_total"] =  $item->biomass_total;
            $arritem[$idx]["size_total"] =  $item->size_total;
            $arritem[$idx]["total_ikan_akhir"] =  $item->total_ikan_akhir;
        }

        $this->session->set_tempdata(["list_grading" => $arritem], NULL, 5 * 60);
//        unset($_SESSION["list_obat"]);
        $this->data["list_grading"] = json_encode($arritem);
    }


    public function load_form_data(){
        $this->form_validation->set_rules('tblok', 'Blok', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tkolam', 'Kolam', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('sampling', 'Sampling', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_rules('biomass', 'Biomass', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['selected_kolam'] = $this->input->post('tkolam');
        $this->data['selected_blok'] = $this->input->post('tblok');
        $this->data["arr_kolam"] = $this->Kolam->get_kolam_by_blok($this->data['selected_blok']);
        $this->data['sampling'] = $this->input->post('sampling');
        $this->data['biomass'] = $this->input->post('biomass');
        $this->data['size'] = $this->input->post('size');
        $this->data['total_pakan'] = $this->input->post('total_pakan');
        $this->data['dosis_pakan'] = $this->input->post('dosis_pakan');
        $this->data['total_ikan'] = $this->input->post('total_ikan');
        $this->data['pagi'] = $this->input->post('pagi');
        $this->data['sore'] = $this->input->post('sore');
        $this->data['malam'] = $this->input->post('malam');
        $this->data['fr'] = $this->input->post('fr');
        $this->data['sr'] = $this->input->post('sr');
        $this->data['tangka'] = $this->input->post('tangka');
        $this->data['tsatuan'] = $this->input->post('tsatuan');
        $this->data['ukuran'] = $this->input->post('ukuran');
        $this->data['total_pakan_monitoring'] = $this->input->post('total_pakan_monitoring');
        $this->data['total_biomass'] = $this->input->post('total_biomass');
        $this->data['total_populasi'] = $this->input->post('total_populasi');
        $this->data['sr_akhir'] = $this->input->post('sr_akhir');
        $this->data['pertumbuhan_daging'] = $this->input->post('pertumbuhan_daging');
        $this->data['fcr'] = $this->input->post('fcr');
        $this->data['adg'] = $this->input->post('adg');
        $this->data['tebar_id'] = $this->input->post('tebar_id');
        $this->data['sampling_id'] = $this->input->post('sampling_id');
        $this->data['kolam_id'] = $this->input->post('kolam_id');
        $this->data['his_id'] = $this->input->post('his_id');
        $this->data['k_total_ikan'] = $this->input->post('k_total_ikan');
        $this->data['k_biomass'] = $this->input->post('k_biomass');
        $this->data['selected_kolam_txt'] = $this->input->post('selected_kolam_txt');

        $arritem = [];
        if(isset($_SESSION['list_grading'])){
            $temp = $_SESSION['list_grading'];
            for($i=0; $i <sizeof($temp); $i ++){
                $idx = sizeof($arritem);
                $arritem[$idx]["urutan"] = $i;
                $arritem[$idx]["ukuran"] = $temp[$i]["ukuran"];
                $arritem[$idx]["sampling"] = $temp[$i]["sampling"];
                $arritem[$idx]["angka"] =  $temp[$i]["angka"];
                $arritem[$idx]["satuan"] =  $temp[$i]["satuan"];
                $arritem[$idx]["biomass"] =  $temp[$i]["biomass"];
                $arritem[$idx]["size"] =  $temp[$i]["size"];
                $arritem[$idx]["total_ikan"] =  $temp[$i]["total_ikan"];
                $arritem[$idx]["fr"] =  $temp[$i]["fr"];
                $arritem[$idx]["sr"] =  $temp[$i]["sr"];
                $arritem[$idx]["dosis_pakan"] =  $temp[$i]["dosis_pakan"];
                $arritem[$idx]["total_pakan"] =  $temp[$i]["total_pakan"];
                $arritem[$idx]["pagi"] =  $temp[$i]["pagi"];
                $arritem[$idx]["sore"] =  $temp[$i]["sore"];
                $arritem[$idx]["malam"] =  $temp[$i]["malam"];
                $arritem[$idx]["kolam_tujuan"] =  $temp[$i]["kolam_tujuan"];
                $arritem[$idx]["blok_tujuan"] =  $temp[$i]["blok_tujuan"];
                $arritem[$idx]["kolam_tujuan_txt"] =  $temp[$i]["kolam_tujuan_txt"];
                $arritem[$idx]["blok_tujuan_txt"] =  $temp[$i]["blok_tujuan_txt"];

                $arritem[$idx]["sampling_akhir"] =  $temp[$i]["sampling_akhir"];
                $arritem[$idx]["tangka_akhir"] =  $temp[$i]["tangka_akhir"];
                $arritem[$idx]["satuan_akhir"] =  $temp[$i]["satuan_akhir"];
                $arritem[$idx]["biomass_total"] =  $temp[$i]["biomass_total"];
                $arritem[$idx]["size_total"] =  $temp[$i]["size_total"];
                $arritem[$idx]["total_ikan_akhir"] =  $temp[$i]["total_ikan_akhir"];
            }
        }
        $this->data['list_grading'] = json_encode($arritem);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->load_form_data();
        if ($this->form_validation->run() != FALSE)
        {
            #insert tabel grading
            $result = $this->Grading->insert($this->data['tebar_id'], $this->data['sampling_id'],  $this->data['selected_kolam'], $this->data['total_biomass'], $this->data['total_populasi'], $this->data['sr_akhir'], $this->data['pertumbuhan_daging'], $this->data['fcr'], $this->data['adg'], $_SESSION['id']);
            if($result) {
                #insert pemberian pakan
                if(isset($_SESSION['list_grading'])) {
                    $temp = $_SESSION['list_grading'];
                    $isMoveAll = true;
                    $isSuccess = true;
                    for ($i = 0; $i < sizeof($temp); $i++) {
                        $kolam_tujuan = $temp[$i]["kolam_tujuan"];
                        if($temp[$i]["blok_tujuan"] == '-'){
                            $kolam_tujuan = $this->data['selected_kolam'];
                            $isMoveAll = false;
                        }
                        $pemberian_pakan_id = $this->Pemberian_pakan->insert_v2($temp[$i]["ukuran"], $temp[$i]["fr"], $temp[$i]["sr"], $temp[$i]['dosis_pakan'], $temp[$i]['total_pakan'], $temp[$i]['pagi'],
                            $temp[$i]['sore'], $temp[$i]['malam'], $this->data['tebar_id'], $kolam_tujuan, 0, $result, $temp[$i]['sampling'], $temp[$i]['size'], $temp[$i]['biomass'],
                            $temp[$i]['total_ikan'], $temp[$i]['angka'], $temp[$i]['satuan'], $_SESSION['id'],
                            $temp[$i]["sampling_akhir"], $temp[$i]["tangka_akhir"], $temp[$i]["satuan_akhir"], $temp[$i]["biomass_total"], $temp[$i]["size_total"], $temp[$i]["total_ikan_akhir"] );
                        if ($pemberian_pakan_id) {
                            $data_kolam = $this->Kolam->get($kolam_tujuan);
                            if($data_kolam[0]->tebar_id != 0 && $temp[$i]["blok_tujuan"] != '-'){
                                $sampling = $this->Sampling->insert($data_kolam[0]->tebar_id, $kolam_tujuan, 0, 0, 0, $_SESSION['id']);
                                if($sampling){
                                    $pkn_id = $this->Pemberian_pakan->insert_v2(0, $temp[$i]["fr"], $temp[$i]["sr"], $temp[$i]['dosis_pakan'], $temp[$i]['total_pakan'], $temp[$i]['pagi'],
                                        $temp[$i]['sore'], $temp[$i]['malam'], $data_kolam[0]->tebar_id, $kolam_tujuan, $sampling, 0, $temp[$i]['sampling_akhir'], $temp[$i]['size_total'], $temp[$i]['biomass_total'],
                                        $temp[$i]['total_ikan_akhir'], $temp[$i]['tangka_akhir'], $temp[$i]['satuan_akhir'], $_SESSION['id'],
                                        0, 0, 0, 0, 0, 0);
                                    if($pkn_id){
                                        $kolam_id = $this->Kolam->update_pemberian_pakan($pkn_id, $data_kolam[0]->tebar_id, $kolam_tujuan, $_SESSION['id']);
                                        $kolam_pengirim = $this->Kolam->get($this->data['selected_kolam']);
                                        #tambahkan history sampling
                                        $tebar_history = $this->Tebar_history->insert(
                                            $data_kolam[0]->tebar_id,
                                            $sampling,
                                            0,
                                            "Sampling Setelah di Gabung Dari " . $kolam_pengirim[0]->blok_name . "-" . $kolam_pengirim[0]->name . "(" . $kolam_pengirim[0]->kode . ")",
                                            0,
                                            $kolam_tujuan,
                                            $_SESSION['id'],
                                            0);
                                        #insert tebar history
                                        $tebar_history = $this->Tebar_history->insert($this->data['tebar_id'], 0, $result, "Grading Gabung Kolam", $this->data['selected_kolam'], $kolam_tujuan, $_SESSION['id'], 0);
                                        if (!$tebar_history) {
                                            $isSuccess = false;
                                        }
                                    }
                                }
                            } else {
                                $kolam_id = $this->Kolam->update_pemberian_pakan($pemberian_pakan_id, $this->data['tebar_id'], $kolam_tujuan, $_SESSION['id']);
                                if ($kolam_id) {
                                    #insert tebar history
                                    $tebar_history = $this->Tebar_history->insert($this->data['tebar_id'], 0, $result, "Grading", $this->data['selected_kolam'], $kolam_tujuan, $_SESSION['id'], 0);
                                    if (!$tebar_history) {
                                        $isSuccess = false;
                                    }
                                }
                            }
                        }
                    }
                    if($isMoveAll){
                        $kolam_id = $this->Kolam->update_pemberian_pakan(0, 0, $this->data['selected_kolam'], $_SESSION['id']);
                        if (!$kolam_id) {
                            $isSuccess = false;
                        }
                    }
                    if($isSuccess){
                        redirect('Mastergrading');
                    }
                }
            }
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";

        }
        $this->data["state"] = "create";
        $this->load->view('grading_form', $this->data);
    }


    public function delete_data(){
        $this->check_role();
        $this->load_form_data();
        if($this->input->post('delete') == "delete") {
            $cek_grading = $this->Tebar_history->check_sequence_grading($this->data['id']);
            if($cek_grading == 0) {
                $cek_kolam_sebelumnya_available = $this->Kolam->is_available_gradingid($this->data['kolam_id'], $this->data['id']);
                if($cek_kolam_sebelumnya_available){
                    $result = $this->Grading->delete($this->data['id'], $_SESSION['id']);
                    if ($result == 1) {
                        $tb_his = $this->Tebar_history->delete_by_grading($this->data['id'], $_SESSION['id']);
                        if ($tb_his) {
                            $pem_pakan = $this->Pemberian_pakan->delete_by_grading($this->data['id'], $_SESSION['id']);
                            if($pem_pakan){
                                #mengosongkan tebar_id dan pemberian pakan pada kolam
                                if(isset($_SESSION['list_grading'])){
                                    $temp = $_SESSION['list_grading'];
                                    for($i=0; $i <sizeof($temp); $i ++){
                                        $this->Kolam->update_pemberian_pakan(0, 0, $temp[$i]["kolam_tujuan"], $_SESSION['id']);
                                    }
                                }
                                #mengembalikan data pemberian pakan pada kolam sebelumnya
                                $up_res = $this->Kolam->get_last_pakan($this->data['kolam_id'], $this->data['tebar_id'], 0, 1, $_SESSION['id']);
                                #set tebar id sebelumnya
                                $up_res = $this->Kolam->set_tebar_id($this->data['tebar_id'], $this->data['kolam_id'], $_SESSION['id']);
                                $tebar_history = $this->Tebar_history->insert($this->data['tebar_id'], 0, $this->data['id'], "Delete Grading", $this->data['kolam_id'], 0, $_SESSION['id'], 0);
                                redirect('Mastergrading');
                            }
                        }
                    } else {
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
                    }
                } else {
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Kolam sebelumnya telah terpakai. Grading tidak dapat dibatalkan.</div>";
                }
            } else {
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Data tidak bisa dihapus karena terdapat data sampling/grading setelahnya.</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('grading_form', $this->data);
        } else {
            redirect('Mastergrading');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Grading->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Grading->get_count_all()]);
    }


    public function addItemList(){
        $this->check_role();
        $ukuran= $this->input->post('ukuran');
        $sampling= $this->input->post('sampling');
        $angka= $this->input->post('angka');
        $satuan= $this->input->post('satuan');
        $biomass= $this->input->post('biomass');
        $size= $this->input->post('size');
        $total_ikan= $this->input->post('total_ikan');
        $fr= $this->input->post('fr');
        $sr= $this->input->post('sr');
        $dosis_pakan= $this->input->post('dosis_pakan');
        $total_pakan= $this->input->post('total_pakan');
        $pagi= $this->input->post('pagi');
        $sore= $this->input->post('sore');
        $malam= $this->input->post('malam');
        $kolam_tujuan= $this->input->post('kolam_tujuan');
        $blok_tujuan= $this->input->post('blok_tujuan');
        $kolam_tujuan_txt= $this->input->post('kolam_tujuan_txt');
        $blok_tujuan_txt= $this->input->post('blok_tujuan_txt');
        $sampling_akhir= $this->input->post('sampling_akhir');
        $tangka_akhir= $this->input->post('tangka_akhir');
        $satuan_akhir= $this->input->post('satuan_akhir');
        $biomass_total= $this->input->post('biomass_total');
        $size_total= $this->input->post('size_total');
        $total_ikan_akhir= $this->input->post('total_ikan_akhir');

        $total_biomass= 0;
        $total_populasi_ikan=0;
        $sr_all = 0;
        $pertumbuhan_daging = 0;
        $fcr = 0;
        $adg = 0;

        $is_add = 1;
        $arritem = [];
        if(isset($_SESSION['list_grading'])){
            $temp = $_SESSION['list_grading'];
            for($i=0; $i <sizeof($temp); $i ++){
                if($temp[$i]["kolam_tujuan"] == $kolam_tujuan and $temp[$i]["blok_tujuan"] == $blok_tujuan){
                    $is_add = 0;
                }
                $addValue = 0;
                $idx = sizeof($arritem);
                $arritem[$idx]["urutan"] = $i;
                $arritem[$idx]["ukuran"] = $temp[$i]["ukuran"];
                $arritem[$idx]["sampling"] = $temp[$i]["sampling"];
                $arritem[$idx]["angka"] =  $temp[$i]["angka"];
                $arritem[$idx]["satuan"] =  $temp[$i]["satuan"];
                $arritem[$idx]["biomass"] =  $temp[$i]["biomass"];
                $arritem[$idx]["size"] =  $temp[$i]["size"];
                $arritem[$idx]["total_ikan"] =  $temp[$i]["total_ikan"];
                $arritem[$idx]["fr"] =  $temp[$i]["fr"];
                $arritem[$idx]["sr"] =  $temp[$i]["sr"];
                $arritem[$idx]["dosis_pakan"] =  $temp[$i]["dosis_pakan"];
                $arritem[$idx]["total_pakan"] =  $temp[$i]["total_pakan"];
                $arritem[$idx]["pagi"] =  $temp[$i]["pagi"];
                $arritem[$idx]["sore"] =  $temp[$i]["sore"];
                $arritem[$idx]["malam"] =  $temp[$i]["malam"];
                $arritem[$idx]["kolam_tujuan"] =  $temp[$i]["kolam_tujuan"];
                $arritem[$idx]["blok_tujuan"] =  $temp[$i]["blok_tujuan"];
                $arritem[$idx]["kolam_tujuan_txt"] =  $temp[$i]["kolam_tujuan_txt"];
                $arritem[$idx]["blok_tujuan_txt"] =  $temp[$i]["blok_tujuan_txt"];
                $total_biomass += $temp[$i]["biomass"];
                $total_populasi_ikan += $temp[$i]["total_ikan"];

                $arritem[$idx]["sampling_akhir"] =  $temp[$i]["sampling_akhir"];
                $arritem[$idx]["tangka_akhir"] =  $temp[$i]["tangka_akhir"];
                $arritem[$idx]["satuan_akhir"] =  $temp[$i]["satuan_akhir"];
                $arritem[$idx]["biomass_total"] =  $temp[$i]["biomass_total"];
                $arritem[$idx]["size_total"] =  $temp[$i]["size_total"];
                $arritem[$idx]["total_ikan_akhir"] =  $temp[$i]["total_ikan_akhir"];
            }
        }

        if($sampling > 0 and $sampling != '' and $biomass > 0 and $biomass != '' and is_numeric($sampling) and is_numeric($sampling) and $is_add == 1){
            $idx = sizeof($arritem);
            $arritem[$idx]["urutan"] = $idx;
            $arritem[$idx]["ukuran"] = $ukuran;
            $arritem[$idx]["sampling"] = $sampling;
            $arritem[$idx]["angka"] =  $angka;
            $arritem[$idx]["satuan"] =  $satuan;
            $arritem[$idx]["biomass"] =  $biomass;
            $arritem[$idx]["size"] =  $size;
            $arritem[$idx]["total_ikan"] =  $total_ikan;
            $arritem[$idx]["fr"] = $fr;
            $arritem[$idx]["sr"] =  $sr;
            $arritem[$idx]["dosis_pakan"] =  $dosis_pakan;
            $arritem[$idx]["total_pakan"] =  $total_pakan;
            $arritem[$idx]["pagi"] = $pagi;
            $arritem[$idx]["sore"] = $sore;
            $arritem[$idx]["malam"] = $malam;
            $arritem[$idx]["kolam_tujuan"] = $kolam_tujuan;
            $arritem[$idx]["blok_tujuan"] = $blok_tujuan;
            $arritem[$idx]["kolam_tujuan_txt"] = $kolam_tujuan_txt;
            $arritem[$idx]["blok_tujuan_txt"] = $blok_tujuan_txt;
            $total_biomass += $biomass;
            $total_populasi_ikan += $total_ikan;
            $arritem[$idx]["sampling_akhir"] =  $sampling_akhir;
            $arritem[$idx]["tangka_akhir"] =  $tangka_akhir;
            $arritem[$idx]["satuan_akhir"] =  $satuan_akhir;
            $arritem[$idx]["biomass_total"] =  $biomass_total;
            $arritem[$idx]["size_total"] =  $size_total;
            $arritem[$idx]["total_ikan_akhir"] =  $total_ikan_akhir;
        }

        $this->session->set_tempdata(["list_grading" => $arritem], NULL, 5 * 60);
//        unset($_SESSION["list_obat"]);
        echo json_encode([$arritem, $is_add, $total_biomass, $total_populasi_ikan]);
    }


    public function removeDetailList (){
        $this->check_role();
        $urutan = $this->input->post('urutan');
        $arritem = [];
        $total_biomass= 0;
        $total_populasi_ikan=0;
        if(isset($_SESSION['list_grading'])){
            $temp = $_SESSION['list_grading'];
            for($i=0; $i <sizeof($temp); $i ++){
                if($temp[$i]["urutan"] != $urutan){
                    $idx = sizeof($arritem);
                    $arritem[$idx]["urutan"] = $i;
                    $arritem[$idx]["ukuran"] = $temp[$i]["ukuran"];
                    $arritem[$idx]["sampling"] = $temp[$i]["sampling"];
                    $arritem[$idx]["angka"] =  $temp[$i]["angka"];
                    $arritem[$idx]["satuan"] =  $temp[$i]["satuan"];
                    $arritem[$idx]["biomass"] =  $temp[$i]["biomass"];
                    $arritem[$idx]["size"] =  $temp[$i]["size"];
                    $arritem[$idx]["total_ikan"] =  $temp[$i]["total_ikan"];
                    $arritem[$idx]["fr"] =  $temp[$i]["fr"];
                    $arritem[$idx]["sr"] =  $temp[$i]["sr"];
                    $arritem[$idx]["dosis_pakan"] =  $temp[$i]["dosis_pakan"];
                    $arritem[$idx]["total_pakan"] =  $temp[$i]["total_pakan"];
                    $arritem[$idx]["pagi"] =  $temp[$i]["pagi"];
                    $arritem[$idx]["sore"] =  $temp[$i]["sore"];
                    $arritem[$idx]["malam"] =  $temp[$i]["malam"];
                    $arritem[$idx]["kolam_tujuan"] =  $temp[$i]["kolam_tujuan"];
                    $arritem[$idx]["blok_tujuan"] =  $temp[$i]["blok_tujuan"];
                    $arritem[$idx]["kolam_tujuan_txt"] =  $temp[$i]["kolam_tujuan_txt"];
                    $arritem[$idx]["blok_tujuan_txt"] =  $temp[$i]["blok_tujuan_txt"];
                    $total_biomass += $temp[$i]["biomass"];
                    $total_populasi_ikan += $temp[$i]["total_ikan"];
                    $arritem[$idx]["sampling_akhir"] =  $temp[$i]["sampling_akhir"];
                    $arritem[$idx]["tangka_akhir"] =  $temp[$i]["tangka_akhir"];
                    $arritem[$idx]["satuan_akhir"] =  $temp[$i]["satuan_akhir"];
                    $arritem[$idx]["biomass_total"] =  $temp[$i]["biomass_total"];
                    $arritem[$idx]["size_total"] =  $temp[$i]["size_total"];
                    $arritem[$idx]["total_ikan_akhir"] =  $temp[$i]["total_ikan_akhir"];
                }
            }
        }
        $this->session->set_tempdata(["list_grading" => $arritem], NULL, 5 * 60);
        echo json_encode([$arritem, $total_biomass, $total_populasi_ikan]);
    }


    public function getKolamDetailforGrading(){
        $this->check_role();
        $kolam = $this->input->post('kolam_id');
        $temp = $this->Kolam->get($kolam);
        echo json_encode([$this->Kolam->get_total_pakan_for_grading($kolam), $temp[0]->total_ikan, $temp[0]->biomass,  $temp[0]->tebar_id, $temp[0]->sampling_id]);
    }
}
