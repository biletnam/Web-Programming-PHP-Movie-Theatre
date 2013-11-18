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
		$query = $this->db->query('select  m.title, h.name, t.seat, t.first, t.last, t.creditcardnumber,t.creditcardexpiration
								from  ticket t, movie m, showtime s, theater h
								WHERE t.showtime_id=s.id and s.movie_id=m.id and 
								s.theater_id=h.id');
		return $query;
	}
	function delete_tickets() {
		$this->db->query("delete from ticket");
	}
	function get_available($showtime_id) {
		$query = $this->db->query('select available from showtime where showtime.id='. $showtime_id);
		return $query;
	}
	
	function set_available ($pastSeats,$showtime_id) {
		$this->db->query('update showtime set available ='.$pastSeats .' where id='. $showtime_id);
	}
	
	function get_reservedSeats($queryString) {
		$query = $this->db->query($queryString);
		return $query;
	}
}
?>
