<?php
class Monitoring_pakan extends CI_Model
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
        $query = $this->db->select('mon.id, mon.kolam_id, mon.tebar_id, mon.pemberian_pakan_id, mon.dt, mon.waktu_pakan, mon.pakan_id, mon.jumlah_pakan, mon.mr, mon.keterangan, b.name as blok_name, k.name as kolam_name, t.kode, pakan.name as pakan_name')
            ->from('monitoring_pakan mon')
            ->join('kolam k', 'k.id = mon.kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('tebar t', 't.id = mon.tebar_id')
            ->join('pakan', 'pakan.id = mon.pakan_id')
            ->where('mon.deleted', 0)
            ->group_start()
            ->like('b.name ', $searchword)
            ->or_like('k.name ', $searchword)
            ->or_like('mon.waktu_pakan ', strtoupper($searchword))
            ->or_like('mon.mr ', $searchword)
            ->or_like('t.kode ', $searchword)
            ->or_like('mon.dt ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('mon.create_time desc')
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name')
            ->from('obat')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('monitoring_pakan');
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


    public function insert($kolam_id, $tebar_id, $pemberian_pakan_id, $waktu_pakan, $pakan_id, $jumlah_pakan, $mr, $keterangan, $create_uid){
        $data = array(
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'dt' => $this->get_now(),
            'waktu_pakan' => $waktu_pakan,
            'pakan_id' => $pakan_id,
            'jumlah_pakan' => $jumlah_pakan,
            'mr' => $mr,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('monitoring_pakan', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('mon.id, mon.kolam_id, mon.tebar_id, mon.pemberian_pakan_id, mon.dt, mon.waktu_pakan, mon.pakan_id, mon.jumlah_pakan, mon.mr, mon.keterangan, b.name as blok_name, k.name as kolam_name, t.kode, pakan.name as pakan_name, b.id as blok_id')
            ->from('monitoring_pakan mon')
            ->join('kolam k', 'k.id = mon.kolam_id')
            ->join('blok b', 'b.id = k.blok_id')
            ->join('tebar t', 't.id = mon.tebar_id')
            ->join('pakan', 'pakan.id = mon.pakan_id')
            ->where('mon.deleted', 0)
            ->where('mon.id', $id)
            ->get();
        return $query->result();
    }


    public function update($kolam_id, $tebar_id, $pemberian_pakan_id, $waktu_pakan, $pakan_id, $jumlah_pakan, $mr, $keterangan, $id, $write_uid){
        $data = array(
            'kolam_id' => $kolam_id,
            'tebar_id' => $tebar_id,
            'pemberian_pakan_id' => $pemberian_pakan_id,
            'dt' => $this->get_now(),
            'waktu_pakan' => $waktu_pakan,
            'pakan_id' => $pakan_id,
            'jumlah_pakan' => $jumlah_pakan,
            'mr' => $mr,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_pakan', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_pakan', $data);
    }
}