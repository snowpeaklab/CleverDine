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
class cleverdineViewtables extends JViewUI {
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
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrtable.keysearch', 'keysearch', '', 'string');
		$filters['id_room'] 	= $mainframe->getUserStateFromRequest('vrtable.id_room', 'id_room', 0, 'uint');

		$ordering = OrderingManager::getColumnToOrder('tables', 'id', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrtable.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `t`.*, `r`.`name` AS `room_name` 
		FROM `#__cleverdine_table` AS `t`
		LEFT JOIN `#__cleverdine_room` AS `r` ON `t`.`id_room`=`r`.`id`
		WHERE 1 
		".(!empty($filters['keysearch']) ? " AND `t`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		".($filters['id_room'] > 0 ? " AND `t`.`id_room`=".$filters['id_room'] : "")." 
		ORDER BY `t`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$new_type = OrderingManager::getSwitchColumnType( 'tables', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$rooms = array();

		$q = "SELECT `id`, `name` FROM `#__cleverdine_room` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rooms = $dbo->loadAssocList();
		}

		$this->rows 	= &$rows;
		$this->rooms 	= &$rooms;
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
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTABLES'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newtable', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('edittable', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteTables', JText::_('VRDELETE'));
		}

	}

}
?>