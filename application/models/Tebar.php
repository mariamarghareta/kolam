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
        $query = $this->db->select('t.id, t.tgl_tebar, t.sampling, t.size, round(t.biomass, 2) as biomass, t.total_ikan, t.kode, t.angka, t.satuan, k.name as kolam_name, b.name as blok_name')
            ->from('tebar t')
            ->join('tebar_history his', 'his.tebar_id = t.id', 'left')
            ->join('kolam k', 'k.id = his.tujuan_kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->where('t.deleted', 0)
            ->where('his.sequence', 1)
            ->group_start()
            ->like('t.tgl_tebar ', $searchword)
            ->or_like('t.sampling ', $searchword)
            ->or_like('t.size ', $searchword)
            ->or_like('t.biomass ', $searchword)
            ->or_like('t.total_ikan ', $searchword)
            ->or_like('k.name ', $searchword)
            ->or_like('b.name ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }

    public function get_kode_all(){
        $query = $this->db->select('t.id, t.kode')
            ->from('tebar t')
            ->get();
        return $query->result();
    }

    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('tebar');
        return $this->db->count_all_results();
    }


    public function insert($sampling, $size, $biomass, $total_ikan, $angka, $satuan, $create_uid){
        $data = array(
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'tgl_tebar' => $this->get_now(),
            'kode' => $this->get_kode(),
            'angka' => $angka,
            'satuan' => $satuan,
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('tebar', $data);
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('tebar.id, tebar.sampling, tebar.size, tebar.biomass, tebar.total_ikan, tebar.tgl_tebar, tebar.kode, kolam.id as kolam_id, blok.id as blok_id, his.id as his_id, pakan.id as pakan_id, his.sampling_id, tebar.angka, tebar.satuan, kar.name as create_user, karw.name as write_user, tebar.create_time, tebar.write_time')
            ->from('tebar')
            ->join('tebar_history his', 'his.tebar_id = tebar.id', 'left')
            ->join('kolam', 'kolam.id = his.tujuan_kolam_id', 'left')
            ->join('blok', 'blok.id = kolam.blok_id', 'left')
            ->join('pemberian_pakan pakan', 'pakan.sampling_id = his.sampling_id', 'left')
            ->join('karyawan kar', 'kar.id = tebar.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = tebar.write_uid', 'left')
            ->where('tebar.id', $id)
            ->where('his.sequence', 1)
            ->where('tebar.deleted',0)
            ->get();
        return $query->result();
    }

    public function get_array($id){
        $query = $this->db->select('tebar.id, tebar.sampling, tebar.size, tebar.biomass, tebar.total_ikan, tebar.tgl_tebar, tebar.kode, kolam.id as kolam_id, blok.id as blok_id, his.id as his_id, pakan.id as pakan_id, his.sampling_id, tebar.angka, tebar.satuan, kar.name as create_user, karw.name as write_user, tebar.create_time, tebar.write_time')
            ->from('tebar')
            ->join('tebar_history his', 'his.tebar_id = tebar.id', 'left')
            ->join('kolam', 'kolam.id = his.tujuan_kolam_id', 'left')
            ->join('blok', 'blok.id = kolam.blok_id', 'left')
            ->join('pemberian_pakan pakan', 'pakan.sampling_id = his.sampling_id', 'left')
            ->join('karyawan kar', 'kar.id = tebar.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = tebar.write_uid', 'left')
            ->where('tebar.id', $id)
            ->where('his.sequence', 1)
            ->where('tebar.deleted',0)
            ->get();
        return $query->result_array();
    }


    public function update($sampling, $size, $biomass, $total_ikan, $angka, $satuan, $id, $write_uid){
        $data = array(
            'sampling' => $sampling,
            'size' => $size,
            'biomass' => $biomass,
            'total_ikan' => $total_ikan,
            'angka' => $angka,
            'satuan' => $satuan,
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

    public function get_report($from, $to)
    {
        $query = $this->db->select('lap.*')
            ->from('tebar lap')
            ->where('CAST(lap.tgl_tebar As DATETIME) >=', $from)
            ->where('CAST(lap.tgl_tebar As DATETIME) <=', $to)
            ->get();
        return $query->result_array();
    }
}