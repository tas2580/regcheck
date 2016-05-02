<?php
/**
*
* @package phpBB Extension - AJAX Registration check
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace tas2580\regcheck\controller;

class main
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\user */
	protected $user;

	/** @var $phpbb_root_path */
	protected $phpbb_root_path;

	/** @var $php_ext */
	protected $php_ext;

	/**
	* Constructor
	*
	* @param \phpbb\config\config			$config				Config object
	* @param \phpbb\request\request			$request			Request object
	* @param \phpbb\user					$user				User object
	* @param string							$phpbb_root_path
	* @param string							$php_ext
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\request\request $request, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->request = $request;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	public function check()
	{
		$this->user->add_lang('ucp');
		$this->user->add_lang_ext('tas2580/regcheck', 'common');

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}

		$data = array(
			'username'			=> utf8_normalize_nfc($this->request->variable('username', '', true)),
			'new_password'		=> $this->request->variable('new_password', '', true),
			'password_confirm'	=> $this->request->variable('password_confirm', '', true),
			'email'				=> $this->request->variable('email', '', true),
		);

		/**
		 * Check username
		 */
		if (!empty($data['username']))
		{
			$error = validate_data($data, array(
				'username'			=> array(
					array('string', false, $this->config['min_name_chars'], $this->config['max_name_chars']),
					array('username', '')),
			));

			$this->return_data($error, 'USERNAME_FREE');
		}

		/**
		 * Check password confirm
		 */
		else if (!empty($data['password_confirm']))
		{
			$error = validate_data($data, array(
				'password_confirm'		=> array(
					array('string', false, $this->config['min_pass_chars'], $this->config['max_pass_chars']),
					array('password')),
			));
			if ($data['new_password'] <> $data['password_confirm'])
			{
				$error = array('NEW_PASSWORD_ERROR');
			}
			$this->return_data($error, 'NEW_PASSWORD_GOOD');
		}

		/**
		 * Check password
		 */
		else if (!empty($data['new_password']))
		{
			$error = validate_data($data, array(
				'new_password'		=> array(
					array('string', false, $this->config['min_pass_chars'], $this->config['max_pass_chars']),
					array('password')),
			));

			$this->return_data($error, 'PASSWORD_GOOD');
		}

		/**
		 * Check email
		 */
		else if (!empty($data['email']))
		{
			$error = validate_data($data, array(
				'email'				=> array(
					array('string', false, 6, 60),
					array('user_email')),
			));

			$this->return_data($error, 'EMAIL_GOOD');
		}
		else
		{
			$this->return_data(array(''), '');
		}
	}

	/**
	 * Return data as JSON
	 *
	 * @param string	$error		Error message
	 * @param string	$message	Success message
	 */
	private function return_data($error, $message)
	{
		$error = $this->set_error($error);
		$return = array();

		if (sizeof($error))
		{
			$return['code'] = 0;
			$return['message'] = implode('<br>', $error);
		}
		else
		{
			$return['code'] = 1;
			$return['message'] = $this->user->lang($message);
		}

		$json_response = new \phpbb\json_response;
		$json_response->send($return);
	}

	/**
	 * Check errors
	 *
	 * @param string	$error	Error message
	 * @return object
	 */
	private function set_error($error)
	{
		// Replace "error" strings with their real, localised form
		$error = array_map(array($this->user, 'lang'), $error);

		return $error;
	}
}
