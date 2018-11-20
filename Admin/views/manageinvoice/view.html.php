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
class cleverdineViewmanageinvoice extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		cleverdine::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$filters = array();
		$filters['year'] 		= $input->get('year', '', 'string');
		$filters['month'] 		= $input->get('month', '', 'string');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');
		$filters['group'] 		= $input->get('group', '', 'string');
		
		// Set the toolbar
		$this->addToolBar();

		$row = array();

		$id = $this->getInvoiceFromRequest($input);
		$q = "SELECT * FROM `#__cleverdine_invoice` WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$row = $dbo->loadAssoc();
		} else {
			$mainframe->redirect('index.php?option=com_cleverdine&task=invoices');
			exit;
		}

		$invoice = cleverdine::getInvoiceObject();
		
		$this->row 		= &$row;
		$this->invoice 	= &$invoice;
		$this->filters 	= &$filters;

		// Display the template
		parent::display($tpl);

	}

	private function getInvoiceFromRequest($input = null) {
		if( $input === null ) {
			$input = JFactory::getApplication()->input;
		}

		$ids = $input->get('cid', array(), 'string');
		foreach( $ids as $id ) {
			if( intval($id[0]) == 1 ) {
				$exp = explode('::', $id);
				return intval($exp[1]);
			}
		}
		return 0;
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolBarHelper::title(JText::_('VRMAINTITLEEDITINVOICE'), 'restaurants');
		
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