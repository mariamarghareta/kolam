<?php
class Monitoring_air extends CI_Model
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
        $query = $this->db->select('k.name as kolam_name, m.kolam_id, m.id, m.tinggi_air, m.waktu, m.ph, m.suhu, m.kcr, m.warna, m.pemberian_pakan_id, m.tebar_id, m.create_time, t.kode, b.name as blok_name')
            ->from('monitoring_air m')
            ->join('kolam k', 'k.id = m.kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('tebar t', 't.id = m.tebar_id')
            ->where('m.deleted', 0)
            ->group_start()
            ->like('k.name ', $searchword)
            ->or_like('m.tinggi_air ', $searchword)
            ->or_like('m.waktu ', $searchword)
            ->or_like('m.ph ', $searchword)
            ->or_like('m.suhu ', $searchword)
            ->or_like('m.kcr ', $searchword)
            ->or_like('m.warna ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('m.create_time desc')
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('*')
            ->from('monitoring_air')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('monitoring_air');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('obat')
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


    public function insert($kolam_id, $tebar_id, $pemberian_pakan_id, $tinggi_air, $waktu, $suhu, $ph, $kcr, $warna, $keterangan, $create_uid){
        $data = array(
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'waktu' => $waktu,
            'tinggi_air' => $tinggi_air,
            'suhu' => $suhu,
            'ph' => $ph,
            'kcr' => $kcr,
            'warna' => $warna,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $this->db->insert('monitoring_air', $data);
        return $this->db->insert_id();
    }


    public function insert_bahan_penolong($monitoring_air_id, $obat_id, $jumlah, $create_uid){
        $data = array(
            'monitoring_air_id' => $monitoring_air_id,
            'obat_id' => $obat_id,
            'jumlah' => $jumlah,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $this->db->insert('bahan_penolong', $data);
        return $this->db->insert_id();
    }


    public function delete_bahan_penolong($monitoring_air_id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );
        $this->db->where('monitoring_air_id', $monitoring_air_id);
        return $this->db->update('bahan_penolong', $data);
    }


    public function get($id){
        $query = $this->db->select('k.name as kolam_name, m.kolam_id, m.id, m.tinggi_air, m.waktu, m.ph, m.suhu, m.kcr, m.warna, m.pemberian_pakan_id, m.tebar_id, m.create_time, t.kode, b.name as blok_name, b.id as blok_id, m.keterangan')
            ->from('monitoring_air m')
            ->join('kolam k', 'k.id = m.kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('tebar t', 't.id = m.tebar_id')
            ->where('m.deleted', 0)
            ->where('m.id', $id)
            ->where('m.deleted',0)
            ->get();
        return $query->result();
    }


    public function get_bahan_penolong($id){
        $query = $this->db->select('bhn.id, bhn.obat_id, bhn.jumlah, obat.name as obat_name, obat.satuan, bhn.monitoring_air_id')
            ->from('bahan_penolong bhn')
            ->join('obat', 'obat.id = bhn.obat_id')
            ->where('bhn.deleted', 0)
            ->where('bhn.monitoring_air_id', $id)
            ->get();
        return $query->result_array();
    }


    public function update($kolam_id, $tebar_id, $pemberian_pakan_id, $tinggi_air, $waktu, $suhu, $ph, $kcr, $warna, $keterangan, $id, $write_uid){
        $data = array(
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'waktu' => $waktu,
            'tinggi_air' => $tinggi_air,
            'suhu' => $suhu,
            'ph' => $ph,
            'kcr' => $kcr,
            'warna' => $warna,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_air', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_air', $data);
    }
}