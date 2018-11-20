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
class cleverdineViewtrackorder extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		cleverdine::load_css_js();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$oid = $input->get('oid', 0, 'uint');
		$sid = $input->get('sid', '', 'alnum');
		$tid = ($input->get('tid', 0, 'uint') == 0 ? 1 : 2);

		$order_details = null;
		if( $tid == 1 ) {
			$order_details = cleverdine::fetchOrderDetails($oid);
		} else {
			$order_details = cleverdine::fetchTakeAwayOrderDetails($oid);
		}

		$status_list = cleverdine::getOrderStatusList($oid, $sid, $tid);

		if( $status_list !== null ) {
			// split statuses within the same day
			$app = array();

			foreach( $status_list as $status ) {
				$day = getdate($status['createdon']);
				$day = mktime(0, 0, 0, $day['mon'], $day['mday'], $day['year']);

				if( empty($app[$day]) ) {
					$app[$day] = array();
				}

				$app[$day][] = $status;
			}

			$status_list = $app;
		}

		$this->statusList = &$status_list;
		$this->orderDetails = &$order_details;
		
		// Display the template
		parent::display($tpl);

	}

}
?>