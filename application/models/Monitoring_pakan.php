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
            ->order_by('SUBSTR(k.name FROM 1 FOR 1)')
            ->order_by('CAST(SUBSTR(k.name FROM 2) AS UNSIGNED)')
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

    public function get_all_monitoring_by_date($dt){
        $q = "select k.id, k.name as kolam_name, b.name as blok_name, tebar.kode, pkn.total_ikan, pkn.id as pemberian_pakan_id,";
        $q .= " (case when sampling.id is not null then sampling.fcr else grading.fcr end) as fcr,";
        $q .= " (case when pagi.id is null then 0 else 1 end) as pakan_pagi,";
        $q .= " (case when sore.id is null then 0 else 1 end) as pakan_sore,";
        $q .= " (case when malam.id is null then 0 else 1 end) as pakan_malam,";
        $q .= " (case when pagi_air.id is null then 0 else 1 end) as air_pagi,";
        $q .= " (case when sore_air.id is null then 0 else 1 end) as air_sore";
        $q .= " FROM(";
        $q .= "   select kolam.id, DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') as waktu";
        $q .= "   from kolam";
        $q .= "   where kolam.tebar_id != 0";
        $q .= " ) kolam";
        $q .= " left join kolam k on k.id = kolam.id";
        $q .= " left join blok b on b.id = k.blok_id";
        $q .= " left join pemberian_pakan pkn on pkn.id = k.pemberian_pakan_id";
        $q .= " left join tebar on tebar.id = k.tebar_id";
        $q .= " left join sampling on pkn.sampling_id = sampling.id";
        $q .= " left join grading on grading.id = pkn.grading_id";
        $q .= " left JOIN(";
        $q .= "   select * from monitoring_pakan where waktu_pakan = 'PAGI' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') and deleted = 0";
        $q .= " ) pagi on pagi.kolam_id = kolam.id";
        $q .= " left JOIN(";
        $q .= "   select * from monitoring_pakan where waktu_pakan = 'SORE' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') and deleted = 0";
        $q .= " ) sore on sore.kolam_id = kolam.id";
        $q .= " left JOIN(";
        $q .= "   select * from monitoring_pakan where waktu_pakan = 'MALAM' and DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') and deleted = 0";
        $q .= " ) malam on malam.kolam_id = kolam.id";
        $q .= " left JOIN (";
        $q .= "  select * from monitoring_air where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') and waktu = 'PAGI' and deleted = 0";
        $q .= " ) pagi_air on pagi_air.kolam_id = kolam.id";
        $q .= " left JOIN (";
        $q .= "  select * from monitoring_air where DATE_FORMAT(create_time, '%Y-%m-%d') = DATE_FORMAT('" . $dt . "' , '%Y-%m-%d') and waktu = 'SORE' and deleted = 0";
        $q .= " ) sore_air on sore_air.kolam_id = kolam.id";
        $q .= " order by SUBSTR(k.name FROM 1 FOR 1), CAST(SUBSTR(k.name FROM 2) AS UNSIGNED)";
        $res = $this->db->query($q);
        return ($res->result_array());
    }
}