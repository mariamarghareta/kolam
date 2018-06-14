<?php
class Monitoring_sayur extends CI_Model
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
        $query = $this->db->select('id, ph, tds, keterangan, create_time')
            ->from('monitoring_sayur m')
            ->where('m.deleted', 0)
            ->group_start()
            ->like('m.ph ', $searchword)
            ->or_like('m.tds ', $searchword)
            ->or_like('m.keterangan ', $searchword)
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


    public function insert($ph, $tds, $waktu , $keterangan, $create_uid){
        $data = array(
            'ph' => $ph,
            'tds' => $tds,
            'waktu' => $waktu,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $this->db->insert('monitoring_sayur', $data);
        return $this->db->insert_id();
    }


    public function insert_bahan_penolong($monitoring_sayur_id, $obat_id, $jumlah, $create_uid){
        $data = array(
            'monitoring_sayur_id' => $monitoring_sayur_id,
            'obat_id' => $obat_id,
            'jumlah' => $jumlah,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $this->db->insert('treatment_sayur', $data);
        return $this->db->insert_id();
    }

    public function get_treatment($monitoring_sayur_id){
        $query = $this->db->select('id, obat_id')
            ->from('treatment_sayur')
            ->where('deleted', 0)
            ->where('monitoring_sayur_id', $monitoring_sayur_id)
            ->get();
        return $query->result_array();
    }
    public function delete_bahan_penolong($monitoring_sayur_id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );
        $this->db->where('monitoring_sayur_id', $monitoring_sayur_id);
        return $this->db->update('treatment_sayur', $data);
    }


    public function get($id){
        $query = $this->db->select('m.id, m.ph, m.tds, m.waktu, m.keterangan, kar.name as create_user, karw.name as write_user, m.create_time, m.write_time')
            ->from('monitoring_sayur m')
            ->join('karyawan kar', 'kar.id = m.create_uid', 'left')
            ->join('karyawan karw', 'karw.id = m.write_uid', 'left')
            ->where('m.deleted', 0)
            ->where('m.id', $id)
            ->get();
        return $query->result();
    }


    public function get_bahan_penolong($id){
        $query = $this->db->select('bhn.id, bhn.obat_id, bhn.jumlah, obat.name as obat_name, obat.satuan, bhn.monitoring_sayur_id')
            ->from('treatment_sayur bhn')
            ->join('obat', 'obat.id = bhn.obat_id')
            ->where('bhn.deleted', 0)
            ->where('bhn.monitoring_sayur_id', $id)
            ->get();
        return $query->result_array();
    }


    public function update($ph, $tds, $waktu, $keterangan, $id, $write_uid){
        $data = array(
            'ph' => $ph,
            'tds' => $tds,
            'waktu' => $waktu,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_sayur', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('monitoring_sayur', $data);
    }
}