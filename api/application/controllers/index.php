<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model('incidentsmodel', 'Incidents', TRUE);
        $this->load->model('jobsmodel', 'Jobs', TRUE);
        $this->load->model('mashupsmodel', 'Mashups', TRUE);
    }

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
            $res = $this->Incidents->add_incident();
            $status = ($res) ? 'ok' : 'error';
            // update job progress
            echo json_encode(array('status' => $status));
            die;
        }
    }

    public function getmu() {
        //echo json_encode(array('status' => 'error'));
        // get all mashups
        $data = $this->Mashups->getAllActive();
        echo json_encode($data);
        die;
    }

    public function getinc() {
        $data = $this->Incidents->all();
        echo json_encode($data);
        die;
    }

    public function updatejob() {
        error_log(json_encode($this->input->post()));
        error_log('hitting update API');
        if ($this->Jobs->update()) {
            $status = 'ok';
        } else {
            $status = 'error';
        }
        echo json_encode(array('status' => $status));
        die;
    }
    public function jobstatus() {
        $status = $this->Jobs->getJobStatus();
        echo json_encode($status);
        die;
    }
}
