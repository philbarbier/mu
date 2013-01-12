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

