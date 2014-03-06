<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public function index()
	{	
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->library('session');
		$this->load->helper('url');
		
		//Login Check
		if($this->session->userdata('loggedIn')!=TRUE)
		{
			redirect('/', 'refresh');
		}
		
				
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		
		
		$masterData['content']=$this->load->view('home',$data, TRUE);
		$this->load->view('master',$masterData);
		
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
?>