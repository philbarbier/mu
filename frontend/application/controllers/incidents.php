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
	public function index() {
        $data = array();
        $text = file_get_contents('http://mu-api.philnic.lan/index.php/getmu');
        $mashups = json_decode($text);
        $data['mashups'] = json_decode($text);
        $this->load->view('mashups', $data);
	}

    public function view() {
        $data = array();
        $text = file_get_contents('http://mu-api.philnic.lan/index.php/getinc?muid='.$this->input->get('muid'));
        $d = json_decode($text);

        $data['incidents'] = $d;
        $data['mashuptitle'] = $d->mashuptitle; 
        $this->load->view('incidents', $data);
    }

    public function add() {
        if (!$_POST) {
            $this->load->view('add_incident');
        } else {
            // add it
            $res = $this->Incidents->add_incident();
            $this->load->view('incidents');
        }
    }
}
