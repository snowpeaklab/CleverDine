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

class GetOrdersList extends EventAPIs
{
	protected function doAction(array $args, ResponseAPIs &$response)
	{
		$input = JFactory::getApplication()->input;

		$last_ids = $input->get('last_id', array(), 'int');
		if (count($last_ids) != 2) {
			$last_ids = array(0, 0);
		}

		$obj = new stdClass;
		$obj->status = 1;
		$obj->orders = array();

		$dbo = JFactory::getDbo();

		// get restaurant reservations

		$q = "SELECT `id`, `purchaser_nominative`, `purchaser_mail`, `created_on`, 0 AS `group`
		FROM `#__cleverdine_reservation`
		WHERE `status`='CONFIRMED' AND `id`>".$last_ids[0]."
		ORDER BY `id` DESC;";

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows()) {
			$obj->orders = $dbo->loadAssocList();
		}

		if ($res_count = count($obj->orders)) {
			$response->appendContent("Restaurant reservations retrieved: #$res_count\n");
		}

		// get takeaway orders

		$q = "SELECT `id`, `purchaser_nominative`, `purchaser_mail`, `created_on`, 1 AS `group`
		FROM `#__cleverdine_takeaway_reservation`
		WHERE `status`='CONFIRMED' AND `id`>".$last_ids[1]."
		ORDER BY `id` DESC;";

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows()) {
			$obj->orders = array_merge($obj->orders, $dbo->loadAssocList());
		}

		if($res_count = count($obj->orders)-$res_count) {
			$response->setContent("Take-Away orders retrieved: #$res_count\n");
		}

		// sort orders by creation date DESC

		for ($i = 0; $i < count($obj->orders)-1; $i++) {
			for ($j = ($i+1); $j < count($obj->orders); $j++) {
				if ($obj->orders[$i]['created_on'] < $obj->orders[$j]['created_on']) {
					$app = $obj->orders[$i];
					$obj->orders[$i] = $obj->orders[$j];
					$obj->orders[$j] = $app;
				}
			}
		}

		$response->setStatus(1);

		echo json_encode($obj);
	}

	// parent override
	public function getDescription()
	{
		$html = 'Download the list of all the take-away orders and restaurant reservations.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=get_orders_list<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.<br />';
		$html .= 'last_id 	(array) 	Specify an ID for both the sections to download all the orders after those IDs. 
				The first index of the array represents the initial ID (excluded) for the restaurant reservation. 
				The second index of the array represents the initial ID (excluded) for the take-away orders. 
				The example query string "&last_id[]=10&last_id[]=16" means you will download all the restaurant 
				reservations with ID higher than 10 and all the take-away orders with ID higher than 16.<br />';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Orders List URL</h3>';
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-username" placeholder="Username" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-password" placeholder="Password" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-lastid-0" placeholder="Restaurant ID" size="12" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-lastid-1" placeholder="Take-Away ID" size="12" style="margin-right: 5px;"/>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 1,\n";
		$html .= "   orders: [\n";
		$html .= "      {\n";
		$html .= "         id: 25,\n";
		$html .= "         sid: \"GD83GDK83HSMZ0H9\",\n";
		$html .= "         purchaser_nominative: \"John Smith\",\n";
		$html .= "         purchaser_mail: \"mail.one@domain.com\",\n";
		$html .= "         created_on: 1478188531, // UNIX timestamp\n";
		$html .= "         group: 0 // restaurant reservation\n";
		$html .= "      },\n";
		$html .= "      {\n";
		$html .= "         id: 39,\n";
		$html .= "         sid: \"LAJS92YSBW0SHW05\",\n";
		$html .= "         purchaser_nominative: \"Barney Black\",\n";
		$html .= "         purchaser_mail: \"mail.two@domain.com\",\n";
		$html .= "         created_on: 1478188644, // UNIX timestamp\n";
		$html .= "         group: 1 // take-away order\n";
		$html .= "      }\n";
		$html .= "   ]\n";
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
				jQuery("#plg-username, #plg-password, #plg-oid, #plg-lastid-0, #plg-lastid-1").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=get_orders_list&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val()+"&last_id[]="+jQuery("#plg-lastid-0").val()+"&last_id[]="+jQuery("#plg-lastid-1").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a>");
				});

				jQuery("#plg-username").trigger("change");
			});
		</script>';

		return $html;
	}

}
