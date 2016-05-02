<?php
/**
*
* @package phpBB Extension - AJAX Registration check
* @copyright (c) 2016 tas2580 (https://tas2580.net)
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace tas2580\regcheck\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\controller\helper */
	protected $helper;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var \phpbb\user\user */
	protected $user;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper			$helper				Helper Object
	* @param \phpbb\template\template			$template			Template object
	* @param \phpbb\user						$user				User object
	* @access public
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template, \phpbb\user $user)
	{
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.ucp_register_data_before'			=> 'ucp_register_data_before',
		);
	}

	/**
	* Send AJAX URLs to template
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function ucp_register_data_before()
	{
		$this->user->add_lang_ext('tas2580/regcheck', 'common');

		$this->template->assign_vars(array(
			'U_REGCHECK'			=> $this->helper->route('tas2580_regcheck', array()),
		));
	}
}
