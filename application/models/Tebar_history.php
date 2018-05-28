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


    public function show_all($tebar_id)
    {
        $query = $this->db->select('his.id, his.dt, his.sequence, his.keterangan, his.sampling_id, his.grading_id, his.asal_kolam_id, his.tujuan_kolam_id, kar.name as karyawan_name, CONCAT(b.name, " ", k.name) as asal_kolam_name, CONCAT(bt.name, " ", kt.name) as tujuan_kolam_name, t.kode as tebar_kode, his.tebar_id, his.jual_id')
            ->from('tebar_history his')
            ->join('kolam k', 'k.id = his.asal_kolam_id', 'left')
            ->join('blok b', 'b.id = k.blok_id', 'left')
            ->join('kolam kt', 'kt.id = his.tujuan_kolam_id', 'left')
            ->join('blok bt', 'bt.id = kt.blok_id', 'left')
            ->join('karyawan kar', 'kar.id = his.write_uid', 'left')
            ->join('tebar t', 't.id = his.tebar_id', 'left')
            ->where('his.tebar_id', $tebar_id)
            ->get();
        return $query->result();
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


    public function insert($tebar_id, $sampling_id, $grading_id, $keterangan, $asal_kolam, $tujuan_kolam, $create_uid, $jual_id){
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
            'write_time' => $this->get_now(),
            'jual_id' => $jual_id,
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


    public function get_by_jual($id){
        $query = $this->db->select('id, tebar_id, sequence, sampling_id, grading_id, keterangan, asal_kolam_id, tujuan_kolam_id')
            ->from('tebar_history')
            ->where('jual_id', $id)
            ->where('deleted',0)
            ->get();
        return $query->result();
    }


    public function get_total_ikan($kolam_id){
        $query = $this->db->select('his.sampling_id, his.grading_id, s.deleted as sampling_deleted, g.deleted as grading_deleted')
            ->from('tebar_history his')
            ->join('grading g', 'g.id = his.grading_id', 'left')
            ->join('sampling s', 's.id = his.sampling_id', 'left')
            ->where('his.deleted', 0)
            ->where('his.tujuan_kolam_id', $kolam_id)
            ->group_start()
            ->where('his.sampling_id !=', 0)
            ->or_where('his.grading_id !=', 0)
            ->group_end()
            ->order_by('sequence desc')
            ->limit(1)
            ->get();
        $id = $query->result_array();
        if(sizeof($id)>0){
            if($id[0]["sampling_id"] != 0 and $id[0]["sampling_deleted"] == 0){
                $query = $this->db->select('total_ikan, biomass, total_pakan')
                    ->where('sampling_id', $id[0]["sampling_id"])
                    ->where('kolam_id', $kolam_id)
                    ->from('pemberian_pakan')
                    ->get();
                return ($query->result_array());
            } else if($id[0]["grading_id"] != 0 and $id[0]["grading_deleted"] == 0){
                $query = $this->db->select('total_ikan, biomass, total_pakan')
                    ->where('grading_id', $id[0]["grading_id"])
                    ->where('kolam_id', $kolam_id)
                    ->from('pemberian_pakan')
                    ->get();
                return ($query->result_array());
            }
        }
    }


    public function get_history_by_sampling($kolam_id, $seq){
        $query = $this->db->select('his.sampling_id, his.grading_id, s.deleted as sampling_deleted, g.deleted as grading_deleted')
            ->from('tebar_history his')
            ->join('grading g', 'g.id = his.grading_id', 'left')
            ->join('sampling s', 's.id = his.sampling_id', 'left')
            ->where('his.deleted', 0)
            ->where('his.tujuan_kolam_id', $kolam_id)
            ->where('his.sequence <', $seq)
            ->group_start()
            ->where('his.sampling_id !=', 0)
            ->or_where('his.grading_id !=', 0)
            ->group_end()
            ->order_by('sequence desc')
            ->limit(1)
            ->get();
        $id = $query->result_array();
        if(sizeof($id)>0) {
            if($id[0]["sampling_id"] != 0 and $id[0]["sampling_deleted"] == 0){
                $query = $this->db->select('total_ikan, biomass, total_pakan')
                    ->where('sampling_id', $id[0]["sampling_id"])
                    ->where('kolam_id', $kolam_id)
                    ->from('pemberian_pakan')
                    ->get();
                return ($query->result_array());
            } else if($id[0]["grading_id"] != 0 and $id[0]["grading_deleted"] == 0){
                $query = $this->db->select('total_ikan, biomass, total_pakan')
                    ->where('grading_id', $id[0]["grading_id"])
                    ->where('kolam_id', $kolam_id)
                    ->from('pemberian_pakan')
                    ->get();
                return ($query->result_array());
            }
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

    public function delete_by_grading($id, $write_uid){
        $data = array(
            'deleted' => 1,
            'write_uid' => $write_uid,
            'write_time' => $this->get_now()
        );

        $this->db->where('grading_id', $id);
        return $this->db->update('tebar_history', $data);
    }


    public function check_sequence_grading($grading_id){
        $query = $this->db->select('max(tebar_id) as tebar_id, max(sequence) as sequence')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('grading_id', $grading_id)
            ->get();
        $res = $query->result_array();
        $query = $this->db->select('tebar_id, count(id) as jumlah')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('tebar_id', $res[0]["tebar_id"])
            ->where('sequence >', $res[0]["sequence"])
            ->where('keterangan !=', "Delete Grading")
            ->where('keterangan !=', "Delete Sampling")
            ->where('keterangan !=', "Delete Tebar Bibit")
            ->group_by('tebar_id')
            ->get();
        $res = ($query->result_array());
        if(sizeof($res)>0){
            return $res[0]["jumlah"];
        } else {
            return 0;
        }
    }


    public function check_sequence_sampling($sampling_id){
        $query = $this->db->select('max(tebar_id) as tebar_id, max(sequence) as sequence')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('sampling_id', $sampling_id)
            ->get();
        $res = $query->result_array();
        $query = $this->db->select('tebar_id, count(id) as jumlah')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('tebar_id', $res[0]["tebar_id"])
            ->where('sequence >', $res[0]["sequence"])
            ->where('keterangan !=', "Delete Grading")
            ->where('keterangan !=', "Delete Sampling")
            ->where('keterangan !=', "Delete Tebar Bibit")
            ->group_by('tebar_id')
            ->get();
        $res = ($query->result_array());
        if(sizeof($res)>0){
            return $res[0]["jumlah"];
        } else {
            return 0;
        }
    }


    public function check_sequence_tebar($tebar_id){
        $query = $this->db->select('tebar_id, count(id) as jumlah')
            ->from('tebar_history his')
            ->where('deleted', 0)
            ->where('tebar_id', $tebar_id)
            ->where('sequence >', 1)
            ->where('keterangan !=', "Delete Grading")
            ->where('keterangan !=', "Delete Sampling")
            ->where('keterangan !=', "Delete Tebar Bibit")
            ->group_by('tebar_id')
            ->get();
        $res = ($query->result_array());
        if(sizeof($res)>0){
            return $res[0]["jumlah"];
        } else {
            return 0;
        }
    }
}