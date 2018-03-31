<?php
class Karyawan extends CI_Model {

    public $kd_kar;
    public $pass;
    public $kd_role;

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
    }


    public function get_role($kd, $pass)
    {
        //$query = $this->db->get_where('karyawan', array('kd_kar' => $kd, 'pass' => $pass));
        $pass = md5($pass);
        $query = $this->db->select('role_id')
            ->get_where('karyawan', array('uname'=>$kd, 'pass'=>$pass));
        return($query->result());
    }


    public function show_all(){
        $query = $this->db->select('k.kd_kar, k.uname, k.nama, k.alamat, k.telp,r.role_id, r.name')
            ->from('karyawan k')
            ->where('k.deleted',0)
            ->join('role r', 'r.id = k.role_id')
            ->get();
        return $query->result();
    }


    public function insert($uname, $name, $alamat, $telp, $pass, $kd_role){
        $pass = md5($pass);
        $data = array(
            'uname' =>$uname,
            'nama' => $name,
            'alamat' => $alamat,
            'telp' => $telp,
            'pass' => $pass,
            'kd_role' => $kd_role,
            'deleted' => 0
        );

        $query = $this->db->insert('karyawan', $data);
        return $query;
    }


    public function cek_uname($uname){
        $this->db->from('karyawan');
        $this->db->where('uname', $uname);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function cek_uname_update($uname, $kode){
        $this->db->from('karyawan');
        $this->db->where('uname', $uname);
        $this->db->where('kd_kar!=',$kode);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function grab_data($kode){
        $query = $this->db->select('k.kd_kar, k.uname, k.nama, k.alamat, k.telp,r.role_id, r.name, k.pass')
            ->from('karyawan k')
            ->where('k.kd_kar', $kode)
            ->where('k.deleted',0)
            ->where('r.deleted',0)
            ->join('role r', 'r.id = k.role_id')
            ->get();
        return $query->result_array();
    }


    public function grab_data_login($username, $pass){
        $query = $this->db->select('role_id, uname, id')
            ->from('karyawan')
            ->where('uname', $username)
            ->where('pass',md5($pass))
            ->where('deleted',0)
            ->limit(1)
            ->get();
        if (sizeof($query->result_array())> 0){
            $temp = $query->result_array();
            return $temp[0];
        } else {
            return False;
        }
    }


    private function get_pass($kode){
        $query = $this->db->select('k.pass')
            ->from('karyawan k')
            ->where('k.kd_kar', $kode)
            ->get();
        return $query->result_array();
    }


    public function update_data($kode, $uname, $name, $alamat, $telp, $pass, $kd_role){
        $passlama = $this->get_pass($kode);
        if($passlama != $pass){
            $pass = md5($pass);
        }

        $data = array(
            'uname' => $uname,
            'nama' => $name,
            'alamat' => $alamat,
            'telp'=> $telp,
            'pass'=> $pass,
            'kd_role'=> $kd_role
        );

        $this->db->where('kd_kar', $kode);
        return $this->db->update('karyawan', $data);
    }


    public function delete_data($kode){
        $data = array(
            'deleted' => 1
        );
        $this->db->where('kd_kar', $kode);
        return $this->db->update('karyawan', $data);
    }


    public function change_pass($kd_kar, $old, $new){
        $query = $this->db->select('count(*) as jumlah')
            ->from('karyawan')
            ->where('pass', md5($old))
            ->where('kd_kar', $kd_kar)
            ->get()
            ->row();

        if($query->jumlah == 0){
            return 2;
        }
        $data = array(
            'pass'=> md5($new)
        );
        $this->db->where('pass', md5($old))
            ->where('kd_kar',$kd_kar);
        return $this->db->update('karyawan', $data);
    }
}
?>