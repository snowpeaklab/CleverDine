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
class cleverdineViewmanagetkrescart extends JViewUI {
	
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
		
		$id_order = $input->get('cid', array(0), 'uint');
		$id_order = $id_order[0];
		
		$order_details = cleverdine::fetchTakeawayOrderDetails($id_order);

		if( $order_details === null ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=tkreservations');
			exit;
		}
		
		$all_items = array();
		$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`, `e`.`id`, `e`.`name` 
		FROM `#__cleverdine_takeaway_menus` AS `m`
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `m`.`id`=`e`.`id_takeaway_menu` 
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$last_menu_id = -1;
			foreach( $rows as $r ) {
				if( $last_menu_id != $r['id_menu'] ) {
					array_push($all_items, array(
						"id_menu" => $r['id_menu'],
						"menu_title" => $r['menu_title'],
						"items" => array()
					));
					$last_menu_id = $r['id_menu'];
				}
				
				array_push($all_items[count($all_items)-1]['items'], array(
					"id" => $r['id'],
					"name" => $r['name'],
				));
			}
		}
		
		$this->order 	= &$order_details;
		$this->idOrder 	= &$id_order;
		$this->allItems = &$all_items;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLETKORDERCART'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('edittkreservation', JText::_('VRSAVE'));
			JToolbarHelper::divider();
			JToolbarHelper::save('tkreservations', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::spacer();
		} else {
			JToolbarHelper::cancel('edittkreservation', JText::_('VRCANCEL'));
		}
	}

}
?>