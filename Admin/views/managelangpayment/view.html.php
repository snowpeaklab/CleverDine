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
class cleverdineViewmanagelangpayment extends JViewUI {
	
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

			$q = "SELECT `p`.`id` AS `id_payment`, `p`.`name` AS `payment_name`, `p`.`note` AS `payment_note`, `p`.`prenote` AS `payment_prenote`,
			`pl`.`id` AS `id_lang_payment`, `pl`.`name` AS `payment_lang_name`, `pl`.`note` AS `payment_lang_note`, `pl`.`prenote` AS `payment_lang_prenote`, `pl`.`tag`
			
			FROM `#__cleverdine_gpayments` AS `p` 
			LEFT JOIN `#__cleverdine_lang_payments` AS `pl` ON `p`.`id`=`pl`.`id_payment`
			
			WHERE `pl`.`id`=$id LIMIT 1;";
		} else {
			$id_payment = $input->get('id_payment', 0, 'uint');
			
			$q = "SELECT `p`.`id` AS `id_payment`, `p`.`name` AS `payment_name`, `p`.`note` AS `payment_note`, `p`.`prenote` AS `payment_prenote`
			
			FROM `#__cleverdine_gpayments` AS `p` 
			
			WHERE `p`.`id`=$id_payment LIMIT 1;";
		}
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=payments');
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
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITLANGPAYMENT'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWLANGPAYMENT'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveLangPayment', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseLangPayment', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewLangPayment', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelLangPayment', JText::_('VRCANCEL'));
	}

}
?>