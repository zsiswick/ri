<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require 'Tank_auth.php';


/**
 * Extends the Tank Auth library with support to add user email to session
 *
 * @author Zac.Siswick
 */
class Tank_auth_my extends Tank_auth {
    
    function __construct()
    {
			//Run parent constructor to setup everything normally
			parent::__construct();
			
			$this->ci->load->model('client_model');
		
		}
    /**
     * Login user on the site. Return TRUE if login is successful
     * (user exists and activated, password is correct), otherwise FALSE.
     *
     * @param	string	(username or email or both depending on settings in config file)
     * @param	string
     * @param	bool
     * @return	bool
     */
    function login($login, $password, $remember, $login_by_username, $login_by_email)
    {
    		// Which function to use to login (based on config)
    		if ($login_by_username AND $login_by_email) {
    			$get_user_func = 'get_user_by_login';
    		} else if ($login_by_username) {
    			$get_user_func = 'get_user_by_username';
    		} else {
    			$get_user_func = 'get_user_by_email';
    		}
    		$loggedIn = parent::login($login, $password, $remember, $login_by_username, $login_by_email);
    	
    			if($loggedIn) 
    			{
    				$user = $this->ci->users->$get_user_func($login);
    				$this->ci->session->set_userdata(array(
    						'email'	=> $user->email
    				));
    			}
    			return $loggedIn;
    }
    
    /**
    	 * Create new user on the site and return some data about it:
    	 * user_id, username, password, email, new_email_key (if any).
    	 *
    	 * @param	string
    	 * @param	string
    	 * @param	string
    	 * @param	bool
    	 * @return	array
    	 */
    	function create_user($username, $email, $password, $email_activation, $company)
    	{
    		if ((strlen($username) > 0) AND !$this->ci->users->is_username_available($username)) {
    			$this->error = array('username' => 'auth_username_in_use');
    
    		} elseif (!$this->ci->users->is_email_available($email)) {
    			$this->error = array('email' => 'auth_email_in_use');
    
    		} else {
    			// Hash password using phpass
    			$hasher = new PasswordHash(
    					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
    					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
    			$hashed_password = $hasher->HashPassword($password);
    
    			$data = array(
    				'username'	=> $username,
    				'password'	=> $hashed_password,
    				'email'		=> $email,
    				'last_ip'	=> $this->ci->input->ip_address(),
    			);
    
    			if ($email_activation) {
    				$data['new_email_key'] = md5(rand().microtime());
    			}
    			if (!is_null($res = $this->ci->users->create_user($data, !$email_activation))) {
    			
    				$data['user_id'] = $res['user_id'];
    				$data['password'] = $password;
    				
    				$this->ci->users->update_profile_info($data['email'], $company, $data['user_id']);
    				
    				$this->ci->client_model->set_sample_client($data['user_id']);
    				
    				unset($data['last_ip']);
    				return $data;
    			}
    		}
    		return NULL;
    	}
    
    function get_email()
    {
    	return $this->ci->session->userdata('email');
    }
}
