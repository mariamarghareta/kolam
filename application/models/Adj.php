<?php
class Adj extends CI_Model
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
        $query = $this->db->select('ref_id, type, stok, create_time, name, create_user')
            ->from('v_inv_adj')
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


    public function get_count_all()
    {
        $this->db->from('v_inv_adj');
        return $this->db->count_all_results();
    }


    public function insert_pakan($pakan_id, $stok, $create_uid){
        $data = array(
            'pakan_id' => $pakan_id,
            'stok' => $stok,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now()
        );
        $query = $this->db->insert('pakan_inventory_adj', $data);
        return $query;
    }


    public function insert_obat($obat_id, $stok, $create_uid){
        $data = array(
            'obat_id' => $obat_id,
            'stok' => $stok,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now()
        );
        $query = $this->db->insert('obat_inventory_adj', $data);
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