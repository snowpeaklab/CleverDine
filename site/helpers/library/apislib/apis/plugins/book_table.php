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

class BookTable extends EventAPIs
{
	protected function doAction(array $args, ResponseAPIs &$response)
	{
		// response for admin
		$response->setStatus(1);

		$input = JFactory::getApplication()->input;

		// get booking args
		$args = array();
		$args['date'] 		= $input->getString('date');
		$args['hourmin'] 	= $input->getString('hourmin');
		$args['people'] 	= $input->getUint('people');
		$args['id_table'] 	= $input->getInt('id_table', -1);

		// get current framework instance
		$apis = FrameworkAPIs::getInstance();
		
		// trigger plugin to verify table availability or to get plugin error (in JSON)
		try {
			$json = $apis->dispatch('table_available', $args);
		} catch (Exception $e) {
			return new ErrorAPIs($e->getCode(), $e->getMessage());
		}

		// decode response
		$res = json_decode($json);

		if (!isset($res->status) || !$res->status || isset($res->errcode)) {

			/* we got a json like:
			{
				status: 0
			}

			or:
			{
				errcode: 500,
				error: "something wrong"
			}
			*/

			if (isset($res->status)) { 
				// if is set it means the status is 0 (not available)
				// re-emit the same JSON received
				echo $json;
				$response->setContent($res->message);
			} else {
				// otherwise display error
				$response->setStatus(0);
				$response->setContent($res->error);
			}

			return;

		} else {
			// table is available
			$args['id_table'] = $res->table;
		}

		// insert booking
		$order_id = $this->insertBooking($args);
		
		$obj = new stdClass;

		if ($order_id > 0) {
			$obj->status 	= 1;
			$obj->oid 		= $order_id;
			$obj->date 		= $args['date'];
			$obj->time 		= $args['hourmin'];
			$obj->people 	= $args['people'];
			$obj->table 	= $args['id_table'];
		} else {
			$obj->message = JText::_('VRNEWQUICKRESNOTCREATED');
		}

		echo json_encode($obj);
	}

	// parent override
	public function getTitle()
	{
		return 'Book a Table';
	}

