<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mastermitra extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Timeout');
        $this->load->model('Mitra');
        $this->load->model('Karyawan');
    }
    private $data;


    public function initialization()
    {
        $this->data["state"] = "";
        $this->data["name"] = "";
        $this->data["phone1"] = "";
        $this->data["phone2"] = "";
        $this->data["tipe_mitra"] = "";
        $this->data["keterangan"] = "";
        $this->data["alamat"] = "";
        $this->data["msg"] = "";
        $this->data["id"] = "";
        $this->data["search_word"] = "";
        $data_count = 10;
        $offset = 1;
        $this->data["arr"] = json_encode($this->Mitra->show_all($data_count, $offset, $this->data["search_word"]));
        $this->data["max_data"] = $this->Mitra->get_count_all();
        $this->data["data_per_page"] = $data_count;
        $this->data["page_count"] = 5;
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
        $this->load->view('mastermitra', $this->data);
    }


    public function create(){
        $this->check_role();
        $this->initialization();
        $this->data["state"] = "create";
        $this->load->view('mastermitra_form', $this->data);
    }


    public function update(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "update";
        $datum = $this->Mitra->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["name"] = $datum->name;
        $this->data["phone1"] = $datum->phone1;
        $this->data["phone2"] = $datum->phone2;
        $this->data["tipe_mitra"] = $datum->tipe_mitra;
        $this->data["keterangan"] = $datum->keterangan;
        $this->data["alamat"] = $datum->alamat;
        $this->load->view('mastermitra_form', $this->data);
    }

    public function delete(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->uri->segment(3);

        $this->data["state"] = "delete";
        $datum = $this->Mitra->get($this->data['id'])[0];
        $this->data["id"] = $datum->id;
        $this->data["name"] = $datum->name;
        $this->data["phone1"] = $datum->phone1;
        $this->data["phone2"] = $datum->phone2;
        $this->data["tipe_mitra"] = $datum->tipe_mitra;
        $this->data["keterangan"] = $datum->keterangan;
        $this->data["alamat"] = $datum->alamat;
        $this->load->view('mastermitra_form', $this->data);
    }


    public function add_new_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama Mitra', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('phone1', 'Telefon 1', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('alamat', 'Alamat', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['name'] = $this->input->post('tname');
        $this->data['phone1'] = $this->input->post('phone1');
        $this->data['phone2'] = $this->input->post('phone2');
        $this->data['tipe_mitra'] = $this->input->post('tipe_mitra');
        $this->data['keterangan'] = $this->input->post('keterangan');
        $this->data['alamat'] = $this->input->post('alamat');
        if ($this->form_validation->run() != FALSE)
        {
            if($this->Mitra->cek_kembar($this->data['name'], $this->data['phone1'], $this->data['alamat'], -1) == false){
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Data Mitra kembar</div>";
            } else {
                $result = $this->Mitra->insert($this->data['name'], $this->data['phone1'], $this->data['phone2'], $this->data['tipe_mitra'], $this->data['alamat'], $this->data['keterangan'], $_SESSION['id']);
                if($result == 1){
                    redirect('Mastermitra');
                }else{
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Insert Gagal</div>";
                }
            }
        }
        $this->data["state"] = "create";
        $this->load->view('mastermitra_form', $this->data);
    }


    public function update_data(){
        $this->check_role();
        $this->initialization();
        $this->form_validation->set_rules('tname', 'Nama Mitra', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('phone1', 'Telefon 1', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_rules('alamat', 'Alamat', 'required', array('required' => '%s harus diisi'));
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        $this->data['id'] = $this->input->post('tid');
        $this->data['name'] = $this->input->post('tname');
        $this->data['phone1'] = $this->input->post('phone1');
        $this->data['phone2'] = $this->input->post('phone2');
        $this->data['tipe_mitra'] = $this->input->post('tipe_mitra');
        $this->data['keterangan'] = $this->input->post('keterangan');
        $this->data['alamat'] = $this->input->post('alamat');
        if($this->input->post('write') == "write"){
            if ($this->form_validation->run() != FALSE)
            {
                if($this->Mitra->cek_kembar($this->data['name'], $this->data['phone1'], $this->data['alamat'], $this->data['id']) == false){
                    $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Data Mitra kembar</div>";
                } else {
                    $result = $this->Mitra->update($this->data['name'], $this->data['phone1'], $this->data['phone2'], $this->data['tipe_mitra'], $this->data['alamat'], $this->data['keterangan'], $this->data['id'], $_SESSION['id']);
                    if($result == 1){
                        redirect('mastermitra');
                    }else{
                        $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Update Gagal</div>";
                    }
                }
            }
            $this->data["state"] = "update";
            $this->load->view('mastermitra_form', $this->data);
        } else {
            redirect('Mastermitra');
        }
    }


    public function delete_data(){
        $this->check_role();
        $this->initialization();
        $this->data['id'] = $this->input->post('tid');
        if($this->input->post('delete') == "delete") {
            $result = $this->Mitra->delete($this->data['id'], $_SESSION['id']);
            if($result == 1){
                redirect('mastermitra');
            }else{
                $this->data['msg'] = "<div id='err_msg' class='alert alert-danger sldown' style='display:none;'>Hapus Data Gagal</div>";
            }
            $this->data["state"] = "delete";
            $this->load->view('mastermitra_form', $this->data);
        } else {
            redirect('Mastermitra');
        }
    }


    public function page(){
        $this->check_role();
        $page = $this->input->post('page');
        $data_per_page = $this->input->post('data_per_page');
        $search_word = $this->input->post('search_word');
        echo json_encode([$this->Mitra->show_all($data_per_page, $page, $search_word),$this->data["max_data"] = $this->Mitra->get_count_all()]);

    }
}
