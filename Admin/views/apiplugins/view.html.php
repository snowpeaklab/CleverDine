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
class cleverdineViewapiplugins extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_font_awesome();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();

		cleverdine::loadFrameworkApis();

		// Set the toolbar
		$this->addToolBar();
		
		$filters = array();
		$filters['key'] = $input->get('keysearch', '', 'string');

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $input->get('limitstart', 0, 'uint');
		$navbut = "";

		$apis = FrameworkAPIs::getInstance();
		$rows = $apis->getPluginsList();

		if( strlen($filters['key']) ) {

			$key = strtolower($filters['key']);

			$app = $rows;

			$rows = array();

			foreach( $app as $i => $r ) {
				if( strpos(strtolower($r->getName()), $key) !== false || strpos(strtolower($r->getTitle()), $key) !== false ) {
					$rows[] = $r;
				}
			}

		}

		if( ($count = count($rows)) > $lim ) {
			
			$rows = array_slice($rows, $lim0, $lim);

			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $count, $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		}
		
		$this->rows 		= &$rows;
		$this->lim0 		= &$lim0;
		$this->navbut 		= &$navbut;
		$this->filters 		= &$filters;
		
		// Display the template (default.php)
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWAPIPLUGINS'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteApiplugins', JText::_('VRDELETE'));
		}

		JToolbarHelper::cancel('cancelConfig', JText::_('VRCANCEL'));

	}

}
?>