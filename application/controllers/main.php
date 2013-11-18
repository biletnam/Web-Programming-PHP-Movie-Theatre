<?php

class Main extends CI_Controller {
	public $title,$theatre,$date,$time,$seatNo;
	public $id;
    function __construct() {
    	// Call the Controller constructor
    	parent::__construct();
    	$this->session->set_userdata('tickets', array());
    	
    }
        
    function index() {
			$data['main']='main/index';				
	    	//load the dates model
	    	$this->load->model('cinemadata_model');
	    	//grab all the dates available
	    	$dates = $this->cinemadata_model->get_dates();
	    	
	    	$dropcontent[]='Please Select a Date';
	    	if ($dates->num_rows() > 0){
	    		foreach ($dates->result() as $row){
	    			$dropcontent[]=$row->date;
	    		}
	    	}
	    	$data['dates']=array_fill_keys ( $dropcontent ,'' );
	    	foreach ($dropcontent as $e){
	    		$data['dates'][$e]=$e;
	    	}
	    	$this->load->view('template', $data);
	    	}
    
    function getVenue() {
    	$this->load->model('cinemadata_model');
    	$venues =$this->cinemadata_model->get_venues($this->input->post('dateDrop'));
    	$dropcontent[]='Please Select a Venue:';
    	if ($venues->num_rows() > 0){
    		foreach ($venues->result() as $row){
    			$dropcontent[]=$row->name;
    		}
    	}
    	$data['venues']=array_fill_keys ( $dropcontent ,'' );
    	foreach ($dropcontent as $e){
    		$data['venues'][$e]=$e;
    	}
    	$this->getMovies($data, $this->input->post('dateDrop'));
    }
    
    function getMovies($data, $date) {
    	$this->load->model('cinemadata_model');
    	$movies=$this->cinemadata_model->get_movies($date);
    	$dropcontent[]='Please Select a Movie:';
    	if ($movies->num_rows() > 0){
    		foreach ($movies->result() as $row){
    			$dropcontent[]=$row->title;
    		}
    	}
    	$data['movies']=array_fill_keys ( $dropcontent ,'' );
    	foreach ($dropcontent as $e){
    		$data['movies'][$e]=$e;
    	}
    	
    	//Now we are prepared to call the view, passing all the necessary variables inside the $data array
    	$data['main']='main/venuesandmovies';
    	$this->load->view('template', $data);
    }
    
    function getShowtimes() {
    	$this->load->library('table');
    	$this->load->model('cinemadata_model');
    	$venues =$this->input->post('venueDrop');
    	if ($venues!='') {
    		$queryString='select m.title, t.name, t.address, s.date, s.time, s.available
								from movie m, theater t, showtime s
								where m.id = s.movie_id and s.available>0 and t.id=s.theater_id and t.name=\''. $venues. '\'';
    		$showtimes=$this->cinemadata_model->get_showtimes($queryString);
    	}
    	else {
    		$movies =$this->input->post('movieDrop');

    		$queryString='select m.title, t.name, t.address, s.date, s.time, s.available
								from movie m, theater t, showtime s
								where m.id = s.movie_id and t.id=s.theater_id and m.title=\''. $movies. '\'';
    		$showtimes=$this->cinemadata_model->get_showtimes($queryString);
    	} 	
    	
    	//If it returns some results we continue
    	if ($showtimes->num_rows() > 0){
    	
    		//Prepare the array that will contain the data
    		$table = array();
    	
    		$table[] = array('Movie','Theater','Date','Time','Available','Find Seat');
    	
    		foreach ($showtimes->result() as $row){
    			$table[] = array($row->title,$row->name,$row->date,$row->time,$row->available,anchor(
    					'main/process_Seats/' .$row->title . '/'. $row->name . '/' .$row->date . '/'.$row->time,'Select Seats'));
    			
    		}
    		//Next step is to place our created array into a new array variable, one that we are sending to the view.
    		$data['showtimes'] = $table;
    	}
    	
    	//Now we are prepared to call the view, passing all the necessary variables inside the $data array
    	$data['main']='main/showtimes';
    	$this->load->view('template', $data);
    		
    }
    
