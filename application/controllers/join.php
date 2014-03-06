<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Join extends CI_Controller {
	
	public function activation($activationCode)
	{	
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('auth'); //Custom Auth class
		
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		
		$auth = new auth;
		$auth->activationCode=$activationCode;
		
		if($auth->activateAccount()==FALSE)
		{
			$data['errors'] = $auth->errors;
		}
		else
		{
			$data['success'] = "Congratulations! your account is now active, now you can login in your account.";
		}
		
		$this->load->view('join',$data);
		
	}
}

/* End of file join.php */
/* Location: ./application/controllers/join.php */
?>