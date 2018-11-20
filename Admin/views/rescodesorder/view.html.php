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
class cleverdineViewrescodesorder extends JViewUI {
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

		$filters = array();
		$filters['id_order'] 	= $input->get('id_order', 0, 'uint');
		$filters['group'] 		= $input->get('group', 1, 'uint');

		// Set the toolbar
		$this->addToolBar($filters['group']);

		$ordering = OrderingManager::getColumnToOrder('rescodesorder', 'createdon', 2);
		
		$dbo = JFactory::getDbo();

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut	= "";
		
		$q = "SELECT SQL_CALC_FOUND_ROWS `os`.*, `rc`.`code`, `rc`.`icon`, `rc`.`notes` AS `code_notes`, `u`.`name` AS `user_name`
		FROM `#__cleverdine_order_status` AS `os`
		LEFT JOIN `#__cleverdine_res_code` AS `rc` ON `rc`.`id`=`os`.`id_rescode`
		LEFT JOIN `#__users` AS `u` ON `u`.`id`=`os`.`createdby`
		WHERE `os`.`id_order`=".$filters['id_order']." AND `os`.`group`=".$filters['group']." 
		ORDER BY `os`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$new_type = OrderingManager::getSwitchColumnType( 'rescodesorder', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->ordering 	= &$ordering;
		$this->filters 		= &$filters;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($group = 1) {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWRESCODESORDER'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newrescodeorder', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editrescodeorder', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteResCodesOrder', JText::_('VRDELETE'));	
			JToolbarHelper::spacer();
		}

		JToolbarHelper::cancel('cancel'.($group == 2 ? 'Tkreservation' : 'Reservation'), JText::_('VRCANCEL'));
		
	}

}
?>