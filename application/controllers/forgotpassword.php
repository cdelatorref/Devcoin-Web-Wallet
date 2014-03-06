<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgotpassword extends CI_Controller {
	
	public function index()
	{	
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('auth'); //Custom Auth class
		
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		
		$data['email'] = "";
		
		
		
		if (isset($_POST['resetpassword']))
		{
			$auth = new auth;
			
			$auth->email = $_POST['email'];
			$auth->google2auth = $_POST['authCode'];
			if($auth->passwordResetRequest()==TRUE)
			{
				$data['success'] = "An e-mail was sent with detailed instructions.";
			}
			else
			{
				$data['errors'] = $auth->errors;
				
			}
		}

		$this->load->view('forgotpassword/forgotpassword',$data);
	}
	public function reset($resetcode)
	{
		
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('auth'); //Custom Auth class
		
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		
		$data['resetcode'] = $resetcode;
		
	
		if (isset($_POST['resetpassword']))
		{
			$auth = new auth;
			
			$auth->activationCode = $resetcode;
			$auth->password = $_POST['password'];
			$auth->password2 = $_POST['password2'];
			
			if($auth->passwordReset()==TRUE)
			{
				$data['success']="The password was reset successfully!";
			}
			else
			{
				$data['errors']=$auth->errors;
			}
		}
		
		
		$this->load->view('forgotpassword/forgotpasswordchange',$data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/forgotpassword.php */
?>