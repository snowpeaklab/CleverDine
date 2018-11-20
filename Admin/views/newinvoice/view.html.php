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
class cleverdineViewnewinvoice extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		cleverdine::load_complex_select();

		$input = JFactory::getApplication()->input;

		$filters = array();
		$filters['year'] 		= $input->get('year', '', 'string');
		$filters['month'] 		= $input->get('month', '', 'string');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');
		$filters['group'] 		= $input->get('group', '', 'string');
		
		// Set the toolbar
		$this->addToolBar();

		$invoice = cleverdine::getInvoiceObject();
		
		$this->invoice = &$invoice;
		$this->filters = &$filters;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VRMAINTITLENEWINVOICE'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolBarHelper::apply('saveInvoice', JText::_('VRSAVE'));
			JToolBarHelper::save('saveAndCloseInvoice', JText::_('VRSAVEANDCLOSE'));
			JToolBarHelper::custom('saveAndNewInvoice', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolBarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelInvoice', JText::_('VRCANCEL'));
	}

}
?>