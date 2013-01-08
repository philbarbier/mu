<?
class JobsModel extends CI_Model {

     

    function __construct()
    {
        parent::__construct();
    }

    public function update() {
        //$res = $this->db->query("select `id` from jobs where `url`='" . $data['url'] . "'"); 
        $res = $this->db->where('url', $this->input->post('url'));
        error_log('res: ' . $res);
    }

}
