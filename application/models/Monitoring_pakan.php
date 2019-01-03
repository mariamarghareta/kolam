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
            ->join('kolam k', 'k.id = mon.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = mon.tebar_id', 'left')
            ->join('pakan', 'pakan.id = mon.pakan_id', 'left')
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
        return $this->db->insert_id();
    }


    public function get($id){
        $query = $this->db->select('mon.id, mon.kolam_id, mon.tebar_id, mon.pemberian_pakan_id, mon.dt, mon.waktu_pakan, mon.pakan_id, mon.jumlah_pakan, mon.mr, mon.keterangan, b.name as blok_name, k.name as kolam_name, t.kode, pakan.name as pakan_name, b.id as blok_id, kar.name as create_user, karw.name as write_user, mon.create_time, mon.write_time')
            ->from('monitoring_pakan mon')
            ->join('kolam k', 'k.id = mon.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.id = mon.tebar_id', 'left')
            ->join('pakan', 'pakan.id = mon.pakan_id', 'left')
            ->join('karyawan kar', 'kar.id = mon.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = mon.write_uid', 'left')
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


    public function monitoring_all(){
        $query = $this->db->select('m.kolam_id, m.kode, m.air_pagi, m.air_sore, m.pakan_pagi, m.pakan_sore, m.pakan_malam, b.name as blok_name, k.name as kolam_name, m.pemberian_pakan_id, m.fcr, m.total_ikan, t.id as tebar_id')
            ->from('v_monitoring_all m')
            ->join('kolam k', 'k.id = m.kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('tebar t', 't.kode = m.kode', 'left')
            ->get();
        return $query->result_array();
    }

    public function insert_bahan_penolong($monitoring_pakan_id, $obat_id, $jumlah, $satuan, $create_uid){
        $data = array(
            'monitoring_pakan_id' => $monitoring_pakan_id,
            'obat_id' => $obat_id,
            'jumlah' => $jumlah,
            'satuan' => $satuan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $this->db->insert('pakan_obat', $data);
        return $this->db->insert_id();
    }

    public function get_bahan_penolong($id){
        $query = $this->db->select('bhn.id, bhn.obat_id, bhn.jumlah, obat.name as obat_name, obat.satuan, bhn.monitoring_pakan_id')
            ->from('pakan_obat bhn')
            ->join('obat', 'obat.id = bhn.obat_id')
            ->where('bhn.deleted', 0)
            ->where('bhn.monitoring_pakan_id', $id)
            ->get();
        return $query->result_array();
    }

    public function delete_bahan_penolong($monitoring_pakan_id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );
        $this->db->where('monitoring_pakan_id', $monitoring_pakan_id);
        return $this->db->update('pakan_obat', $data);
    }
}