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
class cleverdineViewmanagelangtkattribute extends JViewUI {
	
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

			$q = "SELECT `a`.`id` AS `id_attribute`, `a`.`name` AS `attribute_name`,
			`al`.`id` AS `id_lang_attribute`, `al`.`name` AS `attribute_lang_name`, `al`.`tag`
			
			FROM `#__cleverdine_takeaway_menus_attribute` AS `a` 
			LEFT JOIN `#__cleverdine_lang_takeaway_menus_attribute` AS `al` ON `a`.`id`=`al`.`id_attribute`
			
			WHERE `al`.`id`=$id LIMIT 1;";
		} else {
			$id_attribute = $input->get('id_attribute', 0, 'uint');

			$q = "SELECT `a`.`id` AS `id_attribute`, `a`.`name` AS `attribute_name`
			
			FROM `#__cleverdine_takeaway_menus_attribute` AS `a` 
			
			WHERE `a`.`id`=$id_attribute LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkmenuattr');
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
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGTKATTRIBUTE'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGTKATTRIBUTE'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangTkattribute', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangTkattribute', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangTkattribute', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangTkattribute', JText::_('VRCANCEL'));
	}

}
?>