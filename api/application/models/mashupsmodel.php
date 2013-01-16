<?
class MashupsModel extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function getAllActive() {
        $this->db->where(array('active'=>1));
        $this->db->from('mashups');
        $query = $this->db->get();        
        $data = array();
        foreach ($query->result_array() as $field => $val) {
            $data[$field] = $val;
        }
        return $data;
    }

    function getTitle() {
        if (!$this->input->get('muid')) {
            return false; 
        }
        $this->db->from('mashups');
        $this->db->where(array('id'=>$this->input->get('muid')));
        $result = $this->db->get()->row();
        return $result->title;

    }

}

?>
