<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
	/* PHP Auth class (Join + Login + Account information)
		Author: cdelatorref
		Version: 1.0
		Link: http://www.devtome.com/doku.php?id=wiki:user:cdelatorref
		DVC tips: 13TRk2LRQuPH926Dq4u1uL63gUvfX5wRN3
		Licence: http://creativecommons.org/licenses/by-sa/3.0/
		NOTE: I'm not responsible of any damage this code should cause or bugs, use it at your own risk.
	*/
	class auth extends CI_Controller
	{
		//Attributes used to join/login/Account Info
		
        public $email="";
      	public $email2="";
      	public $password="";
      	public $password2="";
      	public $captcha=FALSE;
      	public $google2auth=""; 
      	public $tos=""; 
      	public $errors="";
      	public $activationCode="";
      	public $idUser="";
      	public $privileges="";
      	public $nickname="";
      	public $personalMessage="";

		public function __construct()
     	{
     		parent::__construct();
     		$this->load->model('users');
     		//The Session class does not utilize native PHP sessions. It generates its own session data (CI native)
     		$this->load->library('session');
     		$this->load->library('twofaauth');
     		
     	}

		//After you fill the initial attributes, you must execute this to ensure everything is valid
		//NOTE: This validations can be done with javascript, but it can be disabled.
		public function validateInputs()
		{
			//The errors will be stored on this variable
			$errors="";
			//First layer of validations (Code Igniter can do this with the form validation library too)
			$errors = $this->_notEmptyElements($errors);
			//Second layer of validations (Captcha)
			if($errors=="")
			{
				if($this->captcha==FALSE)
				{
					$errors.="Incorrect captcha <br>";
				}
			}
			//Third layer of validations (E-mail)
			if($errors=="")
			{
				$errors = $this->_checkMail($errors);
			}
			//Fourth layer of validations (Password matching and strenght)
			if($errors=="")
			{
				$errors = $this->_checkPassword($errors);
			}
			$this->errors=$errors;
			

			if($errors=="")
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		
		//This is a public function because in the future should be used to make batch registrations
	   public function registerAccount()
	   {
	   		$activation_code = $this->_generateRandom(25);
	   		$google2auth = $this->twofaauth->createSecret();
	   		$this->users->newUser($this->email, $this->password, 0, $activation_code, 0, $google2auth);
	   		$this->_sendMail($activation_code, "activation");
	   		
	   }
	   //Based on the activation code this function will activate the account
	   public function activateAccount()
	   {
	   		if($this->users->checkActivationCode($this->activationCode)==0)
	   		{
	   			$this->errors="ERROR: The activation code don't exist or the user is already activated, if you believe this is an error, please contact the system administrator";
	   			return FALSE;
	   		}
	   		else
	   		{
	   			$this->users->activateUser($this->activationCode);
	   			return TRUE;
	   		}
	   }
	   //Will register a session if the user can login in the system, otherwise, will show an error.
	   public function login()
	   {
	   		$userAttributes=$this->users->checkUserLogin($this->email,$this->password);
	   		if($userAttributes->num_rows()==0)
	   		{
	   			$this->errors ="ERROR: Your email or password are invalid or maybe your account is not active, if you believe this is an error, please contact the system administrator";
	   			$this->session->sess_destroy();
	   			return FALSE;
	   		}
	   		else
	   		{
	   			$this->idUser = $userAttributes->first_row()->id_user;
	   			$this->privileges = $userAttributes->first_row()->privileges;

				if($userAttributes->first_row()->activegoogle2auth == 1)
				{
					if ($this->_verifyTwoAuthCode($this->idUser)==FALSE)
					{
						$this->errors= "ERROR: The 2FA Auth code is not valid";
						$this->session->sess_destroy();
						return FALSE;
					}
				}
				
	   			$newdata = array(
                   	'idUser'  => $this->idUser,
                   	'privileges'     => $this->privileges,
                   	'loggedIn' => TRUE
               		);

				$this->session->set_userdata($newdata);
				return TRUE;
	   		}
	   }
	   //Will send an e-mail with instructions to reset the password
	   public function passwordResetRequest()
	   {
	   		if($this->users->checkMail($this->email)==0)
			{
				$this->errors = "ERROR: The e-mail you captured is invalid";
				
				return FALSE;
			}
			else
			{
				$userInfo = $this->users->checkUserByMail($this->email);
				$this->idUser = $userInfo->first_row()->id_user;
				if($userInfo->first_row()->activegoogle2auth == 1)
				{
					if ($this->_verifyTwoAuthCode()==FALSE)
					{
						$this->errors= "ERROR: The 2FA Auth code is not valid";
						return FALSE;
					}
				}

				$this->email2 = $this->email;
				$newCode=$this->_generateRandom(25);
				$this->users->changeActivation($this->email,$newCode);
				$this->_sendMail($newCode,"reset");
				
				return TRUE;
			}
	   }
	   //Resets the password with the given code
	   public function passwordReset()
	   {
	   		if($this->users->checkActivationCode($this->activationCode,1)==0)
			{
				$this->errors = "ERROR: Invalid reset code <br>";
			}
			else
			{
				$errors="";
				$this->errors = $this->_checkPassword($errors);
			}
			if ($this->errors=="")
			{
				$this->users->changePasswordActivation($this->password, $this->activationCode);
				$this->email = $this->users->checkMailActivationCode($this->activationCode)->first_row()->mail;
				$this->activationCode = $this->_generateRandom(25);
				$this->users->changeActivation($this->email,$this->activationCode);
				return TRUE;
			}
			else
			{
				return FALSE;
			}
	   }
	   //Changes the password in a standard way
	   public function passwordChange()
	   {
	   		$errors="";
			$this->errors = $this->_checkPassword($errors);
			if ($this->errors=="")
			{
				if($this->users->checkUserLoginId($this->session->userdata('idUser'),$this->activationCode)->num_rows()==0)
				{
					$this->errors= "ERROR: You must insert your actual password correctly.";
				}
			}
			if ($this->errors=="")
			{
				$userInfo = $this->users->checkUserById($this->session->userdata('idUser'));
				if($userInfo->first_row()->activegoogle2auth == 1)
				{
					if ($this->_verifyTwoAuthCode()==FALSE)
					{
						$this->errors= "ERROR: The 2FA Auth code is not valid";
						return FALSE;
					}
				}
				$this->users->changePasswordID($this->session->userdata('idUser'),$this->password2);
				return TRUE;
			}
			else
			{
				return FALSE;
			}
			
	   }
	   //Changes the basic information (Disallow nickname change when is registered)
	   public function basicInfoChange()
	   {
	   		if($this->nickname=="")
	   		{
	   			$this->errors="ERROR: The nickname can't be empty";
	   			return FALSE;
	   		}
	   		if($this->users->checkNickname($this->nickname,$this->session->userdata('idUser'))>0)
	   		{
	   			$this->errors="ERROR: The nickname is already registered";
	   			return FALSE;
	   		}
	   		$userInfo = $this->users->checkUserById($this->session->userdata('idUser'));
	   		//Overwrites the nickname to prevent cheats on XSS attack
	   		if($userInfo->first_row()->nickname!="")
	   		{
	   			$this->nickname=$userInfo->first_row()->nickname;
	   		}
	   		else
	   		{
	   			if(preg_match('/^[a-zA-Z0-9]+$/', $this->nickname)==FALSE)
	   			{
	   				$this->errors="ERROR: The nickname must be alphanumerical";
	   				return FALSE;
	   			}
	   		}
	   		$this->users->changeBasicInfo($this->session->userdata('idUser'),$this->nickname,$this->personalMessage);
	   		return TRUE;

	   }
	   //Returns the html code to show the QR Code if the activation code is not set
	   public function showQR()
	   {
	   		$userInfo = $this->users->checkUserById($this->session->userdata('idUser'));
	   		
	   		if($userInfo->first_row()->activegoogle2auth==0)
	   		{
	   			$imgCode = '<img src="';
	   			$imgCode.= $this->twofaauth->getQRCodeGoogleUrl(base_url(), $userInfo->first_row()->google2authcode);
	   			$imgCode.= '">';
	   			return $imgCode;
	   		}
	   		else
	   		{
	   			return "The code is already activated to be used in this account";
	   		}
	   }
	   //Activates the Google 2FA to be used in the account
	   public function activateTwoAuthCode()
	   {
	   		if($this->_verifyTwoAuthCode()==FALSE)
	   		{
	   			$this->errors="ERROR: Invalid code";
	   			return FALSE;
	   		}
	   		else
	   		{
	   			$this->users->modifyGoogleTwoAuth($this->session->userdata('idUser'), "1");
	   			return TRUE;
	   		}
	   }
	   //Checks if the auth code is a valid one
	   private function _verifyTwoAuthCode()
	   {
	   		if($this->idUser=="")
	   		{
	   			$this->idUser=$this->session->userdata('idUser');
	   		}
	   		$userInfo = $this->users->checkUserById($this->idUser);
	   		return $this->twofaauth->verifyCode($userInfo->first_row()->google2authcode, $this->google2auth, 2);
	   } 
	   //This method will send an E-mail confirmation, almost all attributes are taken from the config file
	   private function _sendMail($activation_code, $type)
	   {
	   		date_default_timezone_set('America/Los_Angeles');
			$this->load->library('email');
			//Load the CI Config file for email
            $this->_ci =& get_instance();
            $this->_ci->load->config('email');
            
			$config['smtp_host'] = $this->_ci->config->item('smtp_host');
			$config['smtp_port'] = $this->_ci->config->item('smtp_port');
			$config['protocol'] = $this->_ci->config->item('protocol');
			$this->email->initialize($config);

			$this->email->from($this->_ci->config->item('fromMailJoin'), $this->_ci->config->item('fromJoin'));
			$this->email->to($this->email2); 
			
			if($type=="activation")
			{
				$this->email->subject($this->_ci->config->item('joinSubject'));
				$mail_body = $this->_ci->config->item('mail_body1');	
				$mail_body .= "http://".$_SERVER['HTTP_HOST']."/index.php/join/activation/".$activation_code;
				$mail_body .= $this->_ci->config->item('mail_body2');	
			}
			if($type=="reset")
			{
				$this->email->subject($this->_ci->config->item('resetSubject'));
				$mail_body = $this->_ci->config->item('reset_body1');	
				$mail_body .= "http://".$_SERVER['HTTP_HOST']."/index.php/forgotpassword/reset/".$activation_code;
				$mail_body .= $this->_ci->config->item('reset_body2');	
			}
			$this->email->message($mail_body);	

			$this->email->send();

 			//TODO log errors if exists
 			//echo $this->email->print_debugger();
	   }
       
       //The elements must have at least something registered
       private function _notEmptyElements($errors)
       {
       		if($this->email=="")
       		{
       			$errors = "The E-mail must not be empty <br>";
       		}
       		if($this->email2=="")
       		{
       			$errors .= "The E-mail confirmation must not be empty <br>";
       		}
			if($this->password=="")
       		{
       			$errors .= "The password must not be empty <br>";
       		}
       		if($this->password2=="")
       		{
       			$errors .= "The password confirmation must not be empty <br>";
       		}
       		if($this->tos=="")
       		{
       			$errors .= "You must agree with the Terms of Service <br>";
       		}
       		return $errors;
       }
       //This function checks the e-mail entered, also checks the database to avoid duplicated emails
		private function _checkMail($errors)
		{
			if($this->email!=$this->email2)
			{
				$errors .= "The e-mails must match <br>";
			}
			if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
			{
				$errors .= "The e-mail you entered is not valid <br>";
			}
			if($errors=="")
			{
				if($this->users->checkMail($this->email)>0)
				{
					$errors .= "The e-mail you captured is already registered <br>";
				}
			}
			return $errors;
		}
		//Checks the Password Strenght and make a matching validation
		private function _checkPassword($errors)
		{
			if ($this->password!=$this->password2)
			{
				$errors .= "The passwords don't match <br>";
			}
   			if ($errors==""&&(!(preg_match('#[0-9]#', $this->password) && preg_match('#[A-Z]#', $this->password) && preg_match('#[a-z]#', $this->password) && (strlen($this->password)>=8))))
   			{
     			$errors.= "Password too weak, It must have at least 1 capital letter, 1 number and at least 8 characters <br>";
   			}
   			return $errors;
		}
		//Generates a random string to be used as activation code
		private function _generateRandom($length)
		{
			$_rand_src = array(
				array(48,57) //digits
				, array(97,122) //lowercase chars
			);
			srand ((double) microtime() * 1000000);
			$random_string = "";
			for($i=0;$i<$length;$i++)
			{
				$i1=rand(0,sizeof($_rand_src)-1);
				$random_string .= chr(rand($_rand_src[$i1][0],$_rand_src[$i1][1]));
			}
			return $random_string;
		}

	}
	
?>