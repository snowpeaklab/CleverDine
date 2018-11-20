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
class cleverdineViewrooms extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['keysearch'] = $mainframe->getUserStateFromRequest('vrrooms.keysearch', 'keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('rooms', 'ordering', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrrooms.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";

		$now = time();

		$q = "SELECT SQL_CALC_FOUND_ROWS `r`.*, (
		  SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `c` WHERE `c`.`id_room`=`r`.`id` AND `c`.`start_ts`<=$now AND $now<`c`.`end_ts`
		) AS `is_closed` 
		FROM `#__cleverdine_room` AS `r` 
		".(!empty($filters['keysearch']) ? " WHERE `r`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		ORDER BY `r`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$new_type = OrderingManager::getSwitchColumnType( 'rooms', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$q = "SELECT MIN(`ordering`) AS `min`, MAX(`ordering`) AS `max` FROM `#__cleverdine_room`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$constraints = $dbo->loadAssoc();

		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->ordering 	= &$ordering;
		$this->filters 		= &$filters;
		$this->constraints 	= &$constraints;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWROOMS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newroom', JText::_('VRNEW'));
			JToolbarHelper::divider();  
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editroom', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::custom('roomclosures', 'calendar', 'calendar', JText::_('VRMANAGECLOSURES'), false);
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteRooms', JText::_('VRDELETE'));
		}
		
	}

}
?>