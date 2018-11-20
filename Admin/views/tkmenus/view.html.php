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
class cleverdineViewtkmenus extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		// Set the toolbar
		$this->addToolBar();
		
		$filters = array();
		$filters['keysearch'] = $mainframe->getUserStateFromRequest('vrtkmenus.keysearch', 'keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('tkmenus', 'ordering', 1);

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrtkmenus.limitstart', 'limitstart', 0, 'uint');
		$navbut = "";

		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__cleverdine_takeaway_menus` 
		".(!empty($filters['keysearch']) ? "WHERE `title` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		ORDER BY ".$ordering['column']." ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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
		
		$new_type = OrderingManager::getSwitchColumnType( 'tkmenus', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );
		
		$def_lang = cleverdine::getDefaultLanguage();
		for( $i = 0; $i < count($rows); $i++ ) {
			$rows[$i]['languages'] = array($def_lang);
			
			$q = "SELECT `tag` FROM `#__cleverdine_lang_takeaway_menus` WHERE `id_menu`=".$rows[$i]['id'].";";
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

		$q = "SELECT MIN(`ordering`) AS `min`, MAX(`ordering`) AS `max` FROM `#__cleverdine_takeaway_menus`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$constraints = $dbo->loadAssoc();
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
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
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWTKMENUS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newtkmenu', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('edittkmenu', JText::_('VREDIT'));
			JToolbarHelper::spacer();
			
			JToolbarHelper::custom('publishTakeawayMenus', 'publish', 'publish', JText::_('VRPUBLISH'), true);
			JToolbarHelper::divider();
			
			JToolbarHelper::custom('unpublishTakeawayMenus', 'unpublish', 'unpublish', JText::_('VRUNPUBLISH'), true);
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteTkmenus', JText::_('VRDELETE'));
		}

	}

}
?>