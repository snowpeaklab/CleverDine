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
class cleverdineViewapilogs extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();
		
		$filters = array();
		$filters['id_login'] 	= $input->get('id_login', 0, 'int');
		$filters['key'] 		= $input->get('keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('apilogs', 'id', 2);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut = "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `l`.*, `u`.`application`, `u`.`username`
		FROM `#__cleverdine_api_login_logs` AS `l`
		LEFT JOIN `#__cleverdine_api_login` AS `u` ON `l`.`id_login`=`u`.`id`
		WHERE 1
		".(!empty($filters['key']) ? " AND (`u`.`application` LIKE ".$dbo->quote("%".$filters['key']."%")." OR `u`.`username` LIKE ".$dbo->quote("%".$filters['key']."%")." OR `l`.`content` LIKE ".$dbo->quote("%".$filters['key']."%").")" : "")." 
		".($filters['id_login'] > 0 ? " AND `l`.`id_login`=".$filters['id_login'] : "")." 
		ORDER BY `l`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$new_type = OrderingManager::getSwitchColumnType( 'apilogs', $ordering['column'], $ordering['type'], array( 1, 2 ) );
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
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWAPILOGS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteApilogs', JText::_('VRDELETE'));

			JToolbarHelper::custom('truncateApilogs', 'trash', 'trash', JText::_('VRDELETEALL'), false);
		}

		JToolbarHelper::cancel('cancelApiuser', JText::_('VRCANCEL'));

	}

}
?>