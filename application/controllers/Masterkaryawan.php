<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterkaryawan extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Karyawan');
        $this->load->model('Role');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["name"] = "";
        $this->data["uname"] = "";
        $this->data["alamat"] = "";
        $this->data["telp"] = "";
        $this->data["pass"] = "";
        $this->data["repass"] = "";
        $this->data["role"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Karyawan->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Karyawan->get_count_all();
        $this->data["arr_role"] = $this->Role->show_all();
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
        $this->load->view('masterkaryawan', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('masterkaryawan_form', $this->data);
    }

    public function load_data(){
        $datum = $this->Karyawan->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["uname"] = $datum->uname;
        $this->data["name"] = $datum->name;
        $this->data["alamat"] = $datum->alamat;
        $this->data["telp"] = $datum->telp;
        $this->data["pass"] = $datum->pass;
        $this->data["repass"] = $datum->pass;
        $this->data["role"] = $datum->role_id;
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
        $this->load->view('masterkaryawan_form', $this->data);
    }

    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $this->load_data();
        $this->load->view('masterkaryawan_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $this->load_data();
        $this->load->view('masterkaryawan_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('uname', 'Username', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tname', 'Nama Karyawan', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('telp', 'No. Telp', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('alamat', 'Alamat', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('pass', 'Passowrd', 'required|min_length[5]', array('required'=>'Harus diisi', 'min_length' => 'Minimal 5 karakter'));
        $this->form_validation->set_rules('repass', 'Passowrd', 'required|matches[pass]', array('required'=>'Harus diisi','matches' => 'Password tidak sama'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['uname'] = $this->input->post('uname');
        $this->data['name'] = $this->input->post('tname');
        $this->data['telp'] = $this->input->post('telp');
        $this->data['alamat'] = $this->input->post('alamat');
        $this->data['pass'] = $this->input->post('pass');
        $this->data['role'] = $this->input->post('role');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Karyawan->cek_kembar($this->data['uname'], -1) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Username kembar</div>";
            } else {
                $result = $this->Karyawan->insert($this->data['uname'], $this->data['name'], $this->data['telp'], $this->data['alamat'], $this->data['pass'], $this->data['role'], $_SESSION['id']);
                if($result == 1){
                    redirect('Masterkaryawan');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
        }
        $this->data["state"] = "create";
        $this->load->view('masterkaryawan_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('uname', 'Username', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('tname', 'Nama Karyawan', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('telp', 'No. Telp', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('alamat', 'Alamat', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('pass', 'Passowrd', 'required|min_length[5]', array('required'=>'Harus diisi', 'min_length' => 'Minimal 5 karakter'));
        $this->form_validation->set_rules('repass', 'Passowrd', 'required|matches[pass]', array('required'=>'Harus diisi','matches' => 'Password tidak sama'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['uname'] = $this->input->post('uname');
        $this->data['name'] = $this->input->post('tname');
        $this->data['telp'] = $this->input->post('telp');
        $this->data['alamat'] = $this->input->post('alamat');
        $this->data['pass'] = $this->input->post('pass');
        $this->data['repass'] = $this->input->post('repass');
        $this->data['role'] = $this->input->post('role');
        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->Karyawan->cek_kembar($this->data['uname'], $this->data['id']) == false){
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Username kembar</div>";
                } else {
                    $result = $this->Karyawan->update($this->data['uname'], $this->data['name'], $this->data['telp'], $this->data['alamat'], $this->data['pass'], $this->data['role'], $this->data['id'], $_SESSION['id']);
                    if($result == 1){
                        redirect('Masterkaryawan');
                    }else{
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('masterkaryawan_form', $this->data);
        } else {
            redirect('Masterkaryawan');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Karyawan->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                redirect('MasterKaryawan');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('masterkaryawan_form', $this->data);
        } else {
            redirect('Masterkaryawan');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Karyawan->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Karyawan->get_count_all()]);

    }
}
