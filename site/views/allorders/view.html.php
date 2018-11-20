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
class cleverdineViewallorders extends JViewUI {

	/**
	 * Order view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$lim 		= 5;
		$lim0 		= $tklim0 = $input->get('limitstart', 0, 'uint');
		$ordtype 	= $input->get('ordtype', 0, 'int');

		$orders_navigation 		= '';
		$tkorders_navigation 	= '';
		
		if( empty($ordtype) ) {
			$tklim0 = $input->get('prevlim', 0, 'uint');
		} else {
			$lim0 = $input->get('prevlim', 0, 'uint');
		}
		
		$user = JFactory::getUser();
		
		$orders = array();
		$tkorders = array();
		
		if( !$user->guest ) {
			$q = "SELECT SQL_CALC_FOUND_ROWS `r`.* FROM `#__cleverdine_reservation` AS `r` 
			LEFT JOIN `#__cleverdine_users` AS `u` ON `r`.`id_user`=`u`.`id` 
			WHERE `u`.`jid`=".$user->id." AND `r`.`status`<>'REMOVED' 
			ORDER BY `r`.`id` DESC";
			$dbo->setQuery($q, $lim0, $lim);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$orders = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
				$pageNav->setAdditionalUrlParam('prevlim', $tklim0); // prev takeaway lim
				$pageNav->setAdditionalUrlParam('ordtype', 0); // order type
				$orders_navigation = $pageNav->getPagesLinks();
			}
			
			$q = "SELECT SQL_CALC_FOUND_ROWS `r`.* FROM `#__cleverdine_takeaway_reservation` AS `r` 
			LEFT JOIN `#__cleverdine_users` AS `u` ON `r`.`id_user`=`u`.`id` 
			WHERE `u`.`jid`=".$user->id." AND `r`.`status`<>'REMOVED' 
			ORDER BY `r`.`id` DESC";
			$dbo->setQuery($q, $tklim0, $lim);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$tkorders = $dbo->loadAssocList();
				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $tklim0, $lim );
				$pageNav->setAdditionalUrlParam('prevlim', $lim0); // prev restaurant lim
				$pageNav->setAdditionalUrlParam('ordtype', 1); // order type
				$tkorders_navigation = $pageNav->getPagesLinks();
			}
		}
		
		$this->user 				= &$user;
		$this->orders 				= &$orders;
		$this->tkorders 			= &$tkorders;
		
		$this->ordersNavigation 	= &$orders_navigation;
		$this->tkordersNavigation 	= &$tkorders_navigation;

		// prepare page content
		cleverdine::prepareContent($this);
		
		// Display the template
		parent::display($tpl);

	}
	
}
?>