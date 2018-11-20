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

class TableAvailable extends EventAPIs
{

	protected function doAction(array $args, ResponseAPIs &$response)
	{
		// response for admin
		$response->setStatus(1);

		$input = JFactory::getApplication()->input;

		if ($args === null || count(array_keys($args)) == 0) {

			// get booking args
			$args = array();
			$args['date'] 		= $input->getString('date');
			$args['hourmin'] 	= $input->getString('hourmin');
			$args['people'] 	= $input->getUint('people');
			$args['id_table'] 	= $input->getInt('id_table', -1);

		}

		// otherwise get them from method params

		// validate booking args
		$v = cleverdine::isRequestReservationValid($args);
		if ($v > 0) {
			$response->setStatus(0);
			$response->setContent(JText::_(cleverdine::getResponseFromReservationRequest($v)));
			return;
		}

		list($args['hour'], $args['min']) = explode(':', $args['hourmin']);

		$args['ts'] = cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']);

		// response for client
		$obj = new stdClass;
		$obj->status = 0;

		// check for restaurant availability
		if (!cleverdine::isReservationsAllowedOn($args['ts'])) {
			// restaurant is blocked for today
			$obj->message = JText::_('VRNOMORERESTODAY');
			$response->setContent($obj->message);

			echo json_encode($obj);
			return;
		}

		// check for special days availability
		$closed = false;
		$ignore_cd = false;

		$special_days = cleverdine::getSpecialDaysOnDate($args, 1);
		
		if (!cleverdine::isContinuosOpeningTime()) {
			$shifts = cleverdine::getWorkingShifts(1);
			
			if ($special_days != -1 && count($special_days) > 0) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 1 );
			}
			
