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
class cleverdineViewmanageoperator extends JViewUI {
	
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
		
		$type 		= $input->get('type');
		$active_tab = $mainframe->getUserStateFromRequest('operator.activetab', 'active_tab', 'operator_details', 'string');
		
		// Set the toolbar
		$this->addToolBar($type);
		
		// if type is edit -> assign the selected item
		$selectedOperator = array();
		
		if( $type == "edit" ) {
			$ids = $input->get('cid', array(0), 'uint');
			$id = $ids[0];

			$q = "SELECT * FROM `#__cleverdine_operator` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if($dbo->getNumRows() > 0) {
				$selectedOperator = $dbo->loadAssoc();
			}
		}
		
		$jid = 0;
		if( !empty($selectedOperator['jid']) ) {
			$jid = $selectedOperator['jid'];
		}
		
		$users = array();
		$q = "SELECT `u`.`id`, `u`.`name`, `a`.`group_id`, `g`.`title` 
		FROM `#__users` AS `u` LEFT JOIN `#__user_usergroup_map` AS `a` ON `u`.`id`=`a`.`user_id` LEFT JOIN `#__usergroups` AS `g` ON `a`.`group_id`=`g`.`id`
		WHERE `group_id`>=3 AND (`u`.`id`=".intval($jid)." OR NOT EXISTS (
			SELECT 1 FROM `#__cleverdine_operator` AS `o` WHERE `o`.`jid`=`u`.`id`
		)) 
		GROUP BY `u`.`id`
		ORDER BY `a`.`group_id` ASC, `u`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$users = $dbo->loadAssocList();
		}
		
		$user_groups = array();
		$q = "SELECT `id`, `title` FROM `#__usergroups` WHERE `id`>=3;"; 
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$user_groups = $dbo->loadAssocList();
		}
		
		$this->selectedOperator = &$selectedOperator;
		$this->users 			= &$users;
		$this->userGroups 		= &$user_groups;
		$this->activeTab 		= &$active_tab;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($type) {
		//Add menu title and some buttons to the page
		if( $type == "edit" ) {
			JToolbarHelper::title(JText::_('VRMAINTITLEEDITOPERATOR'), 'restaurants');
		} else {
			JToolbarHelper::title(JText::_('VRMAINTITLENEWOPERATOR'), 'restaurants');
		}
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveOperator', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseOperator', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::custom('saveAndNewOperator', 'save-new', 'save-new', JText::_('VRSAVEANDNEW'), false, false);
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelOperator', JText::_('VRCANCEL'));
	}

}
?>