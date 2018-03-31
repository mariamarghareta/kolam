<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterblok extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Blok');
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
        $this->data["arr"] = json_encode($this->Blok->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Blok->get_count_all();
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
    }


    public function check_role(){
        if(isset($_SESSION['role_id'])){
            $newdata = array(
                'role_id'  => $_SESSION['role_id'],
                'uname'     => $_SESSION['uname'],
                'id' => $_SESSION['id']
            );
            $this->session->set_tempdata($newdata, NULL, $this->Timeout->get_time()->minute * 60);
        } else {
            session_destroy();
            redirect('Login');
        }
    }


    public function index()
    {
        $this->check_role();
        $this->initialization();
        $this->load->view('masterblok', $this->data);
    }


    public function create(){
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('masterblok_form', $this->data);
    }


    public function update(){
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $datum = $this->Blok->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["name"] = $datum->name;
        $this->load->view('masterblok_form', $this->data);
    }

    public function delete(){
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $datum = $this->Blok->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["name"] = $datum->name;
        $this->load->view('masterblok_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama blok', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['name'] = $this->input->post('tname');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Blok->cek_kembar($this->data['name'], -1) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Nama blok kembar</div>";
            } else {
                $result = $this->Blok->insert($this->data['name']);
                if($result == 1){
                    redirect('Masterblok');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
        }
        $this->load->view('masterblok_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama blok', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['name'] = $this->input->post('tname');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Blok->cek_kembar($this->data['name'], $this->data['id']) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Nama blok kembar</div>";
            } else {
                $result = $this->Blok->update($this->data['name'], $this->data['id']);
                if($result == 1){
                    redirect('Masterblok');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                }
            }
        }
        $this->data["state"] = "update";
        $this->load->view('masterblok_form', $this->data);
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "delete";
        $this->data['id'] = $this->input->post('tid');
        print $this->data['id'];
        print "aaa";
        $result = $this->Blok->delete($this->data['id']);
        if($result == 1){
            redirect('Masterblok');
        }else{
            $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
        }
        $this->load->view('masterblok_form', $this->data);
    }


    public function page(){
        $page = $this->uri->segment(3);
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');

        print $page; print $data_per_page;

        $this->data["arr"] = json_encode($this->Blok->show_all($data_per_page, $page, $search_word));
        $this->data["max_data"] = $this->Blok->get_count_all();
        $this->data["data_per_page"] = $data_per_page;
        $this->data["page_count"] = 5;

        return $this->data["arr"];
        //echo "a";
    }
}
