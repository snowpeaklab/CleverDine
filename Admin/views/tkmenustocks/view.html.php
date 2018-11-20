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
class cleverdineViewtkmenustocks extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();

		$filters = array();
		$filters['id_menu'] 	= $input->get('id_menu');
		$filters['keysearch'] 	= $input->get('keysearch');

		$menus = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_menus` ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = $dbo->loadAssocList();

			if( empty($filters['id_menu'])  ) {
				$filters['id_menu'] = $menus[0]['id'];
			}
		}

		$list = array();

		$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `e`.`items_in_stock` AS `estock`, `e`.`notify_below` AS `enotify`,
		 `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`items_in_stock` AS `ostock`, `o`.`notify_below` AS `onotify` 
		FROM `#__cleverdine_takeaway_menus_entry` AS  `e`  
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON  `e`.`id` = `o`.`id_takeaway_menu_entry` 
		WHERE `e`.`id_takeaway_menu`=".$filters['id_menu'].(empty($filters['keysearch']) ? "" : " AND CONCAT_WS(' ', `e`.`name`, `o`.`name`) LIKE ".$dbo->quote("%".$filters['keysearch']."%"))." 
		ORDER BY `e`.`ordering`, `o`.`ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() > 0) {
			$app = $dbo->loadAssocList();
			$last_id = -1;
			foreach( $app as $r ) {
				if( $r['eid'] != $last_id ) {
					array_push($list, array(
						"id" => $r['eid'],
						"name" => $r['ename'],
						"items_in_stock" => $r['estock'],
						"notify_below" => $r['enotify'],
						"options" => array()
					));
					$last_id = $r['eid'];
				}

				if( !empty($r['oid']) ) {
					array_push($list[count($list)-1]['options'], array(
						"id" => $r['oid'],
						"name" => $r['oname'],
						"items_in_stock" => $r['ostock'],
						"notify_below" => $r['onotify']
					));
				}
			}
		}
		
		$this->productsList = &$list;
		$this->filters 		= &$filters;
		$this->menus 		= &$menus;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTKMENUSTOCKS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::apply('saveTkMenuStocks', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseTkMenuStocks', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelTkmenu', JText::_('VRCANCEL'));

	}

}
?>