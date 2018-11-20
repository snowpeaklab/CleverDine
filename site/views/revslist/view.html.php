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
class cleverdineViewrevslist extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		cleverdine::load_css_js();
		
		$id_tk_prod = $input->get('id_tk_prod', 0, 'uint');

		// compose request
		$request = new stdClass;
		//$request->limit 		= $mainframe->getUserStateFromRequest("com_cleverdine.limit", 'limit', $mainframe->get('list_limit'), 'int');
		$request->limit 		= cleverdine::getReviewsListLimit();
		$request->limitstart 	= $input->get('limitstart', 0, 'uint');

		$request->sortby 		= $input->get('sortby', '', 'string');
		$request->filterstar 	= $input->get('filterstar', '', 'string');
		$request->filterlang 	= $input->get('filterlang', '', 'string');

		$request->id_tk_prod 	= $id_tk_prod;

		// parse request
		if( $request->sortby < 1 || $request->sortby > 3 ) {
			$request->sortby = 1; // default ordering
		}
		if( $request->filterstar < 0 || $request->filterstar > 5 ) {
			$request->filterstar = 0; // default stars filter
		}
		
		$item = array();
		
		$q = "SELECT `e`.`id`, `e`.`name`, `e`.`description`, `e`.`img_path`, `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`
		FROM `#__cleverdine_takeaway_menus` AS `m` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `m`.`id`=`e`.`id_takeaway_menu` 
		WHERE `m`.`published`=1 AND `e`.`published`=1 AND `e`.`id`=$id_tk_prod LIMIT 1";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$item = $dbo->loadAssoc();
		} else {
			$mainframe->redirect(JRoute::_('index.php?option=com_cleverdine&view=takeaway'));
			exit;
		}
		
		$this->item 	= &$item;
		$this->request 	= &$request;
		
		// Display the template
		parent::display($tpl);

	}

}
?>