<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastersampling extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Sampling');
        $this->load->model('Karyawan');
        $this->load->model('Sampling');
        $this->load->model('Tebar_history');
        $this->load->model('Blok');
        $this->load->model('Kolam');
        $this->load->model('Pemberian_pakan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["name"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["his_id"] = "";
        $this->data["pakan_id"] = "";
        $this->data["kolam_id"] = "";
        $this->data["sampling_id"] = "";
        $this->data["sampling"] = "";
        $this->data["size"] = 0;
        $this->data["biomass"] = "";
        $this->data["biomass_before"] = 0;
        $this->data["dosis_pakan"] = 0;
        $this->data["total_pakan"] = 0;
        $this->data["total_pakan_before"] = 0;
        $this->data["total_ikan"] = 0;
        $this->data["selected_kolam"] = 0;
        $this->data["fr"] = 0;
        $this->data["sr"] = 0;
        $this->data["pagi"] = 0;
        $this->data["sore"] = 0;
        $this->data["malam"] = 0;
        $this->data["tangka"] = 1;
        $this->data["kenaikan_daging"] = 0;
        $this->data["fcr"] = 0;
        $this->data["adg"] = 0;
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Sampling->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Sampling->get_count_all();
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($this->data["arr_blok"][0]["id"]);
        }
    }


    public function check_role(){
        if(isset($_SESSION['role_id'])){
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
        $this->load->view('mastersampling', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('mastersampling_form', $this->data);
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "update";
        $this->load_data();
        $this->load->view('mastersampling_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);
        $this->data["state"] = "delete";
        $this->load_data();
        $this->load->view('mastersampling_form', $this->data);
    }


    public function load_data(){
        $datum = $this->Sampling->get($this->data["id"])[0];
        $his = $this->Tebar_history->get_by_sampling($this->data["id"])[0];
        $pakan = $this->Pemberian_pakan->get_by_sampling($this->data["id"])[0];
        $before = $this->Tebar_history->get_history_by_sampling($datum->kolam_id, $his->sequence);
        $this->data["id"] = $datum->tebar_id;
        $this->data["his_id"] = $his->id;
        $this->data["pakan_id"] = $pakan->id;
        $this->data["kolam_id"] = $datum->kolam_id;
        $this->data["sampling_id"] = $datum->id;
        $this->data["sampling"] = $pakan->sampling;
        $this->data["size"] = $pakan->size;
        $this->data["biomass"] = $pakan->biomass;
        $this->data["biomass_before"] = $before[0]["biomass"];
        $this->data["dosis_pakan"] = $pakan->dosis_pakan;
        $this->data["total_pakan"] = $pakan->total_pakan;
        $this->data["total_pakan_before"] = $before[0]["total_pakan"];
        $this->data["total_ikan"] = $pakan->total_ikan;
        $this->data["selected_kolam"] = $datum->kolam_id;
        $this->data["fr"] = $pakan->fr;
        $this->data["sr"] = $pakan->sr;
        $this->data["pagi"] = $pakan->pagi;
        $this->data["sore"] = $pakan->sore;
        $this->data["malam"] = $pakan->malam;
        $this->data["tangka"] = $pakan->angka;
        $this->data["kenaikan_daging"] = $datum->kenaikan_daging;
        $this->data["fcr"] = $datum->fcr;
        $this->data["adg"] = $datum->adg;
        $this->data["arr_blok"] = ($this->Blok->show_blok_tebar());
        $this->data["selected_blok"] = $datum->blok_id;
        $this->data["arr_kolam"] = $this->Kolam->get_occupied_kolam($datum->blok_id);
        $this->data["selected_kolam"] = $datum->kolam_id;
    }


    public function get_form_data(){
        $this->form_validation->set_rules('tblok', 'Blok', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tkolam', 'Kolam', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('sampling', 'Sampling', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_rules('biomass', 'Biomass', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['his_id'] = $this->input->post('his_id');
        $this->data['pakan_id'] = $this->input->post('pakan_id');
        $this->data['kolam_id'] = $this->input->post('kolam_id');
        $this->data['sampling_id'] = $this->input->post('sampling_id');
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
        $this->data['kenaikan_daging'] = $this->input->post('kenaikan_daging');
        $this->data['fcr'] = $this->input->post('fcr');
        $this->data['adg'] = $this->input->post('adg');
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();
        if ($this->form_validation->run() != FALSE)
        {
            #insert sampling
            $tebar_id = $this->Kolam->get($this->data['selected_kolam'])[0]->tebar_id;
            $sampling_id = $this->Sampling->insert($tebar_id, $this->data['selected_kolam'], $this->data['kenaikan_daging'], $this->data['fcr'], $this->data['adg'], $_SESSION['id']);
            if ($sampling_id) {
                #insert pemberian pakan
                $pemberian_pakan_id = $this->Pemberian_pakan->insert("", $this->data['fr'], $this->data['sr'], $this->data['dosis_pakan'], $this->data['total_pakan'], $this->data['pagi'],
                    $this->data['sore'], $this->data['malam'], $tebar_id, $this->data['selected_kolam'], $sampling_id, 0, $this->data['sampling'], $this->data['size'], $this->data['biomass'],
                    $this->data['total_ikan'], $this->data['tangka'], $this->data['tsatuan'], $_SESSION['id']);
                if ($pemberian_pakan_id) {
                    #update kolam pemberian pakan id
                    $kolam_id = $this->Kolam->update_pemberian_pakan($pemberian_pakan_id, $tebar_id, $this->data['selected_kolam'], $_SESSION['id']);
                    if ($kolam_id) {
                        #insert tebar history
                        $tebar_history = $this->Tebar_history->insert($tebar_id, $sampling_id, 0, "Sampling", 0, $this->data['selected_kolam'], $_SESSION['id']);
                        if ($tebar_history) {
                            redirect('Mastersampling');
                        }
                    }
                }
            }
        }
        $this->data["state"] = "create";
        $this->load->view('mastersampling_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();
        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE) {
                $tebar_id = $this->Kolam->get($this->data['selected_kolam'])[0]->tebar_id;
                $tebar_id_old = $this->Kolam->get($this->data['kolam_id'])[0]->tebar_id;
                $sampling_id = $this->Sampling->update($tebar_id, $this->data['selected_kolam'], $this->data['kenaikan_daging'], $this->data['fcr'], $this->data['adg'], $this->data['sampling_id'], $_SESSION['id']);
                if ($sampling_id) {
                    #insert pemberian pakan
                    $pemberian_pakan_id = $this->Pemberian_pakan->update_from_tebar($this->data['fr'], $this->data['sr'], $this->data['dosis_pakan'], $this->data['total_pakan'], $this->data['pagi'],
                        $this->data['sore'], $this->data['malam'], $this->data['pakan_id'], $this->data['selected_kolam'], $this->data['sampling'], $this->data['size'], $this->data['biomass'], $this->data['total_ikan'],
                        $this->data['tangka'], $this->data['tsatuan'], $this->data['pakan_id'], $_SESSION['id']);
                    if ($pemberian_pakan_id) {
                        #update kolam pemberian pakan id
                        $kolam_id = $this->Kolam->update_pemberian_pakan_sampling($this->data['kolam_id'], $this->data['pakan_id'], $tebar_id_old, $this->data['selected_kolam'], $_SESSION['id']);
                        if ($kolam_id) {
                            #insert tebar history
                            $tebar_history = $this->Tebar_history->update_by_tebar($this->data['selected_kolam'], $tebar_id, $this->data['his_id'], $_SESSION['id']);
                            if ($tebar_history) {
                                redirect('Mastersampling');
                            }
                        }
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('mastersampling_form', $this->data);
        }else {
            redirect('Mastersampling');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->get_form_data();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Sampling->delete($this->data['sampling_id'], $_SESSION['id']);
            if($result == 1){
                $del_his = $this->Tebar_history->delete($this->data["his_id"], $_SESSION['id']);
                if($del_his){
                    $tebar_id = $this->Kolam->get($this->data['kolam_id'])[0]->tebar_id;
                    $up_kolam = $this->Kolam->get_last_pakan($this->data['kolam_id'], $tebar_id, $_SESSION['id']);
                    if($up_kolam){
                        redirect('Mastersampling');
                    }
                }
            }
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            $this->data["state"] = "delete";
            $this->load->view('mastersampling_form', $this->data);
        } else {
            redirect('Mastersampling');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Obat->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Obat->get_count_all()]);

    }

    public function getTotalIkan(){
        $kolam_id = $this->input->post('kolam_id');
        echo json_encode($this->Tebar_history->get_total_ikan($kolam_id));
    }
}