			$closed = true;
			$hour_full = $args['hour']*60+$args['min'];
			for ($i = 0; $i < count($shifts) && $closed; $i++) {
				$closed = !( $shifts[$i]['from'] <= $hour_full && $hour_full <= $shifts[$i]['to'] );
			}
		} 
		
		if ($special_days != -1) {
			
			if (count( $special_days ) == 0) {
				//$ignore_cd = true;
			} else {
				for ($i = 0, $n = count($special_days); $i < $n && !$ignore_cd; $i++) {
					$ignore_cd = $special_days[$i]['ignoreclosingdays'];
				}

				if ($special_days[0]['peopleallowed'] != -1 && cleverdine::getPeopleAt($args['ts'])+$args['people'] > $special_days[0]['peopleallowed']) {
					// people limit reached
					$obj->message = JText::_('VRRESNOSINGTABLEFOUND');
					$response->setContent($obj->message);

					echo json_encode($obj);
					return;
				}
			}
		}

		// check for closing day
		if (!$ignore_cd && !$closed && cleverdine::isClosingDay($args)) {
			// the restaurant is closed and there is no special days able to override it
			$obj->message = JText::_('VRSEARCHDAYCLOSED');
			$response->setContent($obj->message);

			echo json_encode($obj);
			return;
		}

		$is_available = false;

		// validate table availability
		if ($args['id_table'] > 0) {
			// check availablity for the specified table
			$is_available = $this->isSpecificTableAvailable($args);
		} else {
			// get a random free table
			$args['id_table'] = $this->isRandomTableAvailable($args);

			$is_available = $args['id_table'] !== false;
		}
		
		if ($is_available) {
			$obj->status 	= 1;
			$obj->table 	= $args['id_table'];
		} else {
			$obj->message = JText::_('VRTNOTAVAILABLE');	
		}
		
		echo json_encode($obj);
	}

	// parent override
	public function getTitle()
	{
		return 'Table Availability';
	}

	// parent override
	public function getDescription()
	{
		$config = UIFactory::getConfig();

		$html = 'Check if a table is available for a certain date, time and people.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=table_available<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.<br />';
		$html .= 'date 		(string) 	The date of the reservation in '.$config->get('dateformat').' format.<br />';
		$html .= 'hourmin 	(string) 	The time of the reservation in H:m format.<br />';
		$html .= 'people 		(int) 		The party size of the reservation between '.$config->getUint('minimumpeople').' and '.$config->getUint('maximumpeople').'.<br />';
		$html .= 'id_table 	(int) 		The ID of the table.
				Specify -1 to book the first available table.<br />';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Table Availability URL</h3>';
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-username" placeholder="Username" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-password" placeholder="Password" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-date" placeholder="Date ('.$config->get('dateformat').')" size="16" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-time" placeholder="Time (H:m)" size="8" style="margin-right: 5px;"/>';
		$html .= '<select id="plg-people" style="margin-right: 5px;">';
		for ($i = $config->getUint('minimumpeople'); $i <= $config->getUint('maximumpeople'); $i++) {
			$html .= '<option value="'.$i.'">'.$i.' people</option>';
		}
		$html .= '</select>';
		$html .= '<select id="plg-table">';
		$html .= '<option></option>';
		foreach ($this->getRooms() as $room) {
			$html .= '<optgroup label="'.$room['name'].'">';

			foreach ($room['tables'] as $table) {
				$html .= '<option value="'.$table['id'].'">'.$table['name'].'</option>';
			}

			$html .= '</optgroup>';
		}
		$html .= '</select>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 1, // table is available\n";
		$html .= "   table: 3 // the ID of the table\n";
		$html .= '}';
		$html .= '</pre><br />';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 0, // table is NOT available\n";
		$html .= "   message: \"Not Available\"\n";
		$html .= '}';
		$html .= '</pre><br />';
		$html .= '<h3>Failure Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   errcode: 200,\n";
		$html .= "   error: \"The reason of the error\"\n";
		$html .= '}';
		$html .= '</pre>';

		$html .= '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#plg-username, #plg-password, #plg-date, #plg-time, #plg-people, #plg-table").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=table_available&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val()+"&date="+jQuery("#plg-date").val()+"&hourmin="+jQuery("#plg-time").val()+"&people="+jQuery("#plg-people").val()+"&id_table="+jQuery("#plg-table").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a>");
				});

				jQuery("#plg-people").select2({
					minimumResultsForSearch: -1,
					allowClear: false,
					width: 150
				});

				jQuery("#plg-table").select2({
					minimumResultsForSearch: -1,
					placeholder: "- first available -",
					allowClear: true,
					width: 150
				});

				jQuery("#plg-username").trigger("change");
			});
		</script>';

		return $html;
	}

	private function getRooms()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('`t`.`id` AS `tid`, `t`.`name` AS `tname`, `r`.`id` AS `rid`, `r`.`name` AS `rname`')
			->from($dbo->quoteName('#__cleverdine_table', 't'))
			->join('LEFT', $dbo->quoteName('#__cleverdine_room', 'r').' ON `r`.`id`=`t`.`id_room`')
			->order('`r`.`ordering` ASC, `t`.`name` ASC');

		$dbo->setQuery($q);
		$dbo->execute();

		if (!$dbo->getNumRows()) {
			return array();
		}

		$rooms = array();

		$last_id = -1;

		foreach ($dbo->loadAssocList() as $row) {

			if ($last_id != $row['rid']) {
				$rooms[] = array(
					'id' 		=> $row['rid'],
					'name' 		=> $row['rname'],
					'tables' 	=> array()
				);

				$last_id = $row['rid'];
			}

			$rooms[count($rooms)-1]['tables'][] = array(
				'id' 	=> $row['tid'],
				'name' 	=> $row['tname']
			);

		}

		return $rooms;
	}

	private function isSpecificTableAvailable($args)
	{
		$dbo = JFactory::getDbo();

		// fit query requirements
		$args['table'] = $args['id_table'];

		$q = cleverdine::getQueryTableJustReserved($args);
		$dbo->setQuery($q);
		$dbo->execute();
		
		return ($dbo->getNumRows() > 0);
	}

	private function isRandomTableAvailable($args)
	{
		$dbo = JFactory::getDbo();

		$rows = array();
		$rows_multi = array();	
		
		$q = cleverdine::getQueryFindTable($args);
		$dbo->setQuery($q);
		$dbo->execute();
		
		// check at least one single table or empty shared tables
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			return $rows[0]['tid'];
		} 
		
		// get all shared tables with at least a reservation
		$q = cleverdine::getQueryFindTableMultiRes($args);
		$dbo->setQuery($q);
		$dbo->execute();
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			return $rows[0]['tid'];
		}
		
		return false;
	}

}
