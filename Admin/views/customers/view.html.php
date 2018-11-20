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
class cleverdineViewcustomers extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		// Set the toolbar
		$this->addToolBar();
		
		$mainframe 	= JFactory::getApplication();
		$dbo 		= JFactory::getDbo();
		
		$ordering = OrderingManager::getColumnToOrder('customers', 'id', 1);
		
		$filters = array();
		$filters['keysearch'] = $mainframe->getUserStateFromRequest('vrcustomers.keysearch', 'keysearch', '', 'string');

		$where_claus = "";
		if( !empty($filters['keysearch']) ) {
			$where_claus = " AND (`u`.`billing_name` LIKE ".$dbo->quote("%".$filters['keysearch']."%")." OR `u`.`billing_mail` LIKE ".$dbo->quote("%".$filters['keysearch']."%").")";
		}

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrcustomers.limitstart', 'limitstart', 0, 'int');
		$navbut	= "";
		
		$q="SELECT SQL_CALC_FOUND_ROWS `u`.*, (
		  SELECT COUNT(1) FROM `#__cleverdine_reservation` AS `r` WHERE `r`.`id_user`<>-1 AND `r`.`status`='CONFIRMED' AND `r`.`id_user`=`u`.`id`
		) AS `rescount`, (
		  SELECT COUNT(1) FROM `#__cleverdine_takeaway_reservation` AS `t` WHERE `t`.`id_user`<>-1 AND `t`.`status`='CONFIRMED' AND `t`.`id_user`=`u`.`id`
		) AS `ordcount` 
		 FROM `#__cleverdine_users` AS `u` WHERE `u`.`billing_name`<>''".$where_claus."
		ORDER BY `".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC').
		', `u`.`jid` '.($ordering['type'] == 2 ? 'DESC' : 'ASC');
		
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
		
		$new_type = OrderingManager::getSwitchColumnType( 'customers', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );
		
		$is_sms = $this->isApiSmsConfigured();
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->ordering 	= &$ordering;
		$this->filters 		= &$filters;
		$this->is_sms 		= &$is_sms;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWCUSTOMERS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newcustomer', JText::_('VRNEW'));
			JToolbarHelper::divider();  
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editcustomer', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteCustomers', JText::_('VRDELETE'));
		}
		
	}
	
	protected function isApiSmsConfigured() {
		$smsapi = cleverdine::getSmsApi(true);
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$smsapi;
		if( file_exists( $sms_api_path ) && strlen($smsapi) > 0 ) {
			require_once( $sms_api_path );
			if( method_exists('VikSmsApi', 'sendMessage') ) {
				return true;
			}
		}
		return false;
	}
}
?>