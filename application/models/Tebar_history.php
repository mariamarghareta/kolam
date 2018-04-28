<?php
class Tebar_history extends CI_Model
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

    }


    public function get_count_all()
    {

    }


    public function get_sequence($tebar_id){
        $query = $this->db->select('count(*) as jumlah')
            ->from('tebar_history')
            ->where('tebar_id', $tebar_id)
            ->get();
        $result = $query->result();

        return $result[0]->jumlah + 1;
    }


    public function insert($tebar_id, $sampling_id, $grading_id, $keterangan, $asal_kolam, $tujuan_kolam, $create_uid){
        $data = array(
            'tebar_id' => $tebar_id,
            'dt' => $this->get_now(),
            'sampling_id' => $sampling_id,
            'grading_id' => $grading_id,
            'keterangan' => $keterangan,
            'asal_kolam_id' => $asal_kolam,
            'tujuan_kolam_id' => $tujuan_kolam,
            'create_uid' => $create_uid,
            'create_time' => $this->get_now(),
            'sequence' => $this->get_sequence($tebar_id),
            'write_uid' => $create_uid,
            'write_time' => $this->get_now()
        );
        $query = $this->db->insert('tebar_history', $data);
        return $this->db->insert_id();
    }


    public function get_by_sampling($id){
        $query = $this->db->select('id, tebar_id, sequence, sampling_id, grading_id, keterangan, asal_kolam_id, tujuan_kolam_id')
            ->from('tebar_history')
            ->where('sampling_id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function get_total_ikan($kolam_id){
        $query = $this->db->select('sampling_id, grading_id')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('tujuan_kolam_id', $kolam_id)
            ->group_start()
            ->group_start()
            ->where('sampling_id !=', 0)
            ->where('sequence', 1)
            ->group_end()
//            ->or_where('grading_id !=', 0)
            ->group_end()
            ->order_by('sequence desc')
            ->limit(1)
            ->get();
        $id = $query->result_array();
        if($id[0]["sampling_id"] != 0){
            $query = $this->db->select('total_ikan, biomass, total_pakan')
                ->where('sampling_id', $id[0]["sampling_id"])
                ->from('pemberian_pakan')
                ->get();
            return ($query->result_array());
        } else if($id[0]["grading_id"] != 0){
            $query = $this->db->select('total_ikan, biomass, total_pakan')
                ->where('grading_id', $id[0]["grading_id"])
                ->from('pemberian_pakan')
                ->get();
            return ($query->result_array());
        }
    }


    public function get_history_by_sampling($kolam_id, $seq){
        $query = $this->db->select('sampling_id, grading_id')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('tujuan_kolam_id', $kolam_id)
            ->where('sequence <=', $seq)
            ->group_start()
            ->group_start()
            ->where('sampling_id !=', 0)
            ->where('sequence', 1)
            ->group_end()
//            ->or_where('grading_id !=', 0)
            ->group_end()
            ->order_by('sequence desc')
            ->limit(1)
            ->get();
        $id = $query->result_array();
        if($id[0]["sampling_id"] != 0){
            $query = $this->db->select('total_ikan, biomass, total_pakan')
                ->where('sampling_id', $id[0]["sampling_id"])
                ->from('pemberian_pakan')
                ->get();
            return ($query->result_array());
        } else if($id[0]["grading_id"] != 0){
            $query = $this->db->select('total_ikan, biomass, total_pakan')
                ->where('grading_id', $id[0]["grading_id"])
                ->from('pemberian_pakan')
                ->get();
            return ($query->result_array());
        }
    }


    public function update_by_tebar($tujuan_kolam, $tebar_id, $id, $write_uid){
        $data = array(
            'tujuan_kolam_id' => $tujuan_kolam,
            'tebar_id' => $tebar_id,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tebar_history', $data);
    }


    public function delete($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('id', $id);
        return $this->db->update('tebar_history', $data);
    }
}