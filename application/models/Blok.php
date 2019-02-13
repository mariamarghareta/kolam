<?php
class Blok extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
        $this->load->helper('date');
    }

    public function get_now(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        return $dt->format("Y-m-d H:i:s");
    }


    public function show_all($data_count, $offset, $searchword)
    {
        $query = $this->db->select('id, name')
            ->from('blok')
            ->where('deleted', 0)
            ->like('name ', $searchword)
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name')
            ->from('blok')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function show_blok_tebar(){
        $query = $this->db->select('blok.id, blok.name')
            ->from('blok')
            ->join('kolam', 'kolam.blok_id = blok.id', 'left')
            ->where('blok.deleted', 0)
            ->where('kolam.tebar_id !=', 0)
            ->distinct()
            ->get();
        return $query->result_array();
    }


    public function show_blok_available(){
        $query = $this->db->select('blok.id, blok.name')
            ->from('blok')
            ->join('kolam', 'kolam.blok_id = blok.id', 'left')
            ->where('blok.deleted', 0)
            ->where('kolam.deleted', 0)
            ->where('kolam.tebar_id', 0)
            ->distinct()
            ->get();
        return $query->result_array();
    }

    public function show_blok_berkolam(){
        $query = $this->db->select('blok.id, blok.name')
            ->from('blok')
            ->join('kolam', 'kolam.blok_id = blok.id', 'left')
            ->where('blok.deleted', 0)
            ->where('kolam.deleted', 0)
            ->distinct()
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('blok');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('blok')
            ->where('name', strtoupper($name))
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($nama, $create_uid){
        $nama = strtoupper($nama);
        $data = array(
            'name' => $nama,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('blok', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('blok.id, blok.name, k.name as create_user, kw.name as write_user, blok.create_time, blok.write_time')
            ->from('blok')
            ->join('karyawan k', 'k.id = blok.create_uid', 'left')
            ->join('karyawan kw', 'kw.id = blok.write_uid', 'left')
            ->where('blok.id', $id)
            ->where('blok.deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('blok', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('blok', $data);
    }
}