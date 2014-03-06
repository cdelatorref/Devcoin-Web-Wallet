<?
	/* PHP user model (Join + Login)
		Author: cdelatorref
		Version: 1.0
		Link: http://www.devtome.com/doku.php?id=wiki:user:cdelatorref
		DVC tips: 13TRk2LRQuPH926Dq4u1uL63gUvfX5wRN3
		Licence: http://creativecommons.org/licenses/by-sa/3.0/
		NOTE: I'm not responsible of any damage or bugs inside the code, use it at your own risk.
	*/
	class Users extends CI_Model {
 
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    //Check if an email is registered
    function checkMail($email)
    {
		$this->db->select('mail');
		$this->db->where('mail', $email); 
		$query = $this->db->get('user');
		return $query->num_rows();
    }
     //Check if a nickname is registered (Excluding self)
    function checkNickname($nickname, $idUser)
    {
		$this->db->select('nickname');
		$this->db->where('nickname', $nickname); 
		$this->db->where_not_in('id_user', $idUser); 
		$query = $this->db->get('user');
		return $query->num_rows();
    }
    //Check if an activation code is registered
    function checkActivationCode($activationCode, $active=0)
    {
		$this->db->select('activation_code');
		$this->db->where('activation_code', $activationCode); 
		$this->db->where('active', $active); 
		$query = $this->db->get('user');
		return $query->num_rows();
    }
    //Check the main parameters of an user
    function checkUserLogin($mail,$password)
    {
    	
		$this->db->select('id_user');
		$this->db->select('privileges');
		$this->db->select('activegoogle2auth');
		$this->db->where('mail', $mail); 
		$this->db->where('password', md5($password)); 
		$this->db->where('active', 1); 
		return $this->db->get('user');
    }
    //Check the main parameters of an active user using the ID and password
    function checkUserLoginId($idUser,$password)
    {
    	
		$this->db->select('mail');
		$this->db->select('privileges');
		$this->db->where('id_user', $idUser); 
		$this->db->where('password', md5($password)); 
		$this->db->where('active', 1); 
		return $this->db->get('user');
    }
    //Check the parameters of an active user using the ID
    function checkUserById($idUser,$active=1)
    {
    	
		$this->db->select('nickname');
		$this->db->select('personalmessage');
		$this->db->select('google2authcode');
		$this->db->select('activegoogle2auth');
		$this->db->select('active');
		$this->db->select('privileges');
		$this->db->where('id_user', $idUser); 
		if($active==1)
		{
			$this->db->where('active', 1); 
		}
		return $this->db->get('user');
    }
    //Check the parameters of an user using the Mail
    function checkUserByMail($mail)
    {
    	$this->db->select('id_user');
		$this->db->select('nickname');
		$this->db->select('personalmessage');
		$this->db->select('google2authcode');
		$this->db->select('activegoogle2auth');
		$this->db->where('mail', $mail); 
		return $this->db->get('user');
    }
    //Returns the email using the activation code
    function checkMailActivationCode($activationCode)
    {
    	$this->db->select('mail');
		$this->db->where('activation_code', $activationCode); 
		return $this->db->get('user');
    }
    
    //Check the parameters of an active user using the ID
    function listUsers($page,$id,$email,$nickname)
    {
    	$this->db->select('id_user');
    	$this->db->select('mail');
    	$this->db->select('active');
		$this->db->select('nickname');
		$this->db->select('privileges');
		if($id!="")
		{
			$this->db->where('id_user',$id);
		}
		$this->db->like('mail', $email); 
		if($nickname!="")
		{
			$this->db->like('nickname', $nickname); 	
		}
		$this->db->limit(10, $page);
		return $this->db->get('user');
    }
    function countUsers($id,$email,$nickname)
    {
    	if($id!="")
		{
			$this->db->where('id_user',$id);
		}
		$this->db->like('mail', $email); 
		if($nickname!="")
		{
			$this->db->like('nickname', $nickname); 	
		}
    	$this->db->from('user');
    	return $this->db->count_all_results();
    }
    //Activate the user using the activation code
    function activateUser($activationCode)
    {
    	$data = array(
               'active' => 1
            	);
		$this->db->where('activation_code', $activationCode);
		$this->db->update('user', $data); 
    }
    //Update the privileges using the id
    function changePrivileges($id,$privileges)
    {
    	$data = array(
               'privileges' => $privileges
            	);
		$this->db->where('id_user', $id);
		$this->db->update('user', $data); 
    }
    function changeActivate($id,$active)
    {
    	$data = array(
               'active' => $active
            	);
		$this->db->where('id_user', $id);
		$this->db->update('user', $data); 
    }
    //Update the activation code with mail
    function changeActivation($mail,$activationCode)
    {
    	$data = array(
               'activation_code' => $activationCode
            	);
		$this->db->where('mail', $mail);
		$this->db->update('user', $data); 
    }
    //Update the password using the activation code
    function changePasswordActivation($password,$activationCode)
    {
    	$data = array(
               'password' => md5($password)
            	);
		$this->db->where('activation_code', $activationCode);
		$this->db->update('user', $data); 
    }
    //Changes the password using the ID
    function changePasswordID($idUser,$password)
    {
    	$data = array(
               'password' => md5($password)
            	);
		$this->db->where('id_user', $idUser);
		$this->db->update('user', $data); 
    }
     //Changes the basic info using the ID
    function changeBasicInfo($idUser,$nickname,$personalmessage)
    {
    	$data = array(
               'nickname' => $nickname,
               'personalmessage' => $personalmessage
            	);
		$this->db->where('id_user', $idUser);
		$this->db->update('user', $data); 
    }
    //Activates/deactivates the Google 2FA code usage using the ID
    function modifyGoogleTwoAuth($idUser, $action)
    {
    	$data = array(
               'activegoogle2auth' => $action
            	);
		$this->db->where('id_user', $idUser);
		$this->db->update('user', $data); 
    }
    //Insert a new user
    function newUser($email, $password, $active, $activation_code, $privileges, $google2authcode)
    {
		$this->db->set('mail', $email);
		$this->db->set('password', md5($password));
		$this->db->set('active', $active);
		$this->db->set('activation_code', $activation_code);
		$this->db->set('privileges', $privileges);
		$this->db->set('google2authcode', $google2authcode);
		$this->db->set('activegoogle2auth', '0');
		$this->db->insert('user');
    }
}
?>
	