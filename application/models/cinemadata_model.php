<?php
class cinemadata_model extends CI_Model {

	function get_dates()
	{
		$query = $this->db->query("select distinct date
								from  showtime ");
		return $query;
	}
	
	function get_venues($date) {
		$query = $this->db->query('select distinct name
								from  showtime, theater where theater.id=showtime.theater_id
								and  date=\''. $date. '\'');
		return $query;
	}
	
	function get_movies($date) {
		$query = $this->db->query('select distinct title
								from  showtime, movie where movie.id=showtime.movie_id
								and  date=\''. $date. '\'');
		return $query;
	}
	
	function get_showtimes($queryString)
	{
		$query = $this->db->query($queryString);
		return $query;
	}	

}
?>