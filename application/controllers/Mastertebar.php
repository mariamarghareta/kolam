<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastertebar extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Tebar');
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
        $this->data["sampling"] = "";
        $this->data["size"] = 0;
        $this->data["biomass"] = "";
        $this->data["dosis_pakan"] = 0;
        $this->data["total_pakan"] = 0;
        $this->data["total_ikan"] = 0;
        $this->data["fr"] = 0;
        $this->data["sr"] = 0;
        $this->data["pagi"] = 0;
        $this->data["sore"] = 0;
        $this->data["malam"] = 0;
        $this->data["selected_kolam"] = 0;
        $this->data["tangka"] = 1;
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["his_id"] = "";
        $this->data["pakan_id"] = "";
        $this->data["kolam_id"] = "";
        $this->data["sampling_id"] = "";
        $this->data["search_word"] = "";
        $this->data["kode"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Tebar->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["list_history"] = json_encode([]);
        $this->data["max_data"] = $this->Tebar->get_count_all();
        $this->data["arr_blok"] = ($this->Blok->show_all_data());
        if(count($this->data["arr_blok"])>0){
            $this->data["selected_blok"] = $this->data["arr_blok"][0]["id"];
            $this->data["arr_kolam"] = $this->Kolam->get_kolam_by_blok($this->data["arr_blok"][0]["id"]);
        }
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
        $this->load->view('mastertebar', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('mastertebar_form', $this->data);
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $this->load_get_data();
        $this->load->view('mastertebar_form', $this->data);
    }

    public function show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "show";
        $this->load_get_data();
        $this->load->view('mastertebar_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $this->load_get_data();
        $this->load->view('mastertebar_form', $this->data);
    }


    public function load_get_data(){
        $datum = $this->Tebar->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["his_id"] = $datum->his_id;
        $this->data["pakan_id"] = $datum->pakan_id;
        $this->data["sampling_id"] = $datum->sampling_id;
        $this->data['selected_kolam'] = $datum->kolam_id;
        $this->data['kolam_id'] = $datum->kolam_id;
        $this->data['selected_blok'] = $datum->blok_id;
        $this->data["arr_kolam"] = $this->Kolam->get_kolam_for_tebar($this->data['selected_blok'], $this->data["id"]);
        $this->data['sampling'] = $datum->sampling;
        $this->data['biomass'] = $datum->biomass;
        $this->data['size'] = $datum->size;
        $this->data['tangka'] = $datum->angka;
        $this->data['tsatuan'] = $datum->satuan;
        $this->data['kode'] = $datum->kode;
        $this->data["list_history"] = json_encode($this->Tebar_history->show_all($datum->id));
    }

    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tblok', 'Blok', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tkolam', 'Kolam', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('sampling', 'Sampling', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_rules('biomass', 'Biomass', 'required|numeric', array('required' => '%s harus diisi', 'numeric' => '%s berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

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
        if ($this->form_validation->run() != FALSE)
        {
            #insert tabel tebar
            $result = $this->Tebar->insert($this->data['sampling'], $this->data['size'], $this->data['biomass'], $this->data['total_ikan'], $this->data['tangka'], $this->data['tsatuan'], $_SESSION['id']);
            if($result) {
                #insert sampling
                $sampling_id = $this->Sampling->insert($result, $this->data['selected_kolam'], 0, 0, 0, $_SESSION['id']);
                if ($sampling_id) {
                    #insert pemberian pakan
                    $pemberian_pakan_id = $this->Pemberian_pakan->insert("", $this->data['fr'], $this->data['sr'], $this->data['dosis_pakan'], $this->data['total_pakan'], $this->data['pagi'],
                        $this->data['sore'], $this->data['malam'], $result, $this->data['selected_kolam'], $sampling_id, 0, $this->data['sampling'], $this->data['size'], $this->data['biomass'],
                        $this->data['total_ikan'], $this->data['tangka'], $this->data['tsatuan'], $_SESSION['id']);
                    if ($pemberian_pakan_id) {
                        #update kolam pemberian pakan id
                        $kolam_id = $this->Kolam->update_pemberian_pakan($pemberian_pakan_id, $result, $this->data['selected_kolam'], $_SESSION['id']);
                        if ($kolam_id) {
                            #insert tebar history
                            $tebar_history = $this->Tebar_history->insert($result, $sampling_id, 0, "Tebar Bibit Ikan", 0, $this->data['selected_kolam'], $_SESSION['id']);
                            if ($tebar_history) {
                                redirect('Mastertebar');
                            }
                        }
                    }
                }
            }
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";

        }
        $this->data["state"] = "create";
        $this->load->view('mastertebar_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
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
        if($this->input->post('write') == "write") {
            if ($this->form_validation->run() != FALSE)
            {
                #update data tebar
                $result = $this->Tebar->update($this->data['sampling'], $this->data['size'], $this->data['biomass'], $this->data['total_ikan'], $this->data['tangka'], $this->data['tsatuan'], $this->data['id'], $_SESSION['id']);
                if($result){
                    #update pemberian pakan
                    $pemberian_pakan_id = $this->Pemberian_pakan->update_from_tebar($this->data['fr'], $this->data['sr'], $this->data['dosis_pakan'], $this->data['total_pakan'], $this->data['pagi'],
                        $this->data['sore'], $this->data['malam'], $result, $this->data['selected_kolam'], $this->data['sampling'], $this->data['size'], $this->data['biomass'], $this->data['total_ikan'],
                        $this->data['tangka'], $this->data['tsatuan'], $this->data['pakan_id'], $_SESSION['id']);
                    if ($pemberian_pakan_id) {
                        #update kolam pemberian pakan id
                        $kolam_id = $this->Kolam->update_tebar_id($this->data['kolam_id'], $this->data['selected_kolam'], $this->data['pakan_id'], $this->data['id'], $_SESSION['id']);
                        if ($kolam_id) {
                            #update tebar history
                            $tebar_history = $this->Tebar_history->update_by_tebar($this->data['selected_kolam'], $this->data['id'], $this->data['his_id'], $_SESSION['id']);
                            if ($tebar_history) {
                                redirect('Mastertebar');
                            }
                        }
                    }

                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                }
            }
            $this->data["state"] = "update";
            $this->load->view('mastertebar_form', $this->data);
        }else {
            redirect('Mastertebar');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        $this->data['kolam_id'] = $this->input->post('kolam_id');
        if($this->input->post('delete') == "delete") {
            $result = $this->Tebar->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                $up_kolam = $this->Kolam->update_kolam_by_delete_tebar($this->data['kolam_id'], $this->data['id'], $_SESSION['id']);
                $tebar_history = $this->Tebar_history->insert( $this->data['id'], 0, 0, "Delete Tebar Bibit", $this->data['kolam_id'], 0, $_SESSION['id']);
                if($up_kolam){
                    redirect('Mastertebar');
                }
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('mastertebar_form', $this->data);
        } else {
            redirect('Mastertebar');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Tebar->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Tebar->get_count_all()]);

    }
}
