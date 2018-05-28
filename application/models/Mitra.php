<?php
class Mitra extends CI_Model
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
        $query = $this->db->select('id, name, phone1, phone2, tipe_mitra, alamat, keterangan')
            ->from('mitra_bisnis')
            ->where('deleted', 0)
            ->group_start()
            ->like('name', $searchword)
            ->or_like('phone1', $searchword)
            ->or_like('phone2', $searchword)
            ->or_like('tipe_mitra', $searchword)
            ->or_like('alamat', $searchword)
            ->or_like('keterangan', $searchword)
            ->group_end()
            ->limit($data_count, ($offset-1) * $data_count)
            ->get();
        return $query->result_array();
    }


    public function show_all_data()
    {
        $query = $this->db->select('id, name, phone1, phone2, tipe_mitra, alamat, keterangan')
            ->from('mitra_bisnis')
            ->where('deleted', 0)
            ->get();
        return $query->result_array();
    }


    public function get_count_all()
    {
        $this->db->like('deleted', 0);
        $this->db->from('mitra_bisnis');
        return $this->db->count_all_results();
    }


    public function cek_kembar($name, $phone1, $alamat, $id){
        if ($name == ""){
            return false;
        }
        $this->db->from('mitra_bisnis')
            ->group_start()
            ->where('name', strtoupper($name))
            ->or_where('phone1', $phone1)
            ->or_where('alamat', $alamat)
            ->group_end()
            ->where('deleted', 0)
            ->where('id !=', $id);
        $query = $this->db->count_all_results();
        if($query >= 1){
            return false;
        } else {
            return true;
        }
    }


    public function insert($name, $phone1, $phone2, $tipe_mitra, $alamat, $keterangan, $create_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'phone1' => $phone1,
            'phone2' => $phone2,
            'tipe_mitra' => $tipe_mitra,
            'alamat' => $alamat,
            'keterangan' => $keterangan,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('mitra_bisnis', $data);
        return $query;
    }


    public function get($id){
        $query = $this->db->select('id, name, phone1, phone2, tipe_mitra, alamat, keterangan')
            ->from('mitra_bisnis')
            ->where('id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function update($name, $phone1, $phone2, $tipe_mitra, $alamat, $keterangan, $id, $write_uid){
        $name = strtoupper($name);
        $data = array(
            'name' => $name,
            'phone1' => $phone1,
            'phone2' => $phone2,
            'tipe_mitra' => $tipe_mitra,
            'alamat' => $alamat,
            'keterangan' => $keterangan,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('mitra_bisnis', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('mitra_bisnis', $data);
    }
}