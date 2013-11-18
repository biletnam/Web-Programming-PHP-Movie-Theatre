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
	function newticket($ticketinfo) 
	{
		$this->db->query('insert into ticket (first,last,creditcardnumber,creditcardexpiration,showtime_id,seat) values (\''. $ticketinfo[0]. '\',\'' .  $ticketinfo[1] . '\',\'' .  $ticketinfo[2] . '\',\'' .  $ticketinfo[3] . '\',' .  $ticketinfo[4] . ',' .  $ticketinfo[5]. ')');
	}
	function get_showtimeID($queryString) {
		$query = $this->db->query($queryString);
		return $query;
	}
	function get_tickets () {
		$query = $this->db->query('select *
								from  ticket');
		return $query;
	}
	function delete_tickets() {
		$this->db->query("delete from ticket");
	}
}
?>