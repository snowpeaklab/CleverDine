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
class cleverdineViewshifts extends JViewUI {
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

		// Set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['group'] 		= $input->get('group', 0, 'uint');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('shifts', 'id', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut	= "";

		$q = "SELECT * FROM `#__cleverdine_shifts` 
		WHERE 1
		".($filters['group'] > 0 ? " AND `group`=".$filters['group'] : "")."
		".(!empty($filters['keysearch']) ? " AND `name` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		ORDER BY `".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		} else {
			$rows = array();
		}

		$new_type = OrderingManager::getSwitchColumnType( 'shifts', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		if( cleverdine::isContinuosOpeningTime(true) ) {
			$mainframe->enqueueMessage(JText::sprintf('VRSHIFTSIGNOREDWARNING', 'index.php?option=com_cleverdine&task=swap_opening_time'), 'warning');
		}

		$this->rows 	= &$rows;
		$this->lim0 	= &$lim0;
		$this->navbut 	= &$navbut;
		$this->ordering = &$ordering;
		$this->filters 	= &$filters;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWSHIFTS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newshift', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editshift', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteShifts', JText::_('VRDELETE'));
		}
		
	}

}
?>