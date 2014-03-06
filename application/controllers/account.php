<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {

	public function index()
	{
		$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('auth'); //Custom Auth class
		$this->load->library('session');
		
		$this->load->model('users');
			
		//Login Check
		if($this->session->userdata('loggedIn')!=TRUE)
		{
			redirect('/', 'refresh');
		}
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		$data['errorsBasicInfo'] = "";
		$data['successBasicInfo'] = "";
		$data['successGoogle2fa'] = "";
		$data['errorsGoogle2fa'] = "";		
		$data['nickname'] = "";
		$data['personalmessage'] = "";
				
		$auth = new auth;
		$data['qrcode'] = $auth->showQR();
	
		if (isset($_POST['changepassword']))
		{
			
			$auth->activationCode = $_POST['password3'];
			$auth->password = $_POST['password'];
			$auth->password2 = $_POST['password2'];
			$auth->google2auth = $_POST['authCode'];
			
			if($auth->passwordChange()==TRUE)
			{
				$data['success']="The password was changed successfully!";
			}
			else
			{
				$data['errors']=$auth->errors;
			}
		}
		
		if (isset($_POST['changebasicinfo']))
		{

			$auth->nickname = $_POST['nickname'];
			$auth->personalMessage = $_POST['personalmessage'];
			
			if($auth->basicInfoChange()==TRUE)
			{
				$data['successBasicInfo']="The basic information was registered successfully!";
			}
			else
			{
				$data['errorsBasicInfo']=$auth->errors;
			}
		}
		
		if (isset($_POST['newcode']))
		{
			$auth->google2auth = $_POST['newcode'];
			if($auth->activateTwoAuthCode()==TRUE)
			{
				$data['successGoogle2fa']="The Google 2FA is active now.";
			}
			else
			{
				$data['errorsGoogle2fa']=$auth->errors;
			}
		}

		$basicInfo=$this->users->checkUserById($this->session->userdata('idUser'));	
		$data['nickname'] = $basicInfo->first_row()->nickname;
		$data['personalmessage'] = $basicInfo->first_row()->personalmessage;
			
		$masterData['content']=$this->load->view('account',$data, TRUE);
		$this->load->view('master',$masterData);

	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
?>