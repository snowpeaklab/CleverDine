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
class cleverdineViewmanagerev extends JViewUI {
	
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
		
		$type = $input->get('type');

		$filters = array();
		$filters['key'] 	= $input->get('key', '', 'string');
		$filters['stars'] 	= $input->get('stars', '', 'string');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedReview = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = intval($ids[0]);

			$q = "SELECT * FROM `#__cleverdine_reviews` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedReview = $dbo->loadAssoc();
			}
		}

		$products = array();
		$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`, `e`.`id`, `e`.`name` 
		FROM `#__cleverdine_takeaway_menus_entry` AS `e`
		LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`e`.`id_takeaway_menu` 
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$last_menu_id = -1;
			foreach( $dbo->loadAssocList() as $r ) {
				if( $last_menu_id != $r['id_menu'] ) {
					$products[] = array(
						'id' => $r['id_menu'],
						'title' => $r['menu_title'],
						'items' => array()
					);
					$last_menu_id = $r['id_menu'];
				}

				$products[count($products)-1]['items'][] = array(
					'id' => $r['id'],
					'name' => $r['name']
				);
			}
		}

		$jid = ( !empty($selectedReview['jid']) && $selectedReview['jid'] > 0 ? $selectedReview['jid'] : -1 );
		$juser = array();
		if( $jid > 0 ) {
			$q = "SELECT `id`, `name` FROM `#__users` WHERE `id`=$jid LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$juser = $dbo->loadAssoc();
			}
		}
		
		$this->selectedReview 	= &$selectedReview;
		$this->products 		= &$products;
		$this->juser 			= &$juser;
		$this->filters 			= &$filters;

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITREVIEW'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWREVIEW'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
		   JToolbarHelper::apply('saveReview', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseReview', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewReview', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}

		JToolbarHelper::cancel('cancelReview', JText::_('VRCANCEL'));
	}

}
?>