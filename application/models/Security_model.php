<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Security_model extends CI_Model
{
	private $table = "logins";
	
	
	
	public function okay($login,$psw){
		
		return(!empty($this->db->query("SELECT * FROM logins WHERE login=? AND psw=?",array($login,md5($psw)))->result()));
	}
}

/*


*/