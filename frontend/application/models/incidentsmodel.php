<?
class IncidentsModel extends CI_Model {

    var $incidenttitle = '';
    var $location = '';
    var $incidentdate = '';

    function __construct()
    {
        parent::__construct();
    }

    function all() {
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

    }

    function add_incident() {
        
        $this->incidenttitle = $this->input->post('title');
        $this->location = $this->input->post('location');
        $this->incidentdate = $this->input->post('incidentdate');
        return $this->db->insert('incidents', $this);
    
    }

}

