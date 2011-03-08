<?php
class Hector_identity_adapter
{
	var $name = "Hector Identity Adapter";
	var $version = "1.0.0";
	var $description = "Hector-compatible login credentials";
	var $docs_url = "http://github.com/raws/hector-identity-ee";
	var $settings_exist = "n";
	var $settings = array();
	
	function Hector_identity_adapter($settings="")
	{
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
		global $DB;
		
		$DB->query("DELETE FROM `exp_extensions` WHERE `class` = 'Hector_identity_adapter'");
		$DB->query("ALTER TABLE `exp_members` DROP `hector_username`");
	}
	
	function on_member_member_register($member)
	{
		global $DB;
		
		$data = array("hector_username" => $this->hector_username($member["username"]));
		$DB->query($DB->update_string("exp_members", $data, "`username` = '".$DB->escape_str($member["username"])."'"));
	}
	
	function on_cp_members_member_create($member_id, $member)
	{
		global $DB;
		
		$data = array("hector_username" => $this->hector_username($member["username"]));
		$DB->query($DB->update_string("exp_members", $data, "`member_id` = '".$DB->escape_str($member_id)."'"));
	}
	
	private function hector_username($username)
	{
		return substr(preg_replace('/\W/', "", preg_replace('/\s/', "_", strtolower($username))), 0, 16);
	}
	
	private function activate_database()
	{
		global $DB;
		
		$DB->query("ALTER TABLE `exp_members` ADD `hector_username` VARCHAR(16) NOT NULL");
		
		$query = $DB->query("SELECT `member_id`, `username` FROM `exp_members`");
		if ($query->num_rows > 0)
		{
			foreach ($query->result as $member)
			{
				$data = array("hector_username" => $this->hector_username($member["username"]));
				$DB->query($DB->update_string("exp_members", $data, "`member_id` = '".$DB->escape_str($member["member_id"])."'"));
			}
		}
	}
	
	private function activate_hook($hook)
	{
		global $DB;
		
		$DB->query($DB->insert_string("exp_extensions", array(
			"extension_id" => "",
			"class" => "Hector_identity_adapter",
			"hook" => $hook,
			"method" => "on_$hook",
			"settings" => "",
			"priority" => 10,
			"version" => $this->version,
			"enabled" => "y"
		)));
	}
}
?>
