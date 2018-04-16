<?php
class Kolam extends CI_Model
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
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->group_start()
            ->like('kolam.name ', $searchword)
            ->or_like('blok.name ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('kolam');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $blok_id, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('kolam')
            ->where('name', strtoupper($name))
            ->where('blok_id', $blok_id)
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($nama, $blok_id, $create_uid){
        $nama = strtoupper($nama);
        $data = array(
            'name' => $nama,
            'blok_id' => $blok_id,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now()
        );
        $query = $this->db->insert('kolam', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('kolam.id, kolam.name, blok.id as blok_id, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.id', $id)
            ->where('kolam.deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $blok_id, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'blok_id' => $blok_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }


    public function get_kolam_by_blok($blok_id){
        $query = $this->db->select('kolam.id, kolam.name, blok.name as blok_name')
            ->from('kolam')
            ->join('blok', 'blok.id = kolam.blok_id')
            ->where('kolam.deleted', 0)
            ->where('blok.id', $blok_id)
            ->where('kolam.tebar_id', 0)
            ->get();
        return $query->result_array();
    }


    public function update_pemberian_pakan($pemberian_pakan_id, $tebar_id, $id, $write_uid){
        $data = array(
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'tebar_id' => $tebar_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('kolam', $data);
    }
}