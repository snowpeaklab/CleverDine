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
class cleverdineViewmanagelangtktopping extends JViewUI {
	
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

			$q = "SELECT `t`.`id` AS `id_topping`, `t`.`name` AS `topping_name`,
			`tl`.`id` AS `id_lang_topping`, `tl`.`name` AS `topping_lang_name`, `tl`.`tag`
			
			FROM `#__cleverdine_takeaway_topping` AS `t` 
			LEFT JOIN `#__cleverdine_lang_takeaway_topping` AS `tl` ON `t`.`id`=`tl`.`id_topping`
			
			WHERE `tl`.`id`=$id LIMIT 1;";

		} else {
			$id_topping = $input->get('id_topping', 0, 'uint');

			$q = "SELECT `t`.`id` AS `id_topping`, `t`.`name` AS `topping_name`
			
			FROM `#__cleverdine_takeaway_topping` AS `t` 
			
			WHERE `t`.`id`=$id_topping LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tktoppings');
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
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGTKTOPPING'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGTKTOPPING'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangTktopping', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangTktopping', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangTktopping', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangTktopping', JText::_('VRCANCEL'));
	}

}
?>