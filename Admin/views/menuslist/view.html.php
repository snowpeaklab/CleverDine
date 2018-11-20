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

class cleverdineViewmenuslist extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$id = $input->get('id', 0, 'uint');
		
		$rows = array();
		
		$q = "SELECT `s`.`id`, `s`.`group` FROM `#__cleverdine_specialdays` AS `s` WHERE `s`.`id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$sel = $dbo->loadAssoc();
			
			if( $sel['group'] == 1 ) {
				$q = "SELECT `m`.`name` AS `menu_name` FROM `#__cleverdine_sd_menus` AS `a` 
				LEFT JOIN `#__cleverdine_menus` AS `m` ON `m`.`id`=`a`.`id_menu` WHERE `id_spday`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$rows = $dbo->loadAssocList();
				}
			} else {
				$q = "SELECT `m`.`title` AS `menu_name` FROM `#__cleverdine_sd_menus` AS `a` 
				LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`a`.`id_menu` WHERE `id_spday`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$rows = $dbo->loadAssocList();
				}
			}
			
		}
		
		$this->rows = &$rows;

		// Display the template
		parent::display($tpl);
		
	}
}
?>