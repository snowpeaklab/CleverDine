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
class cleverdineViewmedia extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		cleverdine::load_fancybox();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		// Set the toolbar
		$this->addToolBar();

		//db object
		$lim 	= $mainframe->getUserStateFromRequest('com_cleverdine.limit', 'limit', $mainframe->get('list_limit'), 'int');
		$lim0 	= $mainframe->getUserStateFromRequest('vrmedia.limitstart', 'limitstart', 0, 'uint');
		$navbut	= "";
		
		$filters = array();
		$filters['keysearch'] = $mainframe->getUserStateFromRequest('vrmedia.keysearch', 'keysearch', '', 'string');

		// retrieve all images and apply filters
		$all_img = RestaurantsHelper::getAllMedia(true);

		if( !empty($filters['keysearch']) ) {
			$app = array();
			foreach( $all_img as $img ) {
				$file_name = substr($img, strrpos($img, '/'));
				if( strpos($file_name, $filters['keysearch']) !== false ) {
					array_push($app, $img);
				}
			}
			$all_img = $app;
			unset($app);
		}
		
		$tot_count = count($all_img);
		if( $tot_count > $lim ) {
			$all_img = array_slice($all_img, $lim0, $lim);

			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $tot_count, $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		}

		$attr = RestaurantsHelper::getDefaultFileAttributes();
		foreach( $all_img as $i => $f ) {
			$all_img[$i] = RestaurantsHelper::getFileProperties($f, $attr);
		}
		
		$this->rows 		= &$all_img;
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
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWMEDIA'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newmedia', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editmedia', JText::_('VREDIT'));
			JToolbarHelper::spacer();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteMedia', JText::_('VRDELETE'));	
		}
		
	}

}
?>