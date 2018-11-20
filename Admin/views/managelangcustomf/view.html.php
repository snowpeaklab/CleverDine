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
class cleverdineViewmanagelangcustomf extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$type = $input->get('type');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		$where = "";
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];	

			$q = "SELECT `c`.`id` AS `id_customf`, `c`.`name` AS `customf_name`, `c`.`choose` AS `customf_choose`, `c`.`poplink` AS `customf_poplink`, `c`.`type` AS `customf_type`, `c`.`rule` AS `customf_rule`,
			`cl`.`id` AS `id_lang_customf`, `cl`.`name` AS `customf_lang_name`, `cl`.`choose` AS `customf_lang_choose`, `cl`.`poplink` AS `customf_lang_poplink`, `cl`.`tag`
			
			FROM `#__cleverdine_custfields` AS `c` 
			LEFT JOIN `#__cleverdine_lang_customf` AS `cl` ON `c`.`id`=`cl`.`id_customf`
			
			WHERE `cl`.`id`=$id LIMIT 1;";
		} else {
			$id_customf = $input->get('id_customf', 0, 'uint');
			
			$q = "SELECT `c`.`id` AS `id_customf`, `c`.`name` AS `customf_name`, `c`.`choose` AS `customf_choose`, `c`.`poplink` AS `customf_poplink`, `c`.`type` AS `customf_type`, `c`.`rule` AS `customf_rule`
			
			FROM `#__cleverdine_custfields` AS `c` 
			
			WHERE `c`.`id`=$id_customf LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=customf');
			exit;
		}
		
		$struct = $dbo->loadAssoc();
		
		$this->struct 	= &$struct;
		$this->type 	= &$type;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGCUSTOMF'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGCUSTOMF'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangCustomf', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangCustomf', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangCustomf', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangCustomf', JText::_('VRCANCEL'));
	}

}
?>