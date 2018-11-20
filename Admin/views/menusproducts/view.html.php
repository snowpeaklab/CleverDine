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
class cleverdineViewmenusproducts extends JViewUI {
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
		
		$filters = array();
		$filters['id_menu'] 	= $mainframe->getUserStateFromRequest('vrprod.id_menu', 'id_menu', 0, 'int');
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrprod.keysearch', 'keysearch', '', 'string');
		$filters['status'] 		= $mainframe->getUserStateFromRequest('vrprod.status', 'status', 0, 'uint');
		$filters['tools']		= $mainframe->getUserStateFromRequest('vrprod.tools', 'tools', 0, 'uint');

		// Set the toolbar
		$this->addToolBar($filters['status']);
		
		$ordering = OrderingManager::getColumnToOrder('menusproducts', 'ordering', 1);
		
		$where_claus = "";

		switch($filters['status']) {
			case 1: $where_claus = "`p`.`hidden`=0 AND `p`.`published`=1"; break;
			case 2: $where_claus = "`p`.`hidden`=0 AND `p`.`published`=0"; break;
			case 3: 
				$where_claus = "`p`.`hidden`=1"; 
				$filters['id_menu'] = 0; // always unset menus filtering
				break;

			default:
				$where_claus = "`p`.`hidden`=0";
		}

		$q_menu_filter = "";
		if( !empty($filters['id_menu']) ) {
			$q_menu_filter .= "LEFT JOIN `#__cleverdine_section_product_assoc` AS `a` ON `a`.`id_product`=`p`.`id` 
			LEFT JOIN `#__cleverdine_menus_section` AS `s` ON `s`.`id`=`a`.`id_section` ";

			$where_claus .= " AND `s`.`id_menu`=".$filters['id_menu']; 
		}

		if( !empty($filters['keysearch']) ) {
			$where_claus .= " AND `p`.`name` LIKE ".$dbo->quote("%".$filters['keysearch']."%");
		}
		
		$shifts = array();

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrprod.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";
		
		$q = "SELECT SQL_CALC_FOUND_ROWS `p`.* FROM `#__cleverdine_section_product` AS `p` ".$q_menu_filter."
			WHERE $where_claus
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
		
		$menus = array();
		$q = "SELECT `id`, `name` FROM `#__cleverdine_menus` ORDER BY `name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = $dbo->loadAssocList();
		}
		
		$new_type = OrderingManager::getSwitchColumnType( 'menusproducts', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$q = "SELECT MIN(`ordering`) AS `min`, MAX(`ordering`) AS `max` FROM `#__cleverdine_section_product` WHERE `hidden`=0;";
		$dbo->setQuery($q);
		$dbo->execute();
		$constraints = $dbo->loadAssoc();
		
		$this->rows 		= &$rows;
		$this->menus 		= &$menus;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->filters 		= &$filters;
		$this->ordering 	= &$ordering;
		$this->constraints 	= &$constraints;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar($status = 0) {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWMENUSPRODUCTS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newmenusproduct', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editmenusproduct', JText::_('VREDIT'));
			JToolbarHelper::spacer();
			
			if( $status != 3 ) {
				JToolbarHelper::custom('publishMenusProducts', 'publish', 'publish', JText::_('VRPUBLISH'), true);
				JToolbarHelper::spacer();
				
				JToolbarHelper::custom('unpublishMenusProducts', 'unpublish', 'unpublish', JText::_('VRUNPUBLISH'), true);
				JToolbarHelper::divider();
			}
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteMenusProducts', JText::_('VRDELETE'));	
		}
		
	}

}
?>