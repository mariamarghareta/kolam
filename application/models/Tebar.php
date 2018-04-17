<?php
class Tebar extends CI_Model
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


    public function get_kode(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        $prefix = $dt->format("Ymd");
        return (string)$prefix . str_pad($this->get_sequence($prefix),3,"0",STR_PAD_LEFT );
    }


    public function get_sequence($prefix){
        $query = $this->db->select('count(*) as jumlah')
            ->from('tebar')
            ->like('substr(kode,1,8)', $prefix)
            ->get();
        $result = $query->result();

        return $result[0]->jumlah + 1;
    }


    public function show_all($data_count, $offset, $searchword)
    {
        $query = $this->db->select('id, tgl_tebar, sampling, size, biomass, total_ikan, kode')
            ->from('tebar')
            ->where('deleted', 0)
            ->group_start()
            ->like('tgl_tebar ', $searchword)
            ->or_like('sampling ', $searchword)
            ->or_like('size ', $searchword)
            ->or_like('biomass ', $searchword)
            ->or_like('total_ikan ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('tebar');
        return $this->db->count_all_results();
    }


    public function insert($sampling, $size, $biomass, $total_ikan, $create_uid){
        $data = array(
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'tgl_tebar' => $this->get_now(),
            'kode' => $this->get_kode()
        );
        $query = $this->db->insert('tebar', $data);
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('tebar.id, tebar.sampling, tebar.size, tebar.biomass, tebar.total_ikan, tebar.tgl_tebar, tebar.kode, kolam.id as kolam_id, blok.id as blok_id, his.id as his_id, pakan.id as pakan_id')
            ->from('tebar')
            ->join('tebar_history his', 'his.tebar_id = tebar.id', 'left')
            ->join('kolam', 'kolam.id = his.tujuan_kolam_id', 'left')
            ->join('blok', 'blok.id = kolam.blok_id', 'left')
            ->join('pemberian_pakan pakan', 'pakan.sampling_id = his.sampling_id', 'left')
            ->where('tebar.id', $id)
            ->where('his.sequence', 1)
            ->where('tebar.deleted',0)
            ->get();
        return $query->result();
    }


    public function update($sampling, $size, $biomass, $total_ikan, $id, $write_uid){
        $data = array(
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tebar', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tebar', $data);
    }
}