<?php
class admin extends CI_Controller {
	public $title;
	function __construct() {
		// Call the Controller constructor
		parent::__construct();
	}
	
	function index() {
		$data['main']='main/adminpage';
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
		redirect('admin', 'refresh');
	
	}

	function delete()
	{
		$this->load->model('movie_model');
		$this->load->model('theater_model');
		$this->load->model('showtime_model');
		 
		$this->movie_model->delete();
		$this->theater_model->delete();
		$this->showtime_model->delete();
		redirect('admin', 'refresh');
	
	}
	function showTickets()
	{
		$this->load->library('table');
		$this->load->model('cinemadata_model');
		$tickets=$this->cinemadata_model->get_tickets();
		
		if ($tickets->num_rows() > 0){
			//Prepare the array that will contain the data
			$tickettable=array();
			$tickettable[]=array('First Name','Last Name','CC Number','CC Expiry', 'showtime_id','seatNo');
			 
			foreach ($tickets->result() as $row){
				$tickettable[] = array($row->first,$row->last,$row->creditcardnumber,$row->creditcardexpiration,$row->showtime_id,$row->seat);
			}
		}
		if (isset($tickettable)) {
			$data['tickets']=$tickettable;
		}
		$data['main']='main/showtickets';
		$this->load->view('template', $data);
	}
	function delete_tickets() {
		$this->load->model('cinemadata_model');
		$this->cinemadata_model->delete_tickets();
		redirect('admin', 'refresh');
	}
}