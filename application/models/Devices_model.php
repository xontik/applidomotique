<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Devices_model extends CI_Model
{
	private $table = "devices";
	
	public function get_devices_by_room($room = "")
	{
		if($room == ""){
			return $this->db->query("SELECT d.id id, d.name name, r.name room, d.state state, i.path path_ico, i.name alt_ico, d.description description FROM devices d INNER JOIN rooms r ON d.room = r.id INNER JOIN icons i ON d.icon = i.id  ORDER BY id" )->result();
		}else{
		return $this->db->query("SELECT d.id id, d.name name, r.name room, d.state state, i.path path_ico, i.name alt_ico, d.description description FROM devices d INNER JOIN rooms r ON d.room = r.id INNER JOIN icons i ON d.icon = i.id WHERE r.name = ? ORDER BY id",array($room) )->result();
		}

	}
	public function get_devices_by_state($state)
	{

		return $this->db->query("SELECT d.id id, d.name name, r.name room, d.state state, i.path path_ico, i.name alt_ico, d.description description FROM devices d INNER JOIN rooms r ON d.room = r.id  INNER JOIN icons i ON d.icon = i.id WHERE d.state = ? ORDER BY id ",array($state) )->result();

	}
	public function get_rooms()
	{

		return $this->db->query("SELECT name FROM rooms ORDER BY name")->result();

	}
	public function set_state($id,$state){
		$this->db->query("UPDATE devices SET state=? WHERE id=?",array($state,$id));
		if($id<10){
			$id = "0".$id;
		}
		if($state == "on"){
			exec("sudo python /var/www/html/script/do.py ".$id." 1");
			
			
		}
		else{	
			exec("sudo python /var/www/html/script/do.py ".$id." 0");
		}
		return true;
	}
	public function set_all_state($state,$room = ""){
		if($room==""){
			$this->db->query("UPDATE devices SET state=? ",array($state));
			if($state == "on"){
				exec("sudo python /var/www/html/script/doall.py 1 > /dev/null &");
			}
			else{	
				exec("sudo python /var/www/html/script/doall.py 0 > /dev/null &");
			}
			
		}else{
			$this->db->query("UPDATE devices d INNER JOIN rooms r ON r.id=d.room SET state=? WHERE r.name=?",array($state,$room));

			if($state == "on"){
				exec("sudo python /var/www/html/script/doroom.py ".$room." 1 > /dev/null &");
			}
			else{	
				exec("sudo python /var/www/html/script/doroom.py ".$room." 0 > /dev/null &");
			}
		}
		
	}
}

/*


*/