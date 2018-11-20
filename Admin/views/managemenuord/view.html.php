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
class cleverdineViewmanagemenuord extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		// Set the toolbar
		$this->addToolBar();
		
		$cid = $input->get('cid', array(0), 'uint');
		$id_menu = $cid[0];
		
		$rows = array();

		$q = "SELECT `s`.*, `p`.`id` AS `pid`, `p`.`name` AS `pname`, `a`.`id` AS `aid`, `a`.`ordering` AS `pordering`, `m`.`name` AS `menu_name` 
		FROM `#__cleverdine_menus_section` AS `s` 
		LEFT JOIN `#__cleverdine_section_product_assoc` AS `a` ON `s`.`id`=`a`.`id_section` 
		LEFT JOIN `#__cleverdine_section_product` AS `p` ON `p`.`id`=`a`.`id_product` 
		LEFT JOIN `#__cleverdine_menus` AS `m` ON `m`.`id`=`s`.`id_menu` 
		WHERE `m`.`id`=$id_menu ORDER BY `s`.`ordering` ASC, `a`.`ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
		} else {
			$mainframe->redirect('index.php?option=com_cleverdine&task=menus');
			exit;
		}
		
		$this->rows 	= &$rows;
		$this->menu_id 	= &$id_menu;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
	
		JToolbarHelper::title(JText::_('VRMAINTITLEMANAGEMENUORD'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::save('saveMenuOrdering', JText::_('VRSAVE'));
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelMenu', JText::_('VRCANCEL'));
	}

}
?>