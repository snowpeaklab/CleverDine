<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

defined('_JEXEC') OR die('Restricted Area');

class VikExporter {
	
	private $from;
	private $to;
	private $ids;
	
	private $head;
	
	public function __construct($from_ts, $to_ts, $ids = array()) {
		$this->from = $from_ts;
		$this->to 	= $to_ts;
		$this->ids 	= $ids;
	}
	
	/*
	 * 0 = restaurant
	 * 1 = takeaway
	 */
	public function getString($type = 0) {
		if( $type == 0 ) {
			return $this->fetchRestaurantArray();
		} 
		
		return $this->fetchTakeAwayArray();
	}
	
	protected function fetchRestaurantArray() {
		$dbo = JFactory::getDbo();
		
		$date_format = cleverdine::getDateFormat(true);
		$time_format = cleverdine::getTimeFormat(true);
		
		$curr_symb = cleverdine::getCurrencySymb(true);
		$symb_pos = cleverdine::getCurrencySymbPosition(true);
		
		$where_cl = "";
		if( count($this->ids) > 0 ) {
			for( $i = 0; $i < count($this->ids)-1; $i++ ) {
				$where_cl .= "`r`.`id`=" . $this->ids[$i] . " OR ";
			}
			$where_cl .= "`r`.`id`=" . $this->ids[$i];
		} else {
			$where_cl = $this->from."<=`r`.`checkin_ts` AND `r`.`checkin_ts`<=".$this->to;
		}
		
		// get reservations rows
		$q = "SELECT `r`.`id`, `r`.`sid`, `r`.`checkin_ts`, `r`.`bill_value`, `r`.`custom_f`, `r`.`people`, `r`.`coupon_str`, `r`.`status`, `r`.`notes`, `t`.`name` AS `tname` 
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id`
		WHERE $where_cl";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			return array();
		}
		
		$rows = $dbo->loadAssocList();
		
		// get all custom fields
		$all_cf = array();

		$q = "SELECT `name`
		FROM `#__cleverdine_custfields` 
		WHERE `group`=0 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0) 
		ORDER BY `ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			foreach( $dbo->loadAssocList() as $app ) {
				// the key is the original name
				// tha val is the translated name
				$all_cf[$app['name']] = JText::_($app['name']);
			}
		}
		
		// compose table header
		$header = array( 
			JText::_('VRMANAGERESERVATION1'), 		// order number
			JText::_('VRMANAGERESERVATION2'), 		// order key
			JText::_('VRMANAGERESERVATION3'), 		// checkin
			JText::_('VRMANAGERESERVATION5'), 		// table
			JText::_('VRMANAGERESERVATION10'), 		// bill value
			JText::_('VRMANAGERESERVATION4'), 		// people
			JText::_('VRMANAGERESERVATION8'), 		// coupon
			JText::_('VRMANAGERESERVATION12'),		// status
			JText::_('VRMANAGERESERVATIONTITLE3')	// notes
		);
		
		// push all custom fields in header
		foreach( $all_cf as $k => $v ) {
			$header[] = JText::_($v);
		}
		
		
		// fetch rows array
		$csv = array($header); // first line of CSV is always the header

		for( $i = 0, $n = count($rows); $i < $n; $i++ ) {

			$coupon_str = '';
			if( strlen($rows[$i]['coupon_str']) ) {
				$coupon_str = explode(';;', $rows[$i]['coupon_str']);
				$coupon_str = $coupon_str[0].': '.($coupon_str[2] == 2 ? cleverdine::printPriceCurrencySymb($coupon_str[1], $curr_symb, $symb_pos) : $coupon_str[1].'%');
			}
			
			$_app = array( 
				$rows[$i]['id'],
				$rows[$i]['sid'],
				date($date_format.' '.$time_format, $rows[$i]['checkin_ts']),
				$rows[$i]['tname'],
				cleverdine::printPriceCurrencySymb($rows[$i]['bill_value'], $curr_symb, $symb_pos, true),
				$rows[$i]['people'],
				$coupon_str,
				strtoupper(JText::_('VRRESERVATIONSTATUS'.strtoupper($rows[$i]['status']))),
				$rows[$i]['notes']
			);
			
			$cfields = json_decode($rows[$i]['custom_f'], true);

			foreach( $all_cf as $k => $v ) {
				$str = '';

				if( !empty($cfields[$k]) ) {
					$str = $cfields[$k];
				}

				$_app[] = $str;
			}
			
			$_app[] = ''; // ITEMS
			
			$csv[] = $_app;
		}
		
		return $csv;
		
	}
	
	protected function fetchTakeAwayArray() {
		$dbo = JFactory::getDbo();
		
		$date_format = cleverdine::getDateFormat(true);
		$time_format = cleverdine::getTimeFormat(true);
		
		$curr_symb = cleverdine::getCurrencySymb(true);
		$symb_pos = cleverdine::getCurrencySymbPosition(true);
		
		$where_cl = "";
		if( count($this->ids) > 0 ) {
			for( $i = 0; $i < count($this->ids)-1; $i++ ) {
				$where_cl .= "`r`.`id`=" . $this->ids[$i] . " OR ";
			}
			$where_cl .= "`r`.`id`=" . $this->ids[$i];
		} else {
			$where_cl = $this->from."<=`r`.`checkin_ts` AND `r`.`checkin_ts`<=".$this->to;
		}
		
		// get reservations rows
		$q = "SELECT `r`.`id`, `r`.`sid`, `r`.`checkin_ts`, `r`.`total_to_pay`, `r`.`custom_f`, `r`.`delivery_service`, `r`.`coupon_str`, `r`.`status`, `r`.`notes`,
		`a`.`quantity` AS `quantity`, `e`.`name` AS `ename`, `o`.`name` AS `oname`, `e`.`price` AS `eprice`, `o`.`inc_price` AS `oprice` 
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `a` ON `r`.`id` = `a`.`id_res` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `e`.`id` = `a`.`id_product` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `o`.`id` = `a`.`id_product_option` WHERE $where_cl;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			return array();
		}
		
		$rows = $dbo->loadAssocList();
		
		// get all custom fields
		$all_cf = array();

		$q = "SELECT `name`
		FROM `#__cleverdine_custfields` 
		WHERE `group`=1 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0) 
		ORDER BY `ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			foreach( $dbo->loadAssocList() as $app ) {
				// the key is the original name
				// tha val is the translated name
				$all_cf[$app['name']] = JText::_($app['name']);
			}
		}
		
		// compose table header
		$header = array( 
			JText::_('VRMANAGETKRES1'), 	// order number
			JText::_('VRMANAGETKRES2'), 	// order key
			JText::_('VRMANAGETKRES3'), 	// checkin
			JText::_('VRMANAGETKRES8'), 	// total to pay
			JText::_('VRMANAGETKRES4'), 	// delivery service
			JText::_('VRMANAGETKRES7'), 	// coupon
			JText::_('VRMANAGETKRES9'), 	// status
			JText::_('VRMANAGETKRESTITLE4') // notes
		);
		
		// push all custom fields in header
		foreach( $all_cf as $k => $v ) {
			$header[] = JText::_($v);
		}
		
		$header[] = JText::_('VRMANAGETKRES22'); // items
		
		// fetch rows array
		$csv = array($header); // first line of CSV is always the header
		
		$_last_id = -1;

		for( $i = 0; $i < count($rows); $i++ ) {

			if( $_last_id != $rows[$i]['id'] ) {
				
				$coupon_str = '';
				if( strlen($rows[$i]['coupon_str']) ) {
					$coupon_str = explode(";;", $rows[$i]['coupon_str']);
					$coupon_str = $coupon_str[0].": ".($coupon_str[2] == 2 ? cleverdine::printPriceCurrencySymb($coupon_str[1], $curr_symb, $symb_pos) : $coupon_str[1]."%");
				}
				
				$_app = array( 
					$rows[$i]['id'], 
					$rows[$i]['sid'], 
					date($date_format.' '.$time_format, $rows[$i]['checkin_ts']), 
					cleverdine::printPriceCurrencySymb($rows[$i]['total_to_pay'], $curr_symb, $symb_pos, true), 
					JText::_(($rows[$i]['delivery_service'] == 1)?'VRMANAGETKRES14':'VRMANAGETKRES15'),
					$coupon_str,
					strtoupper(JText::_('VRRESERVATIONSTATUS'.strtoupper($rows[$i]['status']))),
					$rows[$i]['notes']
				);
				
				$cfields = json_decode($rows[$i]['custom_f'], true);

				foreach( $all_cf as $k => $v ) {
					$str = '';

					if( !empty($cfields[$k]) ) {
						$str = $cfields[$k];
					}

					$_app[] = $str;
				}
				
				$_app[] = ''; // ITEMS
				
				$csv[] = $_app;
			} 
			
			if( $_last_id == $rows[$i]['id'] || !empty($rows[$i]['ename']) ) {

				$items = &$csv[count($csv)-1][count($csv[count($csv)-1])-1];

				$_str = '';
				if( strlen($items) > 0 ) {
					$_str .= ', ';
				}

				$_str .= $rows[$i]['ename'].((!empty($rows[$i]['oname'])) ? ' - '.$rows[$i]['oname'] : '').' x'.$rows[$i]['quantity'].' '.
					cleverdine::printPriceCurrencySymb(($rows[$i]['eprice']+$rows[$i]['oprice'])*$rows[$i]['quantity'], $curr_symb, $symb_pos);
					
				$items .= $_str;
			}
			
			$_last_id = $rows[$i]['id'];
			
		}
		
		return $csv;
		
	}
	
	public function export($csv = array(), $file_name = '') {
		//header("Content-Type: application/octet-stream;"); 
		//header("Cache-Control: no-store, no-cache");
		header('Content-Type: text/csv');
		//header('Content-Encoding: UTF-8');
		//header('Content-type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment;filename='.$file_name);
		//echo "\xEF\xBB\xBF"; // UTF-8 BOM for correct encoding on excel
		
		$f = fopen( 'php://output', "w" );
		foreach( $csv as $fields ) {
			fputcsv( $f, $fields );
		}
		
		fclose( $f );
		
		exit;
	}
	
}

?>