<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('incidentsmodel', 'Incidents', TRUE);
        $this->load->model('jobsmodel', 'Jobs', TRUE);
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
        echo json_encode(array('status' => 'error')); 
        die;
	}

    public function set() {
        if (count($_POST)==0) {
            echo json_encode(array('status' => 'error')); 
            die; 
        } else {
            // add it
            //$res = $this->Incidents->add_incident();
            $status = ($res) ? 'ok' : 'error';
            // update job progress
            echo json_encode(array('status' => $status));
            die;
        }
    }

    public function get() {
        echo json_encode(array('status' => 'error')); 
        die;
    }

    public function updatejob() {
        error_log('hitting update API');
        if ($this->Jobs->update()) {
            $status = 'ok';
        } else {
            $status = 'error';
        }
        echo json_encode(array('status' => $status));
        die;
    }
    public function getjob() {

    }
}
