<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterkolam extends CI_Controller {
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
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["name"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Kolam->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["arr_blok"] = ($this->Blok->show_all_data());
        $this->data["selected_blok"] = 0;
        $this->data["max_data"] = $this->Kolam->get_count_all();
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
        $this->load->view('masterkolam', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('masterkolam_form', $this->data);
    }


    public function load_data(){
        $datum = $this->Kolam->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["name"] = $datum->name;
        $this->data["selected_blok"] = $datum->blok_id;
        $this->data["create_user"] = $datum->create_user;
        $this->data["create_time"] = $datum->create_time;
        $this->data["write_user"] = $datum->write_user;
        $this->data["write_time"] = $datum->write_time;
    }


    public function show(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "show";
        $this->load_data();
        $this->load->view('masterkolam_form', $this->data);
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $this->load_data();
        $this->load->view('masterkolam_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $this->load_data();
        $this->load->view('masterkolam_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama Kolam', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tblok', 'Blok', 'required', array('required' => '%s harus dipilih'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['name'] = $this->input->post('tname');
        $this->data['blok_id'] = $this->input->post('tblok');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Kolam->cek_kembar($this->data['name'], $this->data['blok_id'], -1) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Nama kolam kembar</div>";
            } else {
                $result = $this->Kolam->insert($this->data['name'], $this->data['blok_id'], $_SESSION['id']);
                if($result == 1){
                    redirect('Masterkolam');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
        }
        $this->data["state"] = "create";
        $this->load->view('masterkolam_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama kolam', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tblok', 'Blok', 'required', array('required' => '%s harus dipilih'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['name'] = $this->input->post('tname');
        $this->data['blok_id'] = $this->input->post('tblok');
        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->Kolam->cek_kembar($this->data['name'], $this->data['blok_id'], $this->data['id']) == false){
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Nama kolam kembar</div>";
                } else {
                    $result = $this->Kolam->update($this->data['name'], $this->data['blok_id'], $this->data['id'], $_SESSION['id']);
                    if($result == 1){
                        redirect('Masterkolam');
                    }else{
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('masterkolam_form', $this->data);
        } else {
            redirect('Masterkolam');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Kolam->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                redirect('Masterkolam');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('masterkolam_form', $this->data);
        } else {
            redirect('Masterkolam');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Kolam->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Kolam->get_count_all()]);

    }


    public function getkolam(){
        $this->check_role();
        $blok_id = $this->input->post('blok_id');
        echo json_encode($this->Kolam->get_kolam_by_blok($blok_id));
    }


    public function get_occupied_kolam(){
        $this->check_role();
        $blok_id = $this->input->post('blok_id');
        echo json_encode($this->Kolam->get_occupied_kolam($blok_id));
    }

    public function get_available_kolam(){
        $this->check_role();
        $blok_id = $this->input->post('blok_id');
        $kolam_id = $this->input->post('kolam_id');
        echo json_encode($this->Kolam->get_all_kolam_by_blok_wo($blok_id, $kolam_id));
    }

    public function get_kolam_detail(){
        $this->check_role();
        $kolam_id = $this->input->post('kolam_id');
        echo json_encode($this->Kolam->get($kolam_id));
    }
}
