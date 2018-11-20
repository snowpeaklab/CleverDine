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
class cleverdineViewcustomf extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		// Set the toolbar
		$this->addToolBar();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		$filters = array();
		$filters['group'] 		= $mainframe->getUserStateFromRequest('vrcf.group', 'group', '', 'string');
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrcf.keysearch', 'keysearch', '', 'string');

		$ordering = OrderingManager::getColumnToOrder('customf', 'ordering', 1);

		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrcf.limitstart', 'limitstart', 0, 'int');
		$navbut = "";

		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__cleverdine_custfields` 
		WHERE 1
		".(strlen($filters['group']) ? " AND `group`=".intval($filters['group']) : "")."
		".(!empty($filters['keysearch']) ? " AND `name` LIKE ".$dbo->quote("%".$filters['keysearch']."%") : "")." 
		ORDER BY `group` ASC, `".$ordering['column']."` ".(($ordering['type'] == 2 ) ? 'DESC' : 'ASC');

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

		$def_lang = cleverdine::getDefaultLanguage();
		for( $i = 0; $i < count($rows); $i++ ) {
			$rows[$i]['languages'] = array($def_lang);
			
			$q = "SELECT `tag` FROM `#__cleverdine_lang_customf` WHERE `id_customf`=".$rows[$i]['id'].";";
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

		$new_type = OrderingManager::getSwitchColumnType( 'customf', $ordering['column'], $ordering['type'], array( 1, 2 ) );
		$ordering = array( $ordering['column'] => $new_type );

		$q = "SELECT MIN(`ordering`) AS `min`, MAX(`ordering`) AS `max` FROM `#__cleverdine_custfields`;";
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
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWCUSTOMFS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newcustomf', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editcustomf', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteCustomf', JText::_('VRDELETE'));
		}
		
	}

}
?>