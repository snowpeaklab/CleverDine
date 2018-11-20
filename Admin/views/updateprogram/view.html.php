<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * restaurants View
 */
class cleverdineViewupdateprogram extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$this->addToolBar();

		$config = UIFactory::getConfig();

		$params = new stdClass;
		$params->version 	= $config->get('version');
		$params->alias 		= CLEVERAPP;

		JPluginHelper::importPlugin('e4j');

		$dispatcher = JEventDispatcher::getInstance();
		
		$result = $dispatcher->trigger('getVersionContents', array(&$params));

		if( !count($result) || !$result[0] ) {
			$result = $dispatcher->trigger('checkVersion', array(&$params));
		}

		if( !count($result) || !$result[0]->status || !$result[0]->response->status ) {
			exit('error');
		}

		$this->version = &$result[0]->response;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEUPDATEPROGRAM'), 'restaurants');
		
		JToolbarHelper::cancel('dashboard', JText::_('VRCANCEL'));
	}

	/**
	 * Scan changelog structure.
	 *
	 * @param 	array 	$arr 	The list containing changelog elements.
	 * @param 	mixed 	$html 	The html built. 
	 * 							Specify false to echo the structure immediately.
	 *
	 * @return 	string|void 	The HTML structure or nothing.
	 */
	public function digChangelog(array $arr, $html = '') {

		foreach( $arr as $elem ):

			if( isset($elem->tag) ):

				// build attributes

				$attributes = "";
				if( isset($elem->attributes) ) {

					foreach( $elem->attributes as $k => $v ) {
						$attributes .= " $k=\"$v\"";
					}

				}

				// build tag opening

				$str = "<{$elem->tag}$attributes>";

				if( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

				// display contents

				if( isset($elem->content) ) {

					if( $html ) {
						$html .= $elem->content;
					} else {
						echo $elem->content;
					}

				}

				// recursive iteration for elem children

				if( isset($elem->children) ) {
					$this->digChangelog($elem->children, $html);
				}

				// build tag closure

				$str = "</{$elem->tag}>";

				if( $html ) {
					$html .= $str;
				} else {
					echo $str;
				}

			endif;

		endforeach;

		return $html;

	}

}
?>