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
class cleverdineViewmanagelangtkmenu extends JViewUI {
	
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

			$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_name`, `m`.`description` AS `menu_description`,
			`ml`.`id` AS `id_lang_menu`, `ml`.`name` AS `menu_lang_name`, `ml`.`description` AS `menu_lang_description`, `ml`.`tag`
			
			FROM `#__cleverdine_takeaway_menus` AS `m` 
			LEFT JOIN `#__cleverdine_lang_takeaway_menus` AS `ml` ON `m`.`id`=`ml`.`id_menu`
			
			WHERE `ml`.`id`=$id LIMIT 1;";
		} else {
			$id_menu = $input->get('id_menu', 0, 'uint');

			$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_name`, `m`.`description` AS `menu_description`
			
			FROM `#__cleverdine_takeaway_menus` AS `m` 
			
			WHERE `m`.`id`=$id_menu LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkmenus');
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
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGTKMENU'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGTKMENU'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangTkmenu', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangTkmenu', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangTkmenu', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangTkmenu', JText::_('VRCANCEL'));
	}

}
?>