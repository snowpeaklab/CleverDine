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
class cleverdineViewinvoices extends JViewUI {
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();

		// set the toolbar
		$this->addToolBar();

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$year 	= $mainframe->getUserStateFromRequest('vrinvoice.year', 'year', 0, 'int');
		$month 	= $mainframe->getUserStateFromRequest('vrinvoice.month', 'month', 0, 'int');

		if( empty($year) || empty($month) ) {
			$d = getdate();
			$year 	= $d['year'];
			$month 	= $d['mon'];
		}

		$filters = array();
		$filters['group'] 	= $mainframe->getUserStateFromRequest('vrinvoice.group', 'group', '', 'string');
		$filters['keysearch'] 	= $mainframe->getUserStateFromRequest('vrinvoice.keysearch', 'keysearch', '', 'string');

		// get invoices
		$invoices	= array();
		$loadedAll 	= true;
		$limit 		= 20;
		$maxLimit 	= 0;

		$start_ts = mktime(0, 0, 0, $month, 1, $year);
		$end_ts = mktime(0, 0, 0, $month+1, 1, $year)-1;

		$q = "SELECT SQL_CALC_FOUND_ROWS * 
		FROM `#__cleverdine_invoice` 
		WHERE `inv_date` BETWEEN $start_ts AND $end_ts 
		".(strlen($filters['group']) ? " AND `group`=".intval($filters['group']) : "")."
		".(!empty($filters['keysearch']) ? " AND (`file` LIKE ".$dbo->quote("%".$filters['keysearch']."%")." OR `inv_number` LIKE ".$dbo->quote("%".$filters['keysearch']."%").")" : "")." 
		ORDER BY `inv_date` ASC, `id_order` ASC";

		$dbo->setQuery($q, 0, $limit);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$invoices = $dbo->loadAssocList();

			$dbo->setQuery('SELECT FOUND_ROWS();');
			if( ($maxLimit = $dbo->loadResult()) > count($invoices) ) {
				$loadedAll = false;
			}
		}

		// build tree
		
		$tree = array();
		
		$q = "SELECT DATE_FORMAT(FROM_UNIXTIME(`inv_date`), '%Y') AS `year`,
		DATE_FORMAT(FROM_UNIXTIME(`inv_date`), '%c') AS `mon`
		FROM `#__cleverdine_invoice`
		GROUP BY `year`, `mon`
		ORDER BY CAST(`year` AS unsigned) DESC, CAST(`mon` AS unsigned) DESC;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			foreach( $rows as $r ) {
				if(empty($r['year']) ) {
					$r['year'] = -1;
					$r['mon'] = -1;
				}
				
				if( empty($tree[$r['year']]) ) {
					$tree[$r['year']] = array();
				}
				array_push($tree[$r['year']], $r['mon']);
			}
		}

		$seek = array('year' => $year, 'month' => $month);
		
		$this->invoices = &$invoices;
		$this->tree 	= &$tree;
		$this->filters 	= &$filters;
		$this->seek 	= &$seek;

		$this->limit 		= &$limit;
		$this->maxLimit 	= &$maxLimit;
		$this->loadedAll 	= &$loadedAll;
		
		// Display the template (default.php)
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLEVIEWINVOICES'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.create', 'com_cleverdine')) {
			JToolbarHelper::addNew('newinvoice', JText::_('VRNEW'));
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::editList('editinvoice', JText::_('VREDIT'));
			JToolbarHelper::spacer();
			JToolBarHelper::custom('downloadInvoices', 'download', 'download', JText::_('VRDOWNLOAD'), true, false);
			JToolbarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.delete', 'com_cleverdine')) {
			JToolbarHelper::deleteList( '', 'deleteInvoices', JText::_('VRDELETE'));
		}
		
	}

}
?>