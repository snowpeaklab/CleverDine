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

class GetOrderDetails extends EventAPIs
{
	protected function doAction(array $args, ResponseAPIs &$response)
	{
		$input = JFactory::getApplication()->input;

		$oid = $input->getUint('id', 0);
		$tid = $input->getUint('type', 0);

		$order_details = null;

		if ($tid == 0) {

			$order_details = cleverdine::fetchOrderDetails($oid);

			if ($order_details) {
				$tmpl = cleverdine::loadAdminEmailTemplate($order_details);
				$order_details['template'] = cleverdine::parseAdminEmailTemplate($tmpl, $order_details);

				$response->setContent("Restaurant reservation [$oid] : ".$order_details['sid']."\n");
			} else {
				$response->setContent("Restaurant reservation [$oid] not found\n");
			}

		} else {

			$order_details = cleverdine::fetchTakeAwayOrderDetails($oid);

			if ($order_details) {
				$tmpl = cleverdine::loadTakeAwayAdminEmailTemplate();
				$order_details['template'] = cleverdine::parseTakeAwayAdminEmailTemplate($tmpl, $order_details);

				$response->setContent("Take-Away order [$oid] : ".$order_details['sid']."\n");
			} else {
				$response->setContent("Take-Away order [$oid] not found\n");
			}

		}

		$response->setStatus(1);

		// return to client always success

		$obj = new stdClass;
		$obj->status = 1;
		$obj->orderDetails = $order_details;

		echo json_encode($obj);
	}

	// parent override
	public function getDescription()
	{
		$html = 'Retrieve the details of a given restaurant reservation or take-away order.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=get_order_details<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.<br />';
		$html .= 'id 		(int) 		The ID of the order/reservation.<br />';
		$html .= 'type 		(bool) 		Specify 1 for take-away, otherwise 0 for restaurant.';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Order Details URL</h3>';
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-username" placeholder="Username" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-password" placeholder="Password" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-oid" placeholder="Order ID" size="12" style="margin-right: 5px;"/>';
		$html .= '<select id="plg-type">';
		$html .= '<option value="0">Restaurant</option>';
		$html .= '<option value="1">Take-Away</option>';
		$html .= '</select>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 1,\n";
		$html .= "   orderDetails: {\n";
		$html .= "      id: 90,\n";
		$html .= "      sid: \"ABCD1234EFGH5678\",\n";
		$html .= "      purchaser_mail: \"mail@domain.com\",\n";
		$html .= "      // a lot of other fields\n";
		$html .= "      template: \"the HTML e-mail template of the order\"\n";
		$html .= "   }\n";
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
				jQuery("#plg-username, #plg-password, #plg-oid, #plg-type").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=get_order_details&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val()+"&id="+jQuery("#plg-oid").val()+"&type="+jQuery("#plg-type").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a>");
				});

				jQuery("#plg-type").select2({
					minimumResultsForSearch: -1,
					allowClear: false,
					width: 150
				});

				jQuery("#plg-username").trigger("change");
			});
		</script>';

		return $html;
	}

}
