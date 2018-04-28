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

    public function get_now(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        return $dt->format("Y-m-d H:i:s");
    }


    public function get_role($kd, $pass)
    {
        //$query = $this->db->get_where('karyawan', array('kd_kar' => $kd, 'pass' => $pass));
        $pass = md5($pass);
        $query = $this->db->select('role_id')
            ->get_where('karyawan', array('uname'=>$kd, 'pass'=>$pass));
        return($query->result());
    }


    public function show_all($data_count, $offset, $searchword){
        $query = $this->db->select('k.id, k.uname, k.name, k.alamat, k.telp, r.id as role_id, r.role as role_name')
            ->from('karyawan k')
            ->where('k.deleted',0)
            ->group_start()
            ->like('k.name ', $searchword)
            ->or_like('k.uname ', $searchword)
            ->or_like('k.alamat ', $searchword)
            ->or_like('k.telp ', $searchword)
            ->or_like('r.role ', $searchword)
            ->group_end()
            ->join('role r', 'r.id = k.role_id')
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('karyawan');
        return $this->db->count_all_results();
    }

    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('karyawan')
            ->where('uname', strtoupper($name))
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }

    public function insert($uname, $name, $telp, $alamat, $pass, $kd_role, $id){
        $pass = md5($pass);
        $data = array(
            'uname' =>strtoupper($uname),
            'name' => strtoupper($name),
            'alamat' => $alamat,
            'telp' => $telp,
            'pass' => $pass,
            'role_id' => $kd_role,
            'deleted' => 0,
            'create_uid' => $id,
            'create_time' => $this->get_now(),
            'write_uid' => $id,
            'write_time' => $this->get_now()
        );

        $query = $this->db->insert('karyawan', $data);
        return $query;
    }


    public function update($uname, $name, $telp, $alamat, $pass, $kd_role, $id, $write_uid){
        $name = strtoupper($name);
        $uname = strtoupper($uname);
        $data = array(
            'uname' => $uname,
            'name' => $name,
            'alamat' => $alamat,
            'telp' => $telp,
            'pass' => md5($pass),
            'role_id' => $kd_role,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('karyawan', $data);
    }


    public function get($kode){
        $query = $this->db->select('k.id, k.uname, k.name, k.alamat, k.telp, r.id as role_id, r.role, k.pass')
            ->from('karyawan k')
            ->where('k.id', $kode)
            ->where('k.deleted',0)
            ->join('role r', 'r.id = k.role_id')
            ->get();
        return $query->result();
    }


    public function grab_data_login($username, $pass){
        $query = $this->db->select('role_id, uname, id, hash')
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


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('karyawan', $data);
    }


    public function change_pass($kd_kar, $old, $new){
        $query = $this->db->select('count(*) as jumlah')
            ->from('karyawan')
            ->where('pass', md5($old))
            ->where('id', $kd_kar)
            ->get()
            ->row();

        if($query->jumlah == 0){
            return 2;
        }
        $data = array(
            'pass'=> md5($new)
        );
        $this->db->where('pass', md5($old))
            ->where('id',$kd_kar);
        return $this->db->update('karyawan', $data);
    }


    public function set_hash($kd_kar, $hash){
        $data = array(
            'hash' => $hash
        );

        $this->db->where('id', $kd_kar);
        return $this->db->update('karyawan', $data);
    }


    public function check_double_login($kd_kar, $hash){
        $this->db->like('id', $kd_kar);
        $this->db->like('hash', $hash);
        $this->db->from('karyawan');
        if($this->db->count_all_results() > 0){
            return false;
        } else {
            return true;
        }
    }
}
?>