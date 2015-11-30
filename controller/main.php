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
	* @param \phpbb\user					$user				User object
	* @param string						$phpbb_root_path
	* @param string						$php_ext
	*/
	public function __construct(\phpbb\config\config $config, \phpbb\user $user, $phpbb_root_path, $php_ext)
	{
		$this->config = $config;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		include($this->phpbb_root_path . 'includes/functions_user.' . $php_ext);
		$this->user->add_lang('ucp');
	}

	/**
	 * Check username
	 *
	 * @return object
	 */
	public function username()
	{
		$username = utf8_normalize_nfc(request_var('username', '', true));
		if (strlen($username) > $this->config['max_name_chars'])
		{
			$return = $this->user->lang('USERNAME_CHARS_ANY_EXPLAIN', $this->config['min_name_chars'], $this->config['max_name_chars']);
		}
		else if (strlen($username) < $this->config['min_name_chars'])
		{
			$return = $this->user->lang('USERNAME_CHARS_ANY_EXPLAIN', $this->config['min_name_chars'], $this->config['max_name_chars']);
		}
		else if ($return = validate_username($username))
		{
			if ($return)
			{
				$return = $this->user->lang($return . '_USERNAME');
			}
		}
		else
		{
			$return = 0;
		}
		return new Response($return);
	}

	/**
	 * Check password
	 *
	 * @return object
	 */
	public function password()
	{
		$password = utf8_normalize_nfc(request_var('password', '', true));
		if (strlen($password) > $this->config['max_pass_chars'])
		{
			$return = $this->user->lang('TOO_LONG_USER_PASSWORD');
		}
		else if (strlen($password) < $this->config['min_pass_chars'])
		{
			$return = $this->user->lang('TOO_SHORT_USER_PASSWORD');
		}
		else if ($return = validate_password($password))
		{
			$return = $this->user->lang($return . '_NEW_PASSWORD');
		}
		else
		{
			$return = 0;
		}
		return new Response($return);
	}

	/**
	 * Check email
	 *
	 * @return object
	 */
	public function email()
	{
		$email = utf8_normalize_nfc(request_var('email', '', true));
		if ($return = phpbb_validate_email($email))
		{
			$return = $this->user->lang($return . '_EMAIL');
		}
		else
		{
			$return = 0;
		}
		return new Response($return);
	}
}
