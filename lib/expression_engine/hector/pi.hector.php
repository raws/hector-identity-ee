<?php if (!defined("BASEPATH")) exit("Direct script access not allowed");

$plugin_info = array(
	"pi_name" => "Hector",
	"pi_version" => "2.0.0",
	"pi_author" => "Ross Paffett",
	"pi_author_url" => "http://github.com/raws/hector-identity-ee",
	"pi_description" => "Hector-compatible login credentials",
	"pi_usage" => "Display the logged in user's Hector credentials with {exp:hector:username} and {exp:hector:password}."
);

class Hector
{
	public function __construct()
	{
		$this->EE =& get_instance();
	}
	
	public function username()
	{
		if ($member_id = $this->EE->TMPL->fetch_param("member_id"))
		{
			$this->EE->db->select("hector_username");
			$query = $this->EE->db->get_where("members", array("member_id" => $member_id), 1);
			if ($query->num_rows() > 0)
			{
				return $query->row("hector_username");
			}
		}
		else
		{
			return $this->EE->session->userdata["hector_username"];
		}
		
		return FALSE;
	}
	
	public function password()
	{
		if ($member_id = $this->EE->TMPL->fetch_param("member_id"))
		{
			$this->EE->db->select("hector_password");
			$query = $this->EE->db->get_where("members", array("member_id" => $member_id), 1);
			if ($query->num_rows() > 0)
			{
				return $query->row("hector_password");
			}
		}
		else
		{
			return $this->EE->session->userdata["hector_password"];
		}
		
		return FALSE;
	}
}
?>