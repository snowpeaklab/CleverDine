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
	
	const ICS_DATETIME_FORMAT = 'Ymd\THis\Z';
	const ICS_DATETIME_FORMAT_NO_TZ = 'Ymd\THis';
	
	private $from;
	private $to;
	private $ids;
	
	private $head;
	
	public function __construct($from_ts, $to_ts, $ids = array()) {
		$this->from = $from_ts;
		$this->to = $to_ts;
		$this->ids = $ids;
		
		$this->setHeader();
	}
	
	private function setHeader($v="2.0", $calscale="GREGORIAN") {
		$this->head  = "VERSION:".$v."\n";
		$this->head .= "PRODID:-//e4j//cleverdine ".cleverdine_SOFTWARE_VERSION."//EN\n";
		$this->head .= "CALSCALE:".$calscale."\n";
		$this->head .= "X-WR-TIMEZONE:".date_default_timezone_get()."\n";
	}
	
	/*
	 * 0 = restaurant
	 * 1 = takeaway
	 */
	public function getString($type = 0) {
		$dbo = JFactory::getDbo();
		
		$table_px = "";
		if( $type == 1 ) {
			$table_px = "takeaway_";
		}
		
		
		$where_cl = "";
		if( count($this->ids) > 0 ) {
			for( $i = 0; $i < count($this->ids)-1; $i++ ) {
				$where_cl .= "`id`=" . $this->ids[$i] . " OR ";
			}
			$where_cl .= "`id`=" . $this->ids[$i];
		} else {
			$where_cl = $this->from."<=`checkin_ts` AND `checkin_ts`<=".$this->to;
		}

		$ics  = "BEGIN:VCALENDAR\n";
		$ics .= $this->head;
		
		$q = "SELECT * FROM `#__cleverdine_".$table_px."reservation` WHERE `status`='CONFIRMED' AND ".$where_cl.";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			
			$ics .= $this->fetchArray($rows, $type);
		}

		$ics .= "END:VCALENDAR\n";
		
		return $ics;
	}
	
	protected function fetchArray($rows, $type = 0) {
		
		$default_tz = date_default_timezone_get();
		
		$str = "";
		$jtext_summary_key = "";
		
		$time_stay = 0;
		if( $type == 0 ) {
			$time_stay = cleverdine::getAverageTimeStay(true);
			$jtext_summary_key = "VREXPORTSUMMARY";
		} else {
			$time_stay = cleverdine::getTakeAwayMinuteInterval(true);
			$jtext_summary_key = "VRTKEXPORTSUMMARY";
		}
		
		$address = cleverdine::getRestaurantName(true);
		
		$time_format = cleverdine::getTimeFormat(true);
		
		if( !empty($rows[0]['checkin_ts']) ) {
			foreach( $rows as $r ) {

				if( $type == 0 ) {
					$summary = $r['people'];
				} else {
					$summary = (!empty($r['purchaser_nominative']) ? $r['purchaser_nominative'] : $r['purchaser_mail']);
				}

				if (empty($r['stay_time'])) {
					$r['stay_time'] = $time_stay;
				}

				$r['stay_time'] *= 60;

				$uri = JUri::root() . "index.php?option=com_cleverdine&view=order&ordnum=" . $r['id'] . "&ordkey=" . $r['sid'] . '&ordtype='.$type;
				
				$cf = json_decode($r['custom_f']);
				$description = "";
				foreach( $cf as $k => $v ) {
					if( !empty($v) ) {
						$description .= JText::_($k).": ".$v."\\n";
					}
				}
				
				$str .= "BEGIN:VEVENT\n";
				$str .= "DTEND;TZID=".$default_tz.":".$this->tsToCal($r['checkin_ts']+$r['stay_time'], false)."\n";
				$str .= "UID:".$r['id']."-".$r['sid']."\n";
				$str .= "DTSTAMP:".$this->tsToCal(time())."\n";
				$str .= "LOCATION:".$this->escape($address)."\n";
				$str .= ((strlen($description) > 0 ) ? "DESCRIPTION:".$this->escape($description)."\n" : "");
				$str .= "URL;VALUE=URI:".$this->escape($uri)."\n";
				$str .= "SUMMARY:".JText::sprintf($jtext_summary_key, $this->escape($summary))."\n";
				$str .= "DTSTART;TZID=".$default_tz.":".$this->tsToCal($r['checkin_ts'], false)."\n";
				$str .= "END:VEVENT\n";
			}
		}
		
		return $str;
	}
	
	public function export($ics = '', $file_name = '') {
		header("Content-Type: application/octet-stream; "); 
		header("Content-Disposition: attachment; filename=\"$file_name\"");
		header("Cache-Control: no-store, no-cache");
		
		$f = fopen('php://output', "w");
		fwrite( $f, $ics );
		fclose( $f );
		
		exit;
	}

	public function renderBrowser($ics = '', $file_name = '') {
		header("Content-Type: text/calendar; charset=utf-8");
		header("Content-Disposition: attachment; filename=\"".$file_name."\"");
		echo $ics;
	}
	
	protected function tsToCal($_ts, $use_time_zone = true) {
		$df = ($use_time_zone ? self::ICS_DATETIME_FORMAT : self::ICS_DATETIME_FORMAT_NO_TZ);
		return date($df, $_ts);
	}
	 
	protected function escape($_str) {
		return preg_replace('/([\,;])/','\\\$1', $_str);
	}
	
}

?>