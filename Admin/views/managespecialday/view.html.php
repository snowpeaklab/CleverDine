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
class cleverdineViewmanagespecialday extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		cleverdine::load_fancybox();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type 	= $input->get('type');
		$group 	= $input->get('post_group', 0, 'uint');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedSpecialDay = array();
		$sel_menus = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = intval($ids[0]);
			
			$q = "SELECT `s`.* FROM `#__cleverdine_specialdays` AS `s` WHERE `s`.`id`=$id LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedSpecialDay = $dbo->loadAssoc();
				
				if( empty($group) ) {
					$group = $selectedSpecialDay['group'];
				}
				
				if( $group == 1 ) {
					$q = "SELECT `m`.`id` AS `mid` FROM `#__cleverdine_sd_menus` AS `a` 
					LEFT JOIN `#__cleverdine_menus` AS `m` ON `m`.`id`=`a`.`id_menu` WHERE `id_spday`=".$selectedSpecialDay['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$sel_menus = $dbo->loadAssocList();
					}
				} else {
					$q = "SELECT `m`.`id` AS `mid` FROM `#__cleverdine_sd_menus` AS `a` 
					LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`a`.`id_menu` WHERE `id_spday`=".$selectedSpecialDay['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$sel_menus = $dbo->loadAssocList();
					}
				}
				
			}
			
		}
		
		if( empty($group) ) {
			$group = 1;
		}
		
		$shifts = cleverdine::getWorkingShifts($group, true);
		
		$menus = array();
		$q = "SELECT `id`, `name`, `special_day` FROM `#__cleverdine_menus` ORDER BY `special_day` DESC, `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = $dbo->loadAssocList();
		}
		
		$tk_menus = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_menus` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$tk_menus = $dbo->loadAssocList();
		}
		
		$this->selectedSpecialDay 	= &$selectedSpecialDay;
		$this->shifts 				= &$shifts;
		$this->menus 				= &$menus;
		$this->tkmenus 				= &$tk_menus;
		$this->sel_menus 			= &$sel_menus;
		$this->post_group 			= &$group;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITSPECIALDAY'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWSPECIALDAY'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveSpecialDay', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseSpecialDay', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewSpecialDay', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelSpecialDay', JText::_('VRCANCEL'));
	}
	
}
?>