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
        $this->db->limit(50);
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
            error_log('filtering fromdate: ' . $this->input->get('fromdate'));
            $this->db->where(array('actual_timestamp >=' => date('Y-m-d H:i:s', strtotime($this->input->get('fromdate')))));
        }
        if ($this->input->get('todate')) {
            $todate = ($this->input->get('fromdate')!=$this->input->get('todate')) ? $this->input->get('todate') : date('Y-m-d', strtotime($this->input->get('todate') . ' + 1 day'));
            error_log('filtering todate: ' . $todate);
            $this->db->where(array('actual_timestamp <=' => date('Y-m-d H:i:s', strtotime($todate))));
        }
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

    function add_incident() {

        //$sql = "INSERT INTO `incidents` (`epoch_timestamp`, `actual_timestamp`, `latitude`, `longitude`,`mashup_id`, `data`) VALUES ";

        $this->mashup_id = $this->input->post('mashupid');
        $this->epoch_timestamp = $this->input->post('epoch_timestamp');
        $this->actual_timestamp = $this->input->post('actual_timestamp');
        $this->latitude = $this->input->post('latitude');
        $this->longitude = $this->input->post('longitude');
        $this->data = $this->input->post('data');
       
        return $this->db->insert('incidents', $this);

    }

}

