<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastertabelpakan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Tabelpakan');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["age"] = "";
        $this->data["weight"] = "";
        $this->data["fr"] = "";
        $this->data["sr"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Tabelpakan->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Tabelpakan->get_count_all();
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
        $this->load->view('mastertabelpakan', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('mastertabelpakan_form', $this->data);
    }


    public function load_data(){
        $datum = $this->Tabelpakan->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["age"] = $datum->age;
        $this->data["weight"] = $datum->weight;
        $this->data["fr"] = $datum->fr;
        $this->data["sr"] = $datum->sr;
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
        $this->load->view('mastertabelpakan_form', $this->data);
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $this->load_data();
        $this->load->view('mastertabelpakan_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $this->load_data();
        $this->load->view('mastertabelpakan_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('age', 'Usia', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('weight', 'Berat', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_rules('fr', 'FR', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_rules('sr', 'SR', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['age'] = $this->input->post('age');
        $this->data['weight'] = $this->input->post('weight');
        $this->data['fr'] = $this->input->post('fr');
        $this->data['sr'] = $this->input->post('sr');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Tabelpakan->cek_kembar($this->data['weight'], $this->data['fr'], $this->data['sr'], -1) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Data tabel pakan kembar</div>";
            } else {
                $result = $this->Tabelpakan->insert($this->data['age'], $this->data['weight'], $this->data['fr'], $this->data['sr'], $_SESSION['id']);
                if($result == 1){
                    redirect('Mastertabelpakan');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
        }
        $this->data["state"] = "create";
        $this->load->view('mastertabelpakan_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('age', 'Usia', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('weight', 'Berat', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_rules('fr', 'FR', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_rules('sr', 'SR', 'required', array('required' => '%s harus diisi', 'numeric'=>'Harus berupa angka'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['tid'] = $this->input->post('tid');
        $this->data['age'] = $this->input->post('age');
        $this->data['weight'] = $this->input->post('weight');
        $this->data['fr'] = $this->input->post('fr');
        $this->data['sr'] = $this->input->post('sr');
        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->Tabelpakan->cek_kembar($this->data['weight'], $this->data['fr'], $this->data['sr'], -1) == false){
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Data tabel pakan kembar</div>";
                } else {
                    $result = $this->Tabelpakan->update($this->data['age'], $this->data['weight'], $this->data['fr'], $this->data['sr'], $this->data['tid'], $_SESSION['id']);
                    if($result == 1){
                        redirect('Mastertabelpakan');
                    }else{
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('mastertabelpakan_form', $this->data);
        } else {
            redirect('Mastertabelpakan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Tabelpakan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                redirect('Mastertabelpakan');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('mastertabelpakan_form', $this->data);
        } else {
            redirect('Mastertabelpakan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Tabelpakan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Tabelpakan->get_count_all()]);

    }


    public function getpakan(){
        $this->check_role();
        $size = $this->input->post('size');
        echo json_encode($this->Tabelpakan->get_pakan($size));
    }
}
