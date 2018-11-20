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
class cleverdineViewapiusers extends JViewUI {
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
		$filters['key'] 	= $input->get('keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('apiusers', 'id', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut = "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `u`.*
		FROM `#__cleverdine_api_login` AS `u`
		".(!empty($filters['key']) ? " WHERE `u`.`application` LIKE ".$dbo->quote("%".$filters['key']."%")." OR `u`.`username` LIKE ".$dbo->quote("%".$filters['key']."%") : "")." 
		ORDER BY `u`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');	

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

		foreach( $rows as $i => $r ) {

			$rows[$i]['log'] = null;

			$q = "SELECT `l`.* FROM `#__cleverdine_api_login_logs` AS `l` WHERE `l`.`id`=(
				SELECT MAX(`l2`.`id`) FROM `#__cleverdine_api_login_logs` AS `l2` WHERE `l2`.`id_login`=".$r['id']."
			) LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() ) {
				$rows[$i]['log'] = $dbo->loadAssoc();
			}

		}

		$new_type = OrderingManager::getSwitchColumnType( 'apiusers', $ordering['column'], $ordering['type'], array( 1, 2 ) );
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
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWAPIUSERS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newapiuser', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editapiuser', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteApiusers', JText::_('VRDELETE'));
		}

		JToolbarHelper::cancel('cancelConfig', JText::_('VRCANCEL'));

	}

}
?>