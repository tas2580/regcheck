<?php
/**
*
* @package phpBB Extension - AJAX Registration check
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace tas2580\regcheck\controller;

use Symfony\Component\HttpFoundation\Response;

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

		if (!function_exists('validate_data'))
		{
			include($this->phpbb_root_path . 'includes/functions_user.' . $this->php_ext);
		}
		$this->user->add_lang('ucp');
		$this->user->add_lang_ext('tas2580/regcheck', 'common');
	}

	/**
	 * Check username
	 *
	 * @return object
	 */
	public function username()
	{
		$data = array(
			'username'			=> utf8_normalize_nfc($this->request->variable('username', '', true)),
		);
		$error = validate_data($data, array(
			'username'			=> array(
				array('string', false, $this->config['min_name_chars'], $this->config['max_name_chars']),
				array('username', '')),
		));

		$error = $this->set_error($error);
		if (sizeof($error))
		{
			return new Response(implode('', $error));
		}

		return new Response($this->user->lang('USERNAME_FREE'));
	}

	/**
	 * Check password
	 *
	 * @return object
	 */
	public function password()
	{
		$data = array(
			'new_password'			=> $this->request->variable('new_password', '', true),
		);
		$error = validate_data($data, array(
			'new_password'		=> array(
				array('string', false, $this->config['min_pass_chars'], $this->config['max_pass_chars']),
				array('password')),
		));

		$error = $this->set_error($error);

		if (sizeof($error))
		{
			return new Response(implode('', $error));
		}

		return new Response($this->user->lang('PASSWORD_GOOD'));
	}

	/**
	 * Check email
	 *
	 * @return object
	 */
	public function email()
	{
		$data = array(
			'email'			=> $this->request->variable('email', '', true),
		);
		$error = validate_data($data, array(
			'email'				=> array(
				array('string', false, 6, 60),
				array('user_email')),
		));

		$error = $this->set_error($error);
		if (sizeof($error))
		{
			return new Response(implode('', $error));
		}

		return new Response($this->user->lang('EMAIL_GOOD'));
	}

	/**
	 * Check errors
	 *
	 * @return object
	 */
	private function set_error($error)
	{
		// Replace "error" strings with their real, localised form
		$error = array_map(array($this->user, 'lang'), $error);

		return $error;
	}
}
