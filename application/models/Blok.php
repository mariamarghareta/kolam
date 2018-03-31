<?php
class Blok extends CI_Model
{

    public function __construct()
    {
        // Call the CI_Model constructor
        parent::__construct();
        $this->load->database();
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


    public function insert($nama){
        $nama = strtoupper($nama);
        $data = array(
            'name' =>$nama
        );
        $query = $this->db->insert('blok', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('id, name')
            ->from('blok')
            ->where('id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $id){
        $name = strtoupper($name);
        $data = array(
            'name' => $name
        );

        $this->db->where('id', $id);
        return $this->db->update('blok', $data);
    }


    public function delete($id){
        $data = array(
            'deleted' => 1
        );

        $this->db->where('id', $id);
        return $this->db->update('blok', $data);
    }
}