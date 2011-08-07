<?php
class Hector_ext
{
	var $name = "Hector";
	var $version = "2.0.0";
	var $description = "Hector-compatible login credentials";
	var $docs_url = "http://github.com/raws/hector-identity-ee";
	var $settings_exist = "n";
	var $settings = array();
	
	function __construct($settings="")
	{
		$this->EE =& get_instance();
		$this->settings = $settings;
	}
	
	function activate_extension()
	{
		$this->activate_database();
		$this->activate_hook("member_member_register");
		$this->activate_hook("cp_members_member_create");
	}
	
	function disable_extension()
	{
		$this->EE->db->where("class", __CLASS__);
		$this->EE->db->delete("extensions");
		
		$this->EE->load->dbforge();
		$this->EE->dbforge->drop_column("members", "hector_username");
	}
	
	function on_member_member_register($member, $member_id)
	{
		$this->set_hector_username_for_member($member_id, $member["username"]);
	}
	
	function on_cp_members_member_create($member_id, $member)
	{
		$this->set_hector_username_for_member($member_id, $member["username"]);
	}
	
	private function set_hector_username_for_member($member_id, $username)
	{
		$this->EE->db->where("member_id", $member_id);
		$this->EE->db->update("members", array(
			"hector_username" => $this->hector_username($username)
		));
	}
	
	private function hector_username($username)
	{
		return substr(preg_replace('/\W/', "", preg_replace('/\s/', "_", strtolower($username))), 0, 16);
	}
	
	private function activate_database()
	{
		$this->EE->load->dbforge();
		$this->EE->dbforge->add_column("members", array(
			"hector_username" => array("type" => "VARCHAR", "constraint" => "16")
		));
		
		$this->EE->db->select("member_id, username");
		$query = $this->EE->db->get("members");
		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $member)
			{
				$this->EE->db->where("member_id", $member->member_id);
				$this->EE->db->update("members", array(
					"hector_username" => $this->hector_username($member->username)
				));
			}
		}
	}
	
	private function activate_hook($hook)
	{
		$this->EE->db->insert("extensions", array(
			"class" => __CLASS__,
			"hook" => $hook,
			"method" => "on_$hook",
			"settings" => serialize($this->settings),
			"priority" => 10,
			"version" => $this->version,
			"enabled" => "y"
		));
	}
}
?>
