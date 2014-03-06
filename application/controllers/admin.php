<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
     {
     	parent::__construct();
     	$this->load->helper('form');
		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('table');
		$this->load->library('pagination');
		$this->load->helper('cookie');
		
		$this->load->model('users');
     		
     }
     
	public function index()
	{
		$this->_showContent();
	}
	public function page($pageNumber=0)
	{
		$this->_showContent($pageNumber);
	}
	private function _showContent($pageNumber=0)
	{
		//Login Check (Uses roles)
		if(($this->session->userdata('loggedIn')!=TRUE)||($this->session->userdata('privileges')!=1))
		{
			redirect('/', 'refresh');
		}
		
		//Information sent to view
		$data['errors'] = "";
		$data['success'] = "";
		$data['id'] = $this->input->cookie('id');
		$data['nickname'] = $this->input->cookie('nickname');
		$data['email'] = $this->input->cookie('email');
		
		//Name Arrays
		$privilegeName[1]="Admin";
		$privilegeName[0]="User";
		$activeName[0]="Inactive";
		$activeName[1]="Active";
		$activeName[2]="Inactivated by Admin";
		
		
		if (isset($_POST['role']))
		{
			$userInfo = $this->users->checkUserById($_POST['id'],0);

			if($userInfo->first_row()->privileges==0)
			{
				$privileges=1;
			}
			else
			{
				$privileges=0;
			}
			$this->users->changePrivileges($_POST['id'],$privileges);
			$data['success']="Privileges changed successfully on user ".$_POST['id'];
		}
		if (isset($_POST['activate']))
		{
			$userInfo = $this->users->checkUserById($_POST['id'],0);
			if(($userInfo->first_row()->active==0)||($userInfo->first_row()->active==2))
			{
				$active=1;
			}
			else
			{
				$active=2;
			}
			$this->users->changeActivate($_POST['id'],$active);
			$data['success']="User ".$_POST['id']." is ".$activeName[$active]." now";
		}
		if ((isset($_POST['applyfilter']))||(isset($_POST['activate']))||(isset($_POST['role'])))
		{
			$data['id'] = $_POST['id'];
			$data['nickname'] = $_POST['nickname'];
			$data['email'] = $_POST['email'];
			//Stores the values of the filters in cookies to avoid a hack in the code igniter pagination code

			$cookie = array(
    		'name'   => 'id',
    		'value'  => $_POST['id'],
    		'expire' => '120',
    		'prefix' => '',
			);
			$this->input->set_cookie($cookie);
			$cookie = array(
    		'name'   => 'nickname',
    		'value'  => $_POST['nickname'],
    		'expire' => '120',
    		'prefix' => '',
			);
			$this->input->set_cookie($cookie);
			$cookie = array(
    		'name'   => 'email',
    		'value'  => $_POST['email'],
    		'expire' => '120',
    		'prefix' => '',
			);
			$this->input->set_cookie($cookie);
			
			//Grid config with Post
			$usersInfo = $this->users->listUsers(
											$pageNumber,
											$_POST['id'],
											$_POST['email'], 
											$_POST['nickname']);
			//Paginator config with Post
			$config['total_rows'] = $this->users->countUsers(
														$_POST['id'],
														$_POST['email'], 
														$_POST['nickname']);
		}
		else
		{
			//Grid config with cookies
			$usersInfo = $this->users->listUsers(
											$pageNumber,
											$this->input->cookie('id'),
											$this->input->cookie('email'), 
											$this->input->cookie('nickname'));
			//Paginator config with cookies
			$config['total_rows'] = $this->users->countUsers(
														$this->input->cookie('id'),
														$this->input->cookie('email'), 
														$this->input->cookie('nickname'));
		}
		
		$this->table->set_heading('ID', 'E-mail', 'Active', 'Nickname', 'Privileges', 'Actions');
		foreach ($usersInfo->result() as $row)
		{
			$actions=form_open("/admin/page/".$pageNumber);
			$actions.=form_hidden('id', $row->id_user);
			$actions.=form_hidden('nickname', $data['nickname']);
			$actions.=form_hidden('email', $data['email']);
			$actions.=form_submit('activate', 'Activate/Deactivate');
			$actions.=form_submit('role', 'Change privileges');
			$actions.=form_close();
			
    		$this->table->add_row($row->id_user, $row->mail, $activeName[$row->active], $row->nickname, $privilegeName[$row->privileges], $actions);
		}	
		$table = $this->table->generate();
		
		$config['base_url'] = base_url("index.php/admin/page/");
		
		$config['per_page'] = 10; 
		$this->pagination->initialize($config); 
		$pagination = $this->pagination->create_links();
		
		$data['userstable'] = $table.$pagination;

		$masterData['content']=$this->load->view('admin',$data, TRUE);
		$this->load->view('master',$masterData);
	}
}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
?>