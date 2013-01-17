<?
class IncidentsModel extends CI_Model {

    var $mashup_id = 0;
    var $epoch_timestamp = 0;
    var $actual_timestamp = 0;
    var $latitude = '';
    var $longitude = '';
    var $data = '';

    function __construct()
    {
        parent::__construct();
        $this->db->limit(150);
    }

    function all() {
        
        $this->db->from('incidents');
        $this->db->where(array('mashup_id'=>$this->input->get('muid')));
        $this->db->order_by('epoch_timestamp', 'desc');
        $query = $this->db->get();
        $data = array();

        if ($query->num_rows() > 0) {
            foreach($query->result() as $field => $value) {
                $data[$field]= $value;
            }
        }

        return $data;
        
    }

    function filtered() {
        $data = array();

        $this->db->from('incidents');
        
        // use filters, if passed
        if ($this->input->get('fromdate')) {
            $this->db->where(array('actual_timestamp >=' => date('Y-m-d H:i:s', strtotime($this->input->get('fromdate')))));
        }
        if ($this->input->get('todate')) {
            $todate = ($this->input->get('fromdate')!=$this->input->get('todate')) ? $this->input->get('todate') : date('Y-m-d', strtotime($this->input->get('todate') . ' + 1 day'));
            $this->db->where(array('actual_timestamp <=' => date('Y-m-d H:i:s', strtotime($todate))));
        }

        if ($this->input->get('viewflag')) {
            $vd = $this->input->get('viewflag');
            
            $nea = explode(',',$vd['ne']);
            $nea[0] = trim(substr($nea[0],1));
            $nea[1] = trim(substr($nea[1],0,strlen($nea[1])-1));
            
            $swa = explode(',',$vd['sw']);
            $swa[0] = trim(substr($swa[0],1));
            $swa[1] = trim(substr($swa[1],0,strlen($swa[1])-1));

            $this->db->where(array('latitude <='=>$nea[0], 'longitude >='=>$nea[1]));
            $this->db->where(array('latitude >='=>$swa[0], 'longitude <='=>$swa[1]));
        }

        $this->db->where(array('mashup_id'=>$this->input->get('muid')));
        
        $this->db->order_by('epoch_timestamp', 'desc');
        $query = $this->db->get();
        //error_log('Executing: ' . $this->db->last_query());
        $data = array();

        if ($query->num_rows() > 0) {
            foreach($query->result() as $field => $value) {
                $data[$field]= $value;
            }
        }
        return $data;
    }

    function add_incident() {

        $this->mashup_id = $this->input->post('mashupid');
        $this->epoch_timestamp = $this->input->post('epoch_timestamp');
        $this->actual_timestamp = $this->input->post('actual_timestamp');
        $this->latitude = $this->input->post('latitude');
        $this->longitude = $this->input->post('longitude');
        $this->data = $this->input->post('data');
       
        return $this->db->insert('incidents', $this);

    }

}

