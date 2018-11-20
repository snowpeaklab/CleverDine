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
class cleverdineViewoperatorlogs extends JViewUI {
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
		$filters['id_operator'] = $input->get('id', 0, 'uint');
		$filters['date'] 		= $input->get('date', '', 'string');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut	= "";
		
		$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__cleverdine_operator_log`
		WHERE `id_operator`=".$filters['id_operator']."
		".(!empty($filters['keysearch']) ? " AND `log` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		".(!empty($filters['date']) ? " AND `createdon`>=".$filters['date'] : "")." 
		ORDER BY `createdon` DESC";

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
		
		$this->rows 	= &$rows;
		$this->lim0 	= &$lim0;
		$this->navbut 	= &$navbut;
		$this->filters 	= &$filters; 
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWOPERATORLOGS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteOperatorLogs', JText::_('VRDELETE'));	
		}
		
		JToolbarHelper::cancel('operators', JText::_('VRCANCEL'));
	}

}
?>