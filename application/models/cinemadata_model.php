<?php
class cinemadata_model extends CI_Model {

	function get_dates()
	{
		$query = $this->db->query("select distinct date
								from  showtime ");
		return $query;
	}
	
	function get_venues($date) {
		$query = $this->db->query("select distinct date
								from  showtime ");
		return $query;
	}


}
?>