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
    }

    function all() {
        /*
        $query = $this->db->query('select * from incidents');
        $data = array();

        $rownum = 0;

        if ($query->num_rows() > 0) {
            foreach($query->result() as $row) {
                $data[$rownum]['id']            = $row->id;
                $data[$rownum]['title']         = $row->incidenttitle;
                $data[$rownum]['location']      = $row->location;
                $data[$rownum]['location_lat']  = $row->location_lat;
                $data[$rownum]['location_long'] = $row->location_long;
                $data[$rownum]['incidentdate']  = $row->incidentdate;

                $rownum++;
            }
        }

        return $data;
        */
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

