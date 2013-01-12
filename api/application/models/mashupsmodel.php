<?
class MashupsModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function getAllActive() {
        $this->db->from('mashups'); 
        $this->db->where(array('active'=>1));
        $query = $this->db->get();        
        $data = array();
        foreach ($query->result_array() as $field => $val) {
            $data[$field] = $val;
        }
        return $data;
    }

}

?>
