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
class cleverdineViewmanagelangtkdeal extends JViewUI {
	
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

			$q = "SELECT `d`.`id` AS `id_deal`, `d`.`name` AS `deal_name`, `d`.`description` AS `deal_description`,
			`dl`.`id` AS `id_lang_deal`, `dl`.`name` AS `deal_lang_name`, `dl`.`description` AS `deal_lang_description`, `dl`.`tag`
			
			FROM `#__cleverdine_takeaway_deal` AS `d` 
			LEFT JOIN `#__cleverdine_lang_takeaway_deal` AS `dl` ON `d`.`id`=`dl`.`id_deal`
			
			WHERE `dl`.`id`=$id LIMIT 1;";
		} else {
			$id_deal = $input->get('id_deal', 0, 'uint');
			
			$q = "SELECT `d`.`id` AS `id_deal`, `d`.`name` AS `deal_name`, `d`.`description` AS `deal_description`
			
			FROM `#__cleverdine_takeaway_deal` AS `d` 
			
			WHERE `d`.`id`=$id_deal LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkdeals');
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
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGTKDEAL'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGTKDEAL'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangTkdeal', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangTkdeal', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangTkdeal', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangTkdeal', JText::_('VRCANCEL'));
	}

}
?>