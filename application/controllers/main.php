<?php

class Main extends CI_Controller {
	public $title,$theatre,$date,$time;
    function __construct() {
    	// Call the Controller constructor
    	parent::__construct();
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
    

	function showShowtimes()
    {

		//First we load the library and the model
		$this->load->library('table');
		$this->load->model('showtime_model');
		
		//Then we call our model's get_showtimes function
		$showtimes = $this->showtime_model->get_showtimes();

		//If it returns some results we continue
		if ($showtimes->num_rows() > 0){
		
			//Prepare the array that will contain the data
			$table = array();	
	
			$table[] = array('Movie','Theater','Address','Date','Time','Available');
		
		   foreach ($showtimes->result() as $row){
				$table[] = array($row->title,$row->name,$row->address,$row->date,$row->time,$row->available);
		   }
			//Next step is to place our created array into a new array variable, one that we are sending to the view.
			$data['showtimes'] = $table; 		   
		}
		
		//Now we are prepared to call the view, passing all the necessary variables inside the $data array
		$data['main']='main/showtimes';
		$this->load->view('template', $data);
    }
    
    function populate()
    {
	    $this->load->model('movie_model');
	    $this->load->model('theater_model');
	    $this->load->model('showtime_model');
	     
	    $this->movie_model->populate();
	    $this->theater_model->populate();
	    $this->showtime_model->populate();
	     
	    //Then we redirect to the index page again
	    redirect('', 'refresh');
	     
    }
    
    function delete()
    {
	    $this->load->model('movie_model');
	    $this->load->model('theater_model');
	    $this->load->model('showtime_model');
    	
	    $this->movie_model->delete();
	    $this->theater_model->delete();
	    $this->showtime_model->delete();
	     
    	//Then we redirect to the index page again
    	redirect('', 'refresh');
    
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
//     	echo '<script language="javascript">';
//     	echo 'alert("'. $venues.'")';
//     	echo '</script>';
    	if ($venues!='') {
    		$queryString='select m.title, t.name, t.address, s.date, s.time, s.available
								from movie m, theater t, showtime s
								where m.id = s.movie_id and t.id=s.theater_id and t.name=\''. $venues. '\'';
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
    	echo $title . $theatre . $date . $time;
    
    }
    function buyform() {
    	$this->load->helper(array('form', 'url'));
    
    	$this->load->library('form_validation');
    
    	$rules['ccnum']	= "required";
    	$rules['ccexp']	= "required";
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
    		$this->load->library('table');
    		$data['ticket'] = $this->ticketFormat('','','','','');
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
    function ticketFormat($title,$theatre,$date,$time,$seat) {
    	//Prepare the array that will contain the data
    	$table = array();
    	 
    	$table[] = array('Movie','Theater','Date','Time', 'seatNumber');

    	//$table[] = array($this->$title,$theatre,$date,$time);
    	$table[] = array('testTitle','testTheatre','testDate','testTime','-1');
    	return $table;
    }
    
}