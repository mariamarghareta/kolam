<?php
class Penjualan extends CI_Model
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
        $query = $this->db->select('j.id, j.dt, j.kolam_id, j.tebar_id, j.pemberian_pakan_id, j.mitra_bisnis_id, j.jumlah, j.harga, j.total, j.keterangan, k.name as kolam_name, b.name as blok_name, t.kode, m.name as mitra_name')
            ->from('jual j')
            ->join('kolam k', 'k.id = j.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = j.tebar_id', 'left')
            ->join('mitra_bisnis m', 'm.id = j.mitra_bisnis_id', 'left')
            ->where('j.deleted', 0)
            ->group_start()
            ->like('j.dt ', $searchword)
            ->or_like('k.name ', $searchword)
            ->or_like('b.name ', $searchword)
            ->or_like('t.kode ', $searchword)
            ->group_end()
            ->order_by('dt desc')
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name')
            ->from('jual')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('jual');
        return $this->db->count_all_results();
    }


    public function insert($mitra_id, $kolam_id, $tebar_id, $pemberian_pakan_id, $jumlah, $harga, $total, $keterangan, $create_uid){
        $data = array(
            'dt' => $this->get_now(),
            'mitra_bisnis_id' => $mitra_id,
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'total' => $total,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now(),
        );
        $query = $this->db->insert('jual', $data);
        return $this->db->insert_id();
    }


    public function update($mitra_id, $kolam_id, $tebar_id, $pemberian_pakan_id, $jumlah, $harga, $total, $keterangan, $id, $write_uid){
        $data = array(
            'mitra_bisnis_id' => $mitra_id,
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'jumlah' => $jumlah,
            'harga' => $harga,
            'total' => $total,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('jual', $data);
    }


    public function get($id){
        $query = $this->db->select('j.id, j.dt, j.kolam_id, j.tebar_id, j.pemberian_pakan_id, j.mitra_bisnis_id, j.jumlah, j.harga, j.total, j.keterangan, k.name as kolam_name, b.name as blok_name, b.id as blok_id, t.kode, m.name as mitra_name')
            ->from('jual j')
            ->join('kolam k', 'k.id = j.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = j.tebar_id', 'left')
            ->join('mitra_bisnis m', 'm.id = j.mitra_bisnis_id', 'left')
            ->where('j.deleted', 0)
            ->where('j.id', $id)
            ->get();
        return $query->result();
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );
        $this->db->where('id', $id);
        return $this->db->update('jual', $data);
    }
}