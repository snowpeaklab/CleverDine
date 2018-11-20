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

class IcsSync extends EventAPIs
{
	protected function doAction(array $args, ResponseAPIs &$response)
	{
		$response->setStatus(1);

		$input = JFactory::getApplication()->input;

		$type = $input->get('type', 0, 'uint');

		// get date bounds

		$dstart = $dend = time();

		$dbo = JFactory::getDbo();

		$db_table = "#__cleverdine_".($type == 0 ? '' : 'takeaway_')."reservation";

		$q = $dbo->getQuery(true);

		$q->select('MIN(`checkin_ts`) AS `min`, MAX(`checkin_ts`) AS `max`')
			->from($dbo->qn($db_table))
			->where($dbo->qn('status') . ' = ' . $dbo->q('CONFIRMED'));

		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {
			$arr = $dbo->loadAssoc();
			
			$dstart = (!empty($arr['min']) ? (int)$arr['min'] : $dstart);
			$dend 	= (!empty($arr['max']) ? (int)$arr['max'] : $dend);
		}
		
		// define ICS filename
		
		$filename = date('Y-m-d-H-i-s');
		$export_class = 'ics';

		// require ICS handler
		
		$file_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.$export_class.'.php';
		if (!file_exists($file_path)) {
			// handler not found : set error and return
			$response->setStatus(0)->setContent('ICS handler not found!');
			return;
		}
		
		require_once($file_path);

		// build exporter class
		$vik_exp = new VikExporter( $dstart, $dend, array() );

		// get parsed ICS string
		$str = $vik_exp->getString($type);

		// push ICS in the header of the page
		$vik_exp->renderBrowser($str, $filename.'.'.$export_class);
	}

	// parent override
	public function getTitle()
	{
		return 'ICS Sync';
	}

	// parent override
	public function getDescription()
	{
		$html = 'Sync your calendars/applications with all your existing orders and reservations.<br />';
		$html .= '<h3>Usage:</h3>';
		$html .= '<pre>';
		$html .= '<strong>End-Point URL</strong><br />';
		$html .= JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=ics_sync<br /><br />';
		$html .= '<strong>Params</strong><br />';
		$html .= 'username 	(string) 	The username of the application.<br />';
		$html .= 'password 	(string) 	The password of the application.<br />';
		$html .= 'type 		(bool) 		Specify 1 to sync take-away orders, 
				otherwise 0 for restaurant reservations.';
		$html .= '</pre><br />';
		$html .= '<h3>Generate Sync URL</h3>';
		$html .= '<div style="margin-bottom: 10px;">';
		$html .= '<input type="text" id="plg-username" placeholder="Username" size="32" style="margin-right: 5px;"/>';
		$html .= '<input type="text" id="plg-password" placeholder="Password" size="32" style="margin-right: 5px;"/>';
		$html .= '<select id="plg-type">';
		$html .= '<option value="0">Restaurant</option>';
		$html .= '<option value="1">Take-Away</option>';
		$html .= '</select>';
		$html .= '</div>';
		$html .= '<pre id="plgurl">';
		$html .= '</pre><br />';
		$html .= '<h3>Success Response (HTTP Header)</h3>';
		$html .= '<pre>';
		$html .= 'The browser will download automatically the ICS file.';
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
				jQuery("#plg-username, #plg-password, #plg-type").on("change", function(){
					var clean = "'.JUri::root().'index.php?option=com_cleverdine&tmpl=component&task=apis&event=ics_sync&username="+jQuery("#plg-username").val()+"&password="+jQuery("#plg-password").val()+"&type="+jQuery("#plg-type").val();

					var url = encodeURI(clean);

					jQuery("#plgurl").html("<a href=\""+url+"\" target=\"_blank\">"+clean+"</a>");
				});

				jQuery("#plg-username").trigger("change");

				jQuery("#plg-type").select2({
					minimumResultsForSearch: -1,
					allowClear: false,
					width: 150
				});
			});
		</script>';

		return $html;
	}

}
