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
class cleverdineViewtktoppings extends JViewUI {
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
		$filters['id_sep'] 	= $mainframe->getUserStateFromRequest('vrtktopping.id_sep', 'id_sep', 0, 'uint');
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrtktopping.keysearch', 'keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('tktoppings', 'ordering', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrtktopping.limitstart', 'limitstart', 0, 'uint');
		$navbut = "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `t`.*, `s`.`title` AS `separator` 
		FROM `#__cleverdine_takeaway_topping` AS `t` 
		LEFT JOIN `#__cleverdine_takeaway_topping_separator` AS `s` ON `t`.`id_separator`=`s`.`id` 
		WHERE 1
		".($filters['id_sep'] > 0 ? " AND `s`.`id`=".$filters['id_sep'] : "")."
		".(!empty($filters['keysearch']) ? " AND `t`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		ORDER BY `t`.`".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		} else {
			$rows = array();
		}
		
		$separators = array();
		$q = "SELECT `id`, `title` FROM `#__cleverdine_takeaway_topping_separator` ORDER BY `ordering`;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$separators = $dbo->loadAssocList();
		}
		
		$def_lang = cleverdine::getDefaultLanguage();
		for( $i = 0; $i < count($rows); $i++ ) {
			$rows[$i]['languages'] = array($def_lang);
			
			$q = "SELECT `tag` FROM `#__cleverdine_lang_takeaway_topping` WHERE `id_topping`=".$rows[$i]['id'].";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				foreach( $dbo->loadAssocList() as $lang ) {
					if( !in_array($lang['tag'], $rows[$i]['languages']) ) {
						array_push($rows[$i]['languages'], $lang['tag']);
					}
				}
			}
		}

		$new_type = OrderingManager::getSwitchColumnType( 'tktoppings', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$q = "SELECT MIN(`ordering`) AS `min`, MAX(`ordering`) AS `max` FROM `#__cleverdine_takeaway_topping`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$constraints = $dbo->loadAssoc();
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->separators 	= &$separators;
		$this->ordering 	= &$ordering;
		$this->filters 		= &$filters;
		$this->constraints 	= &$constraints;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTKTOPPINGS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newtktopping', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('edittktopping', JText::_('VREDIT'));
			JToolbarHelper::spacer();
			
			JToolbarHelper::custom('publishTakeawayToppings', 'publish', 'publish', JText::_('VRPUBLISH'), true);
			JToolbarHelper::divider();
			
			JToolbarHelper::custom('unpublishTakeawayToppings', 'unpublish', 'unpublish', JText::_('VRUNPUBLISH'), true);
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteTkToppings', JText::_('VRDELETE'));
		}

	}

}
?>