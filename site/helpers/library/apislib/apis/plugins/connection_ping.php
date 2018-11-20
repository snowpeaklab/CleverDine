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

class ConnectionPing extends EventAPIs
{
	protected function doAction(array $args, ResponseAPIs &$response)
	{
		// connection ping done correctly

		$response->setStatus(1);	

		$obj = new stdClass;
		$obj->status = 1;

		echo json_encode($obj);
	}

	// parent override
	public function alwaysAllowed()
	{
		return true;
	}

	// parent override
	public function getDescription()
	{
		$html = 'This plugin is needed to verify the connection between the application client and the server.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=connection_ping<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Ping URL</h3>';
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-username" placeholder="Username" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-password" placeholder="Password" size="32" style="margin-right: 5px;"/>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (JSON)</h3>';
		$html .= '<pre>';
		$html .= "{\n";
		$html .= "   status: 1\n";
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
				jQuery("#plg-username, #plg-password").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=connection_ping&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a>");
				});

				jQuery("#plg-username").trigger("change");
			});
		</script>';

		return $html;
	}

}
