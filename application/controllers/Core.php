<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends CI_Controller {
	private $data = array();
	public function __construct(){
		parent::__construct();
		
		if(strstr($_SERVER["REMOTE_ADDR"],"192.168")){
			$this->session->set_userdata("secured",true);
		}
		$this->session->set_userdata("connecting",false);
		$this->data = array('title' => "DOMOTIKTAMERE" );
		$this->load->model("devices_model","dm");
		$this->data["csss"] = array("base");
		$this->data["jss"] = array("jquery");
		$this->data["rooms"] = $this->dm->get_rooms();
	

	}
	private function _load($view, $data){
		$this->load->view("includes/head",$data);
		$this->load->view("includes/menu",$data);
		$this->load->view('core/'.$view, $data);
		$this->load->view("includes/foot",$data);
	}
	public function index()
	{
		if(!$this->session->userdata("secured")){
			echo $this->load->view('core/connect','',true);
			exit();

		}
		$data = $this->data;
		$data["devices"] = $this->dm->get_devices_by_room();
		$data["room"] = "";
		array_push($data["jss"], "onoff");
		$this->_load("room",$data);

	}
	public function connect(){
		
		$this->load->model("security_model","sec");
		
		$login = $this->input->post("login");
		$psw = $this->input->post("psw");
		
		$this->session->set_userdata("secured",false);
		$this->session->set_userdata("connecting",false);
		if(!empty($login) && !empty($psw)){
			
			if($this->sec->okay($login,$psw)){
				$this->session->set_userdata("secured",true);
			}
			else{
				$this->session->set_userdata("secured","Erreur !");
			}
		}
		$this->index();
	}
	public function missing(){
		echo "NOOB ! file de la !";
	}
	public function ajax_id(){
		$id = $this->input->post("device_id");
		$state = $this->input->post("state");
		header("Access-Control-Allow-Origin: *"); 
		if($state=="off"||$state=="on"){
			$this->dm->set_state($id,$state);
			echo json_encode(array('success' => true));
		}else{
			echo json_encode(array('success' => false));
		}
	}
	public function ajax_id2($id = "", $state = ""){
		header("Access-Control-Allow-Origin: *"); 
		$this->dm->set_state($id,$state);
	}
	public function ajax_new(){
		$room = $this->input->post("room");
		$states_js = $this->input->post("states");
		$devices = $this->dm->get_devices_by_room($room);
		$new_states = array();
		foreach ($devices as $device) {
			if($states_js[$device->id]!=$device->state){
				$new_states[$device->id] = $device->state;
			}
		}

		header("Access-Control-Allow-Origin: *"); 
		if(empty($new_states)){
			echo json_encode(array('change' => false));
		}else{
			echo json_encode(array('change' => true, 'states' => $new_states));
		}
	}
	public function ajax_room(){
		$room = $this->input->post("room");
		$state = $this->input->post("state");
		header("Access-Control-Allow-Origin: *"); 
		if($state=="off"||$state=="on"){
			
			echo json_encode(array('success' => true));
			$this->dm->set_all_state($state,$room);
		}else{
			echo json_encode(array('success' => false));
		}
	}
	public function room($room = ""){
		if(!$this->session->userdata("secured")){
			echo $this->load->view('core/connect','',true);
			exit();

		}
		$room = urldecode($room);
		
		
		$data = $this->data;

		$data['title'] = "DOMOTIKTAMERE - ".ucFirst($room);
		$data["devices"] = $this->dm->get_devices_by_room($room);
		$data["room"] = $room;
		array_push($data["jss"], "onoff");



		$this->_load("room", $data);
	}
}

