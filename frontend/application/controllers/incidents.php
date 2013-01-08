<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incidents extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('IncidentsModel', 'Incidents', TRUE);
    }


	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $data = array();
        $data['incidents'] = $this->Incidents->all(); 
        $this->load->view('incidents', $data);
	}

    public function add()
    {
        if (!$_POST) {
            $this->load->view('add_incident');
        } else {
            // add it
            $res = $this->Incidents->add_incident();
            $this->load->view('incidents');
        }
    }
}
