<?php
class Pembuatan_pakan extends CI_Model
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

    public function get_now_date(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        return $dt->format("Y-m-d");
    }

    public function get_now_time(){
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('GMT+7'));
        return $dt->format("H:i:s");
    }

    public function show_all($data_count, $offset, $searchword)
    {
        $query = $this->db->select('mon.id, mon.create_time, mon.pakan_id, mon.jumlah_pakan, mon.keterangan, pakan.name as pakan_name')
            ->from('pembuatan_pakan mon')
            ->join('pakan', 'pakan.id = mon.pakan_id', 'left')
            ->where('mon.deleted', 0)
            ->group_start()
            ->like('pakan.name ', $searchword)
            ->or_like('mon.jumlah_pakan ', $searchword)
            ->or_like('mon.create_time ', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->order_by('mon.create_time desc')
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('pembuatan_pakan');
        return $this->db->count_all_results();
    }


    public function insert($pakan_id, $jumlah_pakan, $keterangan, $create_uid){
        $data = array(
            'pakan_id' => $pakan_id,
            'jumlah_pakan' => $jumlah_pakan,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()

        );
        $query = $this->db->insert('pembuatan_pakan', $data);
        return $this->db->insert_id();
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

    public function get($id){
        $query = $this->db->select('p.id, p.pakan_id, pakan.name as pakan_name, round(p.jumlah_pakan,3) as jumlah_pakan, p.keterangan, kar.name as create_user, karw.name as write_user, p.create_time, p.write_time')
            ->from('pembuatan_pakan p')
            ->join('pakan pakan', 'p.pakan_id = pakan.id', 'left')
            ->join('karyawan kar', 'kar.id = p.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = p.write_uid', 'left')
            ->where('p.id', $id)
            ->where('p.deleted',0)
            ->get();
        return $query->result();
    }

    public function update($pakan_id, $jumlah_pakan, $keterangan, $id, $write_uid){
        $data = array(
            'pakan_id' => $pakan_id,
            'jumlah_pakan' => $jumlah_pakan,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pembuatan_pakan', $data);
    }

    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('pembuatan_pakan', $data);
    }


    public function get_report($from, $to)
    {
        $query = $this->db->select('p.id, p.pakan_id, pakan.name as pakan_name, round(p.jumlah_pakan,2) as jumlah_pakan, p.keterangan, kar.name as create_user, karw.name as write_user, p.create_time, p.write_time')
            ->from('pembuatan_pakan p')
            ->join('pakan pakan', 'p.pakan_id = pakan.id', 'left')
            ->join('karyawan kar', 'kar.id = p.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = p.write_uid', 'left')
            ->where('CAST(p.write_time As DATETIME) >=', $from)
            ->where('CAST(p.write_time As DATETIME) <=', $to)
            ->where('p.deleted',0)
            ->get();
        return $query->result_array();
    }
}