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

JHtml::_('behavior.modal');

/**
 * cleverdine component helper.
 */
abstract class RestaurantsHelper {

	public static function printMenu() {

		self::load_css_js();
		self::load_font_awesome(true);

		$task = self::getParentTask();

		$auth = self::getAuthorisations();

		$base_href = 'index.php?option=com_cleverdine';

		require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."leftboardmenu.php");

		$lbm_compressed = self::isLeftBoardMenuCompressed();

		$board = new LeftBoardMenu();
		$board->compress($lbm_compressed);

		///// DASHBOARD /////

		if( $auth['dashboard']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUDASHBOARD'), $base_href, $task=='cleverdine' );

			$board->push($parent->setCustom('dashboard'));
		}

		///// RESTAURANT /////

		if( $auth['restaurant']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUTITLEHEADER1') );

			if( $auth['restaurant']['actions']['rooms'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUROOMS'), $base_href."&task=rooms", $task=='rooms' );
				$parent->addChild($item->setCustom('home'));
			}
			if( $auth['restaurant']['actions']['tables'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTABLES'), $base_href."&task=tables", $task=='tables' );
				$parent->addChild($item->setCustom('th'));
			}
			if( $auth['restaurant']['actions']['maps'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUVIEWMAPS'), $base_href."&task=maps", $task=='maps' );
				$parent->addChild($item->setCustom('map'));
			}
			if( $auth['restaurant']['actions']['products'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUMENUSPRODUCTS'), $base_href."&task=menusproducts", $task=='menusproducts' );
				$parent->addChild($item->setCustom('glass'));
			}
			if( $auth['restaurant']['actions']['menus'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUMENUS'), $base_href."&task=menus", $task=='menus' );
				$parent->addChild($item->setCustom('bars'));
			}
			if( $auth['restaurant']['actions']['reservations'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENURESERVATIONS'), $base_href."&task=reservations", $task=='reservations' );
				$parent->addChild($item->setCustom('calendar-check-o'));
			}

			$board->push($parent->setCustom('cutlery'));
		}

		///// OPERATIONS /////

		if( $auth['operations']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUTITLEHEADER2') );

			if( $auth['operations']['actions']['shifts'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUSHIFTS'), $base_href."&task=shifts", $task=='shifts' );
				$parent->addChild($item->setCustom('clock-o'));
			}
			if( $auth['operations']['actions']['specialdays'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUSPECIALDAYS'), $base_href."&task=specialdays", $task=='specialdays' );
				$parent->addChild($item->setCustom('calendar'));
			}
			if( $auth['operations']['actions']['operators'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUOPERATORS'), $base_href."&task=operators", $task=='operators' );
				$parent->addChild($item->setCustom('user-secret'));
			}

			$board->push($parent->setCustom('wrench'));
		}

		///// BOOKING /////

		if( $auth['booking']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUTITLEHEADER3') );

			if( $auth['booking']['actions']['customers'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUCUSTOMERS'), $base_href."&task=customers", $task=='customers' );
				$parent->addChild($item->setCustom('user'));
			}
			if( $auth['booking']['actions']['reviews'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUREVIEWS'), $base_href."&task=revs", $task=='revs' );
				$parent->addChild($item->setCustom('star'));	
			}
			if( $auth['booking']['actions']['coupons'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUCOUPONS'), $base_href."&task=coupons", $task=='coupons' );
				$parent->addChild($item->setCustom('gift'));	
			}
			if( $auth['booking']['actions']['invoices'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUINVOICES'), $base_href."&task=invoices", $task=='invoices' );
				$parent->addChild($item->setCustom('file-text-o'));	
			}

			$board->push($parent->setCustom('bookmark'));
		}

		///// TAKEAWAY /////

		if( $auth['takeaway']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUTITLEHEADER5') );

			if( $auth['takeaway']['actions']['tkmenus'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTAKEAWAYMENUS'), $base_href."&task=tkmenus", $task=='tkmenus' );
				$parent->addChild($item->setCustom('bars'));
			}
			if( $auth['takeaway']['actions']['tktoppings'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTAKEAWAYTOPPINGS'), $base_href."&task=tktoppings", $task=='tktoppings' );
				$parent->addChild($item->setCustom('beer'));
			}
			if( $auth['takeaway']['actions']['tkdeals'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTAKEAWAYDEALS'), $base_href."&task=tkdeals", $task=='tkdeals' );
				$parent->addChild($item->setCustom('ticket'));
			}
			if( $auth['takeaway']['actions']['tkareas'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTAKEAWAYDELIVERYAREAS'), $base_href."&task=tkareas", $task=='tkareas' );
				$parent->addChild($item->setCustom('map-marker'));
			}
			if( $auth['takeaway']['actions']['tkorders'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUTAKEAWAYRESERVATIONS'), $base_href."&task=tkreservations", $task=='tkreservations' );
				$parent->addChild($item->setCustom('shopping-bag'));
			}

			$board->push($parent->setCustom('shopping-basket'));
		}

		///// GLOBAL /////

		if( $auth['global']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUTITLEHEADER4') );

			if( $auth['global']['actions']['custfields'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUCUSTOMFIELDS'), $base_href."&task=customf", $task=='customf' );
				$parent->addChild($item->setCustom('filter'));
			}
			if( $auth['global']['actions']['payments'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUPAYMENTS'), $base_href."&task=payments", $task=='payments' );
				$parent->addChild($item->setCustom('credit-card-alt'));
			}
			if( $auth['global']['actions']['media'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENUMEDIA'), $base_href."&task=media", $task=='media' );
				$parent->addChild($item->setCustom('camera'));
			}
			if( $auth['global']['actions']['rescodes'] ) {
				$item = new LeftBoardMenuItem( JText::_('VRMENURESCODES'), $base_href."&task=rescodes", $task=='rescodes' );
				$parent->addChild($item->setCustom('tags'));
			}

			$board->push($parent->setCustom('globe'));
		}

		///// CONFIGURATION /////

		if( $auth['configuration']['numactives'] > 0 ) {
			$parent = new LeftBoardMenuSeparator( JText::_('VRMENUCONFIG'), $base_href."&task=editconfig", $task=='editconfig' );

			$board->push($parent->setCustom('cogs'));
		}

		// CUSTOM
		$line_separator = new LeftBoardMenuLine();

		// split
		$board->push($line_separator);
		$board->push(new LeftBoardMenuSplit());
		//check version
		if( $auth['configuration']['numactives'] > 0 ) {
			if( empty($task) || $task == 'cleverdine' || $task == 'restaurant' || $task == 'editconfig' ) {
				$board->push($line_separator);
				$board->push(new LeftBoardMenuVersion(self::getCheckVersionParams()));
			}
		}
		
		///// BUILD MENU /////

		echo $board->build();

		?>

		<div class="vre-task-wrapper <?php echo ($lbm_compressed ? 'extended' : ''); ?>">
		<!--<div>-->

		<?php

	}

	public static function printFooter() {
		// close div open in printMenu
		?></div><?php
		if( cleverdine::isFooterVisible(true) ) {
			?><p id="vrestfooter"><?php echo JText::sprintf( 'VRFOOTER', cleverdine_SOFTWARE_VERSION ) . ' '; ?><a href="https://woodboxmedia.co.uk/">Wood Box Media</a></p><?php
		}
	}

	public static function getParentTask() {

		$task = JFactory::getApplication()->input->get('task');
		if( empty($task) ) {
			$task = 'cleverdine';
		}

		switch($task) {
			case 'tktopseparators': $task = 'tktoppings'; break;
			case 'tkproducts':		$task = 'tkmenus'; break;
			case 'tkmenuattr':		$task = 'tkmenus'; break;
		}

		return $task;

	}

	public static function isLeftBoardMenuCompressed() {

		$status = 1;

		$session = JFactory::getSession();
		if( !$session->has('mainmenustatus', 'vre') ) {
			$dbo = JFactory::getDbo();
			$q = "SELECT `setting` FROM `#__cleverdine_config` WHERE `param`='mainmenustatus' LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$status = $dbo->loadResult();
			}

			$session->set('mainmenustatus', $status, 'vre');
		} else {
			$status = $session->get('mainmenustatus', 1, 'vre');
		}

		return ($status == 2 ? true : false);

	}

	private static function getCheckVersionParams() {
		return array(
			"url" => strrev(strrev(urlencode(cleverdine_SOFTWARE_VERSION)).'=rev&'.strrev(urlencode(CLEVERAPP)).'=ppa&'.strrev(urlencode(getenv("SERVER_NAME"))).'=ns&'.strrev(urlencode(getenv("HTTP_HOST"))).'=nh?/kcehckiv/moc.almoojrofsnoisnetxe//:sptth'),
			"label" => strrev('setadpU kcehC')
		);
	}
	
	public static function load_css_js() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		$vik->loadFramework('jquery.framework');
		
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery-1.11.1.min.js');	
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery-ui-1.11.1.min.js');
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery-ui.sortable.min.js');
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/jquery-ui.min.css');
		
		$document->addStyleSheet(JUri::root().'administrator/components/com_cleverdine/assets/css/cleverdine.css' );
		if( VersionListener::isJoomla25() === false ) {
			$document->addStyleSheet(JUri::root().'administrator/components/com_cleverdine/assets/css/adapter/J30/vre-admin.css');
		} else {
			$document->addStyleSheet(JUri::root().'administrator/components/com_cleverdine/assets/css/adapter/J25/vre-admin.css');
		}
		
		$vik->addScript(JUri::root().'administrator/components/com_cleverdine/assets/js/colorpicker.js');
		$vik->addScript(JUri::root().'administrator/components/com_cleverdine/assets/js/eye.js');
		$vik->addScript(JUri::root().'administrator/components/com_cleverdine/assets/js/utils.js');
		$vik->addScript(JUri::root().'administrator/components/com_cleverdine/assets/js/cleverdine.js');
		
		$document->addStyleSheet(JUri::root().'administrator/components/com_cleverdine/assets/css/colorpicker.css');
	}

	public static function load_font_awesome($fix = false) {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());

		$document->addStyleSheet( JUri::root() . 'administrator/components/com_cleverdine/assets/css/font-awesome/css/font-awesome.min.css' );

		if( $fix ) {
			$vik->fixContentPadding($document);
		}

	}

	public static function load_complex_select() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		//$vik->loadFramework('jquery.framework');
		
		$vik->addScript( JUri::root() . 'components/com_cleverdine/assets/js/select2/select2.min.js');
		$document->addStyleSheet( JUri::root() . 'components/com_cleverdine/assets/js/select2/select2.css');
	}
	
	public static function load_charts() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		//$vik->loadFramework('jquery.framework');
		
		$vik->addScript( JUri::root() . 'administrator/components/com_cleverdine/assets/js/charts-framework/Chart.min.js');
	}
	
	public static function validateTable($args) {
		$required_elem = array( "name" => true, "min_seating" => true, "max_seating" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateRoom($args) {
		$required_elem = array( "name" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateOperator($args) {
		$required_elem = array( "firstname" => true, "lastname" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateReservation($args) {
		$required_elem = array( "deposit" => true, "bill_value" => true, "status" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateCustomer($args) {
		$required_elem = array( "billing_name" => true, 'billing_mail' => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateShift($args) {
		$required_elem = array( "name" => true, "from" => true, "to" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateMenusProduct($args) {
		$required_elem = array( "name" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateMenu($args) {
		$required_elem = array( "name" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateSpecialDay($args) {
		$required_elem = array( "name" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validatePayment($args) {
		$required_elem = array( "name" => true, "file" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateCustomf($args) {
		$required_elem = array( "name" => true, "type" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}

	public static function validateReview($args) {
		$required_elem = array( "title" => true, "name" => true, "email" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateCoupon($args) {
		$required_elem = array( "code" => true, "type" => true, "percentot" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateResCode($args) {
		$required_elem = array( "code" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateTkmenu($args) {
		$required_elem = array( "title" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateTkmenuattribute($args) {
		$required_elem = array( "name" => true, "icon" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateTktopping($args) {
		$required_elem = array( "name" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateTktoppingSeparator($args) {
		$required_elem = array( "title" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	public static function validateTkdeal($args) {
		$required_elem = array( "name" => true, "type" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}

	public static function validateTkarea($args) {
		$required_elem = array( "name" => true, "type" => true );
		return RestaurantsHelper::validator($args, $required_elem);
	}
	
	private static function validator($args,$required_elem) {
		$keys = array();
		$i = 0;
		foreach( $args as $key => $elem ) {
			if( empty($required_elem[$key]) != 1 ) {
				if( !( !$required_elem[$key] || strlen( $elem ) > 0) ) {
					$keys[$i++] = "" . $key;
				}
			}
		}
		
		return $keys;
	}
	
	public static function mergeIndexedArray($_a, $_b) {
		$i = count( $_a );
		foreach( $_b as $_v ) {
			$_a[$i] = $_v;
			$i++;
		}
		return $_a;
	}
	
	public static function getDefaultGraphicsProperties() {
		return array(
			// options
			"prefix" => "t",
			"people" => 4,
			// properties
			"start_x" => 40,
			"start_y" => 40,
			"minwidth" => 90,
			"minheight" => 90,
			"wpp" => 45,
			"hpp" => 45,
			"hor_spacing" => 60,
			"ver_spacing" => 60,
			"color" => "#a1988d", 
			"mapwidth" => 800,
			"mapheight" => 500,
			"display_next" => 1
		);
	}
	
	public static function getAuthorisations() {
		$rules = array(
			"dashboard" => array( 
				"actions" => array( "dashboard" => 0 ),
				"numactives" => 0
			),
			"restaurant" => array( 
				"actions" => array( "rooms" => 0, "tables" => 0, "maps" => 0, "products" => 0, "menus" => 0, "reservations" => 0 ),
				"numactives" => 0
			),
			"operations" => array( 
				"actions" => array( "shifts" => 0, "specialdays" => 0, "operators" => 0 ),
				"numactives" => 0
			),
			"booking" => array( 
				"actions" => array( "customers" => 0, "reviews" => 0, "coupons" => 0, "invoices" => 0 ),
				"numactives" => 0
			), 
			"takeaway" => array( 
				"actions" => array( "tkmenus" => 0, "tktoppings" => 0, "tkdeals" => 0, "tkareas" => 0, "tkorders" => 0),
				"numactives" => 0
			),
			"global" => array( 
				"actions" => array( "custfields" => 0, "payments" => 0, "media" => 0, "rescodes" => 0 ),
				"numactives" => 0
			),
			"configuration" => array(
				"actions" => array( "config" => 0 ),
				"numactives" => 0
			)
		);
		
		$user = JFactory::getUser();
		
		foreach( $rules as $group => $rule ) {
			foreach( $rule['actions'] as $action => $val ) {
				$rules[$group]['actions'][$action] = $user->authorise("core.access.$action", "com_cleverdine");
				
				if( $rules[$group]['actions'][$action] ) {
					$rules[$group]['numactives']++;
				}
			}
		}

		// settings
		if( !cleverdine::isRestaurantEnabled(true) ) {
			$rules['restaurant']['numactives'] = 0;
		}
		if( !cleverdine::isTakeAwayEnabled(true) ) {
			$rules['takeaway']['numactives'] = 0;
			if( $rules['booking']['actions']['reviews'] ) {
				$rules['booking']['actions']['reviews'] = 0;
				$rules['booking']['numactives']--;
			}
		}

		return $rules;
		
	}
	
	public static function createNewJoomlaUser($args) {
		$vik = new VikApplication(VersionListener::getID());
		
		jimport('joomla.application.component.helper');
		$params = JComponentHelper::getParams('com_users');
		$user = new JUser;
		$data = array();
		//Get the default new user group, Registered if not specified.
		$data['groups'] = (!empty($args['usertype']) && is_array($args['usertype']) && count($args['usertype']) ? $args['usertype'] : array($params->get('new_usertype', 2)));
		$data['name'] = $args['username'];
		$data['username'] = $args['username'];
		$data['email'] = $vik->emailToPunycode($args['usermail']);
		$data['password'] = $args['user_pwd1'];
		$data['password2']= $args['user_pwd2'];
		$data['sendEmail'] = 1;

		if (!$user->bind($data)) {
			return false;
		}
		if (!$user->save()) {
			return false;
		}
		return $user->id;
	}

	public static function checkUserArguments($args) {
		return (!empty($args['username']) && !empty($args['usermail']) && !empty($args['user_pwd1']) && !empty($args['user_pwd2']) && cleverdine::validateUserEmail($args['usermail']) && $args['user_pwd1'] == $args['user_pwd2'] );
	}

	public static function getAllMedia($order_by_creation=false, $thumbs=false) {
		$arr = glob(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.($thumbs ? '@small' : '').DIRECTORY_SEPARATOR.'*.{png,jpg,jpeg,bmp,gif}', GLOB_BRACE);
		if( $order_by_creation ) {
			usort($arr, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));
		}
		return $arr;
	}

	public static function getFileProperties($file, $attr=array()) {
		if( !file_exists($file) ) {
			return null;
		}

		$attr = self::getDefaultFileAttributes($attr);
		
		$file_prop = array(
			'file' 			=> $file,
			'path' 			=> substr($file, 0, strrpos($file, DIRECTORY_SEPARATOR)+1),
			'name' 			=> substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1),
			'file_ext'		=> substr($file, strrpos($file, '.')),
			'size' 			=> filesize($file),
			'creation' 		=> date( $attr['dateformat'], filemtime($file) )
		);

		$file_prop['name_no_ext'] = substr($file_prop['name'], 0, strrpos($file_prop['name'], '.'));

		if( $file_prop['size'] > 1024*1024 ) {
			$file_prop['size'] = number_format($file_prop['size']/(1024*1024), 2).' MB';
		} else if( $file_prop['size'] > 1024 ) {
			$file_prop['size'] = number_format($file_prop['size']/1024, 2).' kB';
		} else {
			$file_prop['size'] .= ' B';
		}

		if( preg_match('/(\.jpeg|\.jpg|\.png|\.bmp|\.gif)$/i', $file_prop['file_ext']) ) {
			$img_size = getimagesize($file);

			$file_prop['width'] = $img_size[0];
			$file_prop['height'] = $img_size[1];
		}

		return $file_prop;
	}

	public static function getDefaultFileAttributes($attr=array()) {
		if( empty($attr['dateformat']) ) {
			$attr['dateformat'] = cleverdine::getDateFormat(true)." ".cleverdine::getTimeFormat(true).":s";
		}

		return $attr;
	}

	public static function buildGroupDropdown($name, $selected, $id, $values=null, $class='', $allowClear = false, $placeholder = null) {

		if( $placeholder === null ) {
			$placeholder = '--';
		}

		if( $values === null || !is_array($values) || count($values) != 2 ) {
			$values = array(0, 1);
		}

		$rs_enabled = cleverdine::isRestaurantEnabled(true);
		$tk_enabled = cleverdine::isTakeAwayEnabled(true);

		if( !$rs_enabled && !$tk_enabled ) {
			return '<input type="hidden" name="'.$name.'" value="" />'.$placeholder;
		}

		$vik = new VikApplication(VersionListener::getID());

		$strlen_sel = strlen($selected);

		$elements = array();
		if( $allowClear ) {
			$elements[] = $vik->initOptionElement('', '', !$strlen_sel);
		}
		if( $rs_enabled ) {
			$elements[] = $vik->initOptionElement($values[0], JText::_('VRMANAGECONFIGTITLE1'), $strlen_sel && $selected == $values[0]);
		}
		if( $tk_enabled ) {
			$elements[] = $vik->initOptionElement($values[1], JText::_('VRMANAGECONFIGTITLE2'), $strlen_sel && $selected == $values[1]);
		}

		$select = $vik->dropdown($name, $elements, $id);
		
		JFactory::getDocument()->addScriptDeclaration('jQuery(document).ready(function(){
			jQuery("#'.$id.'").select2({
				minimumResultsForSearch: -1,
				placeholder: "'.addslashes($placeholder).'",
				allowClear: true,
				width: 300,
			});
		});');

		return $select;

	}

	public static function getMenusItemsList($dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$q = "SELECT `m`.`id` AS `id_menu`, `m`.`menutype`, `i`.`id`, `i`.`title`
		FROM `#__menu_types` AS `m` 
		LEFT JOIN `#__menu` AS `i` ON `m`.`menutype`=`i`.`menutype` ORDER BY `i`.`lft` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() == 0 ) {
			return array();
		}

		$menus = array();
		$last_id = -1;

		foreach( $dbo->loadAssocList() as $i ) {
			if( $last_id != $i['id_menu'] ) {
				array_push($menus, array(
					'id' 	=> $i['id_menu'],
					'type' => $i['menutype'],
					'items' => array()
				));

				$last_id = $i['id_menu'];
			}

			if( !empty($i['id']) ) {
				array_push($menus[count($menus)-1]['items'], array(
					'id' 	=> $i['id'],
					'title' => $i['title']
				));
			}
		}

		return $menus;

	}

	public static function registerUpdaterFields()
	{
		// make sure the Joomla version is 3.2.0 or higher
		// otherwise the extra_fields wouldn't be available
		$jv = new JVersion();
		if (version_compare($jv->getShortVersion(), '3.2.0', '<')) {
			// stop to avoid fatal errors.
			return;
		}

		$config = UIFactory::getConfig();
		$extra_fields = $config->getInt('update_extra_fields', 0);	

		if ($extra_fields > time()) {
			// not needed to rewrite extra fields
			return;
		}

		// get current domain
		$server = JFactory::getApplication()->input->server;
		$domain = base64_encode($server->getString('HTTP_HOST'));
		$ip 	= $server->getString('REMOTE_ADDR');

		// import url update handler
		UILoader::import('library.update.urihandler');

		$update = new UriUpdateHandler('com_cleverdine');

		$update->addExtraField('domain', $domain)
			->addExtraField('ip', $ip)
			->register();

		// validate schema version
		$update->checkSchema($config->get('version'));

		// rewrite extra fields next week
		$config->set('update_extra_fields', time() + 7 * 86400);
	}
	
	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0) {

		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($Id)){
			$assetName = 'com_cleverdine';
		} else {
			$assetName = 'com_cleverdine.message.'.(int) $Id;
		};

		$actions = JAccess::getActions('com_cleverdine', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		};

		return $result;
	}
}

class OrderingManager {
	
	private static $_OPTION_;
	private static $_COLUMN_KEY_;
	private static $_TYPE_KEY_;
	
	public function __construct( $option, $column_key, $type_key ) {
		self::$_OPTION_ = $option;
		self::$_COLUMN_KEY_ = $column_key;
		self::$_TYPE_KEY_ = $type_key;
	}
	
	public static function getLinkColumnOrder($task='', $text='', $col='', $type='', $def_type='', $params=array(), $active_class='') {
		if( empty($type) ) {
			$type = $def_type;
			$active_class = '';
		} 
		
		$url = '<a class="'.$active_class.'" href="index.php?option='.self::$_OPTION_.'&task='.$task.'&'.self::$_COLUMN_KEY_.'='.$col.'&'.self::$_TYPE_KEY_.'='.$type;
		if( count( $params ) > 0 ) {
			foreach($params as $key => $val) {
				if( is_array($val) || is_object($val) ) {
					foreach( $val as $k => $v ) {
						$url .= '&'.$key.'[]='.$v;
					}
				} else {
					$url .= '&'.$key.'='.$val;
				}
			}
		}
		
		return $url.'">'.$text.'</a>';
	}
	
	/*
	 * type = 1 ASC 
	 * type = 2 DESC
	 */
	public static function getColumnToOrder($task='', $def_col='', $def_type='', $skip_session=false) {
		$input = JFactory::getApplication()->input;

		$col 	= $input->get(self::$_COLUMN_KEY_, '', 'string');
		$type 	= $input->get(self::$_TYPE_KEY_, '', 'string');
		
		$session = JFactory::getSession();
		
		if( empty( $col ) ) {
			$col =  $def_col;
			
			if( !$skip_session ) {
				$app_c = $session->get(self::$_COLUMN_KEY_.'_'.$task, '');
				$app_t = $session->get(self::$_TYPE_KEY_.'_'.$task, '');
				
				if( !empty( $app_c ) ) {
					$col = $app_c;
				}
				
				if( !empty( $app_t ) ) {
					$type = $app_t;
				}
			}
		}
		
		if( empty( $type ) ) {
			$type = $def_type;
		}
		
		$session->set(self::$_COLUMN_KEY_.'_'.$task, $col);
		$session->set(self::$_TYPE_KEY_.'_'.$task, $type);
		
		return array( 'column' => $col, 'type' => $type );
	}
	
	public static function getSwitchColumnType( $task, $col, $curr_type, $types ) {
		$session = JFactory::getSession();
		$old_c = $session->get(self::$_COLUMN_KEY_.'_'.$task, '');
		
		if( $old_c == $col ) {
			$found = -1;
			for( $i = 0; $i < count($types) && $found == -1; $i++ ) {
				if( $types[$i] == $curr_type ) {
					$found = $i;
				}
			}
			
			if( $found != -1 ) {
				$found++;
				if( $found >= count($types) ) {
					$found = 0;
				}
				
				return $types[$found];
			}
		} 
		
		return $types[count($types)-1];
	}
	
}

class MediaManagerHTML {

	private $vik = null;
	private $medias = array();
	private $prefix = 'xyz';
	private $image_path = '';

	private $script_used = false;

	public function __construct($all_medias, $image_path, $vik=null, $prefix='') {
		$this->vik = $vik;

		if( $this->vik === null ) {
			$this->vik = new VikApplication(VersionListener::getID());
		}

		$this->image_path = $image_path;

		$this->medias[] = $this->vik->initOptionElement('', '', false);
		$this->medias[] = $this->vik->initOptionElement(-1, JText::_('VRMANAGEMEDIA11'), false);

		foreach( $all_medias as $file ) {
			$m = substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1);
			$this->medias[] = $this->vik->initOptionElement($m, $m, false);
		}

		if( strlen($prefix) ) {
			$this->prefix = $prefix;
		}

	}

	public function useScript($no_image_text='') {

		if( $this->script_used  ) {
			return;
		}

		$this->script_used = true;

		$px = $this->prefix;

		$script = "// MEDIA 

		var UPLOADED_MEDIAS = [];
		var SELECT_ID = '';

		function {$px}RenderMediaSelect(id) {
			var selector = (id.length == 0 ? '.{$px}-image-sel' : '#imageref_'+id);

			jQuery(selector).select2({
				placeholder: '".addslashes($no_image_text)."',
				allowClear: true,
				width: 250
			});

			jQuery(selector).on('change', function(){
				var val = jQuery(this).val();
				var p_id = jQuery(this).data('id');

				if( val.length == 0 ) {
					jQuery('#{$px}-link-preview_'+p_id).hide();
					jQuery(this).data('previous', '');
				} else if( val == '-1' ) {
					SELECT_ID = jQuery(this).attr('id');

					{$px}OpenJModal('newmedia', null, true);
				} else {
					jQuery('#{$px}-link-preview_'+p_id).show();
					jQuery(this).data('previous', val);
				}

			});
		}

		jQuery(document).ready(function(){
			{$px}RenderMediaSelect('');

			jQuery('#jmodal-newmedia').on('show', function() {
				{$px}NewMediaOnShow();
			});

			jQuery('#jmodal-newmedia').on('hidden', function() {
				
			});
		});

		function {$px}ShowPreview(id) {
			var val = jQuery('#'+id).val();
			if( val.length > 0 && val != '-1' ) {
				{$px}OpenModalImage('".$this->image_path."'+val);
			}
		}

		function {$px}DismissHandler() {
			var LAST_MEDIA_SELECTED = jQuery('#'+SELECT_ID).data('previous');

			if( UPLOADED_MEDIAS.length ) {
				if( LAST_MEDIA_SELECTED == '' || UPLOADED_MEDIAS.length == 1 ) {
					LAST_MEDIA_SELECTED = UPLOADED_MEDIAS[0];
				}

				var _html = '';
				for( var i = 0; i < UPLOADED_MEDIAS.length; i++ ) {
					_html += '<option value=\"'+UPLOADED_MEDIAS[i]+'\">'+UPLOADED_MEDIAS[i]+'</option>';
				}
				jQuery('select.{$px}-image-sel').each(function(){
					jQuery(this).html(jQuery(this).html()+_html);

					// refresh current value for each select
					// by copying the html, the selected option will be the empty value
					jQuery(this).select2('val', jQuery(this).data('previous'));
				});

				UPLOADED_MEDIAS = [];
			}

			jQuery('#'+SELECT_ID).select2('val', LAST_MEDIA_SELECTED);
			jQuery('#'+SELECT_ID).data('previous', LAST_MEDIA_SELECTED);
			jQuery('#'+SELECT_ID).trigger('change');
		}

		function {$px}OpenJModal(id, url, jqmodal) {
			".$this->vik->bootOpenModalJS("{$px}DismissHandler")."
		}

		function {$px}CloseJModal() {
			".$this->vik->bootDismissModalJS('#jmodal-newmedia')."
		}
		
		function {$px}NewMediaOnShow() {
			var href = 'index.php?option=".JFactory::getApplication()->input->get('option')."&task=flashupload&tmpl=component';
			var size = {
				width: jQuery('#jmodal-newmedia').width(), //940,
				height: jQuery('#jmodal-newmedia').height(), //590
			}
			{$px}AppendModalContent('jmodal-box-newmedia', href, size);
		}
		
		function {$px}AppendModalContent(id, href, size) {
			jQuery('#'+id).html('<div class=\"modal-body\" style=\"max-height:'+(size.height-20)+'px;\">'+
			'<iframe class=\"iframe\" src=\"'+href+'\" width=\"'+size.width+'\" height=\"'+size.height+'\" style=\"max-height:'+(size.height-100)+'px;\"></iframe>'+
			'</div>');
		};";

		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);

	}

	public function buildModal($title, $width="50%", $height="70%") {
		$margin = intval($width)/2;
		$exp = explode(intval($width), $width);
		if( count($exp) > 1 ) {
			$margin .= $exp[1];
		}

		return '<div class="modal hide fade" id="jmodal-newmedia" style="width:'.$width.';height:'.$height.';margin-left:-'.$margin.';min-width:740px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">×</button>
				<h3>'.$title.'</h3>
			</div>
			<div id="jmodal-box-newmedia"></div>
		</div>';
	}

	public function buildMedia($name, $id, $value='', $js_safe=false) {

		for( $i = 0; $i < count($this->medias); $i++ ) {
			$this->medias[$i]->selected = ( $value == $this->medias[$i]->value );
		}

		$html = $this->vik->dropdown($name, $this->medias, 'imageref_'.$id, $this->prefix.'-image-sel', 'data-previous="'.$value.'" data-id="'.$id.'"').'
			<a href="javascript: void(20)" class="'.$this->prefix.'modal" onClick="'.$this->prefix.'ShowPreview(\'imageref_'.$id.'\');" id="'.$this->prefix.'-link-preview_'.$id.'" '.(strlen($value) ? '' : 'style="display: none;"').'>
				<i class="'.$this->prefix.'-media-preview fa fa-camera"></i>
			</a>';

		if( $js_safe ) {
			$html = str_replace("\n", "", $html);
			$html = str_replace("'", "\'", $html);
		}

		return $html;

	}

}

?>