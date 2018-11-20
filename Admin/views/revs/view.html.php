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
class cleverdineViewrevs extends JViewUI {
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
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrrevs.keysearch', 'keysearch', '', 'string');
		$filters['stars'] 		= $mainframe->getUserStateFromRequest('vrrevs.stars', 'stars', 0, 'uint');

		$ordering = OrderingManager::getColumnToOrder('revs', 'id', 2);

		$where_claus = "";
		if( !empty($filters['keysearch']) ) {
			$where_claus = "AND (
			`r`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%")." OR 
			`r`.`title` LIKE ".$dbo->quote("%".$filters['keysearch']."%")." OR 
			`e`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%").")";
		}
		if( !empty($filters['stars']) ) {
			$where_claus .= " AND `r`.`rating`=".$filters['stars'];
		}

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrrevs.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";

		$q = "SELECT SQL_CALC_FOUND_ROWS `r`.*, `e`.`name` AS `takeaway_product_name` 
		FROM `#__cleverdine_reviews` AS `r`
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `e`.`id`=`r`.`id_takeaway_product` 
		WHERE 1 $where_claus 
		ORDER BY `".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$new_type = OrderingManager::getSwitchColumnType( 'revs', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$this->rows 	= &$rows;
		$this->lim0 	= &$lim0;
		$this->navbut 	= &$navbut;
		$this->ordering = &$ordering;
		$this->filters 	= &$filters;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWREVIEWS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newrev', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editrev', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteReviews', JText::_('VRDELETE'));
		}
		
	}

}
?>