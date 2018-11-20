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
class cleverdineViewopreservations extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		////// LOGIN //////
		
		$operator = cleverdine::getOperator();
		
		if( $operator === false || empty($operator['can_login']) )  {
			$mainframe->enqueueMessage(JText::_('VRLOGINUSERNOTFOUND'), 'error');
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=oversight'));
			exit;
		}
		
		cleverdine::load_css_js();
		
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vropres.limitstart', 'limitstart', 0, 'uint');
		$navbut = "";
		
		$key_search 	= $mainframe->getUserStateFromRequest('vropres.keysearch', 'keysearch', '', 'string');
		$date_filter 	= $mainframe->getUserStateFromRequest('vropres.datefilter', 'datefilter', '', 'string');
		
		$where_claus = "";
		if( !empty($key_search) ) {
			$where_claus .= " AND `r`.`purchaser_nominative` LIKE ".$dbo->quote("%$key_search%");
		}
		if( !empty($date_filter) ) {
			$ts = getdate(cleverdine::createTimestamp($date_filter, 0, 0));
			$where_claus .= " AND `r`.`checkin_ts` BETWEEN ".$ts[0]." AND ".mktime(23, 59, 59, $ts['mon'], $ts['mday'], $ts['year']);
		}
		
		$reservations = array();
		
		$q = "SELECT SQL_CALC_FOUND_ROWS `r`.*, `t`.`name` AS `tname` 
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id` 
		WHERE `status`<>'REMOVED' $where_claus 
		ORDER BY `id` DESC";

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$reservations = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$pageNav->setAdditionalUrlParam('keysearch', $key_search);
			$pageNav->setAdditionalUrlParam('datefilter', $date_filter);
			$navbut = $pageNav->getPagesLinks();
		}
		
		$this->operator 	= &$operator;
		$this->reservations = &$reservations;
		$this->navbut 		= &$navbut;
		
		$this->keySearch 	= &$key_search;
		$this->dateFilter 	= &$date_filter;

		// Display the template
		parent::display($tpl);

	}

}
?>