	// parent override
	public function getDescription()
	{
		$config = UIFactory::getConfig();

		$html = 'Book a table for a certain date, time and people with a direct link.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=book_table<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.<br />';
		$html .= 'date 		(string) 	The date of the reservation in '.$config->get('dateformat').' format.<br />';
		$html .= 'hourmin 	(string) 	The time of the reservation in H:m format.<br />';
		$html .= 'people 		(int) 		The party size of the reservation between '.$config->getUint('minimumpeople').' and '.$config->getUint('maximumpeople').'.<br />';
		$html .= 'id_table 	(int) 		The ID of the table.
				Specify -1 to book the first available table.<br />';
		$html .= 'purchaser 	(array)		The details of the purchaser.
				This object accepts only the parameters: name, mail, phone, country (ISO 3166-1) and prefix (phone prefix).<br />';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Book Table URL</h3>';
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
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-pname" placeholder="Purchaser Name" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-pmail" placeholder="Purchaser E-Mail" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-pphone" placeholder="Purchaser Phone" size="16" style="margin-right: 5px;"/>';
		$html .= '<select id="plg-pcountry" style="margin-right: 5px;">';
		$html .= '<option></option>';
		foreach ($this->getCountries() as $c) {
			$html .= '<option value="'.$c['country_2_code'].'" data-prefix="'.$c['phone_prefix'].'">'.$c['country_name'].'</option>';
		}
		$html .= '</select>';
		$html .= '<input type="text" id="plg-pprefix" placeholder="Purchaser Prefix" size="16" style="margin-right: 5px;"/>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 1, // table booked successfully\n";
		$html .= "   oid: 1, // the order number\n";
		$html .= "   date: \"".date($config->get('dateformat'))."\",\n";
		$html .= "   time: \"".date($config->get('timeformat'))."\",\n";
		$html .= "   people: 4,\n";
		$html .= "   table: 3 // ID of the table booked\n";
		$html .= '}';
		$html .= '</pre><br />';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 0, // table is not available\n";
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
				jQuery("#plg-username, #plg-password, #plg-date, #plg-time, #plg-people, #plg-table, #plg-pname, #plg-pmail, #plg-pphone, #plg-pcountry, #plg-pprefix").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=book_table&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val()+"&date="+jQuery("#plg-date").val()+"&hourmin="+jQuery("#plg-time").val()+"&people="+jQuery("#plg-people").val()+"&id_table="+jQuery("#plg-table").val()+"&purchaser[name]="+jQuery("#plg-pname").val()+"&purchaser[mail]="+jQuery("#plg-pmail").val()+"&purchaser[phone]="+jQuery("#plg-pphone").val()+"&purchaser[country]="+jQuery("#plg-pcountry").val()+"&purchaser[prefix]="+jQuery("#plg-pprefix").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a><br />");
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

				jQuery("#plg-pcountry").select2({
					placeholder: "- select a country -",
					allowClear: true,
					width: 250
				});

				jQuery("#plg-pcountry").on("change", function(){
					jQuery("#plg-pprefix").val(jQuery(this).find("option:selected").data("prefix"));
					jQuery("#plg-pprefix").focus();
					jQuery("#plg-pprefix").trigger("change");
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

	private function getCountries()
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select('*')
			->from($dbo->quoteName('#__cleverdine_countries'))
			->where($dbo->quoteName('published').'=1')
			->order($dbo->quoteName('country_name'));

		$dbo->setQuery($q);
		$dbo->execute();
		
		if ($dbo->getNumRows() > 0) {
			return $dbo->loadAssocList();
		}

		return array();
	}

	private function insertBooking($args)
	{
		$dbo = JFactory::getDbo();

		$sid 		= cleverdine::generateSerialCode(16);
		$conf_key 	= cleverdine::generateSerialCode(12);

		list($args['hour'], $args['min']) = explode(':', $args['hourmin']);

		$purchaser 	= $this->getPurchaser();

		$q = $dbo->getQuery(true);

		$q->insert($dbo->quoteName('#__cleverdine_reservation'))
			->columns(array(
				$dbo->quoteName('sid'),
				$dbo->quoteName('conf_key'),
				$dbo->quoteName('id_table'),
				$dbo->quoteName('checkin_ts'),
				$dbo->quoteName('people'),
				$dbo->quoteName('purchaser_nominative'),
				$dbo->quoteName('purchaser_mail'),
				$dbo->quoteName('purchaser_phone'),
				$dbo->quoteName('purchaser_prefix'),
				$dbo->quoteName('purchaser_country'),
				$dbo->quoteName('langtag'),
				$dbo->quoteName('status'),
				$dbo->quoteName('created_on')
			))
			->values(
				$dbo->quote($sid).",".
				$dbo->quote($conf_key).",".
				$args['id_table'].",".
				cleverdine::createTimestamp($args['date'], $args['hour'], $args['min']).",".
				$args['people'].",".
				$dbo->quote($purchaser['name']).",".
				$dbo->quote($purchaser['mail']).",".
				$dbo->quote($purchaser['phone']).",".
				$dbo->quote($purchaser['prefix']).",".
				$dbo->quote($purchaser['country']).",".
				$dbo->quote(JFactory::getLanguage()->getTag()).",".
				$dbo->quote('CONFIRMED').",".
				time()
			);

		$dbo->setQuery($q);
		$dbo->execute();

		return $dbo->insertid();
	}

	private function getPurchaser()
	{
		$input = JFactory::getApplication()->input;

		$purchaser = $input->get('purchaser', array(), 'array');

		if (!isset($purchaser['name'])) {
			$purchaser['name'] = '';
		}

		if (!isset($purchaser['mail'])) {
			$purchaser['mail'] = '';
		}

		if (!isset($purchaser['phone'])) {
			$purchaser['phone'] = '';
		} else {
			$purchaser['phone'] = preg_replace('/\D/', '', $purchaser['phone']);
		}

		if (!isset($purchaser['country'])) {
			$purchaser['country'] = $this->getCountryDetails()->country_2_code;
		}

		if (!isset($purchaser['prefix'])) {
			$purchaser['prefix'] = $this->getCountryDetails($purchaser['country'])->phone_prefix;
		}

		return $purchaser;

	}

	private function getCountryDetails($code = null)
	{
		if (!isset($this->country)) {

			$dbo = JFactory::getDbo();

			if ($code === null) {

				$code = '';

				$q = $dbo->getQuery(true);

				$q->select($dbo->quoteName('choose'))
					->from($dbo->quoteName('#__cleverdine_custfields'))
					->where($dbo->quoteName('rule') . '=' . VRCustomFields::PHONE_NUMBER . ' AND ' . $dbo->quoteName('group') . '=0');

				$dbo->setQuery($q, 0, 1);
				$dbo->execute();

				if ($dbo->getNumRows()) {
					$code = $dbo->loadResult();
				}

			}

			$q = $dbo->getQuery(true);

			$q->select($dbo->quoteName('country_2_code') . ',' . $dbo->quoteName('phone_prefix'))
				->from($dbo->quoteName('#__cleverdine_countries'))
				->where($dbo->quoteName('country_2_code') . ' = ' . $dbo->quote($code) .' AND '.$dbo->quoteName('published') . ' = 1');

			$dbo->setQuery($q, 0, 1);
			$dbo->execute();

			if ($dbo->getNumRows()) {
				$this->country = $dbo->loadObject();
			} else {
				$this->country = new stdClass;
				$this->country->phone_prefix = 0;
				$this->country->country_2_code = '';
			}

		}

		return $this->country;
	}

}
