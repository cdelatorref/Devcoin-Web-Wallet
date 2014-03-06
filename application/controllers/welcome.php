<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('auth'); //Custom Auth class
		$this->load->library('recaptcha');
		


		//Information sent to view
        $data['recaptcha_html'] = $this->recaptcha->recaptcha_get_html();
		$data['errors'] = "";
		$data['success'] = "";
		$data['emailLogin'] = "";
		$data['email'] = "";
		$data['email2'] = "";
		$data['errorsLogin']="";
		
		if (isset($_POST['join']))
		{
			$this->recaptcha->recaptcha_check_answer();
			
			$auth = new auth;
			
			$auth->email = $_POST['email'];
			$auth->email2 = $_POST['email2'];
			$auth->password = $_POST['password'];
			$auth->password2 = $_POST['password2'];
			$auth->captcha = $this->recaptcha->getIsValid();
			$auth->tos = isset($_POST['tos']);
			
			$data['email'] = $_POST['email'];
			$data['email2'] = $_POST['email2'];

			if($auth->validateInputs()==TRUE)
			{
				$auth->registerAccount();
				$data['success']="Congratulations! you registered a new account, an email was sent with instructions to complete the account registration";
			}
			else
			{
				$data['errors']=$auth->errors;
			}
		}

		if (isset($_POST['login']))
		{
			$auth = new auth;
			$auth->email = $_POST['emailLogin'];
			$auth->password = $_POST['passwordLogin'];
			$auth->google2auth = $_POST['authCode'];
			
			if($auth->login()==FALSE)
			{
				$data['errorsLogin']=$auth->errors;
			}
			else
			{
				  redirect('/home', 'refresh');
			}
			
			$data['emailLogin'] = $_POST['emailLogin'];
			

		}
		
		$this->load->view('welcome_message',$data);

	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>