    function process_Seats ($title,$theatre,$date,$time) {
    	
    	$this->load->model('cinemadata_model');   	
    	$title=rawurldecode ($title);
    	$theatre=rawurldecode ($theatre);
    	$date=rawurldecode ($date);
    	$time=rawurldecode ($time);
    	$queryString='select s.id
					from movie m, theater t, showtime s
					where m.id = s.movie_id and t.id=s.theater_id 
    				and  m.title=\''. $title. '\' and t.name=\''. $theatre. '\' and s.date=\''. $date. '\' and s.time=\''. $time. '\'';
    	$showtimes=$this->cinemadata_model->get_showtimeID($queryString);
    	$id=$showtimes->row()->id;
    	$this->id=$id;
    	$queryString='select seat from ticket where ticket.showtime_id='.$id;
    	$seats = $this->cinemadata_model->get_reservedSeats($queryString);    	
    	
    	$data['main']='main/seat_selection';
//    	$seats = array();
 //   	$seats[] = 1;
  //  	$seats[] = 2;
    	
    	$seatString='';
    	foreach ($seats->result() as $i){
    		$seatString=$seatString . $i->seat;
    	}
    	$this->$title=$title;
    	$this->$theatre=$theatre;
    	$this->$date=$date;
    	$this->$time=$time;
    	$data['seats'] = $seatString;

    	$this->load->view('template', $data);
    }
    
    function storeSeat($seatNo){
    	$this->seatNo = $seatNo;
    }
    
    function buyform() {
    	$this->load->helper(array('form', 'url'));
    
    	$this->load->library('form_validation');
    
    	$rules['ccnum']	= "required";
    	$rules['ccexp']	= "required";
    	
    	$this->form_validation->set_rules('fname', 'First Name', 'required|min_length[1]');
    	
    	$this->form_validation->set_rules('lname', 'Last Name', 'required|min_length[1]');
    	
    	$this->form_validation->set_rules('ccnum', 'Credit Card Number', 'required|numeric|min_length[16]|max_length[16]');
    
    	$this->form_validation->set_rules('ccexp', 'Credit Card Expiry Date', 'callback_expiryCheck');
    	
    	if ($this->form_validation->run() == FALSE)
    	{
    			$data['main']='main/buytickets';
    			$data['errors'] = validation_errors();
    			$this->load->view('template', $data);
    	}
    	else
    	{
    		$this->load->model('cinemadata_model');
    		$ccexp = str_replace('/', '', $this->input->post('ccexp'));
    		$this->cinemadata_model->newticket(array($this->input->post('fname'),$this->input->post('lname'),
    				$this->input->post('ccnum'),$ccexp,27879,'-1')) ;
    		$this->load->library('table');
    		$data['ticket'] = $this->ticketFormat($this->input->post('fname'),$this->input->post('lname'),'','','','','');
    		$data['main']='main/ccsuccess';
    		$this->load->view('template', $data);
    	}
    }
    
    function expiryCheck($str)
    {
    	$date=explode("/",$str);
    	if (preg_match('/^\d{1,2}\/\d{2}$/', $str)==FALSE) {
    		$this->form_validation->set_message('expiryCheck', 'Expiry Date must be in MM/YY format');
    		return FALSE;
    	}
    	else if (checkdate(intval($date[0]), 1, intval("20" . $date[1]))==FALSE ) {
    		$this->form_validation->set_message('expiryCheck', 'Expiry Date must be comprised of valid dates only');
    		return FALSE;
    	}
    	
    	else 
    	{
    		return TRUE;
    	}
    }
    
    function ticketFormat($fname,$lname,$title,$theatre,$date,$time,$seat) {
    	$table = array();
    	 
    	$table[] = array('First Name','Last Name','Movie','Theater','Date','Time', 'seatNumber');

    	//$table[] = array($this->$title,$theatre,$date,$time);
    	$table[] = array($fname,$lname,'testTitle','testTheatre','testDate','testTime','-1');
    	return $table;
    }
    
    function getTicketArray() {
    	return $this->alltickets;
    }
}
