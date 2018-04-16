<?php
class Tebar_history extends CI_Model
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

    }


    public function get_count_all()
    {

    }


    public function insert($tebar_id, $sampling_id, $grading_id, $keterangan, $asal_kolam, $tujuan_kolam, $create_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'dt' => $this->get_now(),
            'sampling_id' => $sampling_id,
            'grading_id' => $grading_id,
            'keterangan' => $keterangan,
            'asal_kolam_id' => $asal_kolam,
            'tujuan_kolam_id' => $tujuan_kolam,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now()
        );
        $query = $this->db->insert('tebar_history', $data);
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('id, name')
            ->from('obat')
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
        return $this->db->update('obat', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('obat', $data);
    }
}