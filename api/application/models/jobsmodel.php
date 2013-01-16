<?
class JobsModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    public function update() {
        $this->db->where(array('url'=>$this->input->post('url'),'mashup_id'=>$this->input->post('mashupid')));
        $data = array();
        $data['last_id_processed'] = $this->input->post('lastid');
        $data['done'] = $this->input->post('done');
        $data['total'] = $this->input->post('total');
         
        return $this->db->update('jobs', $data);
    }

    public function getJobStatus() {
        $this->db->where(array('active' => 1, 'url' => $this->input->get('url')));
        $this->db->from('jobs');
        $res = $this->db->get();
        $job = $res->row();
        $data = array();
        if ($res->num_rows()==0) {
            $data['status'] = 'error';
            return $data;
        }

        //existing data from feed
        $feed = json_decode($this->input->get('data'));
        // update job total
        $update = array();
        $update['total'] = $feed->incidentCount;
        $this->db->where(array('id'=>$job->id));
        $this->db->update('jobs',$update);

        $total = ($feed->incidentCount > $job->total) ? $feed->incidentCount : $job->total;
                
        $data['status'] = 'ok';
        $data['job'] = $job;
        $data['perPage'] = $job->perPage;
        error_log('amount left: ' . ($total - $job->done));
        if (is_null($job->last_id_processed)||$job->last_id_processed=='') {
            //start from the start
            $data['page'] = 1;
        } else {
            if ($job->done < $total) {
                $data['page'] = ceil($job->done / $job->perPage) + 1; 
                
                if (($total - $job->done) <= $job->perPage) {
                    //don't process yet
                    $data = array();
                    $data['status'] = 'error';
                }
            }
        }
        return $data;
    }

}
