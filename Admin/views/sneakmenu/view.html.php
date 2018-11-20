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

class cleverdineViewsneakmenu extends JViewUI {
	
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
		
		$sections = array();
		
		$q = "SELECT `s`.*, `p`.`id` AS `pid`, `p`.`name` AS `pname`, `a`.`id` AS `aid`, `p`.`image` AS `pimage`, `p`.`published` AS `ppublished` FROM `#__cleverdine_menus_section` AS `s` 
		LEFT JOIN `#__cleverdine_section_product_assoc` AS `a` ON `s`.`id`=`a`.`id_section` 
		LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`a`.`id_product` WHERE `s`.`id_menu`=$id ORDER BY `s`.`ordering` ASC, `a`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$sections = $dbo->loadAssocList();
		}
		
		$this->sections = &$sections;

		// Display the template
		parent::display($tpl);
		
	}
}
?>