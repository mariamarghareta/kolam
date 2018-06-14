<?php
class Ikan extends CI_Model
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
            ->from('ikan')
            ->where('deleted', 0)
            ->like('name ', $searchword)
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('ikan');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('ikan')
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
        $query = $this->db->insert('ikan', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('ikan.id, ikan.name, kar.name as create_user, karw.name as write_user, ikan.create_time, ikan.write_time')
            ->from('ikan')
            ->join('karyawan kar', 'kar.id = ikan.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = ikan.write_uid', 'left')
            ->where('ikan.id', $id)
            ->where('ikan.deleted',0)
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
        return $this->db->update('ikan', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('ikan', $data);
    }
}