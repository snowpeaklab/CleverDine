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
class cleverdineViewtkreservations extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['datefilter'] 		= $mainframe->getUserStateFromRequest('vrtkres.datefilter', 'datefilter', '', 'string');
		$filters['shift'] 			= $mainframe->getUserStateFromRequest('vrtkres.shift', 'shift', '', 'string');
		$filters['ordnum']	 		= $mainframe->getUserStateFromRequest('vrtkres.ordnum', 'ordnum', '', 'string');
		$filters['keysearch'] 		= $mainframe->getUserStateFromRequest('vrtkres.keysearch', 'keysearch', '', 'string');
		$filters['couponsearch']	= $mainframe->getUserStateFromRequest('vrtkres.couponsearch', 'couponsearch', '', 'string');
		$filters['ordstatus']		= $mainframe->getUserStateFromRequest('vrtkres.ordstatus', 'ordstatus', '', 'string');
		$filters['tools']			= $mainframe->getUserStateFromRequest('vrtkres.tools', 'tools', 0, 'uint');
		
		$ordering = OrderingManager::getColumnToOrder('tkreservations', 'id', 2);

		$where_claus = "";
		
		if( strlen($filters['datefilter']) > 0 ) {
			$where_claus .= " AND ".cleverdine::createTimestamp($filters['datefilter'], 0, 0, true)." <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= ".cleverdine::createTimeStamp($filters['datefilter'], 23, 59, true);
		}
		
		if( strlen($filters['shift']) > 0 ) {
			$hour_min = explode('-', $filters['shift']); 
			
			$where_claus .= " AND ".$hour_min[0] . " <= DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') AND DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') <= " . $hour_min[1];
		}
		
		if( strlen($filters['ordnum']) > 0 ) {

			$where_claus .= " AND ";

			if( strlen($filters['ordnum']) == 16 ) {
				$where_claus .= "`r`.`sid`=".$dbo->quote($filters['ordnum']);
			} else if( strlen($filters['ordnum']) < 16 ) {
				$where_claus .= "`r`.`id`=".$dbo->quote($filters['ordnum']);
			} else {
				$where_claus .= " CONCAT_WS('-', `r`.`id`, `r`.`sid`)=".$dbo->quote($filters['ordnum']);
			}
		}
		
		if( strlen($filters['keysearch']) > 0 ) {
			$_key = $filters['keysearch'];

			$where_claus .= " AND (`r`.`purchaser_nominative` LIKE ".$dbo->quote("%$_key%")."OR `r`.`purchaser_mail` LIKE ".$dbo->quote("%$_key%")."OR `r`.`purchaser_phone` LIKE ".$dbo->quote("%$_key%").")";
		}

		if( strlen($filters['couponsearch']) > 0 ) {
			$_key = $filters['couponsearch'];

			$where_claus .= " AND `r`.`coupon_str` LIKE ".$dbo->quote("$_key%");
		}

		if( strlen($filters['ordstatus']) ) {
			$where_claus .= " AND `r`.`status`=".$dbo->quote($filters['ordstatus']);
		}

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrtkres.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `r`.*, `g`.`name` AS `payment_name`, 
		`c`.`code`, `c`.`icon` AS `code_icon`, `u`.`name` AS `createdby_name`, 
		(
			SELECT COUNT(1) FROM `#__cleverdine_order_status` AS `os` WHERE `os`.`id_order`=`r`.`id` AND `os`.`group`=2 LIMIT 1
		) AS `order_status_count`
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_gpayments` AS `g` ON `r`.`id_payment`=`g`.`id` 
		LEFT JOIN `#__cleverdine_res_code` AS `c` ON `r`.`rescode`=`c`.`id` 
		LEFT JOIN `#__users` AS `u` ON `u`.`id`=`r`.`created_by` 
		WHERE 1 $where_claus
		ORDER BY ".$ordering['column']." ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');
		
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

		// get shifts

		$shifts = array();

		if( strlen($filters['datefilter']) ) {
			
			$dt_args = array('date' => $filters['datefilter'], 'hourmin' => '-1:0', 'hour' => -1, 'min' => 0);
			
			$special_days = cleverdine::getSpecialDaysOnDate($dt_args, 2, true);
			if( !cleverdine::isContinuosOpeningTime(true) ) {
				$shifts = cleverdine::getWorkingShifts(2, true);
				
				if( $special_days != -1 && count($special_days) > 0 ) {
					$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_days, 2, true);
				}
			}

		}

		//
		
		$all_res_codes = array();
		$q = "SELECT * FROM `#__cleverdine_res_code` WHERE `type`=2 ORDER BY `code`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$all_res_codes = $dbo->loadAssocList(); 
		}
		
		$new_type = OrderingManager::getSwitchColumnType( 'tkreservations', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->shifts 		= &$shifts;
		$this->allResCodes 	= &$all_res_codes;
		$this->filters 		= &$filters;
		$this->ordering 	= &$ordering;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTKRES'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newtkreservation', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			
			JToolbarHelper::editList('edittkreservation', JText::_('VREDIT'));
			JToolbarHelper::spacer();

			JToolbarHelper::custom('tkdiscord', 'tag-2', 'tag-2', JText::_('VRDISCOUNT'), true);
			JToolbarHelper::spacer();
			
			JToolbarHelper::custom('tkexportres', 'out', 'out', JText::_('VREXPORT'), false);
			JToolbarHelper::divider();
			
			JToolbarHelper::custom('tkstatistics', 'chart', 'chart', JText::_('VRSTAT'), false);
			JToolbarHelper::spacer();
			
			JToolbarHelper::custom('tkprintorders', 'print', 'print', JText::_('VRPRINT'), true);
			JToolbarHelper::spacer();

			JToolBarHelper::custom('saveInvoiceFromTakeaway', 'vcard', 'vcard', JText::_('VRINVOICE'), true);
			JToolBarHelper::spacer();
			
			if( $this->isApiSmsConfigured() ) {
				JToolbarHelper::custom('tksendsms', 'comment', 'comment', JText::_('VRSENDSMS'), true);
				JToolbarHelper::spacer();
			}
			
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteTkreservations', JText::_('VRDELETE'));  
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