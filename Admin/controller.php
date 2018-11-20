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

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of cleverdine component
 */
class cleverdineController extends JControllerUI {
	
	/**
	 * display task
	 *
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		// set default view if not set

		$input = JFactory::getApplication()->input;
		
		$is_tmpl = !strcmp($input->get('tmpl'), 'component');
		
		if( !$is_tmpl ) {
			RestaurantsHelper::printMenu();
		}
		
		$input->set('view', $input->get('view', 'restaurant'));

		// call parent behavior
		parent::display();

		if( !$is_tmpl ) {
			RestaurantsHelper::printFooter();
		}
	}
	
	// ITEMS
	
	public function tables() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tables'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function rooms() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'rooms'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function roomclosures() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'roomclosures'));
	
		parent::display();
	}
	
	public function maps() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'maps'));
		
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function operators() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'operators'));
		
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function operatorlogs() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'operatorlogs'));
		
		parent::display();
	}
	
	public function reservations() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'reservations'));
		
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function restbusyres() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'restbusyres'));
		$input->set('tmpl', 'component');
		parent::display();
	}
	
	public function customers() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'customers'));
		
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function shifts() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'shifts'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function menusproducts() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'menusproducts'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function menus() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'menus'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function specialdays() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'specialdays'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function payments() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'payments'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function customf() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'customf'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function coupons() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'coupons'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function revs() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'revs'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function invoices() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'invoices'));
	
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function rescodes() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'rescodes'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function rescodesorder() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'rescodesorder'));
		parent::display();
	}
	
	public function editconfig() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'editconfig'));
		parent::display();

		RestaurantsHelper::printFooter();
	}

	public function apiusers() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'apiusers'));
		parent::display();
	}

	public function apiplugins() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'apiplugins'));
		parent::display();
	}

	public function apilogs() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'apilogs'));
		parent::display();
	}

	public function apibans() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'apibans'));
		parent::display();
	}

	public function tkmenus() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tkmenus'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function tkstocks() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkstocks'));
		parent::display();
	}

	public function tkmenustocks() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkmenustocks'));
		parent::display();
	}

	public function tkstatstocks() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkstatstocks'));
		parent::display();
	}

	public function tkbusyres() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkbusyres'));
		$input->set('tmpl', 'component');
		parent::display();
	}
	
	public function tkmenuattr() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();

		$input->set('view', $input->get('view', 'tkmenuattr'));
		parent::display();

		RestaurantsHelper::printFooter();
	}
	
	public function tkproducts() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tkproducts'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function tktoppings() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tktoppings'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function tktopseparators() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tktopseparators'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function tkdeals() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tkdeals'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}
	
	public function tkareas() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tkareas'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function tkreservations() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
	
		$input->set('view', $input->get('view', 'tkreservations'));
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function tkdiscord() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkdiscord'));
		parent::display();
	}
	
	public function ccdetails() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'ccdetails'));
		parent::display();
	}

	public function purchaserinfo() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'purchaserinfo'));
		parent::display();
	}
	
	public function sneakmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'sneakmenu'));
		parent::display();
	}
	
	public function menuslist() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'menuslist'));
		parent::display();
	}
	
	public function tkpurchaserinfo() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'tkpurchaserinfo'));
		parent::display();
	}
	
	public function exportres() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 0);
		
		$input->set('view', $input->get('view', 'exportres'));
		parent::display();
	}
	
	public function tkexportres() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 1);
		
		$input->set('view', $input->get('view', 'exportres'));
		parent::display();
	}
	
	public function statistics() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 1);
		
		$input->set('view', $input->get('view', 'statistics'));
		parent::display();
	}
	
	public function tkstatistics() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 2);
		
		$input->set('view', $input->get('view', 'statistics'));
		parent::display();
	}
	
	public function printorders() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 1);
		$input->set('tmpl', 'component');
		
		$input->set('view', $input->get('view', 'printorders'));
		parent::display();
	}
	
	public function tkprintorders() {
		$input = JFactory::getApplication()->input;
		$input->set('type', 2);
		$input->set('tmpl', 'component');
		
		$input->set('view', $input->get('view', 'printorders'));
		parent::display();
	}
	
	public function managefile() {
		$input = JFactory::getApplication()->input;
		$input->set('tmpl', 'component');
		$input->set('view', $input->get('view', 'managefile'));
		parent::display();
	}

	public function tkmapareas() {
		$input = JFactory::getApplication()->input;
		$input->set('tmpl', 'component');
		$input->set('view', $input->get('view', 'tkmapareas'));
		parent::display();
	}

	public function media() {
		$input = JFactory::getApplication()->input;
		RestaurantsHelper::printMenu();
		
		$input->set('view', $input->get('view', 'media'));
		
		parent::display();
		
		RestaurantsHelper::printFooter();
	}

	public function updateprogram() {
		$input = JFactory::getApplication()->input;
		
		$input->set('view', $input->get('view', 'updateprogram'));
		
		parent::display();
	}
	
	// LANGUAGES
	
	public function langmenus() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langmenus'));
		parent::display();
	}
	
	public function newlangmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangmenu'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangmenu'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	public function langtkmenus() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langtkmenus'));
		parent::display();
	}
	
	public function newlangtkmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkmenu'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangtkmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkmenu'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	public function langtkproducts() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langtkproducts'));
		parent::display();
	}
	
	public function newlangtkproduct() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkproduct'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangtkproduct() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkproduct'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	public function langtktoppings() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langtktoppings'));
		parent::display();
	}
	
	public function newlangtktopping() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtktopping'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangtktopping() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtktopping'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	public function langtkattributes() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langtkattributes'));
		parent::display();
	}
	
	public function newlangtkattribute() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkattribute'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangtkattribute() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkattribute'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	public function langtkdeals() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langtkdeals'));
		parent::display();
	}
	
	public function newlangtkdeal() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkdeal'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangtkdeal() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangtkdeal'));
		$input->set('type', 'edit');
		
		parent::display();
	}

	public function langpayments() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langpayments'));
		parent::display();
	}

	public function newlangpayment() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangpayment'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangpayment() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangpayment'));
		$input->set('type', 'edit');
		
		parent::display();
	}

	public function langcustomf() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'langcustomf'));
		parent::display();
	}

	public function newlangcustomf() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangcustomf'));
		$input->set('type', 'new');
		
		parent::display();
	}

	public function editlangcustomf() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managelangcustomf'));
		$input->set('type', 'edit');
		
		parent::display();
	}
	
	// BUTTONS
	
	public function newtable() {
		$input = JFactory::getApplication()->input;
		// set default view if not set
		$input->set('view', $input->get('view', 'managetable'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetable folder.
		parent::display();
	}
	
	public function edittable() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetable'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetable folder.
		parent::display();
	}
	
	public function newroom() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageroom'));
		$input->set('type', 'new');
		
		// Go to view.html.php on manageroom folder.
		parent::display();
	}
	
	public function editroom() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageroom'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on manageroom folder.
		parent::display();
	}
	
	public function newroomclosure() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageroomclosure'));
		$input->set('type', 'new');
		
		// Go to view.html.php on manageroomclosure folder.
		parent::display();
	}
	
	public function editroomclosure() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageroomclosure'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on manageroomclosure folder.
		parent::display();
	}
	
	public function editmap() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemap'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managemap folder.
		parent::display();
	}
	
	public function newoperator() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageoperator'));
		$input->set('type', 'new');
		
		// Go to view.html.php on manageoperator folder.
		parent::display();
	}
	
	public function editoperator() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageoperator'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on manageoperator folder.
		parent::display();
	}
	
	public function newreservation() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managereservation'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managereservation folder.
		parent::display();
	}

	public function editreservation() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managereservation'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managereservation folder.
		parent::display();
	}
	
	public function newcustomer() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecustomer'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managecustomer folder.
		parent::display();
	}

	public function editcustomer() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecustomer'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managecustomer folder.
		parent::display();
	}
	
	public function newshift() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageshift'));
		$input->set('type', 'new');
	
		// Go to view.html.php on manageshift folder.
		parent::display();
	}
	
	public function editshift() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageshift'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on manageshift folder.
		parent::display();
	}
	
	public function newmenusproduct() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemenusproduct'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managemenusproduct folder.
		parent::display();
	}
	
	public function editmenusproduct() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemenusproduct'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managemenusproduct folder.
		parent::display();
	}
	
	public function newmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemenu'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managemenu folder.
		parent::display();
	}
	
	public function editmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemenu'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managemenu folder.
		parent::display();
	}

	public function editmenuordering() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemenuord'));
	
		// Go to view.html.php on managemenuord folder.
		parent::display();
	}
	
	public function newspecialday() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managespecialday'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managespecialday folder.
		parent::display();
	}
	
	public function editspecialday() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managespecialday'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managespecialday folder.
		parent::display();
	}
	
	public function newpayment() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managepayment'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managepayment folder.
		parent::display();
	}
	
	public function editpayment() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managepayment'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managepayment  folder.
		parent::display();
	}
	
	public function newcustomf() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecustomf'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managecustomf folder.
		parent::display();
	}
	
	public function editcustomf() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecustomf'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managecustomf folder.
		parent::display();
	}
	
	public function newrescode() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerescode'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managerescode folder.
		parent::display();
	}
	
	public function editrescode() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerescode'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managerescode folder.
		parent::display();
	}

	public function newrescodeorder() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerescodeorder'));
		$input->set('type', 'new');
	
		// Go to view.html.php on managerescodeorder folder.
		parent::display();
	}
	
	public function editrescodeorder() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerescodeorder'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managerescodeorder folder.
		parent::display();
	}

	public function newmedia() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'newmedia'));
		$input->set('type', 'new');
	
		// Go to view.html.php on newmedia folder.
		parent::display();
	}
	
	public function editmedia() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managemedia'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on managemedia folder.
		parent::display();
	}

	public function newinvoice() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'newinvoice'));
		$input->set('type', 'new');
	
		// Go to view.html.php on newinvoice folder.
		parent::display();
	}
	
	public function editinvoice() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageinvoice'));
		$input->set('type', 'edit');
	
		// Go to view.html.php on manageinvoice folder.
		parent::display();
	}

	public function flashupload() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'flashupload'));
		$input->set('tmpl', 'component');
	
		// Go to view.html.php on flashupload folder.
		parent::display();
	}
	
	public function editbill() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managebill'));
	
		// Go to view.html.php on managebill folder.
		parent::display();
	}

	public function newrev() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerev'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managerev folder.
		parent::display();
	}
	
	public function editrev() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managerev'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managecoupon folder.
		parent::display();
	}
	
	public function newcoupon() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecoupon'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managecoupon folder.
		parent::display();
	}
	
	public function editcoupon() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managecoupon'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managecoupon folder.
		parent::display();
	}
	
	public function newtkmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkmenu'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetkmenu folder.
		parent::display();
	}
	
	public function edittkmenu() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkmenu'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetkmenu folder.
		parent::display();
	}

	public function newtkentry() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkentry'));
		$input->set('type', 'new');

		// Go to view.html.php on managetkentry folder.
		parent::display();
	}
	
	public function edittkentry() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkentry'));
		$input->set('type', 'edit');

		// Go to view.html.php on managetkentry folder.
		parent::display();
	}
	
	public function newtkmenuattribute() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkmenuattr'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetkmenuattr folder.
		parent::display();
	}
	
	public function edittkmenuattribute() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkmenuattr'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetkmenuattr folder.
		parent::display();
	}
	
	public function newtktopping() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetktopping'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetktopping folder.
		parent::display();
	}
	
	public function edittktopping() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetktopping'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetktopping folder.
		parent::display();
	}
	
	public function newtktopseparator() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetktopseparator'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetktopseparator folder.
		parent::display();
	}
	
	public function edittktopseparator() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetktopseparator'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetktopseparator folder.
		parent::display();
	}
	
	public function newtkdeal() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkdeal'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetkdeal folder.
		parent::display();
	}
	
	public function edittkdeal() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkdeal'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetkdeal folder.
		parent::display();
	}

	public function newtkarea() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkarea'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetkarea folder.
		parent::display();
	}
	
	public function edittkarea() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkarea'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetkarea folder.
		parent::display();
	}
	
	public function newtkreservation() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkreservation'));
		$input->set('type', 'new');
		
		// Go to view.html.php on managetkreservation folder.
		parent::display();
	}
	
	public function edittkreservation() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkreservation'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on managetkreservation folder.
		parent::display();
	}
	
	public function managetkrescart() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'managetkrescart'));
		
		// Go to view.html.php on managetkrescart folder.
		parent::display();
	}

	public function newapiuser() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageapiuser'));
		$input->set('type', 'new');
		
		// Go to view.html.php on manageapiuser folder.
		parent::display();
	}
	
	public function editapiuser() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageapiuser'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on manageapiuser folder.
		parent::display();
	}

	public function newapiplugin() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageapiplugin'));
		$input->set('type', 'new');
		
		// Go to view.html.php on manageapiplugin folder.
		parent::display();
	}
	
	public function editapiplugin() {
		$input = JFactory::getApplication()->input;
		$input->set('view', $input->get('view', 'manageapiplugin'));
		$input->set('type', 'edit');
		
		// Go to view.html.php on manageapiplugin folder.
		parent::display();
	}
	
	// UTILITY
	
	private function getLastTablePosition($id_room, $table_spacing, $max_w, $default_pos = array('x' => 0, 'y' => 0)) {
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `design_data` FROM `#__cleverdine_table` WHERE `id_room`=$id_room ORDER BY `id` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			return $default_pos;
		}
		
		$prop = json_decode($dbo->loadResult(), true);
		
		$x = (int)$prop['pos']['left'];
		$y = (int)$prop['pos']['top'];
		
		$w = $prop['size']['width'];
		$h = $prop['size']['height'];
		
		if( $x+$w*2+$table_spacing > $max_w ) {
			if( $x != $default_pos['x'] ) {
				$y += $h+$table_spacing;
			}
			$x = $default_pos['x'];
		} else {
			$x += $table_spacing+$w;
		}
		
		return array( 'x' => $x, 'y' => $y );
	}
	
	public function createTableProperties($x, $y, $w, $h, $rot, $bgc) {
		$prop = array();
		$prop['pos'] 		= array( 'left' => $x, 'top' => $y );
		$prop['size'] 		= array( 'width' => $w, 'height' => $h );
		$prop['rotation'] 	= $rot;
		$prop['bgcolor'] 	= $bgc;

		return $prop;
	}
	
	// SAVE TABLE
	
	public function saveAndNewTable() {
		$this->saveTable('index.php?option=com_cleverdine&task=newtable');
	}
	
	public function saveAndCloseTable() {
		$this->saveTable('index.php?option=com_cleverdine&task=tables');
	}
	
	public function saveTable($redirect_url = '') {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['min_capacity'] 	= $input->get('min_capacity', 0, 'int');
		$args['max_capacity'] 	= $input->get('max_capacity', 0, 'int');
		$args['multi_res'] 		= $input->get('multi_res', 0, 'uint');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['id_room'] 		= $input->get('id_room', 0, 'int');
		$args['id'] 			= $input->get('id', 0, 'int');
		$args['design_data'] 	= null;
		
		$blank_keys = RestaurantsHelper::validateTable($args);
		
		if( count( $blank_keys ) == 0 && $args['min_capacity'] <= $args['max_capacity'] ) {
			$dbo = JFactory::getDbo();
			
			if( $args['id'] == -1 ) {
				$last_table_position = $this->getLastTablePosition($args['id_room'], 60, 800, array("x" => 40, "y" => 40));
				// evaluating size properties
				$width = max(90, ($args['max_capacity']-2)*45);
				$height = 90; // always 90
				//
				$args['design_data'] = json_encode( $this->createTableProperties( $last_table_position['x'], $last_table_position['y'], $width, $height, 0, -1 ) );
				$args['id'] = $this->saveNewTable($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTable($args, $dbo, $mainframe);
			}

		} else {
			$errCode = null;
			if( count( $blank_keys ) > 0 ) {
				$errCode = "VRREQUIREDFIELDSERROR";
			}
			else {
				$errCode = "VRMINGREATERTHANMAX";
			}
			$mainframe->enqueueMessage(JText::_($errCode), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtable" : "edittable&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittable&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTable($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_table` (`name`, `min_capacity`, `max_capacity`, `multi_res`, `published`, `design_data`, `id_room`) VALUES(".
		$dbo->quote($args['name']).",".
		$args['min_capacity'].",".
		$args['max_capacity'].",".
		$args['multi_res'].",".
		$args['published'].",".
		$dbo->quote($args['design_data']).",".
		$args['id_room'].
		");";

		$dbo->setQuery($q);
		$dbo->execute();
		
		$lid = $dbo->insertid();

		// mainframe can be null because this function is used also from the MAP
		// when mainframe is null, do not display messages

		if( $mainframe ) {
			if( $lid > 0 ) {
				$mainframe->enqueueMessage(JText::_('VRNEWTABLECREATED1'));
			} else {
				$mainframe->enqueueMessage(JText::_('VRNEWTABLECREATED0'), 'error');
			}
		}

		return $lid;
	}
	
	private function editSelectedTable($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_table` SET 
		`name`=".$dbo->quote($args['name']).",
		`min_capacity`=".$args['min_capacity'].",
		`max_capacity`=".$args['max_capacity'].",
		`multi_res`=".$args['multi_res'].",
		`published`=".$args['published'].",
		".(($args['design_data'] != null ) ? " `design_data`=".$dbo->quote($args['design_data'])."," : "")."
		`id_room`=".$args['id_room']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		// mainframe can be null because this function is used also from the MAP
		// when mainframe is null, do not display messages

		if( $mainframe && $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRTABLEEDITED1'));
		}

	}
	
	// SAVE ROOM
	
	public function saveAndNewRoom() {
		$this->saveRoom('index.php?option=com_cleverdine&task=newroom');
	}
	
	public function saveAndCloseRoom() {
		$this->saveRoom('index.php?option=com_cleverdine&task=rooms');
	}
	
	public function saveRoom($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->getString('name');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['published'] 		= $input->getUint('published', 0);
		$args['image'] 			= $input->getString('image');
		$args['id'] 			= $input->getInt('id');
		
		$blank_keys = RestaurantsHelper::validateRoom($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewRoom($args, $dbo, $mainframe);
			} else {
				$this->editSelectedRoom($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newroom" : "editroom&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editroom&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewRoom($args, $dbo, $mainframe) {
		
		$args['graphics_properties'] = json_encode(RestaurantsHelper::getDefaultGraphicsProperties());
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_room`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_room`(`name`,`description`,`published`,`image`,`graphics_properties`,`ordering`) VALUES( ".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['description']).",".
		$args['published'].",".
		$dbo->quote($args['image']).",".
		$dbo->quote($args['graphics_properties']).",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();

		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWROOMCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWROOMCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedRoom($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_room` SET 
		`name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).", 
		`published`=".$args['published'].", 
		`image`=".$dbo->quote($args['image'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRROOMEDITED1'));
		}
	}
	
	// SAVE ROOM CLOSURE
	
	public function saveAndNewRoomClosure() {
		$this->saveRoomClosure('index.php?option=com_cleverdine&task=newroomclosure');
	}
	
	public function saveAndCloseRoomClosure() {
		$this->saveRoomClosure('index.php?option=com_cleverdine&task=roomclosures');
	}
	
	public function saveRoomClosure($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['id_room'] 	= $input->get('id_room', 0, 'int');
		$args['start_date'] = $input->get('start_date', '', 'string');
		$args['start_hour'] = $input->get('start_hour', 0, 'int');
		$args['start_min'] 	= $input->get('start_min', 0, 'int');
		$args['end_date'] 	= $input->get('end_date', '', 'string');
		$args['end_hour'] 	= $input->get('end_hour', 0, 'int');
		$args['end_min'] 	= $input->get('end_min', 0, 'int');
		$args['id'] 		= $input->get('id', 0, 'int');
		
		$args['start_ts'] 	= cleverdine::createTimestamp($args['start_date'], $args['start_hour'], $args['start_min']);
		$args['end_ts'] 	= cleverdine::createTimestamp($args['end_date'], $args['end_hour'], $args['end_min']);
		
		$blank_keys = array();
		if( $args['start_ts'] == -1 || $args['start_ts'] >= $args['end_ts'] ) {
			array_push($blank_keys, 'start_ts');
		}
		if( $args['end_ts'] == -1 || $args['start_ts'] >= $args['end_ts'] ) {
			array_push($blank_keys, 'end_ts');
		} 
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewRoomClosure($args, $dbo, $mainframe);
			} else {
				$this->editSelectedRoomClosure($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newroomclosure" : "editroomclosure&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editroomclosure&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewRoomClosure($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_room_closure`(`id_room`,`start_ts`,`end_ts`) VALUES( ".
		$args['id_room'].",".
		$args['start_ts'].",".
		$args['end_ts'].
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWROOMCLOSURECREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWROOMCLOSURECREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedRoomClosure($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_room_closure` SET  
		`id_room`=".$args['id_room'].",
		`start_ts`=".$args['start_ts'].",
		`end_ts`=".$args['end_ts']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRROOMCLOSUREEDITED1'));
		}
	}
	
	// MAP
	
	public function reloadMap() {
		$mainframe = JFactory::getApplication();
		$id = $mainframe->input->get('id', 0, 'int');
		$mainframe->redirect('index.php?option=com_cleverdine&task=editmap&selectedroom='.$id);
	}
	
	public function saveandcloseMap() {
		$this->saveMap(true);
	}
	
	public function saveMap($close=false) {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		$id_room = $input->get('id', 0, 'int');
		
		// SAVE TABLES PROPERTIES
		
		$locations 	= $input->get('vrt_pos', array(), 'array');
		$dimensions = $input->get('vrt_size', array(), 'array');
		$rotation 	= $input->get('vrt_rot', array(), 'array');
		$bgcolor 	= $input->get('vrt_bgc', array(), 'array');
		$names 		= $input->get('vrt_name', array(), 'array');
		$seats 		= $input->get('vrt_seats', array(), 'array');
		$multi_res 	= $input->get('vrt_multires', array(), 'array');
		$ids 		= $input->get('vrt_id', array(), 'array');
		
		$removed_tables = $input->get('tablesremoved', array(), 'array');
		
		$offset = explode( "_", $input->get('offset', '', 'string') );
		
		for( $i = 0, $n = count($ids); $i < $n; $i++ ) {
			$seats_split = explode("_", $seats[$i]);
			// encode properties
			$cur_loc_arr = explode("_", $locations[$i]);
			$cur_dim_arr = explode("_", $dimensions[$i]);
			// 11 = padding
			$properties = $this->createTableProperties($cur_loc_arr[0]-$offset[0]-11,$cur_loc_arr[1]-$offset[1]-11,$cur_dim_arr[0],$cur_dim_arr[1],$rotation[$i],$bgcolor[$i]);
			// end encoding
			
			$args = array( 
				'id' => $ids[$i], 
				'name' => $names[$i],
				'min_capacity' => $seats_split[0],
				'max_capacity' => $seats_split[1],
				'multi_res' => $multi_res[$i],
				'published' => 1,
				'design_data' => json_encode($properties),
				'id_room' => $id_room
			);
			if( $ids[$i] != '-1' ) {
				$this->editSelectedTable($args, $dbo, null);
			} else {
				$this->saveNewTable($args, $dbo, null);
			}
		}
		
		$this->deleteTablesByIds($removed_tables);
		
		$mainframe->enqueueMessage(JText::_('VRMAPUPDATED'));
		if( $close ) {
			$mainframe->redirect("index.php?option=com_cleverdine&task=maps");
		} else {
			$mainframe->redirect('index.php?option=com_cleverdine&task=editmap&selectedroom='.$id_room);
		}
	}

	public function store_graphics_properties() {

		$input = JFactory::getApplication()->input;
		
		$id_room = $input->get('id_room', 0, 'int');
		
		$args = array();
		$args['prefix'] 		= $input->get('prefix', '', 'string');
		$args['people'] 		= $input->get('people', 0, 'int');
		$args['start_x'] 		= $input->get('start_x', 0, 'int');
		$args['start_y'] 		= $input->get('start_y', 0, 'int');
		$args['minwidth'] 		= $input->get('minwidth', 0, 'int');
		$args['minheight'] 		= $input->get('minheight', 0, 'int');
		$args['wpp'] 			= $input->get('wpp', 0, 'int');
		$args['hpp'] 			= $input->get('hpp', 0, 'int');
		$args['hor_spacing'] 	= $input->get('hor_spacing', 0, 'int');
		$args['ver_spacing'] 	= $input->get('ver_spacing', 0, 'int');
		$args['color'] 			= $input->get('color', '', 'string');
		$args['mapwidth'] 		= $input->get('mapwidth', 0, 'int');
		$args['mapheight'] 		= $input->get('mapheight', 0, 'int');
		$args['display_next'] 	= $input->get('display_next', 0, 'int');
		
		$dbo = JFactory::getDbo();
		
		$q = "UPDATE `#__cleverdine_room` SET `graphics_properties`=".$dbo->quote(json_encode($args))." WHERE `id`=".$id_room." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		die;
	} 

	// SAVE OPERATOR
	
	public function saveAndNewOperator() {
		$this->saveOperator('index.php?option=com_cleverdine&task=newoperator');
	}
	
	public function saveAndCloseOperator() {
		$this->saveOperator('index.php?option=com_cleverdine&task=operators');
	}

	public function saveOperator($redirect_url = '') {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['code'] 				= $input->get('code', '', 'string');
		$args['firstname'] 			= $input->get('firstname', '', 'string');
		$args['lastname'] 			= $input->get('lastname', '', 'string');
		$args['email'] 				= $input->get('email', '', 'string');
		$args['phone_number'] 		= $input->get('phone_number', '', 'string');
		$args['can_login'] 			= $input->get('can_login', 0, 'uint');
		$args['keep_track'] 		= $input->get('keep_track', 0, 'uint');
		$args['mail_notifications'] = $input->get('mail_notifications', 0, 'uint');
		$args['manage_coupon']		= $input->get('manage_coupon', 0, 'uint');
		$args['group'] 				= $input->get('group', 0, 'uint');
		$args['jid'] 				= $input->get('jid', 0, 'int');
		$args['id'] 				= $input->get('id', 0, 'int');

		// keep active tab in session
		$mainframe->getUserStateFromRequest('operator.activetab', 'active_tab', 'operator_details', 'string');
		
		$blank_keys = RestaurantsHelper::validateOperator($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
			
			if( empty($args['jid']) || $args['jid'] == -1 ) {
				$args['usertype'] 		= $input->get('usertype', array(), 'uint');
				$args['username'] 		= $input->get('username', '', 'string');
				$args['usermail']		= $args['email'];
				$args['user_pwd1'] 		= $input->get('password', '', 'string');
				$args['user_pwd2'] 		= $input->get('confpassword', '', 'string');

				$args['log'] = 1; // show error messages
				if( RestaurantsHelper::checkUserArguments($args, true) ) {
					$args['jid'] = RestaurantsHelper::createNewJoomlaUser($args);
					if( $args['jid'] === false ) {
						$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
						$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newoperator" : "editoperator&cid[]=".$args['id'] ) );
					}
				} else {
					$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
					$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newoperator" : "editoperator&cid[]=".$args['id'] ) );
				}
			}
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewOperator($args, $dbo, $mainframe);
			} else {
				$this->editSelectedOperator($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newoperator" : "editoperator&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editoperator&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewOperator($args, $dbo, $mainframe) {
		
		if( empty($args['code']) ) {
			$args['code'] = rand(1, 99);
		}
		
		$q = "INSERT INTO `#__cleverdine_operator`(`code`,`firstname`,`lastname`,`phone_number`,`email`,`can_login`,`keep_track`,`mail_notifications`,`manage_coupon`,`group`,`jid`) VALUES( ".
		$dbo->quote($args['code']).",".
		$dbo->quote($args['firstname']).",".
		$dbo->quote($args['lastname']).",".
		$dbo->quote($args['phone_number']).",".
		$dbo->quote($args['email']).",".
		$args['can_login'].",".
		$args['keep_track'].",".
		$args['mail_notifications'].",".
		$args['manage_coupon'].",".
		$args['group'].",".
		$args['jid'].
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWOPERATORCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWOPERATORCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedOperator($args, $dbo, $mainframe) {
		
		if( empty($args['code']) ) {
			$args['code'] = rand(1, 99);
		}
		
		$q = "UPDATE `#__cleverdine_operator` SET 
		`code`=".$dbo->quote($args['code']).",
		`firstname`=".$dbo->quote($args['firstname']).",
		`lastname`=".$dbo->quote($args['lastname']).",
		`phone_number`=".$dbo->quote($args['phone_number']).",
		`email`=".$dbo->quote($args['email']).",
		`can_login`=".$args['can_login'].",
		`keep_track`=".$args['keep_track'].",
		`mail_notifications`=".$args['mail_notifications'].",
		`manage_coupon`=".$args['manage_coupon'].",
		`group`=".$args['group'].",
		`jid`=".$args['jid']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VROPERATOREDITED1'));
		}

	}

	// SAVE RESERVATIONS
	
	public function saveAndNewReservation() {
		$from = JFactory::getApplication()->input->get('from');
		if( !empty($from) ) {
			$from = '&from='.$from;
		}

		$this->saveReservation('index.php?option=com_cleverdine&task=newreservation'.$from);
	}
	
	public function saveAndCloseReservation() {
		$from = JFactory::getApplication()->input->get('from');
		if( empty($from) ) {
			$from = 'reservations';
		}

		$this->saveReservation('index.php?option=com_cleverdine&task='.$from);
	}

	public function saveReservation($redirect_url = '') {
		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$args = array();
		$args['date'] 					= $input->getString('date', '');
		$args['hourmin'] 				= $input->getString('hourmin', '');
		$args['id_table'] 				= $input->getUint('id_table', 0);
		$args['people'] 				= $input->getUint('people', 0);
		$args['purchaser_nominative'] 	= $input->getString('purchaser_nominative', '');
		$args['purchaser_mail'] 		= $input->getString('purchaser_mail', '');
		$args['purchaser_phone'] 		= $input->getString('purchaser_phone', '');
		$args['deposit'] 				= $input->getFloat('deposit', 0.0);
		$args['bill_value'] 			= $input->getFloat('bill_value', 0.0);
		$args['bill_closed'] 			= $input->getUint('bill_closed', 0);
		$args['status'] 				= $input->getString('status', '');
		$args['id_payment'] 			= $input->getUint('id_payment', 0);
		$args['notify_customer'] 		= $input->getUint('notify_customer', 0);
		$args['notes'] 					= $input->get('notes', '', 'raw');
		$args['quantity'] 				= $input->get('quantity', array(), 'array'); // menus quantity
		$args['menu_assoc'] 			= $input->get('menu_assoc', array(), 'array'); // menus assoc ids
		$args['stay_time'] 				= $input->getUint('stay_time', 0);
		$args['id'] 					= $input->getInt('id', 0);
		
		$args['created_by'] = JFactory::getUser()->id;
		$args['id_user'] 	= $input->getUint('id_user', 0);

		if( empty($args['id_user']) ) {
			$args['id_user'] = -1;
		}

		if( empty($args['id_payment']) ) {
			$args['id_payment'] = -1;
		}
		
		$args['phone_prefix'] 		= $input->getString('phone_prefix', '');
		$args['purchaser_prefix'] 	= '';
		$args['purchaser_country'] 	= '';
		
		$_cf = array();
		$p_name = $p_mail = $p_phone = $p_prefix = $p_country_code = "";
		
		$user_arr = array();
		$q = "SELECT `u`.*, `c`.`phone_prefix` FROM `#__cleverdine_users` AS `u` 
		LEFT JOIN `#__cleverdine_countries` AS `c` ON `c`.`country_2_code`=`u`.`country_code` WHERE `u`.`id`=".$args['id_user']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$user_arr = $dbo->loadAssoc();
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=0 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0)  
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		$blank_keys = array();
		$_i = 0;
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');
			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']]) ) {
				// IF YOU WANT TO MAKE CUSTOM FIELDS REQUIRED, DECOMMENT THESE LINES
				//$blank_keys[$_i] = 'vrcf'.$_app['id'];
				//$_i++;
			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {
				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];
			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {
				$p_mail = $cust_req[$_app['name']];
			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {
				$p_phone = $cust_req[$_app['name']];
				
				if( !empty($p_phone) ) {
					$country_key = $input->get('vrcf'.$_app['id'].'_prfx', '', 'string');
					if( !empty($country_key) ) {
						$country_key = explode('_', $country_key);
						$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						if( $dbo->getNumRows() > 0 ) {
							$country = $dbo->loadAssoc();
							$p_prefix = $country['phone_prefix'];
							$p_country_code = $country['country_2_code'];
						}
					}
					$p_phone = str_replace(' ', '', $cust_req[$_app['name']]);
				}
			}
		}
		
		if( strlen( $args['purchaser_nominative'] ) == 0 ) {
			$args['purchaser_nominative'] = $p_name;
		}
		
		if( strlen( $args['purchaser_mail'] ) == 0 ) {
			$args['purchaser_mail'] = $p_mail;
		}
		
		if( strlen( $args['purchaser_phone'] ) == 0 ) {
			$args['purchaser_phone'] = $p_phone;
			$p_prefix = $user_arr['phone_prefix'];
			$p_country_code = $user_arr['country_code'];
		}
		
		if( (empty($p_prefix) || empty($p_country_code)) && !empty($args['purchaser_phone']) ) {
			$country_key = $args['phone_prefix'];
			if( !empty($country_key) ) {
				$country_key = explode('_', $country_key);
				$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$country = $dbo->loadAssoc();
					$p_prefix = $country['phone_prefix'];
					$p_country_code = $country['country_2_code'];
				}
			}
		}
		
		$args['purchaser_prefix'] = $p_prefix;
		$args['purchaser_country'] = $p_country_code;
		
		$_bk = RestaurantsHelper::validateReservation($args);
		$blank_keys = RestaurantsHelper::mergeIndexedArray($blank_keys, $_bk);
		
		if( $args['id'] == -1 ) { // ignore validation if the reservation already exists
			$error_type_message = 1;
			$_resp = cleverdine::isRequestReservationValid($args);
			if( $_resp != 0 ) {
				$_MSG = array( 
					array( 'date' ),
					array( 'hourmin' ),
					array( 'date','hourmin' ),
					array( 'people' ),
					array( 'date', 'hourmin' ) );
				$blank_keys = RestaurantsHelper::mergeIndexedArray($blank_keys,$_MSG[$_resp-1]);
				$error_type_message = 2;
			} else {
				$_app = explode(':',$args['hourmin']);
				$args['hour'] = $_app[0];
				$args['min'] = $_app[1];
			}
		} else {
			$_app = explode(':',$args['hourmin']);
			$args['hour'] = $_app[0];
			$args['min'] = $_app[1];
		}
		
		$args['custom_f'] = $cust_req;
		
		$args['table'] = $args['id_table'];
		
		$tb_available = true;

		/*
		SKIP TABLE VALIDATION

		if( count($blank_keys) == 0 ) {
			if( $args['id'] == -1 ) {
				$q = cleverdine::getQueryTableJustReserved($args,true);
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() == 0 ) {
					$tb_available = false;
				}
			} else {
				$q = cleverdine::getQueryTableJustReservedExcludingResId($args,$args['id'],true);
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() == 0 ) {
					$tb_available = false;
				}
			}
		}
		*/
		
		if( count( $blank_keys ) == 0 && $tb_available == true ) {
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewReservation($args, $dbo, $mainframe);
			} else {
				$this->editSelectedReservation($args, $dbo, $mainframe);
			}

		} else {
			if( $tb_available ) {
				if( $error_type_message == 1 ) {
					$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
				} else {
					$mainframe->enqueueMessage(JText::_(cleverdine::getResponseFromReservationRequest($_resp)) , 'error');
				}
			} else {
				$mainframe->enqueueMessage(JText::_('VRRESERVATIONTABNOAV') , 'error');
			}
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newreservation" : "editreservation&cid[]=".$args['id'] ) );
		}
		
		$i = 0;
		foreach( $args['quantity'] as $id_menu => $quant ) {
			$q = "";
			if( !empty($args['menu_assoc'][$i]) ) { // exists
				if( $quant > 0 ) { // update
					$q = "UPDATE `#__cleverdine_res_menus_assoc` SET `quantity`=".$quant." WHERE `id`=".$args['menu_assoc'][$i]." LIMIT 1;";
				} else { // remove
					$q = "DELETE FROM `#__cleverdine_res_menus_assoc` WHERE `id`=".$args['menu_assoc'][$i]." LIMIT 1;";
				}
			} else { // not exists
				if( $quant > 0 ) { // insert
					$q = "INSERT INTO `#__cleverdine_res_menus_assoc` (`id_reservation`, `id_menu`, `quantity`) VALUES (".$args['id'].",".$id_menu.",".$quant.");";
				} // else do nothing
			}
			
			if( !empty($q) ) {
				$dbo->setQuery($q);
				$dbo->execute();
			}
			
			$i++;
		}
		
		if( $args['notify_customer'] == 1 ) {
			$order_details = cleverdine::fetchOrderDetails($args['id']);
			cleverdine::sendCustomerEmail($order_details, true);
		}
		
		if( empty($redirect_url) ) {
			$from = JFactory::getApplication()->input->get('from');
			if( !empty($from) ) {
				$from = '&from='.$from;
			}

			$redirect_url = 'index.php?option=com_cleverdine&task=editreservation&cid[]='.$args['id'].$from;
		}
		
		$mainframe->redirect($redirect_url);
		
	}
	
	private function saveNewReservation($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_reservation` 
		(`checkin_ts`,`id_table`,`id_payment`,`people`,`custom_f`,`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`purchaser_prefix`,`purchaser_country`,`deposit`,`bill_value`,`bill_closed`,`status`,`locked_until`,`sid`,`notes`,`created_on`,`created_by`,`id_user`,`stay_time`) VALUES(".
		cleverdine::createTimestamp($args['date'],$args['hour'],$args['min'], true).",".
		$args['id_table'].",".
		$args['id_payment'].",".
		$args['people'].",".
		$dbo->quote(json_encode($args['custom_f'])).",".
		$dbo->quote($args['purchaser_nominative']).",".
		$dbo->quote($args['purchaser_mail']).",".
		$dbo->quote($args['purchaser_phone']).",".
		$dbo->quote($args['purchaser_prefix']).",".
		$dbo->quote($args['purchaser_country']).",".
		$args['deposit'].",".
		$args['bill_value'].",".
		$args['bill_closed'].",".
		$dbo->quote($args['status']).",".
		(time()+cleverdine::getTablesLockedTime(true)*60).",".
		$dbo->quote(cleverdine::generateSerialCode(16)).",".
		$dbo->quote($args['notes']).",".
		time().",".
		$args['created_by'].",".
		$args['id_user'].",".
		$args['stay_time'].
		");";

		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();

		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWRESERVATIONCREATED1'));
			
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $lid, cleverdine::OPERATOR_RESTAURANT_LOG, cleverdine::OPERATOR_RESTAURANT_INSERT);
				cleverdine::storeOperatorLog($operator['id'], $lid, $log, cleverdine::OPERATOR_RESTAURANT_LOG);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWRESERVATIONCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedReservation($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_reservation` SET 
		`checkin_ts`=".cleverdine::createTimestamp($args['date'],$args['hour'],$args['min'],true).", 
		`id_table`=".$args['id_table'].",
		`id_payment`=".$args['id_payment'].",
		`people`=".$args['people'].", 
		`custom_f`=".$dbo->quote(json_encode($args['custom_f'])).",
		`purchaser_nominative`=".$dbo->quote($args['purchaser_nominative']).",
		`purchaser_mail`=".$dbo->quote($args['purchaser_mail']).",
		`purchaser_phone`=".$dbo->quote($args['purchaser_phone']).", 
		`purchaser_prefix`=".$dbo->quote($args['purchaser_prefix']).", 
		`purchaser_country`=".$dbo->quote($args['purchaser_country']).", 
		`deposit`=".$args['deposit'].", 
		`bill_value`=".$args['bill_value'].", 
		`bill_closed`=".$args['bill_closed'].", 
		`status`='".$args['status']."', 
		`notes`=".$dbo->quote($args['notes']).",
		`locked_until`=".(time()+cleverdine::getTablesLockedTime(true)*60).",
		`id_user`=".$args['id_user'].",
		`stay_time`=".$args['stay_time']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRRESERVATIONEDITED1'));
			
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $args['id'], cleverdine::OPERATOR_RESTAURANT_LOG, cleverdine::OPERATOR_RESTAURANT_UPDATE);
				cleverdine::storeOperatorLog($operator['id'], $args['id'], $log, cleverdine::OPERATOR_RESTAURANT_LOG);
			}
		}
		
	}

	// SAVE CUSTOMER
	 
	public function saveAndCloseCustomer() {
		$this->saveCustomer('index.php?option=com_cleverdine&task=customers');
	}
	
	public function saveAndNewCustomer() {
		$this->saveCustomer('index.php?option=com_cleverdine&task=newcustomer');
	}
	 
	public function saveCustomer($return_url = '') {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$session = JFactory::getSession();
		$session->set('customer_active_tab', $input->get('active_tab'), 'vre');	
		
		$args = array();
		$args['jid'] 				= $input->get('jid', 0, 'int');
		$args['create_new_user'] 	= $input->get('create_new_user', 0, 'int');
		$args['billing_name'] 		= $input->get('billing_name', '', 'string');
		$args['billing_mail'] 		= $input->get('billing_mail', '', 'string');
		$args['billing_phone'] 		= $input->get('billing_phone', '', 'string');
		$args['country_code'] 		= $input->get('country_code', '', 'string');
		$args['billing_state'] 		= $input->get('billing_state', '', 'string');
		$args['billing_city'] 		= $input->get('billing_city', '', 'string');
		$args['billing_address'] 	= $input->get('billing_address', '', 'string');
		$args['billing_address_2'] 	= $input->get('billing_address_2', '', 'string');
		$args['billing_zip'] 		= $input->get('billing_zip', '', 'string');
		$args['company'] 			= $input->get('company', '', 'string');
		$args['vatnum'] 			= $input->get('vatnum', '', 'string');
		$args['ssn'] 				= $input->get('ssn', '', 'string');
		$args['notes'] 				= $input->get('notes', '', 'string');
		$args['id'] 				= $input->get('id', 0, 'int');
		
		$valid = 1;
		
		if( $args['create_new_user'] ) {
			$args['username'] = $input->get('username', '', 'string');
			if( empty($args['username']) ) {
				$args['username'] = $args['billing_name'];
			}
			$args['usermail'] = $input->get('usermail', '', 'string');
			if( empty($args['usermail']) ) {
				$args['usermail'] = $args['billing_mail'];
			}
			$args['user_pwd1'] = $input->get('user_pwd1', '', 'string');
			$args['user_pwd2'] = $input->get('user_pwd2', '', 'string');
			
			if( empty($args['user_pwd1']) || empty($args['user_pwd2']) ) {
				$valid = -1;
			} else if( strcmp($args['user_pwd1'], $args['user_pwd2']) ) {
				$valid = -2;
			}
		}
		
		$dbo = JFactory::getDbo();

		$cust_req = array( array(), array() );

		for( $i = 0; $i < 2; $i++ ) {
			// cycle 0 : restaurant custom fields
			// cycle 1 : take-away custom fields
			
			$q = "SELECT * FROM `#__cleverdine_custfields` 
			WHERE `group`=$i AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0) 
			ORDER BY `ordering` ASC;";

			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {

				foreach( $dbo->loadAssocList() as $_app ) {
					$cust_req[$i][$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');
				}

			}

		}

		$args['fields'] = json_encode($cust_req[0]);
		$args['tkfields'] = json_encode($cust_req[1]);
		
		$blank_keys = RestaurantsHelper::validateCustomer($args);
		if( count($blank_keys) > 0 ) {
			$valid = -3;
		}
		
		if( $valid == -2 || ($valid == -1 && empty($args['user_pwd1'])) ) {
			array_push($blank_keys, 'user_pwd1');
		}
		
		if( $valid == -2 || ($valid == -1 && empty($args['user_pwd2'])) ) {
			array_push($blank_keys, 'user_pwd2');
		}
		
		if( $valid == 1 && empty($args['jid']) && $args['create_new_user'] ) {
			$args['jid'] = intval(RestaurantsHelper::createNewJoomlaUser($args));
		}

		$create = false;
		
		if( $valid == 1 ) {

			if( empty($args['jid']) ) {
				$args['jid'] = -1;
			}

			// prepare plugin

			$options = array(
				'alias' 	=> 'com_cleverdine',
				'version' 	=> cleverdine_SOFTWARE_VERSION,
				'admin' 	=> $mainframe->isAdmin(),
				'call' 		=> __FUNCTION__
			);

			JPluginHelper::importPlugin('e4j');
			$dispatcher = JEventDispatcher::getInstance();

			//
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewCustomer($args, $dbo, $mainframe);
				$create = true;

				// trigger plugin -> customer creation
				$dispatcher->trigger('onCustomerInsert', array(&$args, &$options));
			} else {
				$this->editSelectedCustomer($args, $dbo, $mainframe);

				// trigger plugin -> customer update
				$dispatcher->trigger('onCustomerUpdate', array(&$args, &$options));
			}
			
		} else {
			$mainframe->enqueueMessage(JText::_("VRMANAGECUSTOMERERR".abs($valid)), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newcustomer".$attach_url : "editcustomer&cid[]=".$args['id'] ) );
			exit;
		}

		if( $args['id'] > 0 ) {

			$delete_delivery_id = $input->get('delete_delivery', array(), 'array');
			foreach( $delete_delivery_id as $d_id ) {
				$q = "DELETE FROM `#__cleverdine_user_delivery` WHERE `id`=".intval($d_id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}

			$delivery = array();
			$delivery['country'] 	= $input->get('delivery_country', array(), 'array');
			$delivery['state'] 		= $input->get('delivery_state', array(), 'array');
			$delivery['city'] 		= $input->get('delivery_city', array(), 'array');
			$delivery['address'] 	= $input->get('delivery_address', array(), 'array');
			$delivery['address_2'] 	= $input->get('delivery_address_2', array(), 'array');
			$delivery['zip'] 		= $input->get('delivery_zip', array(), 'array');
			$delivery['id'] 		= $input->get('delivery_id', array(), 'array');

			// if customer has been created and there is no delivery address available
			// try to use the billing address also as delivery
			if( $create && count($delivery['id']) == 1 && empty($delivery['address'][0]) ) {
				$delivery['country'][0] 	= $args['country_code'];
				$delivery['state'][0] 		= $args['billing_state'];
				$delivery['city'][0] 		= $args['billing_city'];
				$delivery['address'][0] 	= $args['billing_address'];
				$delivery['address_2'][0] 	= $args['billing_address_2'];
				$delivery['zip'][0] 		= $args['billing_zip'];
			}

			for( $i = 0; $i < count($delivery['id']); $i++ ) {

				if( $delivery['id'][$i] > 0 ) {
					$q = "UPDATE `#__cleverdine_user_delivery` SET `ordering`=".($i+1).",
					`country`=".$dbo->quote($delivery['country'][$i]).",
					`state`=".$dbo->quote($delivery['state'][$i]).",
					`city`=".$dbo->quote($delivery['city'][$i]).",
					`address`=".$dbo->quote($delivery['address'][$i]).",
					`address_2`=".$dbo->quote($delivery['address_2'][$i]).",
					`zip`=".$dbo->quote($delivery['zip'][$i])." 
					WHERE `id`=".$delivery['id'][$i]." LIMIT 1;";

					$dbo->setQuery($q);
					$dbo->execute();

				} else if( !empty($delivery['address'][$i]) && !empty($delivery['zip'][$i]) ) {
					$q = "INSERT INTO `#__cleverdine_user_delivery` (`id_user`,`country`,`state`,`city`,`address`,`address_2`,`zip`,`ordering`) VALUES(".
						$args['id'].",".
						$dbo->quote($delivery['country'][$i]).",".
						$dbo->quote($delivery['state'][$i]).",".
						$dbo->quote($delivery['city'][$i]).",".
						$dbo->quote($delivery['address'][$i]).",".
						$dbo->quote($delivery['address_2'][$i]).",".
						$dbo->quote($delivery['zip'][$i]).",".
						($i+1).
					");";

					$dbo->setQuery($q);
					$dbo->execute();

				}

			}

		}
		
		if( empty($return_url) ) {
			$return_url = 'index.php?option=com_cleverdine&task=editcustomer&cid[]='.$args['id'];
			if( $input->get('tmpl') == 'component' ) {
				// maintain tmpl=component for customer creation within managereservation page
				$return_url .= '&tmpl=component';
			}
		}

		$mainframe->redirect($return_url);
	}

	private function saveNewCustomer($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO 
		`#__cleverdine_users` ( `jid`,`fields`,`tkfields`,`billing_name`,`billing_mail`,`billing_phone`,`country_code`,`billing_state`,`billing_city`,
		`billing_address`,`billing_address_2`,`billing_zip`,`company`,`vatnum`,`ssn`,`notes` ) 
		VALUES(".
		$args['jid'].",".
		$dbo->quote($args['fields']).",".
		$dbo->quote($args['tkfields']).",".
		$dbo->quote($args['billing_name']).",".
		$dbo->quote($args['billing_mail']).",".
		$dbo->quote($args['billing_phone']).",".
		$dbo->quote($args['country_code']).",".
		$dbo->quote($args['billing_state']).",".
		$dbo->quote($args['billing_city']).",".
		$dbo->quote($args['billing_address']).",".
		$dbo->quote($args['billing_address_2']).",".
		$dbo->quote($args['billing_zip']).",".
		$dbo->quote($args['company']).",".
		$dbo->quote($args['vatnum']).",".
		$dbo->quote($args['ssn']).",".
		$dbo->quote($args['notes']).
		");";
				
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWCUSTOMERCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWCUSTOMERCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedCustomer($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_users` SET 
		`jid`=".$args['jid'].",
		`fields`=".$dbo->quote($args['fields']).",
		`tkfields`=".$dbo->quote($args['tkfields']).",
		`billing_name`=".$dbo->quote($args['billing_name']).",
		`billing_mail`=".$dbo->quote($args['billing_mail']).",
		`billing_phone`=".$dbo->quote($args['billing_phone']).",
		`country_code`=".$dbo->quote($args['country_code']).",
		`billing_state`=".$dbo->quote($args['billing_state']).",
		`billing_city`=".$dbo->quote($args['billing_city']).",
		`billing_address`=".$dbo->quote($args['billing_address']).",
		`billing_address_2`=".$dbo->quote($args['billing_address_2']).",
		`billing_zip`=".$dbo->quote($args['billing_zip']).",
		`company`=".$dbo->quote($args['company']).",
		`vatnum`=".$dbo->quote($args['vatnum']).",
		`ssn`=".$dbo->quote($args['ssn']).",
		`notes`=".$dbo->quote($args['notes'])." 
		 WHERE `id`=".$args['id']." LIMIT 1;";
			
		$dbo->setQuery($q);
		$dbo->execute();

		$mainframe->enqueueMessage(JText::_('VRCUSTOMEREDITED1'));
	}
	
	// SAVE BILL

	public function saveAndCloseBill() {
		$this->saveBill('index.php?option=com_cleverdine&task=reservations');
	}

	public function saveBill($return_url = '') {
		
		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		$args = array();
		$args['id']				= $input->getUint('id', 0);
		$args['bill_value'] 	= $input->getFloat('bill_value', 0);
		$args['bill_closed'] 	= $input->getUint('bill_closed', 0);

		// discount
		$args['method']		= $input->get('method', 0, 'uint');
		$args['id_coupon'] 	= $input->get('id_coupon', 0, 'uint');
		$args['amount'] 	= $input->get('amount', 0.0, 'float');
		$args['percentot'] 	= $input->get('percentot', 0, 'uint');

		$q = "SELECT `discount_val`, `coupon_str` FROM `#__cleverdine_reservation` WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			$mainframe->redirect('index.php?option=com_cleverdine&task=reservations');
			exit;
		}

		$order = $dbo->loadAssoc();
		$order['pay_charge'] = 0; // TODO

		if( $args['method'] == 3 || $args['method'] == 6 ) {
			$args['amount'] = 0;
			$args['percentot'] = 2;
		}

		// remove discount
		$net_no_disc 	= $args['bill_value']+$order['discount_val']-$order['pay_charge'];
		$net_disc 		= $net_no_disc-$order['discount_val'];

		// get new discount
		if( $args['percentot'] == 1 ) {
			$net_disc = $net_no_disc - $net_no_disc * $args['amount'] / 100;
		} else {
			$net_disc = $net_no_disc-$args['amount'];
		}

		$net_disc = max(array(0, $net_disc));

		// apply method
		$coupon_str = $order['coupon_str'];
		if( $args['method'] == 1 || $args['method'] == 2 || $args['method'] == 3 ) {

			// clear coupon
			$coupon_str = '';

			if( $args['method'] == 1 || $args['method'] == 2 ) {
				// add/replace coupon code
				$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `id`=".$args['id_coupon']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$r = $dbo->loadAssoc();
					$coupon_str = $r['code'].";;".$r['value'].";;".$r['percentot'];

					if( $r['gift'] ) {
						$q = "DELETE FROM `#__cleverdine_coupons` WHERE `id`=".$r['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}

		}

		// update values
		$args['discount_val'] 	= $net_no_disc-$net_disc;
		$args['bill_value'] 	= $net_disc+$order['pay_charge'];
		$args['coupon_str']		= $coupon_str;
	
		$this->editSelectedBill($args, $dbo, $mainframe);

		if( empty($return_url) ) {
			$return_url = 'index.php?option=com_cleverdine&task=editbill&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($return_url);
		
	}
	
	private function editSelectedBill($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_reservation` SET 
		`bill_value`=".$args['bill_value'].", 
		`bill_closed`=".$args['bill_closed'].",
		`discount_val`=".$args['discount_val'].",
		`coupon_str`=".$dbo->quote($args['coupon_str'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRBILLEDITED1'));
		}

	}
	
	// SAVE SHIFTS
	
	public function saveAndNewShift() {
		$this->saveShift('index.php?option=com_cleverdine&task=newshift');
	}
	
	public function saveAndCloseShift() {
		$this->saveShift('index.php?option=com_cleverdine&task=shifts');
	}
	
	public function saveShift($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 		= $input->get('name', '', 'string');
		$args['showlabel'] 	= $input->get('showlabel', 0, 'uint');
		$args['label'] 		= $input->get('label', '', 'string');
		$args['from'] 		= $input->get('from', 0, 'uint');
		$args['to'] 		= $input->get('to', 0, 'uint');
		$args['minfrom'] 	= $input->get('minfrom', 0, 'uint');
		$args['minto'] 		= $input->get('minto', 0, 'uint');
		$args['group'] 		= $input->get('group', 0, 'uint');
		$args['id'] 		= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateShift($args);
		
		if( $args['from'] < 0 || $args['from'] > 23 ) {
			$blank_keys[count($blank_keys)] = "from";
		}
		
		if( $args['to'] < 0 || $args['to'] > 23 ) {
			$blank_keys[count($blank_keys)] = "to";
		}
		
		if( $args['from'] >= $args['to'] ) {
			$blank_keys[count($blank_keys)] = "to";
		}
		
		if( $args['group'] == 1 ) {
			if( !cleverdine::isMinuteAnInterval($args['minfrom']) ) {
				$blank_keys[count($blank_keys)] = "minfrom";
			}
			
			if( !cleverdine::isMinuteAnInterval($args['minto']) ) {
				$blank_keys[count($blank_keys)] = "minto";
			}
		} else {
			if( !cleverdine::isTakeAwayMinuteAnInterval($args['minfrom']) ) {
				$blank_keys[count($blank_keys)] = "minfrom";
			}
			
			if( !cleverdine::isTakeAwayMinuteAnInterval($args['minto']) ) {
				$blank_keys[count($blank_keys)] = "minto";
			}
		}
		
		$args['from'] = $args['from']*60+$args['minfrom'];
		$args['to'] = $args['to']*60+$args['minto'];
		
		$dbo = JFactory::getDbo();
		
		if( count( $blank_keys ) == 0 ) {
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewShift($args, $dbo, $mainframe);
			} else {
				$this->editSelectedShift($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRHOURNOTVALIDERROR') , 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newshift" : "editshift&cid[]=".$args['id'] ) );
		}
		
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editshift&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewShift($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_shifts`(`name`,`showlabel`,`label`,`from`,`to`,`group`) VALUES(".
		$dbo->quote($args['name']).", ".
		$args['showlabel'].", ".
		$dbo->quote($args['label']).", ".
		$args['from'].", ".
		$args['to'].", ".
		$args['group'].
		" );";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWSHIFTCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWSHIFTCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedShift($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_shifts` SET `name`=".$dbo->quote($args['name']).
		",`showlabel`=".$args['showlabel'].
		",`label`=".$dbo->quote($args['label']).
		",`from`=".$args['from'].
		",`to`=".$args['to'].
		",`group`=".$args['group'].
		" WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRSHIFTEDITED1'));
		}
	}

	public function swap_opening_time() {

		$dbo = JFactory::getDbo();

		$q = "UPDATE `#__cleverdine_config` SET `setting`=(`setting`+1)%2 WHERE `param`='opentimemode' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		JFactory::getApplication()->redirect('index.php?option=com_cleverdine&task=shifts');

	}
	
	// SAVE MENUS PRODUCT
	
	public function saveAndNewMenusProduct() {
		$this->saveMenusProduct('index.php?option=com_cleverdine&task=newmenusproduct');
	}
	
	public function saveAndCloseMenusProduct() {
		$this->saveMenusProduct('index.php?option=com_cleverdine&task=menusproducts');
	}
	
	public function saveMenusProduct($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['price'] 			= $input->get('price', 0.0, 'float');
		$args['image'] 			= $input->get('image', '', 'string');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['hidden'] 		= $input->get('hidden', 0, 'uint');
		$args['id'] 			= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateMenusProduct($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewMenusProduct($args, $dbo, $mainframe);
			} else {
				$this->editSelectedMenusProduct($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newmenusproduct" : "editmenusproduct&cid[]=".$args['id'] ) );
			exit;
		}
		
		$option_id 		= $input->get('option_id', array(), 'array');
		$option_name 	= $input->get('oname', array(), 'array');
		$option_price 	= $input->get('oprice', array(), 'array');
		
		$remove_options = $input->get('remove_option', array(), 'array');
		
		for( $j = 0; $j < count($option_id); $j++ ) {
			$opt = array( 
				'id' => intval($option_id[$j]),
				'name' => $option_name[$j],
				'inc_price' => floatval($option_price[$j]),
				'id_product' => $args['id'],
				'ordering' => ($j+1)
			);
			 
			if( $opt['id'] == -1 ) {
				$q = "INSERT INTO `#__cleverdine_section_product_option` (`name`,`inc_price`,`id_product`,`ordering`) VALUES (".
				$dbo->quote($opt['name']).",".
				$opt['inc_price'].",".
				$opt['id_product'].",".
				$opt['ordering']."
				);";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_section_product_option` SET 
				`name`=".$dbo->quote($opt['name']).",
				`inc_price`=".$opt['inc_price'].",
				`ordering`=".$opt['ordering']." 
				WHERE `id`=".$opt['id']." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}

		foreach( $remove_options as $r ) {
			if( $r != -1 ) {
				$q = "DELETE FROM `#__cleverdine_section_product_option` WHERE `id`=".intval($r)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editmenusproduct&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewMenusProduct($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_section_product` WHERE `hidden`=0;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;

		if( !array_key_exists('hidden', $args) ) {
			$args['hidden'] = 0;
		} else if( $args['hidden'] == 1 ) {
			$newsortnum = 0;
		}
		
		$q = "INSERT INTO `#__cleverdine_section_product`(`name`,`description`,`price`,`published`,`image`,`hidden`,`ordering`) VALUES(".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['description']).",".
		$args['price'].",".
		$args['published'].",".
		$dbo->quote($args['image']).",".
		$args['hidden'].",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWMENUSPRODUCTCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWMENUSPRODUCTCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedMenusProduct($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_section_product` SET 
		`name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).",
		`price`=".$args['price'].",
		`published`=".$args['published'].",
		`image`=".$dbo->quote($args['image'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$mainframe->enqueueMessage(JText::_('VRMENUSPRODUCTEDITED1'));
	}

	public function publishMenusProducts() {
		$this->changeStatusMenusProducts(1);
	}
	
	public function unpublishMenusProducts() {
		$this->changeStatusMenusProducts(0);
	}
	
	private function changeStatusMenusProducts($status) {
		$dbo = JFactory::getDbo();
		
		$cid = JFactory::getApplication()->input->get('cid', array(), 'uint');
		
		if( count($cid) > 0 ) {
			$q = "UPDATE `#__cleverdine_section_product` SET `published`=".intval($status)." WHERE `id` IN (".implode(',', $cid).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$this->cancelMenusProduct();
	}
	
	// SAVE MENUS
	
	public function saveAndNewMenu() {
	   $this->saveMenu('index.php?option=com_cleverdine&task=newmenu');    
	}
	
	public function saveAndCloseMenu() {
		$this->saveMenu('index.php?option=com_cleverdine&task=menus');
	}
	
	public function saveMenu($redirect_url = '') {
			
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['id'] 			= $input->get('id', 0, 'int');
		$args['name'] 			= $input->get('name', '', 'string');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['image'] 			= $input->get('image', '', 'string');
		$args['special_day'] 	= $input->get('special_day', 0, 'int');
		$args['published'] 		= $input->get('published', 0, 'int');
		$args['choosable'] 		= $input->get('choosable', 0, 'int');
		$args['working_shifts'] = $input->get('working_shifts', array(), 'array');
		$args['days_filter'] 	= $input->get('days_filter', array(), 'array');

		$blank_keys = RestaurantsHelper::validateMenu($args);
		
		if( count( $blank_keys ) == 0 ) {
			
			if( count($args['working_shifts']) > 0 ) {
				$_ws = $args['working_shifts'];
				
				for( $i = 0; $i < count($_ws); $i++ ) {
					$_hours = explode( '-', $_ws[$i]);
					
					$_from = explode( ':', $_hours[0] );
					$_to = explode( ':', $_hours[1] );
					$_ws[$i] = ($_from[0]*60+$_from[1]).'-'.($_to[0]*60+$_to[1]);
				}
				
				$args['working_shifts'] = implode( ', ', $_ws );
			} else {
				$args['working_shifts'] = "";
			}

			if( count($args['days_filter']) > 0 ) {
				$_DAYS = array(
					mb_substr( JText::_('VRDAYSUN'), 0, 3, 'UTF-8' ) => 0,
					mb_substr( JText::_('VRDAYMON'), 0, 3, 'UTF-8' ) => 1,
					mb_substr( JText::_('VRDAYTUE'), 0, 3, 'UTF-8' ) => 2,
					mb_substr( JText::_('VRDAYWED'), 0, 3, 'UTF-8' ) => 3,
					mb_substr( JText::_('VRDAYTHU'), 0, 3, 'UTF-8' ) => 4,
					mb_substr( JText::_('VRDAYFRI'), 0, 3, 'UTF-8' ) => 5,
					mb_substr( JText::_('VRDAYSAT'), 0, 3, 'UTF-8' ) => 6,
				);
				
				$_df = $args['days_filter'];
				$args['days_filter'] = '';
				foreach( $_df as $day ) {
					if( !empty($args['days_filter']) ) {
						$args['days_filter'] .= ', ';
					}
					$args['days_filter'] .= $_DAYS[$day];
				}
			} else {
				$args['days_filter'] = "";
			}
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewMenu($args, $dbo, $mainframe);
			} else {
				$this->editSelectedMenu($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newmenu" : "editmenu" ) );
			exit;
		}
		
		$sec_id 		= $input->get('sec_id', array(), 'int');
		$sec_app_id 	= $input->get('sec_app_id', array(), 'int');
		$sec_name 		= $input->get('sec_name', array(), 'string');
		$sec_desc 		= $input->get('sec_desc', array(), 'array');
		$sec_publ 		= $input->get('sec_publ', array(), 'int');
		$sec_highlight 	= $input->get('sec_highlight', array(), 'int');
		$sec_image 		= $input->get('sec_image', array(), 'string');
		
		$prod_id 		= $input->get('prod_id', array(), 'id');
		$prod_real_id 	= $input->get('real_prod_id', array(), 'id');
		$prod_charge 	= $input->get('charge', array(), 'array'); // changed filter from 'float' to 'array'
		
		$remove_sections = $input->get('remove_section', array(), 'int');
		$remove_products = $input->get('remove_product', array(), 'int');

		foreach( $remove_sections as $s ) {
			$s = intval($s);

			$q = "DELETE FROM `#__cleverdine_section_product_assoc` WHERE `id_section`=$s;";
			$dbo->setQuery($q);
			$dbo->execute();
			
			$q = "DELETE FROM `#__cleverdine_menus_section` WHERE `id`=$s LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		foreach( $remove_products as $r ) {
			$r = intval($r);

			$q = "DELETE FROM `#__cleverdine_section_product_assoc` WHERE `id`=$r LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$section_last_ord = 0;
		$q = "SELECT `ordering` FROM `#__cleverdine_menus_section` ORDER BY `ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$section_last_ord = $dbo->loadResult();
		}
		
		for( $i = 0; $i < count($sec_id); $i++ ) {
			
			if( $sec_id[$i] == -1 ) {
				$section_last_ord++; // increase only for new sections
			}
			
			if( empty($sec_name[$i]) ) {
				$sec_name[$i] = JText::_('VRMANAGEMENU27');
			} 
			
			$section = array( 
				'id' => $sec_id[$i],
				'name' => $sec_name[$i],
				'description' => $sec_desc[$i],
				'published' => $sec_publ[$i],
				'highlight' => $sec_highlight[$i],
				'image' => $sec_image[$i],
				'ordering' => $section_last_ord,
				'id_menu' => $args['id']
			);
			 
			if( $section['id'] == -1 ) {
				$section['id'] = $this->createSection($section, $dbo);
			} else {
				$this->editSection($section, $dbo);
			}
			 
			$key = $sec_app_id[$i];
			 
			if( !empty($prod_id[$key]) ) {
				for( $j = 0; $j < count($prod_id[$key]); $j++ ) {
					$prod = array( 
						'id' => $prod_real_id[$key][$j],
						'id_product' => $prod_id[$key][$j],
						'id_section' => $section['id'],
						'charge' => $prod_charge[$key][$j]                   
					);
					
					if( $prod['id'] == -1 ) {
						$this->createMenuProduct($prod, $dbo);
					} else {
						$this->editMenuProduct($prod, $dbo);
					}
				}
			}

		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editmenu&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
		
	}
	
	private function saveNewMenu($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_menus`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_menus`( `name`, `published`, `choosable`, `special_day`, `working_shifts`, `days_filter`, `description`, `image`, `ordering` ) VALUES(".
		$dbo->quote($args['name']).",".
		$args['published'].",".
		$args['choosable'].",".
		$args['special_day'].",".
		$dbo->quote($args['working_shifts']).",".
		$dbo->quote($args['days_filter']).",".
		$dbo->quote($args['description']).",".
		$dbo->quote($args['image']).",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWMENUCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWMENUCREATED0'), 'error');
		}
		
		return $lid;
		
	}
	
	private function editSelectedMenu($args, $dbo, $mainframe) {
	
		$q = "UPDATE `#__cleverdine_menus` SET 
		`name`=".$dbo->quote($args['name']).",
		`published`=".$args['published'].", 
		`choosable`=".$args['choosable'].", 
		`special_day`=".$args['special_day'].", 
		`working_shifts`=".$dbo->quote($args['working_shifts']).", 
		`days_filter`=".$dbo->quote($args['days_filter']).", 
		`description`=".$dbo->quote($args['description']).",
		`image`=".$dbo->quote($args['image'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		$mainframe->enqueueMessage(JText::_('VRMENUEDITED1'));
	}
	
	private function createSection($args, $dbo) {
		
		$q = "INSERT INTO `#__cleverdine_menus_section` (`name`, `description`, `published`, `highlight`, `image`, `ordering`, `id_menu`) VALUES(
		".$dbo->quote($args['name']).",
		".$dbo->quote($args['description']).",
		".intval($args['published']).",
		".intval($args['highlight']).",
		".$dbo->quote($args['image']).",
		".$args['ordering'].",
		".intval($args['id_menu'])."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		
		return $dbo->insertid();
	}
	
	private function editSection($args, $dbo) {
		
		$q = "UPDATE `#__cleverdine_menus_section` SET 
		`name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).",
		`published`=".intval($args['published']).",
		`highlight`=".intval($args['highlight']).",
		`image`=".$dbo->quote($args['image']).",
		`id_menu`=".intval($args['id_menu'])." 
		WHERE `id`=".intval($args['id'])." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function createMenuProduct($args, $dbo) {
		
		$prod_ord = 1;
		$q = "SELECT `ordering` FROM `#__cleverdine_section_product` WHERE `id`=".$args['id_product']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$prod_ord = $dbo->loadResult();
		}
		
		$q = "INSERT INTO `#__cleverdine_section_product_assoc` (`id_section`, `id_product`, `charge`, `ordering`) VALUES(
		".intval($args['id_section']).",
		".intval($args['id_product']).",
		".floatval($args['charge']).",
		".intval($prod_ord)."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		
		return $dbo->insertid();
	}
	
	private function editMenuProduct($args, $dbo) {
		
		$q = "UPDATE `#__cleverdine_section_product_assoc` SET `charge`=".floatval($args['charge'])." WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	public function saveMenuOrdering() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$id_menu 	= $input->get('id', 0, 'int');
		$sections 	= $input->get('section', array(), 'int');
		$products 	= $input->get('product', array(), 'int');
		
		for( $i = 0; $i < count($sections); $i++ ) {
			$q = "UPDATE `#__cleverdine_menus_section` SET `ordering`=".($i+1)." WHERE `id`=".$sections[$i]." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}

		for( $i = 0; $i < count($products); $i++ ) {
			$q = "UPDATE `#__cleverdine_section_product_assoc` SET `ordering`=".($i+1)." WHERE `id`=".$products[$i]." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$mainframe->enqueueMessage(JText::_('VRMAINTITLEMENUORDUPDATED'));
		$mainframe->redirect('index.php?option=com_cleverdine&task=editmenuordering&cid[]='.$id_menu);
		
	}
	
	// SAVE SPECIAL DAYS 
	
	public function saveAndNewSpecialDay() {
		$this->saveSpecialDay('index.php?option=com_cleverdine&task=newspecialday');
	}
	
	public function saveAndCloseSpecialDay() {
		$this->saveSpecialDay('index.php?option=com_cleverdine&task=specialdays');
	}
	
	public function saveSpecialDay($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 				= $input->getString('name');
		$args['start_date'] 		= $input->getString('start_date');
		$args['end_date'] 			= $input->getString('end_date');
		$args['working_shifts'] 	= $input->get('working_shifts', array(), 'string');
		$args['days_filter'] 		= $input->get('days_filter', array(), 'string');
		$args['depositcost'] 		= $input->getFloat('depositcost', 0);
		$args['perpersoncost'] 		= $input->getUint('perpersoncost', 0);
		$args['peopleallowed'] 		= $input->getInt('peopleallowed', 0);
		$args['markoncal'] 			= $input->getUint('markoncal', 0);
		$args['ignoreclosingdays'] 	= $input->getUint('ignoreclosingdays', 0);
		$args['priority'] 			= $input->getUint('priority', 0);
		$args['choosemenu'] 		= $input->getUint('choosemenu', 0);
		$args['id_menus'] 			= $input->get('id_menu',array(), 'int');
		$args['delivery_service'] 	= $input->getInt('delivery_service', -1);
		$args['group'] 				= $input->getUint('group', 0);
		$args['id'] 				= $input->getInt('id');
		
		$blank_keys = RestaurantsHelper::validateSpecialDay($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
			
			$format = cleverdine::getDateFormat(true);
		
			if( !strlen($args['start_date']) || !strlen($args['end_date']) ) {
				$args['start_ts'] = -1;
				$args['end_ts'] = -1;
			} else {
				$ds = cleverdine::createTimestamp($args['start_date'], 0, 0, true);
				$de = cleverdine::createTimestamp($args['end_date'], 23, 59, true);

				if( $de >= $ds ) {
					$args['start_ts'] 	= $ds;
					$args['end_ts'] 	= $de;
				} else {
					$mainframe->enqueueMessage(JText::_('VRCOUPONDATENOTICE'), 'notice');
				}
			}
			
			if( count( $blank_keys ) == 0 ) {
				
				if( count($args['working_shifts']) > 0 ) {
					$_ws = $args['working_shifts'];
					
					for( $i = 0; $i < count($_ws); $i++ ) {
						$_hours = explode( '-', $_ws[$i]);
						
						$_from = explode( ':', $_hours[0] );
						$_to = explode( ':', $_hours[1] );
						$_ws[$i] = ($_from[0]*60+$_from[1]).'-'.($_to[0]*60+$_to[1]);
					}
					
					$args['working_shifts'] = implode( ', ', $_ws );
				} else {
					$args['working_shifts'] = "";
				}
	
				if( count($args['days_filter']) > 0 ) {
					$_DAYS = array(
						mb_substr( JText::_('VRDAYSUN'), 0, 3, 'UTF-8' ) => 0,
						mb_substr( JText::_('VRDAYMON'), 0, 3, 'UTF-8' ) => 1,
						mb_substr( JText::_('VRDAYTUE'), 0, 3, 'UTF-8' ) => 2,
						mb_substr( JText::_('VRDAYWED'), 0, 3, 'UTF-8' ) => 3,
						mb_substr( JText::_('VRDAYTHU'), 0, 3, 'UTF-8' ) => 4,
						mb_substr( JText::_('VRDAYFRI'), 0, 3, 'UTF-8' ) => 5,
						mb_substr( JText::_('VRDAYSAT'), 0, 3, 'UTF-8' ) => 6,
					);
					
					$_df = $args['days_filter'];
					$args['days_filter'] = '';
					foreach( $_df as $day ) {
						if( !empty($args['days_filter']) ) {
							$args['days_filter'] .= ', ';
						}
						$args['days_filter'] .= $_DAYS[$day];
					}
				} else {
					$args['days_filter'] = "";
				}
				
				// UPLOAD IMAGES
				$images = $input->get('image', array(), 'string');
				$args['images'] = '';

				foreach( $images as $img ) {
					if( !empty($img) ) {
						if( !empty($args['images']) ) {
							$args['images'] .= ';;';
						}
						$args['images'] .= $img;
					}
				}
				
				if( $args['id'] == -1 ) {
					$args['id'] = $this->saveNewSpecialDay($args, $dbo, $mainframe);
				} else {
					$this->editSelectedSpecialDay($args, $dbo, $mainframe);
				}
			} else {
				$mainframe->enqueueMessage(JText::_('VRDATESNOTVALIDERROR') , 'error');
				$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newspecialday" : "editspecialday&cid[]=".$args['id'] ) );
				exit;
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newspecialday" : "editspecialday&cid[]=".$args['id'] ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editspecialday&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewSpecialDay($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_specialdays` (`name`,`start_ts`,`end_ts`,`working_shifts`,`days_filter`,`depositcost`,`perpersoncost`,`peopleallowed`,`markoncal`,`ignoreclosingdays`,`priority`,`choosemenu`,`delivery_service`,`group`,`images`) VALUES (
		".$dbo->quote($args['name']).",
		".$args['start_ts'].",
		".$args['end_ts'].",
		".$dbo->quote($args['working_shifts']).",
		".$dbo->quote($args['days_filter']).",
		".$args['depositcost'].",
		".$args['perpersoncost'].",
		".$args['peopleallowed'].",
		".$args['markoncal'].",
		".$args['ignoreclosingdays'].",
		".$args['priority'].",
		".$args['choosemenu'].",
		".$args['delivery_service'].",
		".$args['group'].",
		".$dbo->quote($args['images'])."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			
			foreach( $args['id_menus'] as $_m ) {
				$q = "INSERT INTO `#__cleverdine_sd_menus` (`id_spday`,`id_menu`) VALUES (".$lid.",".$_m.");";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
			
			$mainframe->enqueueMessage(JText::_('VRNEWSPECIALDAYCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWSPECIALDAYCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedSpecialDay($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_specialdays` SET 
		`name`=".$dbo->quote($args['name']).", 
		`start_ts`=".$args['start_ts'].", 
		`end_ts`=".$args['end_ts'].", 
		`working_shifts`=".$dbo->quote($args['working_shifts']).", 
		`days_filter`=".$dbo->quote($args['days_filter']).", 
		`depositcost`=".$args['depositcost'].",
		`perpersoncost`=".$args['perpersoncost'].",
		`peopleallowed`=".$args['peopleallowed'].",
		`markoncal`=".$args['markoncal'].",
		`ignoreclosingdays`=".$args['ignoreclosingdays'].",
		`priority`=".$args['priority'].",
		`choosemenu`=".$args['choosemenu'].",
		`delivery_service`=".$args['delivery_service'].",
		`group`=".$args['group'].",
		`images`=".$dbo->quote($args['images'])."
		WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$q = "DELETE FROM `#__cleverdine_sd_menus` WHERE `id_spday`=".$args['id'].";";
		$dbo->setQuery($q);
		$dbo->execute();
			
		foreach( $args['id_menus'] as $_m ) {
			$q = "INSERT INTO `#__cleverdine_sd_menus` (`id_spday`,`id_menu`) VALUES (".$args['id'].",".$_m.");";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$mainframe->enqueueMessage(JText::_('VRSPECIALDAYEDITED1'));
	}
	
	// SAVE PAYMENTS
	
	public function saveAndNewPayment() {
		$this->savePayment('index.php?option=com_cleverdine&task=newpayment');
	}
	
	public function saveAndClosePayment() {
		$this->savePayment('index.php?option=com_cleverdine&task=payments');
	}
	
	public function savePayment($redirect_url = '') {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->getString('name');
		$args['file'] 			= $input->getString('file');
		$args['published'] 		= $input->getUint('published', 0);
		$args['enablecost']		= $input->getInt('enablecost_factor')*abs($input->getFloat('enablecost_amount'));
		$args['charge'] 		= $input->getFloat('charge');
		$args['percentot'] 		= $input->getUint('percentot', 0);
		$args['setconfirmed'] 	= $input->getUint('setconfirmed', 0);
		$args['icontype'] 		= $input->getUint('icontype', 0);
		$args['position']		= $input->get('position', '', 'string');
		$args['prenote'] 		= $input->get('prenote', '', 'raw');
		$args['note'] 			= $input->get('note', '', 'raw');
		$args['group']			= $input->getUint('group', 0);
		$args['id'] 			= $input->getInt('id', -1);

		switch( $args['icontype'] ) {
			case 1: $args['icon'] = $input->get('font_icon'); break;
			case 2: $args['icon'] = $input->get('upload_icon'); break;

			default: $args['icon'] = '';
		}
		
		$gp_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'payments'.DIRECTORY_SEPARATOR.$args['file'];
		
		$blank_keys = RestaurantsHelper::validatePayment($args);
		
		if( count($blank_keys) == 0 && !file_exists($gp_path) ) {
			$blank_keys[0] = 'file';
		}
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
			
			require_once($gp_path);
			
			$params = array();
			$admin_params = cleverdinePayment::getAdminParameters();
			foreach( $admin_params as $k => $p ) {
				$params[$k] = $input->getString($k);
			}
			
			$args['params'] = json_encode($params);
			
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewPayment($args, $dbo, $mainframe);
			} else {
				$this->editSelectedPayment($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newpayment" : "editpayment&cid[]=".$args['id'] ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editpayment&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewPayment($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_gpayments`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_gpayments` (`name`,`file`,`published`,`enablecost`,`prenote`,`note`,`charge`,`percentot`,`setconfirmed`,`icontype`,`icon`,`position`,`params`,`group`,`ordering`) VALUES(".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['file']).",".
		$args['published'].",".
		$args['enablecost'].",".
		$dbo->quote($args['prenote']).",".
		$dbo->quote($args['note']).",".
		$args['charge'].",".
		$args['percentot'].",".
		$args['setconfirmed'].",".
		$args['icontype'].",".
		$dbo->quote($args['icon']).",".
		$dbo->quote($args['position']).",".
		$dbo->quote($args['params']).",".
		$args['group'].",".
		$newsortnum.
		");";
				
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWPAYMENTCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWPAYMENTCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedPayment($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_gpayments` SET 
		`name`=".$dbo->quote($args['name']).",
		`file`=".$dbo->quote($args['file']).",
		`published`=".$args['published'].",
		`enablecost`=".$args['enablecost'].",
		`prenote`=".$dbo->quote($args['prenote']).",
		`note`=".$dbo->quote($args['note']).",
		`charge`=".$args['charge'].",
		`percentot`=".$args['percentot'].",
		`setconfirmed`=".$args['setconfirmed'].",
		`icontype`=".$args['icontype'].",
		`icon`=".$dbo->quote($args['icon']).",
		`position`=".$dbo->quote($args['position']).",
		`params`=".$dbo->quote($args['params']).",
		`group`=".$args['group']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRPAYMENTEDITED1'));
		}
	}
	
	// SAVE RESERVATION CODE
	
	public function saveAndNewResCode() {
		$this->saveResCode('index.php?option=com_cleverdine&task=newrescode');
	}
	
	public function saveAndCloseResCode() {
		$this->saveResCode('index.php?option=com_cleverdine&task=rescodes');
	}
	
	public function saveResCode($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['code'] 	= $input->get('code', '', 'string');
		$args['notes'] 	= $input->get('notes', '', 'raw');
		$args['type'] 	= $input->get('type', 0, 'int');
		$args['icon'] 	= $input->get('icon', '', 'string');
		$args['id'] 	= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateResCode($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewResCode($args, $dbo, $mainframe);
			} else {
				$this->editSelectedResCode($args, $dbo, $mainframe);
			}

		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newrescode" : "editrescode&cid[]=".$args['id'] ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editrescode&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	private function saveNewResCode($args, $dbo, $mainframe) {

		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_res_code`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_res_code`(`code`,`icon`,`notes`,`type`,`ordering`) VALUES(".
		$dbo->quote($args['code']).",".
		$dbo->quote($args['icon']).",".
		$dbo->quote($args['notes']).",".
		$args['type'].",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWRESCODECREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWRESCODECREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedResCode($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_res_code` SET 
		`code`=".$dbo->quote($args['code']).",
		`icon`=".$dbo->quote($args['icon']).",
		`notes`=".$dbo->quote($args['notes']).",
		`type`=".$args['type']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRRESCODEEDITED1'));
		}
	}

	// SAVE RESERVATION CODE ORDER
	
	public function saveAndNewResCodeOrder() {
		$filters = JFactory::getApplication()->input->get('filters', array(), 'array');

		$filters_query = '';
		foreach( $filters as $k => $v ) {
			$filters_query .= "&filters[$k]=$v";
		}

		$this->saveResCodeOrder('index.php?option=com_cleverdine&task=newrescodeorder'.$filters_query);
	}
	
	public function saveAndCloseResCodeOrder() {
		$filters = JFactory::getApplication()->input->get('filters', array(), 'array');

		$filters_query = '';
		foreach( $filters as $k => $v ) {
			$filters_query .= "&$k=$v";
		}

		$this->saveResCodeOrder('index.php?option=com_cleverdine&task=rescodesorder'.$filters_query);
	}
	
	public function saveResCodeOrder($redirect_url='', $filters = null) {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
	
		if( $filters === null ) {
			$filters = $input->get('filters', array(), 'array');
		}

		$args = array();
		$args['id_rescode'] = $input->get('id_rescode', 0, 'uint');
		$args['notes'] 		= $input->get('notes', '', 'raw');
		$args['id'] 		= $input->get('id', 0, 'int');

		$blank_keys = array();

		if( !empty($filters['id_order']) ) {
			$args['id_order'] = $filters['id_order'];
		} else {
			$blank_keys[] = 'id_order';
		}

		if( !empty($filters['group']) ) {
			$args['group'] = $filters['group'];
		} else {
			$blank_keys[] = 'group';
		}

		$filters_query = '';
		foreach( $filters as $k => $v ) {
			$filters_query .= "&filters[$k]=$v";
		}
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewResCodeOrder($args, $dbo, $mainframe);
			} else {
				$this->editSelectedResCodeOrder($args, $dbo, $mainframe);
			}

		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newrescodeorder" : "editrescodeorder&cid[]=".$args['id'].$filters_query ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editrescodeorder&cid[]='.$args['id'].$filters_query;
		}
		
		$mainframe->redirect($redirect_url);
	}

	private function saveNewResCodeOrder($args, $dbo, $mainframe) {
		
		$lid = cleverdine::insertOrderStatus($args['id_order'], $args['id_rescode'], $args['group'], $args['notes']);

		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWRESCODEORDERCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWRESCODEORDERCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedResCodeOrder($args, $dbo, $mainframe) {
		
		$lid = cleverdine::insertOrderStatus($args['id_order'], $args['id_rescode'], $args['group'], $args['notes']);
		
		if( $lid ) {
			$mainframe->enqueueMessage(JText::_('VRRESCODEORDEREDITED1'));
		}

	}

	// SAVE MEDIA

	public function saveAndNewMedia() {
		$this->saveMedia('index.php?option=com_cleverdine&task=newmedia');
	}
	
	public function saveAndCloseMedia() {
		$this->saveMedia('index.php?option=com_cleverdine&task=media');
	}
	
	public function saveMedia($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 	= $input->get('name', '', 'string');
		$args['action'] = $input->get('action', 0, 'int');
		$args['media'] 	= $input->get('media', '', 'string');

		$settings = array();
		$settings['resize'] 		= $input->get('resize', 0, 'uint');
		$settings['resize_value'] 	= $input->get('resize_value', 0, 'uint');
		$settings['thumb_value'] 	= $input->get('thumb_value', 0, 'uint');

		cleverdine::storeMediaProperties($settings);

		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;
		
		if( strlen($args['name']) ) {

			$prop = RestaurantsHelper::getFileProperties($basepath.'media'.DIRECTORY_SEPARATOR.$args['media']);
			$args['name'] .= $prop['file_ext'];

			$resp = null;

			if( $args['action'] == 1 ) {
				$resp = cleverdine::uploadFile('image', $basepath.'media'.DIRECTORY_SEPARATOR, 'jpeg,jpg,png,gif');

				if( $resp->esit ) {
					if( file_exists($basepath.'media'.DIRECTORY_SEPARATOR.$args['media']) ) {
						unlink($basepath.'media'.DIRECTORY_SEPARATOR.$args['media']);
					}
					rename($basepath.'media'.DIRECTORY_SEPARATOR.$resp->name, $basepath.'media'.DIRECTORY_SEPARATOR.$args['media']);
				}

			} else if( $args['action'] == 2 ) {
				$resp = cleverdine::uploadFile('image', $basepath.'media@small'.DIRECTORY_SEPARATOR, 'jpeg,jpg,png,gif');

				if( $resp->esit ) {
					if( file_exists($basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']) ) {
						unlink($basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']);
					}
					rename($basepath.'media@small'.DIRECTORY_SEPARATOR.$resp->name, $basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']);
				}

			} else if( $args['action'] == 3 ) {
				$resp = cleverdine::uploadMedia('image', $settings);

				if( $resp->esit ) {
					if( file_exists($basepath.'media'.DIRECTORY_SEPARATOR.$args['media']) ) {
						unlink($basepath.'media'.DIRECTORY_SEPARATOR.$args['media']);
					}
					rename($basepath.'media'.DIRECTORY_SEPARATOR.$resp->name, $basepath.'media'.DIRECTORY_SEPARATOR.$args['media']);

					if( file_exists($basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']) ) {
						unlink($basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']);
					}
					rename($basepath.'media@small'.DIRECTORY_SEPARATOR.$resp->name, $basepath.'media@small'.DIRECTORY_SEPARATOR.$args['media']);
				}
			}

			if( $resp !== null && !$resp->esit ) {
				$mainframe->enqueueMessage(JText::_(($resp->errno == 1 ? 'VRCONFIGUPLOADERROR' : 'VRCONFIGFILETYPEERROR')) , 'error');
				$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newmedia" : "editmedia&cid[]=".$args['media'] ) );
				exit;
			}

			if( $args['name'] != $args['media'] ) {
				if( !file_exists($basepath.'media'.DIRECTORY_SEPARATOR.$args['name']) ) {
					$this->renameMedia($args['media'], $args['name']);
				} else {
					$mainframe->enqueueMessage(JText::sprintf('VRMEDIARENERR', $args['name']), 'error');
					$args['name'] = $args['media'];
				}
			}
				
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=editmedia&cid[]=".$args['media'] );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editmedia&cid[]='.$args['name'];
		}
		
		$mainframe->enqueueMessage(JText::_('VRMEDIAEDITED1'));
		$mainframe->redirect($redirect_url);
	}

	// UPLOAD MEDIA

	public function uploadAndNewMedia() {
		$this->uploadMedia('index.php?option=com_cleverdine&task=newmedia');
	}
	
	public function uploadAndCloseMedia() {
		$this->uploadMedia('index.php?option=com_cleverdine&task=media');
	}
	
	public function uploadMedia($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$settings = array();
		$settings['resize'] 		= $input->get('resize', 0, 'uint');
		$settings['resize_value'] 	= $input->get('resize_value', 0, 'uint');
		$settings['thumb_value'] 	= $input->get('thumb_value', 0, 'uint');

		cleverdine::storeMediaProperties($settings);

		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

		$resp = cleverdine::uploadMedia('image', $settings);

		if( !$resp->esit && $resp->errno !== null ) {
			$mainframe->enqueueMessage(JText::_(($resp->errno == 1 ? 'VRCONFIGUPLOADERROR' : 'VRCONFIGFILETYPEERROR')) , 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=newmedia" );
			exit;
		}
		
		if( empty($redirect_url) ) {
			if( isset($resp->name) ) {
				$redirect_url = 'index.php?option=com_cleverdine&task=editmedia&cid[]='.$resp->name;
			} else {
				$redirect_url = 'index.php?option=com_cleverdine&task=newmedia';
			}
		}
		
		$mainframe->enqueueMessage(JText::_('VRNEWMEDIACREATED1'));
		$mainframe->redirect($redirect_url);
	}

	public function uploadimageajax() {

		$input = JFactory::getApplication()->input;
		
		$prop = array();    
		
		$prop = array();
		$prop['resize'] 		= $input->get('resize', 0, 'uint');
		$prop['resize_value'] 	= $input->get('resize_value', 0, 'uint');
		$prop['thumb_value'] 	= $input->get('thumb_value', 0, 'uint');

		if( empty($prop['resize']) && empty($prop['resize_value']) && empty($prop['thumb_value']) ) {
			$prop = null; // properties will be retrieved from uploadMedia
		} else {
			cleverdine::storeMediaProperties($prop);
		}
		
		$resp = cleverdine::uploadMedia('image', $prop);
		
		if( !$resp->esit ) {
			echo json_encode(array(0));
		} else {
			echo json_encode(array(1, $resp->name));
		}
		exit;
		
	} 

	// RENAME MEDIA

	private function renameMedia($old_name, $new_name) {
		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

		$ok = ( rename($basepath.'media'.DIRECTORY_SEPARATOR.$old_name, $basepath.'media'.DIRECTORY_SEPARATOR.$new_name) &&
			rename($basepath.'media@small'.DIRECTORY_SEPARATOR.$old_name, $basepath.'media@small'.DIRECTORY_SEPARATOR.$new_name) );

		if( $ok ) {
			$dbo = JFactory::getDbo();

			$q = array();

			$q[] = "UPDATE `#__cleverdine_menus` SET `image`=".$dbo->quote($new_name)." WHERE `image`=".$dbo->quote($old_name).";";

			$q[] = "UPDATE `#__cleverdine_menus_section` SET `image`=".$dbo->quote($new_name)." WHERE `image`=".$dbo->quote($old_name).";";

			$q[] = "UPDATE `#__cleverdine_section_product` SET `image`=".$dbo->quote($new_name)." WHERE `image`=".$dbo->quote($old_name).";";
			
			$q[] = "UPDATE `#__cleverdine_takeaway_menus_entry` SET `img_path`=".$dbo->quote($new_name)." WHERE `img_path`=".$dbo->quote($old_name).";";

			$q[] = "UPDATE `#__cleverdine_takeaway_menus_attribute` SET `icon`=".$dbo->quote($new_name)." WHERE `icon`=".$dbo->quote($old_name).";";

			foreach( $q as $query ) {
				$dbo->setQuery($query);
				$dbo->execute();
			}
		}
	}

	// SAVE REVIEW
	
	public function saveAndCloseReview() {
		$this->saveReview('index.php?option=com_cleverdine&task=revs');
	}

	public function saveAndNewReview() {
		$this->saveReview('index.php?option=com_cleverdine&task=newrev');
	}
	
	public function saveReview($return_url='') {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['title'] 		= $input->get('title', '', 'string');
		$args['jid'] 		= $input->get('jid', 0, 'int');
		$args['name'] 		= $input->get('name', '', 'string');
		$args['email'] 		= $input->get('email', '', 'string');
		$args['rating'] 	= $input->get('rating', 0, 'int');
		$args['published'] 	= $input->get('published', 0, 'int');
		$args['verified'] 	= $input->get('verified', 0, 'int');
		$args['id_seremp'] 	= $input->get('id_seremp', '', 'string');
		$args['langtag'] 	= $input->get('langtag', '', 'string');
		$args['comment'] 	= $input->get('comment', '', 'string');
		$args['id'] 		= $input->get('id', 0, 'int');

		$args['timestamp'] 	= cleverdine::createTimestamp( 
				$input->get('timestamp', '', 'string'),
				$input->get('hour', 0, 'int'),
				$input->get('min', 0, 'int'),
				true
			);

		$args['id_takeaway_product'] = $input->get('id_takeaway_product', 0, 'int');
		
		$args['rating'] = max(array(1, $args['rating']));
		$args['rating'] = min(array(5, $args['rating']));

		if( empty($args['jid']) ) {
			$args['jid'] = -1;
		}
		
		$blank_keys = RestaurantsHelper::validateReview($args);
		
		if( count( $blank_keys ) == 0 ) {
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewReview($args, $dbo, $mainframe);
			} else {
				$this->editSelectedReview($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VAPREQUIREDFIELDSERROR') , 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newrev" : "editrev&cid[]=".$args['id'] ) );
		}
		
		if(empty($return_url)) {
			$return_url = 'index.php?option=com_cleverdine&task=editrev&cid[]='.$args['id'];
		}

		$filters = array();
		$filters['key'] 	= $input->get('key', '', 'string');
		$filters['stars'] 	= $input->get('stars', '', 'string');

		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$return_url .= "&$k=$v";
			}
		}

		$mainframe->redirect($return_url);
	}
	
	private function saveNewReview($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_reviews` (`title`,`jid`,`name`,`email`,`timestamp`,`rating`,`published`,`verified`,`id_takeaway_product`,`langtag`,`comment`) VALUES(".
		$dbo->quote($args['title']).",".
		$args['jid'].",".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['email']).",".
		$args['timestamp'].",".
		$args['rating'].",".
		$args['published'].",".
		$args['verified'].",".
		$args['id_takeaway_product'].",".
		$dbo->quote($args['langtag']).",".
		$dbo->quote($args['comment']).
		");";
				
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWREVIEWCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWREVIEWCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedReview($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_reviews` SET 
		`title`=".$dbo->quote($args['title']).",
		`jid`=".$args['jid'].",
		`name`=".$dbo->quote($args['name']).",
		`email`=".$dbo->quote($args['email']).",
		`timestamp`=".$args['timestamp'].",
		`rating`=".$args['rating'].",
		`published`=".$args['published'].",
		`verified`=".$args['verified'].",
		`id_takeaway_product`=".$args['id_takeaway_product'].",
		`langtag`=".$dbo->quote($args['langtag']).",
		`comment`=".$dbo->quote($args['comment'])."    
		WHERE `id`=".$args['id']." LIMIT 1;";
			
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRREVIEWEDITED1'));
		}
	}

	// SAVE INVOICE

	public function saveAndNewInvoice() {
		$this->saveInvoice('index.php?option=com_cleverdine&task=newinvoice');
	}

	public function saveAndCloseInvoice() {
		$this->saveInvoice('index.php?option=com_cleverdine&task=invoices');
	}

	public function saveInvoiceFromRestaurant() {
		$input = JFactory::getApplication()->input;

		$input->set('ord_group', 0);
		$input->set('overwrite', 1);

		$this->saveInvoice('index.php?option=com_cleverdine&task=reservations&limitstart='.$input->get('limitstart', 0, 'int'));
	}

	public function saveInvoiceFromTakeaway() {
		$input = JFactory::getApplication()->input;

		$input->set('ord_group', 1);
		$input->set('overwrite', 1);

		$this->saveInvoice('index.php?option=com_cleverdine&task=tkreservations&limitstart='.$input->get('limitstart', 0, 'int'));
	}

	public function saveInvoice($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		if( !cleverdine::loadFrameworkPDF() ) {
			$mainframe->enqueueMessage(JText::_('VRERRORFRAMEWORKPDF'), 'error');
			$this->cancelInvoice();
			exit;
		}

		$args = array();
		$args['group'] 		= $input->get('ord_group', 0, 'int');
		$args['month'] 		= $input->get('ord_month', 0, 'int');
		$args['year'] 		= $input->get('ord_year', 0, 'int');
		$args['overwrite'] 	= $input->get('overwrite', 0, 'int');
		$args['notifycust'] = $input->get('notifycust', 0, 'int');
		$args['management']	= $input->get('management', 0, 'int');

		$prop = array();
		$prop['number'] 		= $input->get('inv_number', array(), 'string');
		$prop['datetype'] 		= $input->get('inv_date', 0, 'int');
		$prop['custom_date'] 	= $input->get('custom_date', '', 'string');
		$prop['legalinfo'] 		= $input->get('legal_info', '', 'string');
		
		$prop['pageorientation'] 	= $input->get('page_orientation', '', 'string');
		$prop['pageformat'] 		= $input->get('page_format', '', 'string');
		$prop['unit'] 				= $input->get('unit', '', 'string');
		$prop['scale'] 				= (float)$input->get('scale', 0, 'int')/100.0;

		// get IDs from request
		$cid = $input->get('cid', array(), 'int');
		if( !count($cid) ) {
			// get IDs from query

			$table = '#__cleverdine'.($args['group'] == 1 ? '_takeaway' : '').'_reservation';

			$start_ts 	= mktime(0, 0, 0, $args['month'], 1, $args['year']);
			$end_ts 	= mktime(0, 0, 0, $args['month']+1, 1, $args['year'])-1;

			$q = "SELECT `id` FROM `$table` WHERE `status`='CONFIRMED' AND `checkin_ts` BETWEEN $start_ts AND $end_ts ORDER BY `id` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {

				foreach( $dbo->loadAssocList() as $row ) {
					$cid[] = (int)$row['id'];
				}

			}

			$obj = cleverdine::buildInvoiceObject($prop);
		} else {
			// invoice properties not set in request > get from database
			$obj = cleverdine::getInvoiceObject();

			if( !empty($prop['number']) ) {
				$obj->params->number = $prop['number'][0];
				$obj->params->suffix = $prop['number'][1];
			}

			if( !empty($prop['custom_date']) ) {
				$obj->params->customDate = $prop['custom_date'];
			}

		}

		$count_valid = $count_mailed = 0;

		// GENERATE INVOICE
		foreach( $cid as $id ) {
			if( $args['group'] == 0 ) {
				$order_details = cleverdine::fetchOrderDetails($id);
			} else {
				$order_details = cleverdine::fetchTakeAwayOrderDetails($id);
			}

			// generate invoice only if confirmed
			if( $order_details['status'] == 'CONFIRMED' ) {

				// get existing invoice
				$ord_invoice = cleverdine::getOrderInvoice($id, $args['group'], $dbo);

				// if invoice doesn't exist or can be overwritten
				if( $args['overwrite'] || $ord_invoice === null ) {

					$order_details['customer'] = cleverdine::getCustomer($order_details['id_user']);
					$pdf = cleverdine::generateInvoicePDF($order_details, $args['group'], $obj);

					$inv_number = $obj->params->number.(strlen($obj->params->suffix) ? '/' : '').$obj->params->suffix;
					$inv_date 	= ($obj->params->datetype == 1 ? time() : $order_details['created_on']);
					if( isset($obj->params->customDate) ) {
						$inv_date = cleverdine::createTimestamp($obj->params->customDate, 0, 0, true);
					}

					if( $ord_invoice === null ) {
						// create new invoice if not exist
						$q = "INSERT INTO `#__cleverdine_invoice` (`id_order`, `inv_number`, `inv_date`, `file`, `createdon`, `group`) VALUES(".
						$order_details['id'].",".
						$dbo->quote($inv_number).",".
						$inv_date.",".
						$dbo->quote(substr($pdf, strrpos($pdf, DIRECTORY_SEPARATOR)+1)).",".
						time().",".
						$args['group'].
						");";

					} else {
						// update the existing one
						$q = "UPDATE `#__cleverdine_invoice` SET 
						`inv_number`=".$dbo->quote($inv_number).",
						`inv_date`=".$inv_date.",
						`createdon`=".time()."
						WHERE `id`=".$ord_invoice['id']." LIMIT 1;";
					}
					
					$dbo->setQuery($q);
					$dbo->execute();

					$obj->params->number++;
					$count_valid++;
					
					// notify customer
					if( $args['notifycust'] ) {
						if( cleverdine::sendInvoiceMail($order_details, $pdf) ) {
							$count_mailed++;
						}
					}

				}

			}

		}
		//

		// don't register properties when you come from manageinvoice task
		if( !$args['management'] ) {
			cleverdine::storeInvoiceObject($obj);
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editinvoice&cid[]=1::'.$ord_invoice['id'];
		}

		$filters = array();
		$filters['year'] 		= $input->get('year', '', 'string');
		$filters['month'] 		= $input->get('month', '', 'string');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');
		$filters['group'] 		= $input->get('group', '', 'string');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}
		
		if( $count_valid > 0 ) {
			$mainframe->enqueueMessage(JText::sprintf('VRINVGENERATEDMSG', $count_valid));
			if( $count_mailed > 0 ) {
				$mainframe->enqueueMessage(JText::sprintf('VRINVMAILSENT', $count_mailed));
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRNOINVOICESGENERATED'), 'notice');
		}
		$mainframe->redirect($redirect_url.$qs);
	}

	// SAVE CUSTOMF
	
	public function saveAndNewCustomf() {
		$this->saveCustomf('index.php?option=com_cleverdine&task=newcustomf');
	}
	
	public function saveAndCloseCustomf() {
		$this->saveCustomf('index.php?option=com_cleverdine&task=customf');
	}
	
	public function saveCustomf($redirect_url = '') {
	
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
	
		$args = array();
		$args['name'] 				= $input->get('name', '', 'raw');
		$args['type'] 				= $input->get('type', '', 'string');
		$args['choose_select'] 		= $input->get('choose', array(), 'string');
		$args['required'] 			= $input->get('required', 0, 'int');
		$args['required_delivery'] 	= $input->get('required_delivery', 0, 'int');
		$args['rule'] 				= $input->get('rule', 0, 'int');
		$args['def_prfx'] 			= $input->get('def_prfx', '', 'string');
		$args['poplink'] 			= $input->get('poplink', '', 'string');
		$args['group'] 				= $input->get('group', 0, 'int');
		$args['id'] 				= $input->get('id', 0, 'int');
		
		$args['choose'] = '';
	
		if( $args['type'] == 'select' ) {
			foreach($args['choose_select'] as $i => $ch) {
				if(!empty($ch)) {
					$args['choose'] .= ($i > 0 ? ';;__;;' : '').$ch;
				}
			}
		} else if( $args['type'] == 'text' && $args['rule'] == VRCustomFields::PHONE_NUMBER ) {
			$args['choose'] = $args['def_prfx'];
		}

		if( $args['group'] == 0 ) {
			if( $args['rule'] == VRCustomFields::ADDRESS || $args['rule'] == VRCustomFields::DELIVERY ) {
				$args['rule'] = 0;
			}
		}

		if( $args['group'] == 0 || $args['required'] == 0 ) {
			$args['required_delivery'] = 0;
		}
	
		$blank_keys = RestaurantsHelper::validateCustomf($args);
	
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewCustomf($args, $dbo, $mainframe);
			} else {
				$this->editSelectedCustomf($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newcustomf" : "editcustomf&cid[]=".$args['id'] ) );
			exit;
		}

		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editcustomf&cid[]='.$args['id'];
		}
	
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewCustomf($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_custfields`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;

		$q = "INSERT INTO `#__cleverdine_custfields` (`name`,`type`,`choose`,`required`,`required_delivery`,`rule`,`poplink`,`group`,`ordering`) VALUES(".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['type']).",".
		$dbo->quote($args['choose']).",".
		$args['required'].",".
		$args['required_delivery'].",".
		$args['rule'].",".
		$dbo->quote($args['poplink']).",".
		$args['group'].",".
		$newsortnum.
		");";
	
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWCUSTOMFCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWCUSTOMFCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedCustomf($args, $dbo, $mainframe) {
		
		$q="UPDATE `#__cleverdine_custfields` SET 
		`name`=".$dbo->quote($args['name']).",
		`type`=".$dbo->quote($args['type']).",
		`choose`=".$dbo->quote($args['choose']).",
		`required`=".$args['required'].",
		`required_delivery`=".$args['required_delivery'].",
		`rule`=".$args['rule'].",
		`poplink`=".$dbo->quote($args['poplink']).",
		`group`=".$args['group']." 
		WHERE `id`=".intval($args['id'])." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRCUSTOMFEDITED1'));
		}
	}
	
	public function sortField() {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
			
		$sortid = $input->get('cid', array(), 'int');
		$pmode 	= $input->get('mode', '', 'string');
		
		$db_table 		= $input->get('db_table');
		$return_task 	= $input->get('return_task');
		$params 		= $input->get('params', array(), 'array');

		$db_table = "#__cleverdine_$db_table";

		$where = '';
		foreach( $params as $k => $v ) {
			$where .= (strlen($where) ? ' AND ' : '').$dbo->quoteName($k).'='.$dbo->quote($v);
		}
		
		if( !empty($pmode) ) {

			$q = "SELECT `id`,`ordering` 
			FROM ".$dbo->quoteName($db_table)." 
			".(strlen($where) ? "WHERE $where" : "")." 
			ORDER BY `ordering` ASC;";

			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {
				$data = $dbo->loadAssocList();

				if( $pmode == "up" ) {

					foreach( $data as $v ){
						if( $v['id'] == $sortid[0] ) {
							$y = $v['ordering'];
						}
					}

					if( $y && $y > 1 ) {
						$newsort = $y - 1;
						$found = false;
						foreach( $data as $v ){
							if( intval($v['ordering']) == intval($newsort) ) {
								$found = true;
								$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$y' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$newsort' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if( !$found ) {
							$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$newsort' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}

				} else if( $pmode == "down" ) {

					foreach( $data as $v ){
						if( $v['id'] == $sortid[0] ) {
							$y = $v['ordering'];
						}
					}

					if( $y ) {
						$newsort = $y + 1;
						$found = false;
						foreach( $data as $v ){
							if( intval($v['ordering']) == intval($newsort) ) {
								$found = true;
								
								$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$y' WHERE `id`='".$v['id']."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();

								$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$newsort' WHERE `id`='".$sortid[0]."' LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
								break;
							}
						}
						if( !$found ) {
							$q = "UPDATE ".$dbo->quoteName($db_table)." SET `ordering`='$newsort' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->execute();
						}
					}
				}
			}
			$mainframe->redirect("index.php?option=com_cleverdine&task=".$return_task.(count($params) ? '&'.http_build_query($params) : ''));
		} else {
			$mainframe->redirect("index.php?option=com_cleverdine");
		}
	}

	public function saveRoomsSort() {
		$this->saveManualSort('room', 'rooms');
	}

	public function saveMenusProductsSort() {
		$this->saveManualSort('section_product', 'menusproducts');
	}
	
	public function saveMenusSort() {
		$this->saveManualSort('menus', 'menus');
	}

	public function saveRescodesSort() {
		$this->saveManualSort('res_code', 'rescodes');
	}

	public function saveTkmenuSort() {
		$this->saveManualSort('takeaway_menus', 'tkmenus');
	}
	
	public function saveTkmenuattrSort() {
		$this->saveManualSort('takeaway_menus_attribute', 'tkmenuattr');
	}

	public function saveTkproductsSort() {
		$this->saveManualSort('takeaway_menus_entry', 'tkproducts&id_menu='.JFactory::getApplication()->input->get('id_menu', 0, 'int'));
	}
	
	public function saveTktoppingsSort() {
		$this->saveManualSort('takeaway_topping', 'tktoppings');
	}
	
	public function saveTktoppingsSeparatorsSort() {
		$this->saveManualSort('takeaway_topping_separator', 'tktopseparators');
	}
	
	public function saveTkdealSort() {
		$this->saveManualSort('takeaway_deal', 'tkdeals');
	}

	public function saveTkareaSort() {
		$this->saveManualSort('takeaway_delivery_area', 'tkareas');
	}
	
	public function savePaymentsSort() {
		$this->saveManualSort('gpayments', 'payments');
	}

	public function saveCustomfSort() {
		$this->saveManualSort('custfields', 'customf');
	}

	private function saveManualSort($db_table, $return_task) {
		
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDbo();
		
		$ord_arr = $mainframe->input->get('row_ord', array(), 'array');
		
		foreach( $ord_arr as $id => $arr ) {

			$ordering = intval($arr[0]);
			if( $ordering < 0 ) {
				$ordering = abs($ordering);
			} else if( $ordering == 0 ) {
				$ordering = 1;
			}

			$q = "UPDATE ".$dbo->quoteName("#__cleverdine_$db_table")." SET `ordering`=$ordering WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$mainframe->redirect('index.php?option=com_cleverdine&task='.$return_task);
		
	}
	
	// SAVE COUPONS
	
	public function saveAndNewCoupon() {
		$this->saveCoupon('index.php?option=com_cleverdine&task=newcoupon');
	}
	
	public function saveAndCloseCoupon() {
		$this->saveCoupon('index.php?option=com_cleverdine&task=coupons');
	}
	
	public function saveCoupon($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
	
		$args = array();
		$args['code'] 		= $input->get('code', '', 'string');
		$args['type'] 		= $input->get('type', 0, 'uint');
		$args['percentot'] 	= $input->get('percentot', 0, 'uint');
		$args['value'] 		= $input->get('value', 0.0, 'float');
		$args['dstart'] 	= $input->get('dstart', '', 'string');
		$args['dend'] 		= $input->get('dend', '', 'string');
		$args['minvalue'] 	= $input->get('minvalue', 0.0, 'float');
		$args['group'] 		= $input->get('group', 0, 'int');
		$args['id'] 		= $input->get('id', 0, 'int');

		if( $args['group'] == 0 ) {
			$args['minvalue'] = round($args['minvalue']);
		}
	
		if (!strlen($args['dstart']) || !strlen($args['dend'])) {
			$args['datevalid'] = "";
		} else {
			$ds = cleverdine::createTimestamp($args['dstart'], 0, 0, true);
			$de = cleverdine::createTimestamp($args['dend'], 23, 59, true);

			if ($ds == -1 || $de == -1) {
				$args['datevalid'] = "";
			} else if ($de >= $ds) {
				$args['datevalid'] = $ds . "-" . $de;
			} else {
				$mainframe->enqueueMessage(JText::_('VRCOUPONDATENOTICE'), 'notice');
			}
		}
	
		$blank_keys = RestaurantsHelper::validateCoupon($args);
	
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewCoupon($args, $dbo, $mainframe);
			} else {
				$this->editSelectedCoupon($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newcoupon" : "editcoupon&cid[]=".$args['id'] ) );
			exit;
		}
	
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editcoupon&cid[]='.$args['id'];
		}
	
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewCoupon($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_coupons`(`code`,`type`,`percentot`,`value`,`datevalid`,`minvalue`,`group`) VALUES(".
		$dbo->quote($args['code']).",".
		$args['type'].",".
		$args['percentot'].",".
		$args['value'].",".
		$dbo->quote($args['datevalid']).",".
		$args['minvalue'].",".
		$args['group'].
		");";
	
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();

		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWCOUPONCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWCOUPONCREATED0'), 'error');
		}

		return $lid;
	}
	
	private function editSelectedCoupon($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_coupons` SET 
		`code`=".$dbo->quote($args['code']).",
		`type`=".$args['type'].",
		`percentot`=".$args['percentot'].",
		`value`=".$args['value'].",
		`datevalid`=".$dbo->quote($args['datevalid']).",
		`minvalue`=".$args['minvalue'].",
		`group`=".$args['group']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRCOUPONEDITED1'));
		}
	}

	// SAVE API USER
	
	public function saveAndNewApiuser() {
		$this->saveApiuser('index.php?option=com_cleverdine&task=newapiuser');
	}
	
	public function saveAndCloseApiuser() {
		$this->saveApiuser('index.php?option=com_cleverdine&task=apiusers');
	}
	
	public function saveApiuser($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['application'] 	= $input->getString('application');
		$args['username'] 		= $input->getString('username');
		$args['password'] 		= $input->getString('password');
		$args['active'] 		= $input->getUint('active', 0);
		$args['ips']			= $input->get('ip', array(), 'array');
		$args['id'] 			= $input->getInt('id', 0);

		$args['denied'] = array();
		$denied = $input->get('plugin', array(), 'array');

		foreach( $denied as $k => $v ) {
			if( (int)$v != 1 ) {
				array_push($args['denied'], $k);
			}
		}

		$args['denied'] = json_encode($args['denied']);
		
		// check if username already exists

		$blank_keys = array();

		$q = "SELECT 1 FROM `#__cleverdine_api_login` WHERE `id`<>".$args['id']." AND `username`=".$dbo->quote($args['username'])." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() || empty($args['username']) ) {
			$blank_keys[] = 'username';
		}

		// parse IPs

		$ips = array();

		foreach( $args['ips'] as $k => $ip ) {
			if( count($ip) == 4 ) {
				$str = '';

				for( $i = 0; $i < 4; $i++ ) {
					$str .= ($i > 0 ? '.' : '').min(array(255, max(array(0, intval($ip[$i])))));
				}

				$ips[] = $str;
			}
		}

		$args['ips'] = json_encode($ips);
		
		if( count( $blank_keys ) == 0 ) {
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewApiuser($args, $dbo, $mainframe);
			} else {
				$this->editSelectedApiuser($args, $dbo, $mainframe);
			}

		} else {
			if( in_array('username', $blank_keys) ) {
				$mainframe->enqueueMessage(JText::_('VRAPIUSERUSERNAMEEXISTS'), 'error');
			} else {
				$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			}
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newapiuser" : "editapiuser&cid[]=".$args['id'] ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editapiuser&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewApiuser($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_api_login`(`application`,`username`,`password`,`active`,`ips`,`denied`) VALUES( ".
		$dbo->quote($args['application']).",".$dbo->quote($args['username']).",".$dbo->quote($args['password']).",".$args['active'].",".$dbo->quote($args['ips']).",".$dbo->quote($args['denied']).");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWAPIUSERCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWAPIUSERCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedApiuser($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_api_login` SET 
		`application`=".$dbo->quote($args['application']).",
		`username`=".$dbo->quote($args['username']).",
		`password`=".$dbo->quote($args['password']).",
		`active`=".$args['active'].",
		`ips`=".$dbo->quote($args['ips']).",
		`denied`=".$dbo->quote($args['denied'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRAPIUSEREDITED1'));
		}

	}
	
	// SAVE CONFIGURATION
	
	public function saveConfiguration() {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args['firstconfig'] = 0;
		$args['enablerestaurant'] 	= $input->get('enablerestaurant', 0, 'int');
		$args['enabletakeaway'] 	= $input->get('enabletakeaway', 0, 'int');
		$args['restname'] 			= $input->get('restname', '', 'string');
		$args['adminemail'] 		= $input->get('adminemail', '', 'string');
		$args['senderemail'] 		= $input->get('senderemail', '', 'string');
		$args['companylogo'] 		= $input->get('companylogo', '', 'string');
		$args['dateformat'] 		= $input->get('dateformat', '', 'string');
		$args['timeformat'] 		= $input->get('timeformat', '', 'string');
		$args['multilanguage'] 		= $input->get('multilanguage', 0, 'int');
		$args['currencysymb'] 		= $input->get('currencysymb', '', 'string');
		$args['currencyname'] 		= $input->get('currencyname', '', 'string');
		$args['symbpos'] 			= $input->get('symbpos', 0, 'int');
		$args['currdecimalsep'] 	= $input->get('currdecimalsep', '', 'string');
		$args['currthousandssep'] 	= $input->get('currthousandssep', '', 'string');
		$args['currdecimaldig'] 	= $input->get('currdecimaldig', 0, 'int');
		$args['reservationreq'] 	= $input->get('reservationreq', 0, 'int');
		$args['opentimemode'] 		= $input->get('opentimemode', 0, 'int');
		$args['hourfrom'] 			= $input->get('hourfrom', 0, 'int');
		$args['hourto'] 			= $input->get('hourto', 0, 'int');
		$args['minuteintervals'] 	= $input->get('minuteintervals', 0, 'int');
		$args['averagetimestay'] 	= $input->get('averagetimestay', 0, 'int');
		$args['bookrestr'] 			= $input->get('bookrestr', 0, 'int');
		$args['minimumpeople'] 		= $input->get('minpeople', 0, 'int');
		$args['maximumpeople'] 		= $input->get('maxpeople', 0, 'int');
		$args['largepartylbl'] 		= $input->get('largepartylbl', 0, 'int');
		$args['largepartyurl'] 		= $input->get('largepartyurl', '', 'string');
		$args['resdeposit'] 		= $input->get('resdeposit', 0, 'int');
		$args['costperperson'] 		= $input->get('costperperson', 0, 'int');
		$args['choosemenu'] 		= $input->get('choosemenu', 0, 'int');
		$args['tablocktime'] 		= $input->get('tablocktime', 0, 'int');
		$args['phoneprefix'] 		= $input->get('phoneprefix', 0, 'int');
		$args['loadjquery'] 		= $input->get('loadjquery', 0, 'int');
		$args['uiradio'] 			= $input->get('uiradio', '', 'string');
		$args['googleapikey'] 		= $input->get('googleapikey', '', 'string');
		$args['showfooter'] 		= $input->get('showfooter', 0, 'int');
		$args['loginreq'] 			= $input->get('loginreq', 0, 'int');
		$args['enablereg'] 			= $input->get('enablereg', 0, 'int');
		$args['defstatus'] 			= $input->get('defstatus', '', 'string');
		$args['ondashboard'] 		= $input->get('ondashboard', 0, 'int');
		$args['refreshdash'] 		= $input->get('refreshdash', 0, 'int');
		$args['enablecanc'] 		= $input->get('enablecanc', 0, 'int');
		$args['cancreason'] 		= $input->get('cancreason', 0, 'uint');
		$args['canctime'] 			= $input->get('canctime', 0, 'int');
		$args['applycoupon'] 		= $input->get('applycoupon', 0, 'int');
		$args['taxesratio'] 		= $input->get('taxesratio', 0.0, 'float');
		$args['usetaxes'] 			= $input->get('usetaxes', 0, 'int');
		$args['listablecols'] 		= $input->get('listablecols', array(), 'string');
		$args['mailcustwhen'] 		= $input->get('mailcustwhen', 1, 'int');
		$args['mailoperwhen'] 		= $input->get('mailoperwhen', 1, 'int');
		$args['mailadminwhen'] 		= $input->get('mailadminwhen', 2, 'int');
		$args['mailtmpl'] 			= $input->get('mailtmpl', '', 'string');
		$args['adminmailtmpl'] 		= $input->get('adminmailtmpl', '', 'string');
		$args['cancmailtmpl'] 		= $input->get('cancmailtmpl', '', 'string');
		
		$args['closingdays'] = ''; 
		$cd_arr = $input->get('closing_days', array(), 'string');
		
		$args['mincostperorder'] 	= $input->get('mincostperorder', 0.0, 'float');
		$args['tkconfitemid'] 		= $input->get('tkconfitemid', 0, 'int');
		$args['tkminint'] 			= $input->get('tkminint', 0, 'int');
		$args['asapafter'] 			= $input->get('asapafter', 0, 'int');
		$args['mealsperint'] 		= $input->get('mealsperint', 0, 'int');
		$args['deliveryservice'] 	= $input->get('deliveryservice', 0, 'int');
		$args['dsprice'] 			= $input->get('dsprice', 0.0, 'float');
		$args['dspercentot'] 		= $input->get('dspercentot', 0, 'int');
		$args['pickupprice'] 		= $input->get('pickupprice', 0.0, 'float');
		$args['pickuppercentot'] 	= $input->get('pickuppercentot', 0, 'int');
		$args['freedelivery'] 		= $input->get('freedelivery', 0.0, 'float');
		$args['tklocktime'] 		= $input->get('tklocktime', 0, 'int');
		$args['tkshowimages'] 		= $input->get('tkshowimages', 0, 'int');
		$args['tknote'] 			= $input->get('tknote', '', 'raw');
		$args['tktaxesratio'] 		= $input->get('tktaxesratio', 0.0, 'float');
		$args['tkshowtaxes'] 		= $input->get('tkshowtaxes', 0, 'int');
		$args['tkusetaxes'] 		= $input->get('tkusetaxes', 0, 'int');
		$args['tkloginreq'] 		= $input->get('tkloginreq', 0, 'int');
		$args['tkenablereg'] 		= $input->get('tkenablereg', 0, 'int');
		$args['tkdefstatus'] 		= $input->get('tkdefstatus', '', 'string');
		$args['tkenablecanc'] 		= $input->get('tkenablecanc', 0, 'int');
		$args['tkcancreason'] 		= $input->get('tkcancreason', 0, 'uint');
		$args['tkcanctime'] 		= $input->get('tkcanctime', 0, 'int');
		$args['tkmaxitems'] 		= $input->get('tkmaxitems', 0, 'int');
		$args['tkallowdate'] 		= $input->get('tkallowdate', 0, 'int');
		$args['tkwhenopen'] 		= $input->get('tkwhenopen', 0, 'int');
		$args['tkuseoverlay'] 		= $input->get('tkuseoverlay', 0, 'int');
		$args['tkproddesclength']	= $input->get('tkproddesclength', 0, 'uint');
		$args['tkmailcustwhen'] 	= $input->get('tkmailcustwhen', 1, 'int');
		$args['tkmailoperwhen'] 	= $input->get('tkmailoperwhen', 1, 'int');
		$args['tkmailadminwhen'] 	= $input->get('tkmailadminwhen', 2, 'int');
		$args['tkmailtmpl'] 		= $input->get('tkmailtmpl', '', 'string');
		$args['tkadminmailtmpl'] 	= $input->get('tkadminmailtmpl', '', 'string');
		$args['tkcancmailtmpl'] 	= $input->get('tkcancmailtmpl', '', 'string');
		$args['tkreviewmailtmpl'] 	= $input->get('tkreviewmailtmpl', '', 'string');
		$args['tklistablecols'] 	= $input->get('tklistablecols', array(), 'string');
		$args['tkenablestock'] 		= $input->get('tkenablestock', 0, 'int');
		$args['tkstockmailtmpl'] 	= $input->get('tkstockmailtmpl', '', 'string');
		$addr_origins 				= $input->get('tkaddrorigins', array(), 'string');

		if( $args['tkconfitemid'] < 0 ) {
			$args['tkconfitemid'] = max(array(0, $input->get('tkconfitem_custom', 0, 'int')));
		}

		$args['enablereviews'] 		= $input->get('enablereviews', 0, 'int');
		$args['revtakeaway'] 		= $input->get('revtakeaway', 0, 'int');
		$args['revleavemode'] 		= $input->get('revleavemode', 0, 'int');
		$args['revcommentreq'] 		= $input->get('revcommentreq', 0, 'int');
		$args['revminlength'] 		= max(array(0, 		$input->get('revminlength', 0, 'int')));
		$args['revmaxlength'] 		= min(array(2048, 	$input->get('revmaxlength', 0, 'int')));
		$args['revlimlist'] 		= max(array(1, 		$input->get('revlimlist', 5, 'int')));
		$args['revlangfilter'] 		= $input->get('revlangfilter', 0, 'int');
		$args['revautopublished'] 	= $input->get('revautopublished', 0, 'int');

		$args['apifw'] 				= $input->get('apifw', 0, 'uint');
		$args['apilogmode'] 		= $input->get('apilogmode', 0, 'uint');
		$args['apilogflush'] 		= $input->get('apilogflush', 0, 'uint');
		$args['apimaxfail'] 		= max(array(1, $input->get('apimaxfail', 0, 'uint')));
		
		$args['smsapi'] 			= $input->get('smsapi', '', 'string');
		$args['smsapiwhen'] 		= $input->get('smsapiwhen', 0, 'int');
		$args['smsapito'] 			= $input->get('smsapito', 0, 'int');
		$args['smsapiadminphone'] 	= $input->get('smsapiadminphone', '', 'string');
		$admin_phone_prefix 		= $input->get('smsapiadminphone_pfx', '', 'string');
		$args['smsapifields'] 		= '';
		$args['smstmplcust'] 		= array();
		$args['smstmpladmin'] 		= array();

		if( strlen($args['smsapiadminphone']) ) {
			$args['smsapiadminphone'] = $admin_phone_prefix.$args['smsapiadminphone'];
		}

		$sms_cust_tmpl 	= $input->get('smstmplcust', array(), 'array');
		$sms_admin_tmpl = $input->get('smstmpladmin', array(), 'array');
		
		$languages = cleverdine::getKnownLanguages();
		
		for( $i = 0; $i < count($languages); $i++ ) {
			$args['smstmplcust'][$languages[$i]] = $sms_cust_tmpl[0][$i];
			$args['smstmpltkcust'][$languages[$i]] = $sms_cust_tmpl[1][$i];
		}
		$args['smstmplcust'] = json_encode($args['smstmplcust']);
		$args['smstmpltkcust'] = json_encode($args['smstmpltkcust']);
		
		$args['smstmpladmin'] = $sms_admin_tmpl[0];
		$args['smstmpltkadmin'] = $sms_admin_tmpl[1];
				
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$args['smsapi'];
		
		if( file_exists( $sms_api_path ) && strlen($args['smsapi']) > 0 ) {
			require_once($sms_api_path);
			$sms_params = array();
			$admin_params = VikSmsApi::getAdminParameters();
			foreach( $admin_params as $k => $p ) {
				$sms_params[$k] = $input->get('sms'.$k, '', 'string');
			}
			$args['smsapifields'] = json_encode($sms_params);
		}
		
		// validation
		
		if( $args['hourfrom'] < 0 || $args['hourfrom'] > 23 ) {
			$args['hourfrom'] = cleverdine::getFromOpeningHour(true);
		}
		
		if( $args['hourto'] < 0 || $args['hourto'] > 23 ) {
			$args['hourto'] = cleverdine::getToOpeningHour(true);
		}
		
		if( $args['averagetimestay'] < 5 ) {
			$args['averagetimestay'] = cleverdine::getAverageTimeStay(true);
		}
		
		if( $args['bookrestr'] < 0 ) {
			$args['bookrestr'] = 0;
		}
		
		if( $args['minimumpeople'] < 1 ) {
			$args['minimumpeople'] = cleverdine::getMinimumPeople(true);
		}
		
		if( $args['maximumpeople'] < 1 ) {
			$args['maximumpeople'] = cleverdine::getMaximumPeople(true);
		}

		if( $args['minimumpeople'] > $args['maximumpeople'] ) {
			$args['maximumpeople'] = $args['minimumpeople'];
		}
		
		if( $args['resdeposit'] < 0 ) {
			$args['resdeposit'] = cleverdine::getDepositPerReservation(true);
		}
		
		if( $args['tablocktime'] < 5 ) {
			$args['tablocktime'] = cleverdine::getTablesLockedTime(true);
		}
		
		if( $args['tklocktime'] < 1 ) {
			$args['tklocktime'] = cleverdine::getTakeAwayOrdersLockedTime(true);
		}

		// restaurant
		
		if( empty($args['mailtmpl']) ) {
			$args['mailtmpl'] = 'customer_email_tmpl.php';
		} else {
			$args['mailtmpl'] = substr($args['mailtmpl'], strrpos($args['mailtmpl'], '/')+1);
		}

		if( empty($args['adminmailtmpl']) ) {
			$args['adminmailtmpl'] = 'admin_email_tmpl.php';
		} else {
			$args['adminmailtmpl'] = substr($args['adminmailtmpl'], strrpos($args['adminmailtmpl'], '/')+1);
		}

		if( empty($args['cancmailtmpl']) ) {
			$args['cancmailtmpl'] = 'cancellation_email_tmpl.php';
		} else {
			$args['cancmailtmpl'] = substr($args['cancmailtmpl'], strrpos($args['cancmailtmpl'], '/')+1);
		}

		// takeaway
		
		if( empty($args['tkmailtmpl']) ) {
			$args['tkmailtmpl'] = 'takeaway_customer_email_tmpl.php';
		} else {
			$args['tkmailtmpl'] = substr($args['tkmailtmpl'], strrpos($args['tkmailtmpl'], '/')+1);
		}

		if( empty($args['tkadminmailtmpl']) ) {
			$args['tkadminmailtmpl'] = 'takeaway_admin_email_tmpl.php';
		} else {
			$args['tkadminmailtmpl'] = substr($args['tkadminmailtmpl'], strrpos($args['tkadminmailtmpl'], '/')+1);
		}

		if( empty($args['tkcancmailtmpl']) ) {
			$args['tkcancmailtmpl'] = 'takeaway_cancellation_email_tmpl.php';
		} else {
			$args['tkcancmailtmpl'] = substr($args['tkcancmailtmpl'], strrpos($args['tkcancmailtmpl'], '/')+1);
		}

		if( empty($args['tkreviewmailtmpl']) ) {
			$args['tkreviewmailtmpl'] = 'takeaway_review_email_tmpl.php';
		} else {
			$args['tkreviewmailtmpl'] = substr($args['tkreviewmailtmpl'], strrpos($args['tkreviewmailtmpl'], '/')+1);
		}

		// stock

		if( empty($args['tkstockmailtmpl']) ) {
			$args['tkstockmailtmpl'] = 'takeaway_stock_email_tmpl.php';
		} else {
			$args['tkstockmailtmpl'] = substr($args['tkstockmailtmpl'], strrpos($args['tkstockmailtmpl'], '/')+1);
		}
		
		$listable_cols = '';
		foreach( $args['listablecols'] as $k => $v ) {
			$app = explode(':', $v);
			if( $app[1] == 1 ) {
				if( !empty($listable_cols) ) {
					$listable_cols .= ',';
				}
				$listable_cols .= $app[0];
			} 
		}
		$args['listablecols'] = $listable_cols;
		
		$listable_cols = '';
		foreach( $args['tklistablecols'] as $k => $v ) {
			$app = explode(':', $v);
			if( $app[1] == 1 ) {
				if( !empty($listable_cols) ) {
					$listable_cols .= ',';
				}
				$listable_cols .= $app[0];
			} 
		}
		$args['tklistablecols'] = $listable_cols;
		
		if( count( $cd_arr ) > 0 ) {
			$_cd = explode( ':', $cd_arr[0] );
			$args['closingdays'] = cleverdine::createTimestamp($_cd[0],0,0,true) . ':' . $_cd[1];
			for( $i = 1, $n = count( $cd_arr ); $i < $n; $i++ ) {
				$_cd = explode( ':', $cd_arr[$i] );
				$args['closingdays'] .= ';;' . cleverdine::createTimestamp($_cd[0],0,0,true) . ':' . $_cd[1];
			}
		}

		$args['tkaddrorigins'] = array();
		foreach( $addr_origins as $origin ) {
			if( !empty($origin) ) {
				array_push($args['tkaddrorigins'], $origin);
			}
		}
		$args['tkaddrorigins'] = json_encode($args['tkaddrorigins']);		
		
		// end validation
		
		$affected = false;
		foreach( $args as $key => $val ) {
			$q = "UPDATE `#__cleverdine_config` SET 
			`setting`=".$dbo->quote($val)."
			WHERE `param`=".$dbo->quote($key)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			$affected = $affected || $dbo->getAffectedRows();
		}
		
		if( $affected ) {
			$mainframe->enqueueMessage( JText::_( "VRCONFIGEDITED1" ) );
		}
		$mainframe->redirect("index.php?option=com_cleverdine&task=editconfig");
		
	}

	public function store_tab_selected() {
		
		$tab = JFactory::getApplication()->input->get('tab', 1, 'int');
		
		$session = JFactory::getSession();
		$session->set('vretabactive', $tab, 'vreconfig');
		
		die;
	}

	public function validate_zip_code() {
		
		$zip_code = JFactory::getApplication()->input->get('zipcode', '', 'string');
		
		$_resp = cleverdine::validateZipCode($zip_code, true);
		
		echo json_encode(array($_resp));
		die;
		
	}

	public function get_location_delivery_info() {

		$input = JFactory::getApplication()->input;

		$lat 	= $input->get('lat', 0.0, 'float');
		$lng 	= $input->get('lng', 0.0, 'float');
		$zip 	= $input->get('zip', '', 'string');
		$json 	= $input->get('json', 0, 'uint');

		$area = cleverdine::getDeliveryAreaFromCoordinates($lat, $lng, $zip);

		$curr_symb 	= cleverdine::getCurrencySymb(true);
		$symb_pos 	= cleverdine::getCurrencySymbPosition(true);

		$html = '';

		if( $json ) {

			$html = new stdClass;
			$html->status = 0;

			if( $area === null ) {
				$html->message = JText::_('VRTKDELIVERYLOCNOTFOUND');
			} else {

				$html->status = 1;

				$html->latitude	 	= $lat;
				$html->longitude 	= $lng;
				$html->zip 			= $zip;

				$html->area = new stdClass;

				$html->area->id 			= $area['id'];
				$html->area->name 			= $area['name'];
				$html->area->charge 		= (float)$area['charge'];
				$html->area->chargeLabel 	= ($area['charge'] > 0 ? '+ ' : '').cleverdine::printPriceCurrencySymb($area['charge'], $curr_symb, $symb_pos);
				$html->area->minCost 		= (float)$area['min_cost'];
				$html->area->minCostLabel 	= cleverdine::printPriceCurrencySymb($area['min_cost'], $curr_symb, $symb_pos);

			}

		} else {

			if( $area === null ) {
				$html = '<div class="fail">'.JText::_('VRTKDELIVERYLOCNOTFOUND').'</div>';
			} else {

				$html = '<div class="success">';

				$html .= '<div class="info">';
				$html .= '<div class="data-label">'.JText::_('VRMANAGETKAREA16').':</div>';
				$html .= '<div class="data-value">'.$lat.', '.$lng.'</div>';
				$html .= '</div>';

				$html .= '<div class="info">';
				$html .= '<div class="data-label">'.JText::_('VRMANAGETKAREA17').':</div>';
				$html .= '<div class="data-value">'.$area['name'].'</div>';
				$html .= '</div>';

				$html .= '<div class="info">';
				$html .= '<div class="data-label">'.JText::_('VRMANAGETKAREA4').':</div>';
				$html .= '<div class="data-value">'.($area['charge'] > 0 ? '+ ' : '').cleverdine::printPriceCurrencySymb($area['charge'], $curr_symb, $symb_pos, true).'</div>';
				$html .= '</div>';

				$html .= '<div class="info">';
				$html .= '<div class="data-label">'.JText::_('VRMANAGETKAREA18').':</div>';
				$html .= '<div class="data-value">'.cleverdine::printPriceCurrencySymb($area['min_cost'], $curr_symb, $symb_pos, true).'</div>';
				$html .= '</div>';

			}

		}

		echo json_encode($html);
		exit;

	}

	public function changeStatusColumn() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$table 	= "#__cleverdine_".$input->get('table_db');
		$column = $input->get('column_db');
		$val 	= ($input->get('val', 0, 'uint')+1)%2;
		$id 	= $input->get('id', 0, 'uint');
		$params = $input->get('params', array(), 'string');

		$return_url = 'index.php?option=com_cleverdine&task='.$input->get('return_task').(count($params) ? '&'.http_build_query($params) : '');
		
		$dbo = JFactory::getDbo();
		
		$q = "UPDATE ".$dbo->quoteName($table)." SET ".$dbo->quoteName($column)."=$val WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$mainframe->redirect($return_url);
		
	}
	
	public function change_reservation_code() {

		$input = JFactory::getApplication()->input;
		
		$dbo = JFactory::getDbo();
		
		$id 		= $input->get('id', 0, 'uint');
		$id_code 	= $input->get('new_code', 0, 'int');
		$type 		= $input->get('type', 0, 'uint');
		
		$rescode = array( 'id' => 0, 'code' => '', 'icon' => '' );
		
		$q = "SELECT `id`, `code`, `icon` FROM `#__cleverdine_res_code` WHERE `id`=$id_code AND `type`=$type LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rescode = $dbo->loadAssoc();   
		}
		
		$q = "UPDATE `#__cleverdine_".($type == 1 ? '' : 'takeaway_')."reservation` SET `rescode`=".$rescode['id']." WHERE `id`=$id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		cleverdine::insertOrderStatus($id, $id_code, $type);
		
		$html = '<a href="javascript: void(0);" onClick="openResCodeDialog('.$id.','.$rescode['id'].');" class="vrrescodelink">';
		if( empty($rescode['icon']) ) {
			$html .= (!empty($rescode['code']) ? $rescode['code'] : '--');
		} else {
			$html .= '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$rescode['icon'].'" title="'.$rescode['code'].'"/>';
		}
		$html .= '</a>';  
		
		echo json_encode(array(1, $html));
		die;     
	}

	public function truncateSession() {
		
		$dbo = JFactory::getDbo();
		
		$q = "TRUNCATE TABLE `#__session`;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		JFactory::getApplication()->redirect('index.php?option=com_cleverdine&task=editconfig');
		
	}

	public function start_incoming_reservations() {
		$this->edit_incoming_reservations(-1);
	}
	
	public function stop_incoming_reservations() {
		$date = getdate();
		$until = mktime(0, 0, 0, $date['mon'], $date['mday']+1, $date['year']);
		$this->edit_incoming_reservations($until);
	}

	public function start_incoming_tkreservations() {
		$this->edit_incoming_reservations(-1, 1);
	}
	
	public function stop_incoming_tkreservations() {
		$date = getdate();
		$until = mktime(0, 0, 0, $date['mon'], $date['mday']+1, $date['year']);
		$this->edit_incoming_reservations($until, 1);
	}

	private function edit_incoming_reservations($value, $group = 0) {
		$dbo = JFactory::getDbo();

		$param = ($group == 0 ? 'stopuntil' : 'tkstopuntil');
		
		$q = "UPDATE `#__cleverdine_config` SET `setting`=$value WHERE `param`='$param' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		JFactory::getApplication()->redirect('index.php?option=com_cleverdine');
	}
	
	public function store_active_room() {
		$room = JFactory::getApplication()->input->get('room', 0, 'int');
		
		$session = JFactory::getSession();
		$session->set('active-room', $room, 'vre');
		exit;
	}
	
	public function store_dashboard_properties() {

		$input = JFactory::getApplication()->input;

		$prop = array(
			'restaurant' => $input->get('r_page', 1, 'uint'),
			'takeaway' => $input->get('t_page', 1, 'uint'),
			'section' => $input->get('s_page', 1, 'uint'),
			'room' => $input->get('room', 1, 'uint'),
		);
		
		$session = JFactory::getSession();
		$session->set('dashboard-properties', $prop, 'vre');
		exit;
	}

	public function store_mainmenu_status() {
		$status = JFactory::getApplication()->input->get('status', 0, 'uint');
		
		$dbo = JFactory::getDbo();
		$q = "UPDATE `#__cleverdine_config` SET `setting`=$status WHERE `param`='mainmenustatus' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		$session = JFactory::getSession();
		$session->set('mainmenustatus', $status, 'vre');

		exit;
	}
	
	// SAVE TK MENUS
	
	public function saveAndNewTkmenu() {
		$this->saveTkmenu('index.php?option=com_cleverdine&task=newtkmenu');
	}
	
	public function saveAndCloseTkmenu() {
		$this->saveTkmenu('index.php?option=com_cleverdine&task=tkmenus');
	}

	public function saveTkmenu($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['title'] 			= $input->get('title', '', 'string');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['taxes_type']		= $input->get('taxes_type', 0, 'uint');
		$args['taxes_amount']	= $input->get('taxes_amount', 0.0, 'float');
		$args['id'] 			= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateTkmenu($args);
		
		if( count( $blank_keys ) == 0 ) {
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTkmenu($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTkmenu($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtkmenu" : "edittkmenu&cid[]=".$args['id'] ) );
			exit;
		}
		
		$product_id 		= $input->get('entry_id', array(), 'int');
		$product_app_id 	= $input->get('entry_app_id', array(), 'int');
		$product_name 		= $input->get('ename', array(), 'string');
		$product_desc 		= $input->get('edesc', array(), 'array');
		$product_price 		= $input->get('eprice', array(), 'float');
		$product_attributes = $input->get('eattribute', array(array()), 'array');
		$product_ready 		= $input->get('eready', array(), 'uint');
		$product_file 		= $input->get('efile', array(), 'string');
		
		$option_id 		= $input->get('option_id', array(array()), 'array');
		$option_name 	= $input->get('oname', array(array()), 'array');
		$option_price 	= $input->get('oprice', array(array()), 'array');
		
		$remove_products 	= $input->get('remove_entry', array(), 'int');
		$remove_options 	= $input->get('remove_option', array(), 'int');
		
		for( $i = 0; $i < count($product_id); $i++ ) {
			
			$prod = array( 
				'id' => $product_id[$i],
				'name' => $product_name[$i],
				'description' => $product_desc[$i],
				'price' => $product_price[$i],
				'ready' => $product_ready[$i],
				'img_path' => $product_file[$i],
				'id_takeaway_menu' => $args['id'],
				'ordering' => ($i+1)
			 );
			 
			 if( $prod['id'] == -1 ) {
				$prod['id'] = $this->createTkProduct($prod, $dbo);
			 } else {
				$this->editTkProduct($prod, $dbo);
			 }
			 
			 $key = $product_app_id[$i];
			 
			 if( empty($option_id[$key]) ) {
				 $option_id[$key] = array();
			 }
			 
			 for( $j = 0; $j < count($option_id[$key]); $j++ ) {
				 $opt = array( 
					'id' => $option_id[$key][$j],
					'name' => $option_name[$key][$j],
					'inc_price' => $option_price[$key][$j],
					'id_takeaway_menu_entry' => $prod['id'],
					'ordering' => ($j+1)
				 );
				 
				 if( $opt['id'] == -1 ) {
					$this->createTkOption($opt, $dbo);
				 } else {
					$this->editTkOption($opt, $dbo);
				 }
			 }
			 
			 if( empty($product_attributes[$key]) ) {
				 $product_attributes[$key] = array();
			 }
			 
			 $existing_attr = array();
			 $q = "SELECT `id`, `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$prod['id'].";";
			 $dbo->setQuery($q);
			 $dbo->execute();
			 if( $dbo->getNumRows() > 0 ) {
				 $existing_attr = $dbo->loadAssocList();
			 }
			 for( $j = 0; $j < count($product_attributes[$key]); $j++ ) {
				 $attr = array(
					'id_attribute' => $product_attributes[$key][$j],
					'id_takeaway_menu_entry' => $prod['id']
				 );
				 
				 $id_assoc = $this->createTkAttribute($attr, $existing_attr, $dbo);
				 if( $id_assoc > 0 ) {
					 array_push($existing_attr, array('id' => $id_assoc, 'id_attribute' => $attr['id_attribute']));
				 }
			 }
			 $this->removeTkAttributes($product_attributes[$key], $existing_attr, $dbo);

		}
		
		$this->deleteTkentries($remove_products);
		
		foreach( $remove_options as $r ) {
			$r = intval($r);

			if( $r != -1 ) {

				$q = "DELETE FROM `#__cleverdine_takeaway_stock_override` WHERE `id_takeaway_option`=$r;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_entry_option` WHERE `id`=$r LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkmenu&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTkmenu($args, $dbo, $mainframe) {
		
		$q = "SELECT `ordering` FROM `#__cleverdine_takeaway_menus` ORDER BY `ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 1 ) {
			$newsortnum = $dbo->loadResult()+1;
		} else {
			$newsortnum = 1;
		}
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus`(`title`,`description`,`published`,`taxes_type`,`taxes_amount`,`ordering`) VALUES(".
		$dbo->quote($args['title']).",".
		$dbo->quote($args['description']).",".
		$args['published'].",".
		$args['taxes_type'].",".
		$args['taxes_amount'].",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKMENUCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKMENUCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTkmenu($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_menus` SET `title`=".$dbo->quote($args['title']).",
		`description`=".$dbo->quote($args['description']).",
		`published`=".$args['published'].",
		`taxes_type`=".$args['taxes_type'].",
		`taxes_amount`=".$args['taxes_amount']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();
		$mainframe->enqueueMessage(JText::_('VRTKMENUEDITED1'));
	}
	
	private function createTkProduct($args, $dbo) {
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus_entry` (`name`,`description`,`price`,`ready`,`img_path`,`id_takeaway_menu`,`ordering`) VALUES(
		".$dbo->quote($args['name']).",
		".$dbo->quote($args['description']).",
		".floatval($args['price']).",
		".intval($args['ready']).",
		".$dbo->quote($args['img_path']).",
		".intval($args['id_takeaway_menu']).",
		".$args['ordering']." 
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		
		return $dbo->insertid();
	}
	
	private function editTkProduct($args, $dbo) {
		
		$q = "UPDATE `#__cleverdine_takeaway_menus_entry` SET 
		`name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).",
		`price`=".floatval($args['price']).",
		`ready`=".intval($args['ready']).",
		`img_path`=".$dbo->quote($args['img_path']).",
		`id_takeaway_menu`=".intval($args['id_takeaway_menu']).",
		`ordering`=".$args['ordering']." 
		WHERE `id`=".intval($args['id'])." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function createTkOption($args, $dbo) {
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus_entry_option` (`name`,`inc_price`,`id_takeaway_menu_entry`,`ordering`) VALUES(
		".$dbo->quote($args['name']).",
		".floatval($args['inc_price']).",
		".intval($args['id_takeaway_menu_entry']).",
		".$args['ordering']." 
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		
		return $dbo->insertid();
	}
	
	private function editTkOption($args, $dbo) {
		
		$q = "UPDATE `#__cleverdine_takeaway_menus_entry_option` SET 
		`name`=".$dbo->quote($args['name']).",
		`inc_price`=".floatval($args['inc_price']).",
		`id_takeaway_menu_entry`=".intval($args['id_takeaway_menu_entry']).",
		`ordering`=".$args['ordering']." 
		WHERE `id`=".intval($args['id'])." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function createTkAttribute($args, $existing, $dbo) {
		
		foreach( $existing as $ex ) {
			if( $ex['id_attribute'] == $args['id_attribute'] ) {
				return -1; // attribute existing
			}
		}
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus_attr_assoc` (`id_menuentry`, `id_attribute`) VALUES(
		".intval($args['id_takeaway_menu_entry']).",
		".intval($args['id_attribute'])."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		
		return $dbo->insertid();
	}
	
	private function removeTkAttributes($attributes, $existing, $dbo) {
		
		foreach( $existing as $ex ) {
			if( !in_array($ex['id_attribute'], $attributes) ) {
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id`=".intval($ex['id'])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();       
			}
		}
	}
	
	public function publishTakeawayMenus() {
		$this->changeStatusTakeawayMenus(1);
	}
	
	public function unpublishTakeawayMenus() {
		$this->changeStatusTakeawayMenus(0);
	}
	
	private function changeStatusTakeawayMenus($status) {
		$mainframe = JFactory::getApplication();    
		$dbo = JFactory::getDbo();
		
		$cid = $mainframe->input->get('cid', array(), 'int');
		
		if( count($cid > 0 ) ) {
			$q = "UPDATE `#__cleverdine_takeaway_menus` SET `published`=".intval($status)." WHERE `id` IN (".implode(',', $cid).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$this->cancelTkmenu();
	}
	
	// SAVE TK MENU ATTRIBUTE
	
	public function saveAndNewTkentry() {
		$this->saveTkentry('index.php?option=com_cleverdine&task=newtkentry');
	}
	
	public function saveAndCloseTkentry() {
		$this->saveTkentry('index.php?option=com_cleverdine&task=tkproducts');
	}

	public function saveAsCopyTkentry() {
		$this->saveTkentry('', true);
	}
	
	public function saveTkentry($redirect_url='', $as_copy=false) {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['name'] 				= $input->get('name', 'entry example', 'string');
		$args['price'] 				= $input->get('price', 0.0, 'float');
		$args['image'] 				= $input->get('image', '', 'string');
		$args['attributes'] 		= $input->get('attributes', array(), 'uint');
		$args['published'] 			= $input->get('published', 0, 'uint');
		$args['ready'] 				= $input->get('ready', 0, 'uint');
		$args['description'] 		= $input->get('description', '', 'raw');
		$args['id_takeaway_menu'] 	= $input->get('id_menu', 0, 'uint');
		$args['id'] 				= $input->get('id', 0, 'int');
		
		$option_id 		= $input->get('option_id', array(), 'int');
		$option_name 	= $input->get('oname', array(), 'string');
		$option_price 	= $input->get('oprice', array(), 'float');
		$remove_options = $input->get('remove_variation', array(), 'int');

		if( $args['id'] <= 0 || $as_copy ) {
			$args['id'] = $this->saveNewTkentry($args, $dbo, $mainframe);
		} else {
			$this->editSelectedTkentry($args, $dbo, $mainframe);
		}
		
		// attributes
		
		$existing_attr = array();
		$q = "SELECT `id`, `id_attribute` FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=".$args['id'].";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$existing_attr = $dbo->loadAssocList();
		}
		for( $j = 0; $j < count($args['attributes']); $j++ ) {
			$attr = array(
				'id_attribute' => $args['attributes'][$j],
				'id_takeaway_menu_entry' => $args['id']
			);
		 
			$id_assoc = $this->createTkAttribute($attr, $existing_attr, $dbo);
			if( $id_assoc > 0 ) {
				array_push($existing_attr, array('id' => $id_assoc, 'id_attribute' => $attr['id_attribute']));
			}
		}
		if( !$as_copy ) {
			$this->removeTkAttributes($args['attributes'], $existing_attr, $dbo);
		}
		
		// variations
		
		for( $j = 0; $j < count($option_id); $j++ ) {
			$opt = array( 
				'id' => $option_id[$j],
				'name' => $option_name[$j],
				'inc_price' => $option_price[$j],
				'id_takeaway_menu_entry' => $args['id'],
				'ordering' => ($j+1)
			);
			
			if( $opt['id'] == -1 || $as_copy ) {
				$this->createTkOption($opt, $dbo);
			} else {
				$this->editTkOption($opt, $dbo);
			}
		}
		
		if( !$as_copy ) {
			foreach( $remove_options as $r ) {
				$r = intval($r);
				if( $r != -1 ) {

					$q = "DELETE FROM `#__cleverdine_takeaway_stock_override` WHERE `id_takeaway_option`=$r;";
					$dbo->setQuery($q);
					$dbo->execute();

					$q = "DELETE FROM `#__cleverdine_takeaway_menus_entry_option` WHERE `id`=$r LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
				}
			}
		}
		
		// groups
		
		$groups = array();
		$groups['titles'] 		= $input->get('title', array(), 'string');
		$groups['variations'] 	= $input->get('group_var', array(), 'int');
		$groups['multi'] 		= $input->get('multi', array(), 'uint');
		$groups['min'] 			= $input->get('min', array(), 'uint');
		$groups['max'] 			= $input->get('max', array(), 'uint');
		$groups['ids'] 			= $input->get('id_group', array(), 'int');
		$groups['ids_tmp'] 		= $input->get('id_tmp', array(), 'int');
		
		$toppings = array();
		$toppings['prices'] 		= $input->get('topping_price', array(array()), 'array');
		$toppings['ids_topping'] 	= $input->get('id_topping', array(array()), 'array');
		$toppings['ids'] 			= $input->get('id_assoc_gt', array(array()), 'array');
		
		for( $i = 0; $i < count($groups['ids']); $i++ ) {
			$groups_args = array( 
				"id" => intval($groups['ids'][$i]),
				"id_entry" => $args['id'],
				"id_variation" => intval($groups['variations'][$i]),
				"title" => $groups['titles'][$i],
				"multiple" => intval($groups['multi'][$i]),
				"min_toppings" => intval($groups['min'][$i]),
				"max_toppings" => intval($groups['max'][$i]),
				"id_key" => intval($groups['ids_tmp'][$i]),
				"ordering" => ($i+1),
			);

			if( $groups_args['id_variation'] <= 0 ) {
				$groups_args['id_variation'] = -1;
			}
			
			if( $groups_args['id'] == -1 || $as_copy ) {
				$groups_args['id'] = $this->createTkEntryGroup($groups_args, $dbo);
			} else {
				$this->editTkEntryGroup($groups_args, $dbo);
			}
			
			// toppings
			for( $j = 0; $j < count($toppings['ids'][$groups_args['id_key']]); $j++ ) {
				$topping_args = array(
					"id" => intval($toppings['ids'][$groups_args['id_key']][$j]),
					"id_topping" => intval($toppings['ids_topping'][$groups_args['id_key']][$j]),
					"id_group" => $groups_args['id'],
					"price" => floatval($toppings['prices'][$groups_args['id_key']][$j]),
					"ordering" => ($j+1),
				);
				
				if( $topping_args['id'] == -1 || $as_copy ) {
					$topping_args['id'] = $this->createTkGroupTopping($topping_args, $dbo);
				} else {
					$this->editTkGroupTopping($topping_args, $dbo);
				}
			}
			
		}
		
		$remove_groups = $input->get('remove_group', array(), 'uint');
		if( count($remove_groups) > 0 && !$as_copy ) {
			$this->deleteTkEntryGroups($remove_groups, $dbo);
		}
		
		$remove_toppings = $input->get('remove_topping', array(), 'uint');
		if( count($remove_toppings) > 0 && !$as_copy ) {
			$this->deleteTkGroupsToppings($remove_toppings, $dbo);
		}

		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkentry&cid[]='.$args['id'];
		}

		$redirect_url .= '&id_menu='.$args['id_takeaway_menu'];
		
		$mainframe->redirect($redirect_url);
		
	}

	private function saveNewTkentry($args, $dbo, $mainframe) {

		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_takeaway_menus_entry` WHERE `id_takeaway_menu`=".$args['id_takeaway_menu'].";";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus_entry` (`name`,`description`,`price`,`img_path`,`published`,`ready`,`id_takeaway_menu`,`ordering`) VALUES(".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['description']).",".
		$args['price'].",".
		$dbo->quote($args['image']).",".
		$args['published'].",".
		$args['ready'].",".
		$args['id_takeaway_menu'].",".
		$newsortnum.
		");";
				
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKENTRYCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKENTRYCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTkentry($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_menus_entry` SET 
		`name`=".$dbo->quote($args['name']).", 
		`description`=".$dbo->quote($args['description']).",
		`price`=".$args['price'].",
		`published`=".$args['published'].",
		`ready`=".$args['ready'].",
		`img_path`=".$dbo->quote($args['image']).",
		`id_takeaway_menu`=".$args['id_takeaway_menu']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
			
		$mainframe->enqueueMessage(JText::_('VRTKMENUENTRYUPDATED'));
	}

	private function createTkEntryGroup($args, $dbo) {
		
		$q = "INSERT INTO `#__cleverdine_takeaway_entry_group_assoc` (`id_entry`,`id_variation`,`title`,`multiple`,`min_toppings`,`max_toppings`,`ordering`) VALUES (
			".$args['id_entry'].",
			".$args['id_variation'].",
			".$dbo->quote($args['title']).",
			".$args['multiple'].",
			".$args['min_toppings'].",
			".$args['max_toppings'].",
			".$args['ordering']."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		return $dbo->insertid();
	}
	
	private function editTkEntryGroup($args, $dbo) {
		$q = "UPDATE `#__cleverdine_takeaway_entry_group_assoc` SET 
		`id_variation`=".$args['id_variation'].",
		`title`=".$dbo->quote($args['title']).",
		`multiple`=".$args['multiple'].",
		`min_toppings`=".$args['min_toppings'].",
		`max_toppings`=".$args['max_toppings'].",
		`ordering`=".$args['ordering']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function deleteTkEntryGroups($ids, $dbo) {
		$q = "DELETE FROM `#__cleverdine_takeaway_entry_group_assoc` WHERE `id` IN (".implode(",", $ids).");";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$q = "DELETE FROM `#__cleverdine_takeaway_group_topping_assoc` WHERE `id_group` IN (".implode(",", $ids).");";
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function createTkGroupTopping($args, $dbo) {
		
		$q = "INSERT INTO `#__cleverdine_takeaway_group_topping_assoc` (`id_group`,`id_topping`,`rate`,`ordering`) VALUES (
			".$args['id_group'].",
			".$args['id_topping'].",
			".$args['price'].",
			".$args['ordering']."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		return $dbo->insertid();
	}
	
	private function editTkGroupTopping($args, $dbo) {
		$q = "UPDATE `#__cleverdine_takeaway_group_topping_assoc` SET 
		`rate`=".$args['price'].",
		`ordering`=".$args['ordering']."  
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	private function deleteTkGroupsToppings($ids, $dbo) {
		$q = "DELETE FROM `#__cleverdine_takeaway_group_topping_assoc` WHERE `id` IN (".implode(",", $ids).");";
		$dbo->setQuery($q);
		$dbo->execute();
	}
	
	// SAVE TK MENU ATTRIBUTE
	
	public function saveAndNewTkmenuattribute() {
		$this->saveTkmenuattribute('index.php?option=com_cleverdine&task=newtkmenuattribute');
	}
	
	public function saveAndCloseTkmenuattribute() {
		$this->saveTkmenuattribute('index.php?option=com_cleverdine&task=tkmenuattr');
	}
	
	public function saveTkmenuattribute($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['icon'] 			= $input->get('icon', '', 'string');
		$args['id'] 			= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateTkmenuattribute($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTkmenuattribute($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTkmenuattribute($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtkmenuattribute" : "edittkmenuattribute&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkmenuattribute&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTkmenuattribute($args, $dbo, $mainframe) {
		
		$q = "SELECT `ordering` FROM `#__cleverdine_takeaway_menus_attribute` ORDER BY `ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() == 1) {
			$newsortnum = $dbo->loadResult()+1;
		} else {
			$newsortnum = 1;
		}
		
		$q = "INSERT INTO `#__cleverdine_takeaway_menus_attribute`(`name`,`description`,`published`,`icon`,`ordering`) VALUES( ".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['description']).",".
		$args['published'].",".
		$dbo->quote($args['icon']).",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKMENUATTRCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKMENUATTRCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTkmenuattribute($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_menus_attribute` SET `name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).",
		`published`=".$args['published'].",
		`icon`=".$dbo->quote($args['icon'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRTKMENUATTREDITED1'));
		}

	}

	// SAVE TK STOCK

	public function saveAndCloseTkMenuStocks() {
		$this->saveTkMenuStocks('index.php?option=com_cleverdine&task=tkmenus');
	}

	public function saveTkMenuStocks($redirect_url = '') {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$id_menu 	= $input->get('id_menu', 0, 'int');
		$key_search = $input->get('keysearch', '', 'string');

		$products = array();
		$products['ids'] 		= $input->get('prod_ids', array(), 'uint');
		$products['stocks'] 	= $input->get('prod_items_in_stock', array(), 'uint');
		$products['notifies'] 	= $input->get('prod_notify_below', array(), 'uint');

		for( $i = 0; $i < count($products['ids']); $i++ ) {
			$q = "UPDATE `#__cleverdine_takeaway_menus_entry` SET 
			`items_in_stock`=".intval($products['stocks'][$i]).",
			`notify_below`=".intval($products['notifies'][$i])." 
			WHERE `id`=".intval($products['ids'][$i])." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();
		}

		$options = array();
		$options['ids'] 		= $input->get('option_ids', array(), 'uint');
		$options['stocks'] 		= $input->get('option_items_in_stock', array(), 'uint');
		$options['notifies'] 	= $input->get('option_notify_below', array(), 'uint');

		for( $i = 0; $i < count($options['ids']); $i++ ) {
			$q = "UPDATE `#__cleverdine_takeaway_menus_entry_option` SET 
			`items_in_stock`=".intval($options['stocks'][$i]).",
			`notify_below`=".intval($options['notifies'][$i])." 
			WHERE `id`=".intval($options['ids'][$i])." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();
		}

		if( empty($redirect_url) ) {
			$redirect_url .= 'index.php?option=com_cleverdine&task=tkmenustocks&id_menu='.$id_menu.(empty($key_search) ? '' : '&keysearch='.$key_search);
		}
		
		$mainframe->enqueueMessage(JText::_('VRTKMENUSTOCKSEDITED1'));
		$mainframe->redirect($redirect_url);

	}

	public function saveAndCloseTkMenuStocksOverrides() {
		$this->saveTkMenuStocksOverrides('index.php?option=com_cleverdine&task=tkreservations');
	}

	public function saveTkMenuStocksOverrides($redirect_url = '') {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		$filters = array();
		$filters['id_menu'] 	= $input->get('id_menu', 0, 'int');
		$filters['keysearch'] 	= $input->get('keysearch', '', 'string');

		$now = time();

		$args = array();
		$args['eid'] 		= $input->get('id_product', array(), 'uint');
		$args['oid'] 		= $input->get('id_option', array(), 'int');
		$args['override'] 	= $input->get('stock_override', array(), 'int');
		$args['factor'] 	= $input->get('stock_factor', array(), 'int');
		$args['original'] 	= $input->get('original_stock', array(), 'int');

		// GET DAY OVERRIDES

		$day_overrides = array();

		$q = "SELECT * FROM `#__cleverdine_takeaway_stock_override` 
		ORDER BY `id_takeaway_entry` ASC, `id_takeaway_option` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$day_overrides = $dbo->loadAssocList();
		}

		$affected = false;

		for( $i = 0; $i < count($args['eid']); $i++ ) {

			$eid = intval($args['eid'][$i]);
			$oid = intval($args['oid'][$i]);
			$override = intval($args['override'][$i])*intval($args['factor'][$i]);
			$original = intval($args['original'][$i]);

			// GET OVERRIDE
			$j = 0;
			$found = false;
			while( $j < count($day_overrides) && $day_overrides[$j]['id_takeaway_entry'] <= $eid && !$found ) {
				if( $day_overrides[$j]['id_takeaway_entry'] == $eid && (empty($oid) || $day_overrides[$j]['id_takeaway_option'] == $oid) ) {
					$found = true;
				} else {
					$j++;
				}
			}

			if( $found ) {

				if( $override != 0 ) {

					$q = "UPDATE `#__cleverdine_takeaway_stock_override` 
					SET `items_available`=IF(`items_available`+$override < 0, 0, `items_available`+$override) 
					WHERE `id`=".$day_overrides[$j]['id']." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();

					$affected = $affected || $dbo->getAffectedRows();
				}

			} else if( $override != 0 ) {
				$override += $original;
				$override = max(array(0, $override));

				$oid = empty($oid) ? "NULL" : $oid;

				$q = "INSERT INTO `#__cleverdine_takeaway_stock_override` (`items_available`, `ts`, `id_takeaway_entry`, `id_takeaway_option`) VALUES(
					$override, $now, $eid, $oid
				);";
				$dbo->setQuery($q);
				$dbo->execute();

				$affected = $affected || $dbo->insertid();
			}

		}

		if( empty($redirect_url) ) {
			$redirect_url .= 'index.php?option=com_cleverdine&task=tkstocks&'.http_build_query(array_filter($filters));
		}
		
		if( $affected ) {
			$mainframe->enqueueMessage(JText::_('VRTKMENUSTOCKSOVEREDITED1'));
		}
		$mainframe->redirect($redirect_url);

	}

	// SAVE TK DISCOUNT

	public function saveTkDiscountOrder() {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		$args = array();
		$args['method']		 		= $input->get('method', 0, 'uint');
		$args['id_coupon'] 			= $input->get('id_coupon', 0, 'uint');
		$args['amount'] 			= $input->get('amount', 0.0, 'float');
		$args['percentot'] 			= $input->get('percentot', 0, 'uint');
		$args['id'] 				= $input->get('id', 0, 'uint');

		$order = cleverdine::fetchTakeawayOrderDetails($args['id']);
		if( $order === null ) {
			$mainframe->redirect("index.php?option=com_cleverdine&task=tkreservations");
			exit;
		}

		if( $args['method'] == 3 || $args['method'] == 6 ) {
			$args['amount'] = 0;
			$args['percentot'] = 2;
		}

		$use_taxes = cleverdine::isTakeAwayTaxesUsable(true);

		// remove discount
		$net_no_disc 	= $order['total_to_pay']+$order['discount_val']-$order['pay_charge']-$order['delivery_charge']-($use_taxes ? $order['taxes'] : 0);
		$net_disc 		= $net_no_disc-$order['discount_val'];
		$taxes_disc		= $order['taxes'];
		// NET_NO_DISC : TAXES_NO_DISC = NET_DISC : TAXES_DISC
		$taxes_no_disc 	= $net_no_disc * $taxes_disc / $net_disc;

		// get new discount
		if( $args['percentot'] == 1 ) {
			$net_disc = $net_no_disc - $net_no_disc * $args['amount'] / 100;
		} else {
			$net_disc = $net_no_disc-$args['amount'];
		}

		$net_disc = max(array(0, $net_disc));

		// get new taxes
		$taxes_disc = $net_disc * $taxes_no_disc / $net_no_disc;

		// apply method
		$coupon_str = $order['coupon_str'];
		if( $args['method'] == 1 || $args['method'] == 2 || $args['method'] == 3 ) {

			// clear coupon
			$coupon_str = '';

			if( $args['method'] == 1 || $args['method'] == 2 ) {
				// add/replace coupon code
				$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `id`=".$args['id_coupon']." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$r = $dbo->loadAssoc();
					$coupon_str = $r['code'].";;".$r['value'].";;".$r['percentot'];

					if( $r['gift'] ) {
						$q = "DELETE FROM `#__cleverdine_coupons` WHERE `id`=".$r['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
			}

		}

		// update values
		$discount_val 	= $net_no_disc-$net_disc;
		$grand_total 	= $net_disc+$order['pay_charge']+$order['delivery_charge']+($use_taxes ? $order['taxes'] : 0);

		$q = "UPDATE `#__cleverdine_takeaway_reservation` SET 
		`coupon_str`=".$dbo->quote($coupon_str).",
		`discount_val`=$discount_val,
		`total_to_pay`=$grand_total,
		`taxes`=$taxes_disc 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		$mainframe->enqueueMessage(JText::_('VRTKRESEDITED1'));
		$mainframe->redirect("index.php?option=com_cleverdine&task=tkreservations");

	}
	
	// SAVE TK TOPPING
	
	public function saveAndNewTktopping() {
		$this->saveTktopping('index.php?option=com_cleverdine&task=newtktopping');
	}
	
	public function saveAndCloseTktopping() {
		$this->saveTktopping('index.php?option=com_cleverdine&task=tktoppings');
	}
	
	public function saveTktopping($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['price'] 			= $input->get('price', 0.0, 'float');
		$args['old_price'] 		= $input->get('old_price', 0.0, 'float');
		$args['update_price'] 	= $input->get('update_price', 0, 'uint');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['id_separator'] 	= $input->get('id_separator', 0, 'int');
		$args['separator_name'] = $input->get('separator_name', '', 'string');
		$args['id'] 			= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateTktopping($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();

			if( $args['id_separator'] == 0 ) {
				$args['id_separator'] = -1;
			} else if( $args['id_separator'] == -1 ) {
				$args['id_separator'] = $this->saveNewTktoppingSeparator(array('title' => $args['separator_name']), $dbo, $mainframe);
			}

			JFactory::getSession()->set('tklastseparator', $args['id_separator'], 'vre');
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTktopping($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTktopping($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtktopping" : "edittktopping&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittktopping&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTktopping($args, $dbo, $mainframe) {
		
		$q = "SELECT `ordering` FROM `#__cleverdine_takeaway_topping` ORDER BY `ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() == 1) {
			$newsortnum = $dbo->loadResult()+1;
		} else {
			$newsortnum = 1;
		}
		
		$q = "INSERT INTO `#__cleverdine_takeaway_topping`(`name`,`price`,`published`,`id_separator`,`ordering`) VALUES( ".
		$dbo->quote($args['name']).",".$args['price'].",".$args['published'].",".$args['id_separator'].",".$newsortnum." );";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKTOPPINGCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKTOPPINGCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTktopping($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_topping` SET 
		`name`=".$dbo->quote($args['name']).",
		`price`=".$args['price'].",
		`id_separator`=".$args['id_separator'].",
		`published`=".$args['published']." 
		WHERE `id`=".$args['id']." LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRTKTOPPINGEDITED1'));
		}

		// update price
		if( $args['update_price'] > 0 ) {

			if( $args['update_price'] == 1 ) {
				// update all assoc

				$q = "UPDATE `#__cleverdine_takeaway_group_topping_assoc` SET `rate`=".$args['price']." WHERE `id_topping`=".$args['id'].";";

			} else {
				// update assoc with same price

				$q = "UPDATE `#__cleverdine_takeaway_group_topping_assoc` SET `rate`=".$args['price']." WHERE `id_topping`=".$args['id']." AND `rate`=".$args['old_price'].";";
				
			}

			$dbo->setQuery($q);
			$dbo->execute();

			if( $aff = $dbo->getAffectedRows() ) {
				$mainframe->enqueueMessage(JText::sprintf('VRTKTOPPINGRATEUPDATE1', $aff));
			} else {
				$mainframe->enqueueMessage(JText::sprintf('VRTKTOPPINGRATEUPDATE0', $aff), 'notice');
			}

		}
	}
	
	public function publishTakeawayToppings() {
		$this->changeStatusTakeawayToppings(1);
	}
	
	public function unpublishTakeawayToppings() {
		$this->changeStatusTakeawayToppings(0);
	}
	
	private function changeStatusTakeawayToppings($status) {
		$mainframe = JFactory::getApplication();    
		$dbo = JFactory::getDbo();
		
		$cid = $mainframe->input->get('cid', array(), 'int');
		
		if( count($cid > 0 ) ) {
			$q = "UPDATE `#__cleverdine_takeaway_topping` SET `published`=".intval($status)." WHERE `id` IN (".implode(',', $cid).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$this->cancelTktopping();
	}

	// SAVE TK TOPPING SEPARATOR
	
	public function saveAndNewTktoppingSeparator() {
		$this->saveTktoppingSeparator('index.php?option=com_cleverdine&task=newtktopseparator');
	}
	
	public function saveAndCloseTktoppingSeparator() {
		$this->saveTktoppingSeparator('index.php?option=com_cleverdine&task=tktopseparators');
	}
	
	public function saveTktoppingSeparator($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['title'] 	= $input->get('title', '', 'string');
		$args['id'] 	= $input->get('id', 0, 'int');
		
		$blank_keys = RestaurantsHelper::validateTktoppingSeparator($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTktoppingSeparator($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTktoppingSeparator($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtktopseparator" : "edittktopseparator&cid[]=".$args['id'] ) );
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittktopseparator&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTktoppingSeparator($args, $dbo, $mainframe) {
		
		$q = "SELECT `ordering` FROM `#__cleverdine_takeaway_topping_separator` ORDER BY `ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if($dbo->getNumRows() == 1) {
			$newsortnum = $dbo->loadResult()+1;
		} else {
			$newsortnum = 1;
		}
		
		$q = "INSERT INTO `#__cleverdine_takeaway_topping_separator`(`title`,`ordering`) VALUES( ".
		$dbo->quote($args['title']).",".$newsortnum." );";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKTOPPINGSEPCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKTOPPINGSEPCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTktoppingSeparator($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_topping_separator` SET `title`=".$dbo->quote($args['title'])." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRTKTOPPINGSEPEDITED1'));
		}
	}
	
	// SAVE TK DEAL
	
	public function saveAndNewTkdeal() {
		$this->saveTkdeal('index.php?option=com_cleverdine&task=newtkdeal');
	}
	
	public function saveAndCloseTkdeal() {
		$this->saveTkdeal('index.php?option=com_cleverdine&task=tkdeals');
	}
	
	public function saveTkdeal($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['description'] 	= $input->get('description', '', 'raw');
		$args['start_ts']		= cleverdine::createTimestamp($input->get('start_ts', '', 'string'), 0, 0);
		$args['end_ts'] 		= cleverdine::createTimestamp($input->get('end_ts', '', 'string'), 0, 0);
		$args['max_quantity'] 	= $input->get('max_quantity', 0, 'int');
		$args['published'] 		= $input->get('published', 0, 'uint');
		$args['days_filter'] 	= $input->get('days_filter', array(0, 1, 2, 3, 4, 5, 6), 'int');
		$args['type'] 			= $input->get('deal_type', 0, 'uint');
		$args['amount'] 		= $input->get('amount', 0.0, 'float');
		$args['percentot'] 		= $input->get('percentot', 0, 'uint');
		$args['auto_insert'] 	= $input->get('auto_insert', 0, 'uint');
		$args['min_quantity'] 	= $input->get('min_quantity', 0, 'uint');
		$args['cart_tcost'] 	= $input->get('cart_tcost', 0.0, 'float');
		$args['id'] 			= $input->get('id', 0, 'int');
		
		if( $args['type'] <= 0 || $args['type'] > 6 ) {
			$args['type'] = '';
		}
		
		if( $args['min_quantity'] <= 0 ) {
			$args['min_quantity'] = 1;
		}

		if( $args['type'] == 4 || $args['type'] == 6 ) {
			// deals based on total cost > only one application
			$args['max_quantity'] = 1;
		}
		
		$blank_keys = RestaurantsHelper::validateTkdeal($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTkdeal($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTkdeal($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtkdeal" : "edittkdeal&cid[]=".$args['id'] ) );
			exit;
		}
		
		// MANAGE DAYS FILTER
		$q = "SELECT `id_weekday` FROM `#__cleverdine_takeaway_deal_day_assoc` WHERE `id_deal`=".$args['id']." ORDER BY `id_weekday` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$current_week_days = $dbo->loadAssocList();
		}
		
		// remove unselected days
		$days_to_remove = array();
		foreach( $current_week_days as $week_day ) {
			if( !@in_array($week_day['id_weekday'], $args['days_filter']) ) {
				array_push($days_to_remove, $week_day['id_weekday']);
			}
		}
		if( count($days_to_remove) ) {
			$q = "DELETE FROM `#__cleverdine_takeaway_deal_day_assoc` WHERE `id_deal`=".$args['id']." AND `id_weekday` IN (".implode(",", $days_to_remove).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		// push selected days
		foreach( $args['days_filter'] as $day_to_add ) {
			$found = false;
			for( $i = 0; $i < count($current_week_days) && !$found; $i++ ) {
				$found = ($day_to_add == $current_week_days[$i]['id_weekday']);
			}
			if( !$found ) {
				$q = "INSERT INTO `#__cleverdine_takeaway_deal_day_assoc` (`id_deal`,`id_weekday`) VALUES (".$args['id'].", ".$day_to_add.");";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		// DEAL FOOD MANAGEMENT
		$remove_deal_food = $input->get('remove_deal_food', array(), 'array');
		$this->removeSelectedTkdealFood($remove_deal_food, $dbo);
		
		$push_deal_food = $input->get('deal_food', array(), 'array');
		$this->pushSelectedTkdealFood($args['id'], $push_deal_food, $dbo);
		
		// FREE FOOD MANAGEMENT
		$remove_free_food = $input->get('remove_free_food', array(), 'array');
		$this->removeSelectedTkdealFreeFood($remove_free_food, $dbo);
		
		$push_free_food = $input->get('free_food', array(), 'array');
		$this->pushSelectedTkdealFreeFood($args['id'], $push_free_food, $dbo);
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkdeal&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTkdeal($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_takeaway_deal`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_takeaway_deal`(`name`,`description`,`start_ts`,`end_ts`,`max_quantity`,`published`,`type`,`ordering`,`amount`,`percentot`,`auto_insert`,`min_quantity`,`cart_tcost`) VALUES(".
		$dbo->quote($args['name']).",".
		$dbo->quote($args['description']).",".
		$args['start_ts'].",".
		$args['end_ts'].",".
		$args['max_quantity'].",".
		$args['published'].",".
		$args['type'].",".
		$newsortnum.",".
		$args['amount'].",".
		$args['percentot'].",".
		$args['auto_insert'].",".
		$args['min_quantity'].",".
		$args['cart_tcost'].
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKDEALCREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKDEALCREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTkdeal($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_deal` SET 
		`name`=".$dbo->quote($args['name']).",
		`description`=".$dbo->quote($args['description']).",
		`start_ts`=".$args['start_ts'].",
		`end_ts`=".$args['end_ts'].",
		`max_quantity`=".$args['max_quantity'].",
		`published`=".$args['published'].",
		`type`=".$args['type'].",
		`amount`=".$args['amount'].",
		`percentot`=".$args['percentot'].",
		`auto_insert`=".$args['auto_insert'].",
		`min_quantity`=".$args['min_quantity'].",
		`cart_tcost`=".$args['cart_tcost']." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		$mainframe->enqueueMessage(JText::_('VRTKDEALEDITED1'));
	}
	
	private function removeSelectedTkdealFood($arr, $dbo) {
		if( count($arr) > 0 ) {
			$q = "DELETE FROM `#__cleverdine_takeaway_deal_product_assoc` WHERE `id` IN (".implode(",", $arr).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
	
	private function pushSelectedTkdealFood($id_deal, $arr, $dbo) {
		if( empty($arr['id_prod_option']) ) {
			return;
		}
		
		for( $i = 0; $i < count($arr['id_prod_option']); $i++ ) {
			list($id_prod, $id_opt) = explode(":", $arr['id_prod_option'][$i]);
			$item = array(
				"id" => $arr['id'][$i],
				"id_product" => $id_prod,
				"id_option" => $id_opt,
				"required" => ($arr['required'][$i] == 1 ? 1 : 0),
				"quantity" => ($arr['quantity'][$i] > 0 ? $arr['quantity'][$i] : 1),
			);
			
			if( $item['id'] == -1 ) {
				$q = "INSERT INTO `#__cleverdine_takeaway_deal_product_assoc` (`id_deal`,`id_product`,`id_option`,`required`,`quantity`) VALUES(".
				$id_deal.",".
				$item['id_product'].",".
				$item['id_option'].",".
				$item['required'].",".
				$item['quantity']." 
				);";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_takeaway_deal_product_assoc` SET 
				`required`=".$item['required'].",
				`quantity`=".$item['quantity']." 
				WHERE `id`=".$item['id']." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	}

	private function removeSelectedTkdealFreeFood($arr, $dbo) {
		if( count($arr) > 0 ) {
			$q = "DELETE FROM `#__cleverdine_takeaway_deal_free_assoc` WHERE `id` IN (".implode(",", $arr).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}
	
	private function pushSelectedTkdealFreeFood($id_deal, $arr, $dbo) {
		if( empty($arr['id_prod_option']) ) {
			return;
		}
		
		for( $i = 0; $i < count($arr['id_prod_option']); $i++ ) {
			list($id_prod, $id_opt) = explode(":", $arr['id_prod_option'][$i]);
			$item = array(
				"id" => $arr['id'][$i],
				"id_product" => $id_prod,
				"id_option" => $id_opt,
				"quantity" => ($arr['quantity'][$i] > 0 ? $arr['quantity'][$i] : 1),
			);
			
			if( $item['id'] == -1 ) {
				$q = "INSERT INTO `#__cleverdine_takeaway_deal_free_assoc` (`id_deal`,`id_product`,`id_option`,`quantity`) VALUES(".
				$id_deal.",".
				$item['id_product'].",".
				$item['id_option'].",".
				$item['quantity']." 
				);";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_takeaway_deal_free_assoc` SET 
				`quantity`=".$item['quantity']." 
				WHERE `id`=".$item['id']." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	}

	// SAVE TK DELIVERY AREA
	
	public function saveAndNewTkarea() {
		$this->saveTkarea('index.php?option=com_cleverdine&task=newtkarea');
	}
	
	public function saveAndCloseTkarea() {
		$this->saveTkarea('index.php?option=com_cleverdine&task=tkareas');
	}

	public function saveAsCopyTkarea() {
		// set copy
		JFactory::getApplication()->input->set('id', -1);

		$this->saveTkarea();
	}
	
	public function saveTkarea($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 		= $input->get('name', '', 'string');
		$args['type'] 		= $input->get('type', 0, 'uint');
		$args['charge'] 	= $input->get('charge', 0.0, 'float');
		$args['min_cost'] 	= abs($input->get('min_cost', 0.0, 'float'));
		$args['published'] 	= $input->get('published', 0, 'uint');
		$args['id'] 		= $input->get('id', 0, 'int');

		$args['content'] = array();

		$args['attributes'] = array();
		$args['attributes']['color'] 		= $input->get('color', '', 'string');
		$args['attributes']['strokecolor'] 	= $input->get('strokecolor', '', 'string');
		$args['attributes']['strokeweight'] = $input->get('strokeweight', 1, 'int');
		
		if( $args['type'] <= 0 || $args['type'] > 3 ) {
			$args['type'] = "";
		}
		
		$blank_keys = RestaurantsHelper::validateTkarea($args);
		
		if( count( $blank_keys ) == 0 ) {
			$dbo = JFactory::getDbo();

			if( $args['type'] == 1 ) {

				$latitudes 	= $input->get('polygon_latitude', array(), 'float');
				$longitudes = $input->get('polygon_longitude', array(), 'float');

				for( $i = 0, $n = min(array(count($latitudes), count($longitudes))); $i < $n; $i++ ) {
					if( !empty($latitudes[$i]) && !empty($longitudes[$i]) ) {

						$point = new stdClass;
						$point->latitude 	= $latitudes[$i];
						$point->longitude 	= $longitudes[$i];

						array_push($args['content'], $point);
					}
				}

			} else if( $args['type'] == 2 ) {

				$center = new stdClass;
				$center->latitude 	= $input->get('center_latitude', 0.0, 'float');
				$center->longitude 	= $input->get('center_longitude', 0.0, 'float');

				$args['content']['center'] = $center;
				$args['content']['radius'] = $input->get('radius', 0.0, 'float');

			} else if( $args['type'] == 3 ) {
				$from_zip 	= $input->get('from_zip', array(), 'string');
				$to_zip 	= $input->get('to_zip', array(), 'string');

				for( $i = 0, $n = min(array(count($from_zip), count($to_zip))); $i < $n; $i++ ) {
					if( !empty($from_zip[$i]) || !empty($to_zip[$i]) ) {
						if( empty($from_zip[$i]) ) {
							$from_zip[$i] = $to_zip[$i];
						} else if( empty($to_zip[$i]) ) {
							$to_zip[$i] = $from_zip[$i];
						}

						$zip = new stdClass;
						$zip->from 	= $from_zip[$i];
						$zip->to 	= $to_zip[$i];

						array_push($args['content'], $zip);
					}
				}
			}
				
			if( $args['id'] == -1 ) {
				$args['id'] = $this->saveNewTkarea($args, $dbo, $mainframe);
			} else {
				$this->editSelectedTkarea($args, $dbo, $mainframe);
			}
		} else {
			$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			$mainframe->redirect( "index.php?option=com_cleverdine&task=" . ( ( $args['id'] == -1 ) ? "newtkarea" : "edittkarea&cid[]=".$args['id'] ) );
			exit;
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkarea&cid[]='.$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	private function saveNewTkarea($args, $dbo, $mainframe) {
		
		$q = "SELECT MAX(`ordering`) FROM `#__cleverdine_takeaway_delivery_area`;";
		$dbo->setQuery($q);
		$dbo->execute();
		$newsortnum = (int)$dbo->loadResult() + 1;
		
		$q = "INSERT INTO `#__cleverdine_takeaway_delivery_area`(`name`,`type`,`charge`,`min_cost`,`published`,`content`,`attributes`,`ordering`) VALUES(".
		$dbo->quote($args['name']).",".
		$args['type'].",".
		$args['charge'].",".
		$args['min_cost'].",".
		$args['published'].",".
		$dbo->quote(json_encode($args['content'])).",".
		$dbo->quote(json_encode($args['attributes'])).",".
		$newsortnum.
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRNEWTKAREACREATED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRNEWTKAREACREATED0'), 'error');
		}
		
		return $lid;
	}
	
	private function editSelectedTkarea($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_delivery_area` SET 
		`name`=".$dbo->quote($args['name']).",
		`type`=".$args['type'].",
		`charge`=".$args['charge'].",
		`min_cost`=".$args['min_cost'].",
		`published`=".$args['published'].",
		`content`=".$dbo->quote(json_encode($args['content'])).",
		`attributes`=".$dbo->quote(json_encode($args['attributes']))." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRTKAREAEDITED1'));
		}
	}

	public function publishTakeawayAreas() {
		$this->changeStatusTakeawayAreas(1);
	}
	
	public function unpublishTakeawayAreas() {
		$this->changeStatusTakeawayAreas(0);
	}
	
	private function changeStatusTakeawayAreas($status) {
		$dbo = JFactory::getDbo();
		
		$cid = JFactory::getApplication()->input->get('cid', array(), 'uint');
		
		if( count($cid > 0 ) ) {
			$q = "UPDATE `#__cleverdine_takeaway_delivery_area` SET `published`=".intval($status)." WHERE `id` IN (".implode(',', $cid).");";
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		$this->cancelTkarea();
	}
	
	// SAVE TK RESERVATION
	
	public function saveAndCloseTkreservation() {
		$this->saveTkreservation('index.php?option=com_cleverdine&task=tkreservations');
	}

	public function saveAndNewTkreservation() {
		$this->saveTkreservation('index.php?option=com_cleverdine&task=newtkreservation');
	}

	public function saveAndNextTkreservation() {
		$this->saveTkreservation('index.php?option=com_cleverdine&task=managetkrescart');
	}
	
	public function saveTkreservation($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['date'] 					= $input->getString('date', '');
		$args['hourmin'] 				= $input->getString('hourmin', '');
		$args['delivery_service'] 		= $input->getUint('delivery_service', 0);
		$args['purchaser_nominative'] 	= $input->getString('purchaser_nominative', '');
		$args['purchaser_mail'] 		= $input->getString('purchaser_mail', '');
		$args['purchaser_phone'] 		= $input->getString('purchaser_phone', '');
		$args['purchaser_address'] 		= $input->getString('purchaser_address', '');
		$args['total_to_pay'] 			= $input->getFloat('total_to_pay', 0.0);
		$args['status'] 				= $input->getString('status', '');
		$args['id_payment'] 			= $input->getInt('id_payment', 0);
		$args['pay_charge'] 			= $input->getFloat('pay_charge', 0.0);
		$args['delivery_charge'] 		= $input->getFloat('delivery_charge', 0.0);
		$args['taxes'] 					= $input->getFloat('taxes', 0.0);
		$args['notify_customer'] 		= $input->getUint('notify_customer', 0);
		$args['route']			 		= $input->get('route', array(), 'array');
		$args['notes'] 					= $input->get('notes', '', 'raw');
		$args['id'] 					= $input->getInt('id', 0);
		
		$args['created_by'] = JFactory::getUser()->id;
		$args['id_user'] 	= $input->get('id_user', 0, 'int');

		if( empty($args['id_user']) ) {
			$args['id_user'] = -1;
		}

		if( empty($args['id_payment']) ) {
			$args['id_payment'] = -1;
		}
		
		$args['phone_prefix'] 		= $input->get('phone_prefix', '', 'string');
		$args['purchaser_prefix'] 	= '';
		$args['purchaser_country'] 	= '';
		
		$_cf = array();
		$p_name = $p_mail = $p_phone = $p_prefix = $p_country_code = $p_address = "";
		
		$user_arr = array();
		$q = "SELECT `u`.*, `c`.`phone_prefix` FROM `#__cleverdine_users` AS `u` 
		LEFT JOIN `#__cleverdine_countries` AS `c` ON `c`.`country_2_code`=`u`.`country_code` WHERE `u`.`id`=".$args['id_user']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$user_arr = $dbo->loadAssoc();
		}
		
		$q = "SELECT * FROM `#__cleverdine_custfields` 
		WHERE `group`=1 AND `type`<>'separator' AND (`type`<>'checkbox' OR `required`=0)  
		ORDER BY `ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			$_cf = $dbo->loadAssocList();
		}
		
		$cust_req = array();
		
		$blank_keys = array();
		$_i = 0;
		foreach( $_cf as $_app ) {
			$cust_req[$_app['name']] = $input->get('vrcf'.$_app['id'], '', 'string');
			if( !cleverdine::isCustomFieldValid($_app, $cust_req[$_app['name']]) ) {
				// IF YOU WANT TO MAKE CUSTOM FIELDS REQUIRED, DECOMMENT THESE LINES
				//$blank_keys[$_i] = 'vrcf'.$_app['id'];
				//$_i++;
			} else if( $_app['rule'] == VRCustomFields::NOMINATIVE ) {
				if( !empty($p_name) ) {
					$p_name .= ' ';
				}
				$p_name .= $cust_req[$_app['name']];
			} else if( $_app['rule'] == VRCustomFields::EMAIL ) {
				$p_mail = $cust_req[$_app['name']];
			} else if( $_app['rule'] == VRCustomFields::PHONE_NUMBER ) {
				$p_phone = $cust_req[$_app['name']];
				
				if( !empty($p_phone) ) {
					$country_key = $input->get('vrcf'.$_app['id'].'_prfx', '', 'string');
					if( !empty($country_key) ) {
						$country_key = explode('_', $country_key);
						$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						if( $dbo->getNumRows() > 0 ) {
							$country = $dbo->loadAssoc();
							$p_prefix = $country['phone_prefix'];
							$p_country_code = $country['country_2_code'];
						}
					}
					$p_phone = str_replace(" ", "", $cust_req[$_app['name']]);
				}
			} else if( $_app['rule'] == VRCustomFields::ADDRESS ) {
				$p_address = $cust_req[$_app['name']];
			}
		}
		
		if( strlen( $args['purchaser_nominative'] ) == 0 ) {
			$args['purchaser_nominative'] = $p_name;
		}
		
		if( strlen( $args['purchaser_mail'] ) == 0 ) {
			$args['purchaser_mail'] = $p_mail;
		}

		if( strlen( $args['purchaser_address'] ) == 0 ) {
			$args['purchaser_address'] = $p_address;
		}
		
		if( strlen( $args['purchaser_phone'] ) == 0 ) {
			$args['purchaser_phone'] = $p_phone;
			$p_prefix = $user_arr['phone_prefix'];
			$p_country_code = $user_arr['country_code'];
		}
		
		if( (empty($p_prefix) || empty($p_country_code)) && !empty($args['purchaser_phone']) ) {
			$country_key = $args['phone_prefix'];
			if( !empty($country_key) ) {
				$country_key = explode('_', $country_key);
				$q = "SELECT * FROM `#__cleverdine_countries` WHERE `country_2_code`=".$dbo->quote($country_key[1])." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					$country = $dbo->loadAssoc();
					$p_prefix = $country['phone_prefix'];
					$p_country_code = $country['country_2_code'];
				}
			}
		}
		
		$args['purchaser_prefix'] = $p_prefix;
		$args['purchaser_country'] = $p_country_code;
		
		$error_type_message = 1;
		
		$_resp = cleverdine::isRequestTakeAwayOrderValid($args);
		if( $_resp != 0 ) {
			$_MSG = array( 
				array( 'date' ),
				array( 'hourmin' ),
				array( 'date','hourmin' ),
				array( 'date','hourmin' ) );
			$blank_keys = RestaurantsHelper::mergeIndexedArray($blank_keys,$_MSG[$_resp-1]);
			$error_type_message = 2;
		} else {
			$_app = explode(':',$args['hourmin']);
			$args['hour'] = $_app[0];
			$args['min'] = $_app[1];
		}
		
		$args['custom_f'] = $cust_req;
		
		if( count( $blank_keys ) == 0 ) {
			if( $args['id'] == -1 ) {
				$args['sid'] = cleverdine::generateSerialCode(16);
				$args['id'] = $this->saveNewTkreservation($args, $dbo, $mainframe);
			} else {          
				$this->editSelectedTkreservation($args, $dbo, $mainframe);
			}

		} else {
			if( $error_type_message == 1 ) {
				$mainframe->enqueueMessage(JText::_('VRREQUIREDFIELDSERROR'), 'error');
			} else {
				$mainframe->enqueueMessage(JText::_(cleverdine::getResponseFromTakeAwayOrderRequest($_resp)) , 'error');
			}
			$mainframe->redirect( "index.php?option=com_cleverdine&task=edittkreservation&cid[]=".$args['id'] );
			exit;
		}
		
		if( $args['notify_customer'] == 1 ) {
			$order_details = cleverdine::fetchTakeAwayOrderDetails($args['id']);
			cleverdine::sendCustomerEmailTakeAway($order_details, true);
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=edittkreservation&cid[]='.$args['id'];
		} else if(strpos($redirect_url, "managetkrescart") !== false) {
			$redirect_url .= "&cid[]=".$args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	private function saveNewTkreservation($args, $dbo, $mainframe) {
		
		$q = "INSERT INTO `#__cleverdine_takeaway_reservation` (`sid`,`id_payment`,`checkin_ts`,`delivery_service`,`custom_f`,`purchaser_nominative`,`purchaser_mail`,`purchaser_phone`,`purchaser_prefix`,`purchaser_country`,`purchaser_address`,`total_to_pay`,`taxes`,`delivery_charge`,`pay_charge`,`status`,`notes`,`locked_until`,`created_on`,`created_by`,`id_user`,`route`) VALUES (". 
		$dbo->quote($args['sid']).",".
		$args['id_payment'].",".
		cleverdine::createTimestamp($args['date'],$args['hour'],$args['min'],true).",".
		$args['delivery_service'].",".
		$dbo->quote(json_encode($args['custom_f'])).",".
		$dbo->quote($args['purchaser_nominative']).",".
		$dbo->quote($args['purchaser_mail']).",".
		$dbo->quote($args['purchaser_phone']).",".
		$dbo->quote($args['purchaser_prefix']).",".
		$dbo->quote($args['purchaser_country']).",".
		$dbo->quote($args['purchaser_address']).",".
		$args['total_to_pay'].",".
		$args['taxes'].",".
		$args['delivery_charge'].",".
		$args['pay_charge'].",".
		$dbo->quote($args['status']).",".
		$dbo->quote($args['notes']).",".
		(time()+cleverdine::getTakeAwayOrdersLockedTime(true)*60).",".
		time().",".
		$args['created_by'].",".
		$args['id_user'].",".
		$dbo->quote(json_encode($args['route']))." 
		);";

		$dbo->setQuery($q);
		$dbo->execute();
		$lid = $dbo->insertid();
		if( $lid <= 0 ) {
			$mainframe->enqueueMessage(JText::_('VRTKRESCREATED0'), 'error');
		} else {
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $lid, cleverdine::OPERATOR_TAKEAWAY_LOG, cleverdine::OPERATOR_TAKEAWAY_INSERT);
				cleverdine::storeOperatorLog($operator['id'], $lid, $log, cleverdine::OPERATOR_TAKEAWAY_LOG);
			}
		}
		
		return $lid;
	}
	
	private function editSelectedTkreservation($args, $dbo, $mainframe) {
		
		$q = "UPDATE `#__cleverdine_takeaway_reservation` SET `id_payment`=".$args['id_payment'].",
		`checkin_ts`=".cleverdine::createTimestamp($args['date'],$args['hour'],$args['min'],true).", 
		`delivery_service`=".$args['delivery_service'].", 
		`custom_f`=".$dbo->quote(json_encode($args['custom_f'])).",
		`purchaser_nominative`=".$dbo->quote($args['purchaser_nominative']).",
		`purchaser_mail`=".$dbo->quote($args['purchaser_mail']).",
		`purchaser_phone`=".$dbo->quote($args['purchaser_phone']).",
		`purchaser_prefix`=".$dbo->quote($args['purchaser_prefix']).", 
		`purchaser_country`=".$dbo->quote($args['purchaser_country']).", 
		`purchaser_address`=".$dbo->quote($args['purchaser_address']).", 
		`total_to_pay`=".$args['total_to_pay'].",
		`taxes`=".$args['taxes'].",
		`delivery_charge`=".$args['delivery_charge'].",
		`pay_charge`=".$args['pay_charge'].",
		`status`=".$dbo->quote($args['status']).", 
		`notes`=".$dbo->quote($args['notes']).",
		`id_user`=".$args['id_user'].",
		`route`=".$dbo->quote(json_encode($args['route']))." 
		WHERE `id`=".$args['id']." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getAffectedRows() ) {
			$mainframe->enqueueMessage(JText::_('VRTKRESEDITED1'));
			
			// STORE OPERATOR LOG
			$operator = cleverdine::getOperator();
			if( !empty($operator['id']) && $operator['keep_track'] ) {
				$log = cleverdine::generateOperatorLog($operator, $args['id'], cleverdine::OPERATOR_TAKEAWAY_LOG, cleverdine::OPERATOR_TAKEAWAY_UPDATE);
				cleverdine::storeOperatorLog($operator['id'], $args['id'], $log, cleverdine::OPERATOR_TAKEAWAY_LOG);
			}

			// if something has changed -> update lock time
			$q = "UPDATE `#__cleverdine_takeaway_reservation` SET 
			`locked_until`=".(time()+cleverdine::getTakeAwayOrdersLockedTime(true)*60)." 
			WHERE `id`=".$args['id']." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
		}
	}

	// LANGUAGE MENUS

	public function saveAndNewLangMenu() {
		$this->saveLangMenu('index.php?option=com_cleverdine&task=newlangmenu&id_menu=');
	}
	
	public function saveAndCloseLangMenu() {
		$this->saveLangMenu('index.php?option=com_cleverdine&task=langmenus&id=');
	}
	
	public function saveLangMenu($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$menu = array();
		$menu['name'] 			= $input->get('menu_name', '', 'string');
		$menu['description'] 	= $input->get('menu_description', '', 'raw');
		$menu['id'] 			= $input->get('id_menu', 0, 'uint');
		$menu['id_lang'] 		= $input->get('id_lang_menu', 0, 'uint');
		$menu['tag'] 			= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $menu['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_menus` (`name`,`description`,`id_menu`,`tag`) VALUES(".
			$dbo->quote($menu['name']).",".
			$dbo->quote($menu['description']).",".
			$menu['id'].",".
			$dbo->quote($menu['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$menu['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_menus` SET 
			`name`=".$dbo->quote($menu['name']).",
			`description`=".$dbo->quote($menu['description']).",
			`tag`=".$dbo->quote($menu['tag'])." 
			WHERE `id`=".$menu['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		// SECTIONS
		$sections = array();
		$sections['id'] 			= $input->get('id_section', array(), 'uint');
		$sections['id_lang'] 		= $input->get('id_lang_section', array(), 'uint');
		$sections['name'] 			= $input->get('section_name', array(), 'string');
		$sections['description'] 	= $input->get('section_description', array(), 'array');
		
		$sections_assoc = array();
		
		for( $i = 0; $i < count($sections['id']); $i++ ) {
			if( intval($sections['id_lang'][$i]) <= 0 ) {
				$q = "INSERT INTO `#__cleverdine_lang_menus_section` (`name`,`description`,`id_section`,`id_parent`,`tag`) VALUES(".
				$dbo->quote($sections['name'][$i]).",".
				$dbo->quote($sections['description'][$i]).",".
				intval($sections['id'][$i]).",".
				intval($menu['id_lang']).",".
				$dbo->quote($menu['tag']).
				");";
				
				$dbo->setQuery($q);
				$dbo->execute();
				$sections_assoc[intval($sections['id'][$i])] = $dbo->insertid();
			} else {
				$q = "UPDATE `#__cleverdine_lang_menus_section` SET 
				`name`=".$dbo->quote($sections['name'][$i]).",
				`description`=".$dbo->quote($sections['description'][$i]).",
				`id_parent`=".$menu['id_lang'].",
				`tag`=".$dbo->quote($menu['tag'])." 
				WHERE `id`=".intval($sections['id_lang'][$i])." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
				$sections_assoc[intval($sections['id'][$i])] = intval($sections['id_lang'][$i]);
			}
		}
		
		// PRODUCTS
		$products = array();
		$products['id'] 			= $input->get('id_product', array(), 'uint');
		$products['id_lang'] 		= $input->get('id_lang_product', array(), 'uint');
		$products['id_parent'] 		= $input->get('id_product_parent', array(), 'uint');
		$products['name'] 			= $input->get('product_name', array(), 'string');
		$products['description'] 	= $input->get('product_description', array(), 'array');
		
		$products_assoc = array();
		
		for( $i = 0; $i < count($products['id']); $i++ ) {
			if( intval($products['id_lang'][$i]) <= 0 ) {
				$q = "INSERT INTO `#__cleverdine_lang_section_product` (`name`,`description`,`id_product`,`id_parent`,`tag`) VALUES(".
				$dbo->quote($products['name'][$i]).",".
				$dbo->quote($products['description'][$i]).",".
				intval($products['id'][$i]).",".
				$sections_assoc[intval($products['id_parent'][$i])].",".
				$dbo->quote($menu['tag']).
				");";
				
				$dbo->setQuery($q);
				$dbo->execute();
				$products_assoc[intval($products['id'][$i])] = $dbo->insertid();
			} else {
				$q = "UPDATE `#__cleverdine_lang_section_product` SET 
				`name`=".$dbo->quote($products['name'][$i]).",
				`description`=".$dbo->quote($products['description'][$i]).",
				`id_parent`=".$sections_assoc[intval($products['id_parent'][$i])].",
				`tag`=".$dbo->quote($menu['tag'])." 
				WHERE `id`=".intval($products['id_lang'][$i])." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
				$products_assoc[intval($products['id'][$i])] = intval($products['id_lang'][$i]);
			}
		}
		
		// OPTIONS
		$options = array();
		$options['id'] 			= $input->get('id_option', array(), 'uint');
		$options['id_lang'] 	= $input->get('id_lang_option', array(), 'uint');
		$options['id_parent'] 	= $input->get('id_option_parent', array(), 'uint');
		$options['name'] 		= $input->get('option_name', array(), 'string');
		
		for( $i = 0; $i < count($options['id']); $i++ ) {
			if( intval($options['id_lang'][$i]) <= 0 ) {
				$q = "INSERT INTO `#__cleverdine_lang_section_product_option` (`name`,`id_option`,`id_parent`,`tag`) VALUES(".
				$dbo->quote($options['name'][$i]).",".
				intval($options['id'][$i]).",".
				$products_assoc[intval($options['id_parent'][$i])].",".
				$dbo->quote($menu['tag']).
				");";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_lang_section_product_option` SET 
				`name`=".$dbo->quote($options['name'][$i]).",
				`id_parent`=".$products_assoc[intval($options['id_parent'][$i])].",
				`tag`=".$dbo->quote($menu['tag'])." 
				WHERE `id`=".intval($options['id_lang'][$i])." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangmenu&cid[]='.$menu['id_lang'];
		} else {
			$redirect_url .= $menu['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE TAKEAWAY MENUS

	public function saveAndNewLangTkmenu() {
		$this->saveLangTkmenu('index.php?option=com_cleverdine&task=newlangtkmenu&id_menu=');
	}
	
	public function saveAndCloseLangTkmenu() {
		$this->saveLangTkmenu('index.php?option=com_cleverdine&task=langtkmenus&id=');
	}
	
	public function saveLangTkmenu($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('menu_name', '', 'string');
		$args['description'] 	= $input->get('menu_description', '', 'raw');
		$args['id'] 			= $input->get('id_menu', 0, 'uint');
		$args['id_lang'] 		= $input->get('id_lang_menu', 0, 'uint');
		$args['tag'] 			= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_takeaway_menus` (`name`,`description`,`id_menu`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$dbo->quote($args['description']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_takeaway_menus` SET 
			`name`=".$dbo->quote($args['name']).",
			`description`=".$dbo->quote($args['description']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangtkmenu&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}
	
	// LANGUAGE TAKEAWAY PRODUCTS

	public function saveAndNewLangTkproduct() {
		$this->saveLangTkproduct('index.php?option=com_cleverdine&task=newlangtkproduct&id_product=');
	}
	
	public function saveAndCloseLangTkproduct() {
		$this->saveLangTkproduct('index.php?option=com_cleverdine&task=langtkproducts&id=');
	}
	
	public function saveLangTkproduct($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$prod = array();
		$prod['name'] 			= $input->get('product_name', '', 'string');
		$prod['description'] 	= $input->get('product_description', '', 'raw');
		$prod['id'] 			= $input->get('id_product', 0, 'uint');
		$prod['id_lang'] 		= $input->get('id_lang_product', 0, 'uint');
		$prod['tag'] 			= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $prod['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_takeaway_menus_entry` (`name`,`description`,`id_entry`,`tag`) VALUES(".
			$dbo->quote($prod['name']).",".
			$dbo->quote($prod['description']).",".
			$prod['id'].",".
			$dbo->quote($prod['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$prod['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_takeaway_menus_entry` SET 
			`name`=".$dbo->quote($prod['name']).",
			`description`=".$dbo->quote($prod['description']).",
			`tag`=".$dbo->quote($prod['tag'])." 
			WHERE `id`=".$prod['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		// OPTIONS
		$options = array();
		$options['id'] 		= $input->get('id_option', array(), 'uint');
		$options['id_lang'] = $input->get('id_lang_option', array(), 'uint');
		$options['name'] 	= $input->get('option_name', array(), 'string');
		
		for( $i = 0; $i < count($options['id']); $i++ ) {
			if( intval($options['id_lang'][$i]) <= 0 ) {
				$q = "INSERT INTO `#__cleverdine_lang_takeaway_menus_entry_option` (`name`,`id_option`,`id_parent`,`tag`) VALUES(".
				$dbo->quote($options['name'][$i]).",".
				intval($options['id'][$i]).",".
				$prod['id_lang'].",".
				$dbo->quote($prod['tag']).
				");";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_lang_takeaway_menus_entry_option` SET 
				`name`=".$dbo->quote($options['name'][$i]).",
				`id_parent`=".$prod['id_lang'].",
				`tag`=".$dbo->quote($prod['tag'])." 
				WHERE `id`=".intval($options['id_lang'][$i])." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		// GROUPS
		$groups = array();
		$groups['id'] 		= $input->get('id_group', array(), 'uint');
		$groups['id_lang'] 	= $input->get('id_lang_group', array(), 'uint');
		$groups['name'] 	= $input->get('group_name', array(), 'string');
		
		for( $i = 0; $i < count($groups['id']); $i++ ) {
			if( intval($groups['id_lang'][$i]) <= 0 ) {
				$q = "INSERT INTO `#__cleverdine_lang_takeaway_menus_entry_topping_group` (`name`,`id_group`,`id_parent`,`tag`) VALUES(".
				$dbo->quote($groups['name'][$i]).",".
				intval($groups['id'][$i]).",".
				$prod['id_lang'].",".
				$dbo->quote($prod['tag']).
				");";
				
				$dbo->setQuery($q);
				$dbo->execute();
			} else {
				$q = "UPDATE `#__cleverdine_lang_takeaway_menus_entry_topping_group` SET 
				`name`=".$dbo->quote($groups['name'][$i]).",
				`id_parent`=".$prod['id_lang'].",
				`tag`=".$dbo->quote($prod['tag'])." 
				WHERE `id`=".intval($groups['id_lang'][$i])." LIMIT 1;";
				
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangtkproduct&cid[]='.$prod['id_lang'];
		} else {
			$redirect_url .= $prod['id'];
		}
		$redirect_url .= "&id_menu=".$input->get('id_menu', 0, 'uint');
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE TAKEAWAY TOPPINGS

	public function saveAndNewLangTktopping() {
		$this->saveLangTktopping('index.php?option=com_cleverdine&task=newlangtktopping&id_topping=');
	}
	
	public function saveAndCloseLangTktopping() {
		$this->saveLangTktopping('index.php?option=com_cleverdine&task=langtktoppings&id=');
	}
	
	public function saveLangTktopping($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 		= $input->get('topping_name', '', 'string');
		$args['id'] 		= $input->get('id_topping', 0, 'uint');
		$args['id_lang'] 	= $input->get('id_lang_topping', 0, 'uint');
		$args['tag'] 		= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_takeaway_topping` (`name`,`id_topping`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_takeaway_topping` SET 
			`name`=".$dbo->quote($args['name']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangtktopping&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE TAKEAWAY ATTRIBUTES

	public function saveAndNewLangTkattribute() {
		$this->saveLangTkattribute('index.php?option=com_cleverdine&task=newlangtkattribute&id_attribute=');
	}
	
	public function saveAndCloseLangTkattribute() {
		$this->saveLangTkattribute('index.php?option=com_cleverdine&task=langtkattributes&id=');
	}
	
	public function saveLangTkattribute($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 		= $input->get('attribute_name', '', 'string');
		$args['id'] 		= $input->get('id_attribute', 0, 'uint');
		$args['id_lang'] 	= $input->get('id_lang_attribute', 0, 'uint');
		$args['tag'] 		= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_takeaway_menus_attribute` (`name`,`id_attribute`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_takeaway_menus_attribute` SET 
			`name`=".$dbo->quote($args['name']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangtkattribute&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE TAKEAWAY DEALS

	public function saveAndNewLangTkdeal() {
		$this->saveLangTkdeal('index.php?option=com_cleverdine&task=newlangtkdeal&id_deal=');
	}
	
	public function saveAndCloseLangTkdeal() {
		$this->saveLangTkdeal('index.php?option=com_cleverdine&task=langtkdeals&id=');
	}
	
	public function saveLangTkdeal($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('deal_name', '', 'string');
		$args['description'] 	= $input->get('deal_description', '', 'raw');
		$args['id'] 			= $input->get('id_deal', 0, 'uint');
		$args['id_lang'] 		= $input->get('id_lang_deal', 0, 'uint');
		$args['tag'] 			= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_takeaway_deal` (`name`,`description`,`id_deal`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$dbo->quote($args['description']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_takeaway_deal` SET 
			`name`=".$dbo->quote($args['name']).",
			`description`=".$dbo->quote($args['description']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangtkdeal&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE PAYMENTS

	public function saveAndNewLangPayment() {
		$this->saveLangPayment('index.php?option=com_cleverdine&task=newlangpayment&id_payment=');
	}
	
	public function saveAndCloseLangPayment() {
		$this->saveLangPayment('index.php?option=com_cleverdine&task=langpayments&id=');
	}
	
	public function saveLangPayment($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('payment_name', '', 'string');
		$args['prenote'] 		= $input->get('payment_prenote', '', 'raw');
		$args['note'] 			= $input->get('payment_note', '', 'raw');
		$args['id'] 			= $input->get('id_payment', 0, 'uint');
		$args['id_lang'] 		= $input->get('id_lang_payment', 0, 'uint');
		$args['tag'] 			= $input->get('tag', '', 'string');
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_payments` (`name`,`note`,`prenote`,`id_payment`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$dbo->quote($args['note']).",".
			$dbo->quote($args['prenote']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_payments` SET 
			`name`=".$dbo->quote($args['name']).",
			`note`=".$dbo->quote($args['note']).",
			`prenote`=".$dbo->quote($args['prenote']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
		}
		
		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangpayment&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	// LANGUAGE CUSTOMF FIELDS

	public function saveAndNewLangCustomf() {
		$this->saveLangCustomf('index.php?option=com_cleverdine&task=newlangcustomf&id_customf=');
	}
	
	public function saveAndCloseLangCustomf() {
		$this->saveLangCustomf('index.php?option=com_cleverdine&task=langcustomf&id=');
	}
	
	public function saveLangCustomf($redirect_url = '') {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$args = array();
		$args['name'] 			= $input->get('customf_name', '', 'string');
		$args['def_prfx'] 		= $input->get('customf_defprefix', '', 'string');
		$args['choose']			= $input->get('customf_choose', array(), 'string');
		$args['poplink'] 		= $input->get('customf_poplink', '', 'string');
		$args['id'] 			= $input->get('id_customf', 0, 'uint');
		$args['id_lang'] 		= $input->get('id_lang_customf', 0, 'uint');
		$args['tag'] 			= $input->get('tag', '', 'string');

		if( strlen($args['def_prfx']) ) {
			$args['choose'] = $args['def_prfx'];
		} else {

			$str = '';

			foreach( $args['choose'] as $i => $ch ) {
				if( !empty($ch) ) {
					$str .= ($i > 0 ? ';;__;;' : '').$ch;
				}
			}

			$args['choose'] = $str;

		}
		
		$dbo = JFactory::getDbo();
			
		if( $args['id_lang'] <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_lang_customf` (`name`,`choose`,`poplink`,`id_customf`,`tag`) VALUES(".
			$dbo->quote($args['name']).",".
			$dbo->quote($args['choose']).",".
			$dbo->quote($args['poplink']).",".
			$args['id'].",".
			$dbo->quote($args['tag']).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$args['id_lang'] = $dbo->insertid();
		} else {
			$q = "UPDATE `#__cleverdine_lang_customf` SET 
			`name`=".$dbo->quote($args['name']).",
			`choose`=".$dbo->quote($args['choose']).",
			`poplink`=".$dbo->quote($args['poplink']).",
			`tag`=".$dbo->quote($args['tag'])." 
			WHERE `id`=".$args['id_lang']." LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();
		}

		if( empty($redirect_url) ) {
			$redirect_url = 'index.php?option=com_cleverdine&task=editlangcustomf&cid[]='.$args['id_lang'];
		} else {
			$redirect_url .= $args['id'];
		}
		
		$mainframe->redirect($redirect_url);
	}

	///////////

	public function refreshdash() {
		$input = JFactory::getApplication()->input;

		$l_rows = array();
		$r_rows = array();
		
		$last_id = $input->get('last_id', 0, 'uint');
		$from_id = $input->get('from_id', 0, 'uint');
		
		$last_tk_id = $input->get('last_tk_id', 0, 'uint');
		$from_tk_id = $input->get('from_tk_id', 0, 'uint');
		
		$_df = $input->get('date_f', '', 'string');
		$_sh = $input->get('shift_f', '', 'string');
		
		$_tk_df = $input->get('tkdate_f', '', 'string');
		$_tk_sh = $input->get('tkshift_f', '', 'string');
		
		if( strlen( $_df ) == 0 || cleverdine::createTimestamp($_df,0,0,true) == -1 ) {
			$_df = date( cleverdine::getDateFormat(true), time() );
		}
		
		if( strlen( $_tk_df ) == 0 || cleverdine::createTimestamp($_df,0,0,true) == -1 ) {
			$_tk_df = date( cleverdine::getDateFormat(true), time() );
		}
		
		$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);
		$curr_symb = cleverdine::getCurrencySymb(true);
		$symb_pos = cleverdine::getCurrencySymbPosition(true);
		
		$dbo = JFactory::getDbo();
		
		// RESTAURANT //
		
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `t`.`name` AS `tname` FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` WHERE `r`.`id_table`=`t`.`id` ORDER BY `r`.`id` DESC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$l_rows = $dbo->loadAssocList();
		}
		
		$q_date_filter = cleverdine::createTimestamp($_tk_df,0,0,true) . " <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= " . cleverdine::createTimeStamp($_tk_df,23,59) . " AND ";
		$q_shift_filter = "";
		if( strlen( $_sh ) > 0 ) {
			$_sh_e = explode('-', $_sh); 
			$q_shift_filter = $_sh_e[0] . " <= DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') AND DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') <= " . $_sh_e[1] . " AND ";
		}
		
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `t`.`name` AS `tname`, `r`.`status` FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` WHERE " . $q_date_filter . $q_shift_filter . " `r`.`id_table` = `t`.`id` ORDER BY `r`.`checkin_ts` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$r_rows = $dbo->loadAssocList();
		}
		
		$left_tab = '<table id="vrdashboardtableft">'.
			'<th class="vrdashtabtitle" width="100" style="text-align: left;">'.JText::_('VRMANAGERESERVATION1').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION3').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION4').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION5').'</th>';
			
		for( $i = 0; $i < count($l_rows); $i++ ) {
			if( $last_id < $l_rows[$i]['id'] ) {
				$last_id = $l_rows[$i]['id'];
			}
			$_l = $l_rows[$i];
			$left_tab .= '<tr '.($from_id < $l_rows[$i]['id'] ? 'class="vrdashrowhighlight"' : '').'>'.
				'<td>'.$_l['id'].' - <a href="index.php?option=com_cleverdine&task=printorders&tmpl=component&cid[]='.$_l['id'].'" target="_blank"><b>'.JText::_('VRPRINT').'</b></a></td>'.
				'<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=editreservation&cid[]='.$_l['id'].'" target="_blank">'.date( $dt_format, $_l['checkin_ts'] ).'</a></td>'.
				'<td style="text-align: center;">'.$_l['people'].'</td>'.
				'<td style="text-align: center;">'.$_l['tname'].'</td>'.
			'</tr>';
		}
		$left_tab .= '</table>';
		
		$right_tab = '<table id="vrdashboardtabright">'.
			'<th class="vrdashtabtitle" width="100" style="text-align: left;">'.JText::_('VRMANAGERESERVATION1').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION3').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION4').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION5').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGERESERVATION12').'</th>';
			
		foreach($r_rows as $_r) {
			$right_tab .= '<tr>'.
				'<td>'.$_r['id'].'</td>'.
				'<td style="text-align: center;">'.date( $dt_format, $_r['checkin_ts'] ).'</td>'.
				'<td style="text-align: center;">'.$_r['people'].'</td>'.
				'<td style="text-align: center;">'.$_r['tname'].'</td>'.
				'<td style="text-align: center;" class="vrreservationstatus'.strtolower($_r['status']).'">'.JText::_('VRRESERVATIONSTATUS'.$_r['status']).'</td>'.
			'</tr>';
		}
		$right_tab .= '</table>';
		
		// TAKE-AWAY //
		
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`delivery_service`, `r`.`total_to_pay` FROM `#__cleverdine_takeaway_reservation` AS `r` ORDER BY `r`.`id` DESC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$l_rows = $dbo->loadAssocList();
		}
		
		$q_tk_date_filter = cleverdine::createTimestamp($_tk_df,0,0,true) . " <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= " . cleverdine::createTimeStamp($_tk_df,23,59);
		$q_tk_shift_filter = "";
		if( strlen( $_tk_sh ) > 0 ) {
			$_sh_e = explode('-', $_tk_sh); 
			$q_tk_shift_filter = " AND ".$_sh_e[0] . " <= DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') AND DATE_FORMAT(FROM_UNIXTIME(`r`.`checkin_ts`), '%H') <= " . $_sh_e[1];
		}
		
		$q = "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`delivery_service`, `r`.`total_to_pay`, `r`.`status` FROM `#__cleverdine_takeaway_reservation` AS `r` WHERE " . $q_tk_date_filter . $q_tk_shift_filter . " ORDER BY `r`.`checkin_ts` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$r_rows = $dbo->loadAssocList();
		}
		
		$tk_left_tab = '<table id="vrtkdashboardtableft">'.
			'<th class="vrdashtabtitle" width="100" style="text-align: left;">'.JText::_('VRMANAGETKRES1').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES3').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES13').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES8').'</th>';
			
		for( $i = 0; $i < count($l_rows); $i++ ) {
			if( $last_tk_id < $l_rows[$i]['id'] ) {
				$last_tk_id = $l_rows[$i]['id'];
			}
			$_l = $l_rows[$i];
			$tk_left_tab .= '<tr '.($from_tk_id < $l_rows[$i]['id'] ? 'class="vrdashrowhighlight"' : '').'>'.
				'<td>'.$_l['id'].' - <a href="index.php?option=com_cleverdine&task=tkprintorders&tmpl=component&cid[]='.$_l['id'].'" target="_blank"><b>'.JText::_('VRPRINT').'</b></a></td>'.
				'<td style="text-align: center;"><a href="index.php?option=com_cleverdine&task=edittkreservation&cid[]='.$_l['id'].'" target="_blank">'.date( $dt_format, $_l['checkin_ts'] ).'</a></td>'.
				'<td style="text-align: center;">'.JText::_('VRMANAGETKRES'.($_l['delivery_service'] ? '14' : '15')).'</td>'.
				'<td style="text-align: center;">'.cleverdine::printPriceCurrencySymb($_l['total_to_pay'], $curr_symb, $symb_pos, true).'</td>'.
			'</tr>';
		}
		$tk_left_tab .= '</table>';
		
		$tk_right_tab = '<table id="vrtkdashboardtabright">'.
			'<th class="vrdashtabtitle" width="100" style="text-align: left;">'.JText::_('VRMANAGETKRES1').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES3').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES13').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES8').'</th>'.
			'<th class="vrdashtabtitle" width="100" style="text-align: center;">'.JText::_('VRMANAGETKRES9').'</th>';
			
		foreach($r_rows as $_r) {
			$tk_right_tab .= '<tr>'.
				'<td>'.$_r['id'].'</td>'.
				'<td style="text-align: center;">'.date( $dt_format, $_r['checkin_ts'] ).'</td>'.
				'<td style="text-align: center;">'.JText::_('VRMANAGETKRES'.($_r['delivery_service'] ? '14' : '15')).'</td>'.
				'<td style="text-align: center;">'.cleverdine::printPriceCurrencySymb($_r['total_to_pay'], $curr_symb, $symb_pos, true).'</td>'.
				'<td style="text-align: center;" class="vrreservationstatus'.strtolower($_r['status']).'">'.JText::_('VRRESERVATIONSTATUS'.$_r['status']).'</td>'.
			'</tr>';
		}
		$tk_right_tab .= '</table>';
		
		echo json_encode( array( $left_tab, $right_tab, $tk_left_tab, $tk_right_tab, $last_id, $last_tk_id ) );
		die();
	}

	public function detailsinfo() {
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$date 		= $input->get('date', '', 'string');
		$hourmin 	= $input->get('hourmin', '', 'string');
		$people 	= $input->get('people', 1, 'uint');
		$table 		= $input->get('table', 0, 'uint');
		
		$hourmin = explode(':',$hourmin);
		if( count( $hourmin ) != 2 ) {
			$hourmin = array( -1, 0 );
		}
		
		$dbo = JFactory::getDbo();
		
		$rows = array();
		
		$args = array('date' => $date, 'hour' => $hourmin[0], 'min' => $hourmin[1], 'people' => $people, 'table' => $table);
		
		$q = cleverdine::getQueryAllReservationsRelativeTo($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();

			if( count($rows) == 1 ) {

				$input->set('task', 'editreservation');
				$input->set('cid', array($rows[0]['id']));
				$this->editreservation();

			} else {

				$cid = array();
				foreach( $rows as $r ) {
					$cid[] = $r['id'];
				}

				$input->set('task', 'reservations');
				$input->set('cid', $cid);
				$this->reservations();

			}

			//exit;

		} else {
			exit('order not found');
		}
	}

	public function changetable() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();
		
		$args = array();
		$args['date'] 		= $input->get('date', '', 'string');
		$args['hourmin'] 	= $input->get('hourmin', '', 'string');
		$args['people'] 	= $input->get('people', 1, 'uint');
		$args['id_table'] 	= $input->get('oldid', 0, 'uint');
		$args['table'] 		= $args['id_table'];
		
		$_app_exp = explode( ':', $args['hourmin'] );
		$args['hour'] = -1;
		$args['min'] = 0;
		if( count( $_app_exp ) == 2 ) {
			$args['hour'] = $_app_exp[0];
			$args['min'] = $_app_exp[1];
		}
		
		$new_tab_id = $input->get('newid', 0, 'uint');
		
		$q = cleverdine::getQueryAllReservationsRelativeToWithoutPayments($args,true);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$oid = $rows[0]['id'];
			
			$args['table'] = $args['id_table'] = $new_tab_id;
			
			$multi_res = 0;
			$q = "SELECT `t`.`multi_res` FROM `#__cleverdine_table` AS `t`, `#__cleverdine_reservation` AS `r` WHERE `t`.`id`=`r`.`id_table` AND `t`.`id`=".$new_tab_id.";";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$multi_res = $dbo->loadAssocList();
				$multi_res = $multi_res[0]['multi_res'];
			}
			
			$q = "";
			if( $multi_res == 0 ) {
				$q = cleverdine::getQueryTableJustReserved($args, true);
			} else {
				$q = cleverdine::getQueryFindTableMultiResWithID($args, true);
			}
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 || $multi_res ) {
				
				$result = $dbo->loadAssoc();
				
				if( !$multi_res || count($result) == 0 || $result['curr_capacity']+$args['people'] <= $result['max_capacity'] ) {

					$q = "UPDATE `#__cleverdine_reservation` SET `id_table`=".$new_tab_id." WHERE `id`=".$oid.";";
					$dbo->setQuery($q); 
					$dbo->execute();
					$mainframe->enqueueMessage(JText::_('VRMAPTABLECHANGEDSUCCESS'));
				} else {
					$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
				}
				
			} else {
				$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
			}
			
		} else {
			$mainframe->enqueueMessage(JText::_('VRMAPTABLENOTCHANGED'), 'error');
		}
		
		$mainframe->redirect('index.php?option=com_cleverdine&task=maps');

	}

	public function get_payment_fields() {
	
		$input = JFactory::getApplication()->input;			
		$dbo = JFactory::getDbo();
		
		$gpn 	= $input->get('gpn');
		$id_gp 	= $input->get('id_gp', 0, 'int');
		
		$gp_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'payments'.DIRECTORY_SEPARATOR.$gpn;
		
		if( !file_exists( $gp_path ) || strlen($gpn) == 0 ) {
			echo json_encode( array( array(), array() ) );
			die;
		}
		
		require_once( $gp_path );
		
		if( !method_exists('cleverdinePayment', 'getAdminParameters') ) {
			echo json_encode( array( array(), array() ) );
			die;
		}
		
		$params = array();
		
		$q = "SELECT * FROM `#__cleverdine_gpayments` WHERE `id`=".$id_gp." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payment = $dbo->loadAssoc();
			if( !empty($payment['params']) ) {
				$params = json_decode($payment['params'], true);
			}
		}

		$html = $this->buildPaymentForm(cleverdinePayment::getAdminParameters(), $params);
		
		echo json_encode(array($html));
		//echo json_encode( array( cleverdinePayment::getAdminParameters(), $params ) );
		die;
	}

	private function buildPaymentForm($fields, $params) {
		$html = '';

		$vik = new VikApplication(VersionListener::getID());
		
		foreach( $fields as $key => $f ) {
			$def_val = '';
			if( !empty($params[$key]) ) {
				$def_val = $params[$key];
			} else if( !empty($f['default']) ) {
				$def_val = $f['default'];
			}
			
			$_label_arr = explode('//', $f['label']);
			$label = str_replace(':', '', $_label_arr[0]);
			if( !empty($label) ) {
				$label .= (!empty($f['required']) ? '*' : '').':';
			}

			unset($_label_arr[0]);
			$helplabel = implode('//', $_label_arr);	

			$row = $vik->openControl($label);
			
			$input = '';
			if( $f['type'] == 'text' ) {
				$input = '<input type="text" class="'.(!empty($f['required']) ? 'required' : '').'" value="'.$def_val.'" name="'.$key.'" size="40"/>';	
			} else if( $f['type'] == 'password' ) {
				$input = '<input type="password" class="'.(!empty($f['required']) ? 'required' : '').'" value="'.$def_val.'" name="'.$key.'" size="40"/>';	
			} else if( $f['type'] == 'select' ) {
				$is_assoc = (array_keys($f['options']) !== range(0, count($f['options']) - 1));

				$input = '<select name="'.$key.(!empty($f['multiple']) ? '[]' : '').'" class="'.(!empty($f['required']) ? 'required' : '').'" '.(!empty($f['multiple']) ? 'multiple' : '').'>';
				foreach( $f['options'] as $opt_key => $opt_val ) {

					if( !$is_assoc ) {
						$opt_key = $opt_val;
					}

					$input .= '<option value="'.$opt_key.'" '.( (is_array($def_val) && in_array($opt_key, $def_val)) || $opt_key == $def_val ? 'selected="selected"' : '').'>'.$opt_val.'</option>';
				}
				$input .= '</select>';
			} else {
				$input = $f['html']; 
			}
			
			$row .= $input;
			if( $helplabel ) {
				$row .= '<span class="vikpaymentparamlabelhelp">'.$helplabel.'</span>';
			}
			
			$row .= $vik->closeControl();

			$html .= $row;
			
		};
		
		if( empty($html) ) {
			$html = '<div class="vrpaymentparam">'.JText::_('VRMANAGEPAYMENT9').'</div>';
		}

		return $html;
	}

	public function get_sms_api_fields() {
		
		$sms_api = JFactory::getApplication()->input->get('sms_api');
		
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$sms_api;
		
		if( !file_exists( $sms_api_path ) || strlen($sms_api) == 0 ) {
			echo json_encode( array( array(), array() ) );
			die;
		}
		
		require_once( $sms_api_path );
		
		if( !method_exists('VikSmsApi', 'getAdminParameters') ) {
			echo json_encode( array( array(), array() ) );
			die;
		}
		
		$sms_api_params = cleverdine::getSmsApiFields(true);
		
		echo json_encode( array( VikSmsApi::getAdminParameters(), $sms_api_params ) );
		die;
	}
	
	public function get_sms_api_credit() {

		$input = JFactory::getApplication()->input;
		
		$sms_api 		= $input->get('sms_api', '', 'string');
		$phone_number 	= $input->get('sms_api_phone', '', 'string');
		
		if( empty($phone_number) ) {
			$phone_number = '3333333333';
		}
		
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$sms_api;
		
		if( !file_exists( $sms_api_path ) || strlen($sms_api) == 0 ) {
			echo json_encode( array( 0, JText::_('VRSMSESTIMATEERR1') ) );
			die;
		}
		
		require_once( $sms_api_path );
		
		if( !method_exists('VikSmsApi', 'estimate') ) {
			echo json_encode( array( 0, JText::_('VRSMSESTIMATEERR2') ) );
			die;
		}
		
		$fields = cleverdine::getSmsApiFields(true);
		$api_sms = new VikSmsApi(array(), $fields);
		
		$array_result = $api_sms->estimate($phone_number, 'An example message to estimate...');
		
		if( $array_result->errorCode != 0 ) {
			echo json_encode( array( 0, JText::_('VRSMSESTIMATEERR3') ) );
			die;
		}
		
		echo json_encode( array( 1, $array_result->userCredit, cleverdine::printPriceCurrencySymb($array_result->userCredit) ) );
		die;
		
	}

	public function get_working_shifts() {

		$input = JFactory::getApplication()->input;
		
		$date 	= $input->get('date', '', 'string');
		$sel_hm = $input->get('hourmin', '', 'string');
		
		$shifts = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(1, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array('date' => $date, 'hour' => 0, 'min' => 0, "hourmin" => "0:0"), 1, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 1, true);
			}
		} else {
			// do not change
			echo json_encode(array(1, ''));
			exit;
		}
		
		$min_intervals = cleverdine::getMinuteIntervals(true);
		$time_f = cleverdine::getTimeFormat(true);
		
		$html = '';
		for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
		
			if( $shifts[$k]['showlabel'] ) {
			  $html .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]['label'].'">';
			}
			
			for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
				$_hour = intval($_app/60);
				$_min = $_app%60;
				
				$selected = ($sel_hm==$_hour.":".$_min ? 'selected="selected"' : '');
				
				$html .= '<option value="'.$_hour.':'.$_min.'" '.$selected.'>'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
			}
			
			if( $shifts[$k]['showlabel'] ) {
			  $html .= '</optgroup>';
			}
		}
		
		echo json_encode(array(1, $html));
		exit;
		
	}

	public function get_available_tables() {

		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();

		$args = array();
		$args['date'] 		= $input->get('date', '', 'string');
		$args['hourmin'] 	= $input->get('hourmin', '', 'string');
		$args['people'] 	= $input->get('people', 2, 'uint');

		list($args['hour'], $args['min']) = explode(':', $args['hourmin']);

		$free_tables = array();

		$q = cleverdine::getQueryFindTable($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		// check at least one single table 
		if( $dbo->getNumRows() > 0 ) {

			foreach( $dbo->loadAssocList() as $r ) {
				if( $r['multi_res'] == 0 ) {
					$free_tables[] = $r;
				}
			} 

		}

		// get all available shared tables
		$q = cleverdine::getQueryFindAvailableSharedTables($args, true);
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$free_tables = array_merge($free_tables, $dbo->loadAssocList());
		}

		$list = array();
		foreach( $free_tables as $t ) {
			$list[] = $t['tid'];
		}

		echo json_encode($list);
		exit;

	}

	public function get_takeaway_working_shifts() {

		$input = JFactory::getApplication()->input;
		
		$date 	= $input->get('date', '', 'string');
		$sel_hm = $input->get('hourmin', '', 'string');
		
		$shifts = array();
		
		if( !cleverdine::isContinuosOpeningTime() ) {
			$shifts = cleverdine::getWorkingShifts(2, true);
			$special_day_for = cleverdine::getSpecialDaysOnDate(array('date' => $date, 'hour' => 0, 'min' => 0, "hourmin" => "0:0"), 2, true);
			if( count($special_day_for) > 0 && $special_day_for !== -1 ) {
				$shifts = cleverdine::getWorkingShiftsFromSpecialDays($shifts, $special_day_for, 2, true);
			}
		} else {
			// do not change
			echo json_encode(array(1, ''));
			exit;
		}
		
		$min_intervals = cleverdine::getTakeAwayMinuteInterval(true);
		$time_f = cleverdine::getTimeFormat(true);
		
		$html = '';
		for( $k = 0, $n = count($shifts); $k < $n; $k++ ) {
		
			if( $shifts[$k]['showlabel'] ) {
			  $html .= '<optgroup class="vrsearchoptgrouphour" label="'.$shifts[$k]['label'].'">';
			}
			
			for( $_app = $shifts[$k]['from']; $_app <= $shifts[$k]['to']; $_app+=$min_intervals ) {
				$_hour = intval($_app/60);
				$_min = $_app%60;
				
				$selected = ($sel_hm==$_hour.":".$_min ? 'selected="selected"' : '');
				
				$html .= '<option value="'.$_hour.':'.$_min.'" '.$selected.'>'.date($time_f, mktime($_hour,$_min,0,1,1,2000)).'</option>';
			}
			
			if( $shifts[$k]['showlabel'] ) {
			  $html .= '</optgroup>';
			}
		}
		
		echo json_encode(array(1, $html));
		exit;
		
	}
	
	public function storefile() {

		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		
		$file 		= $input->get('file', '', 'string');
		$code 		= $input->get('code', '', 'raw');
		$as_copy 	= $input->get('ascopy', 0, 'uint');
		
		$file_name = substr($file, strrpos($file, DIRECTORY_SEPARATOR)+1);
		
		if( $as_copy ) {
			$file_path = substr($file, 0, strrpos($file, DIRECTORY_SEPARATOR)+1);
			
			$new_name = $input->get('newname', '', 'string');
			if( empty($new_name) ) {
				$j = 2;
				while( file_exists($file_path.$j.$file_name) ) {
					$j++;
				}
				$file = $file_path.$j.$file_name;
			} else {
				$file = $file_path.$new_name;
			}
		}
		
		$handle = fopen($file, 'wb');
		$bytes = fwrite($handle, $code);
		fclose($handle);
		
		if( $bytes > 0 ) {
			$mainframe->enqueueMessage(JText::_('VRMANAGEFILESAVED1'));
		} else {
			$mainframe->enqueueMessage(JText::_('VRMANAGEFILESAVED0'), 'error');
		}
		$mainframe->redirect('index.php?option=com_cleverdine&task=managefile&file='.$file);
		
	}

	// restaurant bill

	public function resadditem() {

		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();

		$id_product = $input->get('id_product', 0, 'int');
		$id_assoc 	= $input->get('id_assoc', 0, 'int');

		if( empty($id_product) && $id_assoc <= 0 ) {
			$html = $this->buildCreateNewProductHtml();
			
			echo json_encode(array(1, $html));
			exit;
		}
		
		$item = array();

		$q = "SELECT `p`.`id`, `p`.`name`, `p`.`price`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
		FROM `#__cleverdine_section_product` AS `p` 
		LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id` 
		WHERE `p`.`id`=$id_product ORDER BY `o`.`ordering`;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() == 0 ) {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}

		$rows = $dbo->loadAssocList();

		$item['id'] 		= $rows[0]['id'];
		$item['name'] 		= $rows[0]['name'];
		$item['price'] 		= $rows[0]['price'];
		$item['quantity'] 	= 1;
		$item['id_var'] 	= 0;
		$item['notes']		= '';
		$item['variations'] = array();
		foreach( $rows as $r ) {
			if( !empty($r['oid']) ) {
				array_push($item['variations'], array(
					'id' => $r['oid'],
					'name' => $r['oname'],
					'price' => $r['oprice'],
				));
			}
		}

		if( $id_assoc > 0 ) {

			$q = "SELECT `i`.`quantity`, `i`.`price`, `i`.`id_product_option` AS `id_var`, `i`.`notes`
			FROM `#__cleverdine_res_prod_assoc` AS `i`
			WHERE `i`.`id`=$id_assoc LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				
				foreach( $dbo->loadAssoc() as $k => $v ) {
					$item[$k] = $v;
				}

			}

		}

		// HTML
		
		$curr_symb = cleverdine::getCurrencySymb(true);
		$symb_pos = cleverdine::getCurrencySymbPosition(true);
		$_symb_arr = array( '', '' );
		if( $symb_pos == 1 ) {
			$_symb_arr[1] = ' '.$curr_symb;
		} else {
			$_symb_arr[0] = $curr_symb.' ';
		}
		
		$vik = new VikApplication(VersionListener::getID());
		
		$html = '<div class="control-group"><h4>'.$item['name'].'</h4></div>
			'.$vik->openControl(JText::_('VRMANAGETKRES20').':').'
				<input type="number" name="quantity" value="'.$item['quantity'].'" size="4" min="1"/>
			'.$vik->closeControl();
			
		if( count($item['variations']) > 0 ) {
			$html .= $vik->openControl(JText::_('VRTKCARTOPTION5').":").'
					<select name="id_option" class="vrtk-variations-reqselect">';
			foreach( $item['variations'] as $var ) {
				$html .= '<option value="'.$var['id'].'" '.($item['id_var'] == $var['id'] ? 'selected="selected"' : '').'>'.$var['name'].'</option>';
			}
			$html .= '</select>
				'.$vik->closeControl();
		}
			
		$html .= $vik->openControl(JText::_('VRMANAGETKRESTITLE4').':').'
				<textarea name="notes" maxlength="128" style="width: 80%;height:100px;">'.$item['notes'].'</textarea>
			'.$vik->closeControl().'
			<div class="control-group">
				<button type="button" class="vrtk-addtocart-button" onClick="vrPostItem();">
					'.JText::_($id_assoc >= 0 ? "VRSAVE" : "VRADDTOCART").'
				</button>
			</div>
			<input type="hidden" name="item_index" value="'.$id_assoc.'"/>
			<input type="hidden" name="id_entry" value="'.$item['id'].'" />';
		
		echo json_encode(array(1, $html));
		exit;

	}

	protected function buildCreateNewProductHtml() {

		$vik = new VikApplication();

		$html = '<div class="control-group"><h4>'.JText::_('VRCREATENEWPROD').'</h4></div>'.
			$vik->openControl(JText::_('VRMANAGEMENUSPRODUCT2').'*:').'
				<input type="text" name="name" value="" size="32" class="required"/>'.
			$vik->closeControl().

			$vik->openControl(JText::_('VRMANAGEMENUSPRODUCT4').':').'
				<input type="number" name="price" value="0" size="4" min="0" step="any"/>'.
			$vik->closeControl().

			$vik->openControl(JText::_('VRMANAGETKRES20').':').'
				<input type="number" name="quantity" value="1" size="4" min="1"/> '.cleverdine::getCurrencySymb(true).
			$vik->closeControl().
			
			$vik->openControl(JText::_('VRMANAGETKRESTITLE4').':').'
				<textarea name="notes" maxlength="128" style="width: 80%;height:100px;"></textarea>'.
			$vik->closeControl().'
			<div class="control-group">
				<button type="button" class="vrtk-addtocart-button" onClick="vrPostNewItem();">
					'.JText::_("VRADDTOCART").'
				</button>
			</div>
			<input type="hidden" name="item_index" value="-1"/>
			<input type="hidden" name="id_entry" value="0" />';

		return $html;

	}

	public function add_item_to_res() {

		$input = JFactory::getApplication()->input;
		
		$id_entry 			= $input->get('id_entry', 0, 'int');
		$id_option 			= $input->get('id_option', 0, 'int');
		$id_res 			= $input->get('id', 0, 'int');
		$item_cart_index 	= $input->get('item_index', 0, 'int');
		
		$quantity 	= $input->get('quantity', 1, 'uint');
		$notes 		= $input->get('notes', '', 'string');
		
		if( $quantity <= 0 ) {
			$quantity = 1;
		}
		
		$dbo = JFactory::getDbo();
		
		$entry = array();

		$q = "SELECT `p`.`id`, `p`.`name`, `p`.`price`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
		FROM `#__cleverdine_section_product` AS `p` 
		LEFT JOIN `#__cleverdine_section_product_option` AS `o` ON `o`.`id_product`=`p`.`id` 
		WHERE `p`.`id`=$id_entry ".($id_option > 0 ? "AND `o`.`id`=$id_option " : "")." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$entry = $dbo->loadAssoc();
		} else {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}
		
		$insert_id = -1;

		$entry_total_cost = ($entry['price']+$entry['oprice']) * $quantity;

		$entry_full_name = $entry['name'].(!empty($entry['oname']) ? ' - '.$entry['oname'] : '');

		// get total bill

		$total_bill_value = 0;

		$q = "SELECT `bill_value` FROM `#__cleverdine_reservation` WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$total_bill_value = $dbo->loadResult();
		}
		
		// create take-away cart item
		if( $item_cart_index <= 0 ) {

			$q = "INSERT INTO `#__cleverdine_res_prod_assoc`(`id_reservation`,`id_product`,`id_product_option`,`name`,`price`,`quantity`,`notes`) VALUES(".
			$id_res.",".
			$id_entry.",".
			(!empty($id_option) ? $id_option : -1).",".
			$dbo->quote($entry_full_name).",".
			$entry_total_cost.",".
			$quantity.",".
			$dbo->quote($notes).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$insert_id = $dbo->insertid();
			if( $insert_id <= 0 ) {
				echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
				exit;
			}

			$total_bill_value += $entry_total_cost;

		} else {

			$q = "SELECT `price` FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$item_cart_index LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() ) {
				$total_bill_value -= (float)$dbo->loadResult();
			}

			$q = "UPDATE `#__cleverdine_res_prod_assoc` SET 
			`id_product_option`=".(!empty($id_option) ? $id_option : -1).", 
			`name`=".$dbo->quote($entry_full_name).",
			`price`=$entry_total_cost,
			`quantity`=$quantity,
			`notes`=".$dbo->quote($notes)." 
			WHERE `id`=$item_cart_index LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$insert_id = $item_cart_index;

			$total_bill_value += $entry_total_cost;

		}

		// update bill value

		$total_bill_value = max(array(0, $total_bill_value));

		$q = "UPDATE `#__cleverdine_reservation` SET `bill_value`=$total_bill_value WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$std = new stdClass;
		$std->item_id = $id_entry;
		$std->item_name = $entry_full_name;
		$std->price = $entry_total_cost;
		$std->quantity = $quantity;
		
		// return : array( success, index, std item, total bill value )
		echo json_encode(array(1, $insert_id, $std, $total_bill_value));
		exit;
		
	}

	public function add_hidden_item_to_res() {

		$mainframe 	= JFactory::getApplication();
		$input 		= $mainframe->input;
		$dbo 		= JFactory::getDbo();
		
		// product info
		$args = array();
		$args['name'] 			= $input->get('name', '', 'string');
		$args['price'] 			= $input->get('price', 0, 'float');
		$args['description'] 	= '';
		$args['image']			= '';
		$args['published']		= 0;
		$args['hidden']			= 1;

		if( empty($args['name']) ) {
			echo json_encode(array(0, JText::_('VRMANAGECUSTOMERERR3')));
			exit;
		}

		$args['id'] = $this->saveNewMenusProduct($args, $dbo, $mainframe);

		if( $args['id'] <= 0 ) {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}

		$id_res = $input->get('id', 0, 'int');

		// cart prod info
		$quantity 	= $input->get('quantity', 1, 'uint');
		$notes 		= $input->get('notes', '', 'string');

		if( $quantity <= 0 ) {
			$quantity = 1;
		}

		$entry_total_cost = $args['price'] * $quantity;

		// get total bill

		$total_bill_value = 0;

		$q = "SELECT `bill_value` FROM `#__cleverdine_reservation` WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$total_bill_value = $dbo->loadResult();
		}
		
		// create hidden cart item

		$q = "INSERT INTO `#__cleverdine_res_prod_assoc`(`id_reservation`,`id_product`,`id_product_option`,`name`,`price`,`quantity`,`notes`) VALUES(".
		$id_res.",".
		$args['id'].",".
		"-1,".
		$dbo->quote($args['name']).",".
		$entry_total_cost.",".
		$quantity.",".
		$dbo->quote($notes).
		");";
		
		$dbo->setQuery($q);
		$dbo->execute();
		$insert_id = $dbo->insertid();
		if( $insert_id <= 0 ) {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}

		$total_bill_value += $entry_total_cost;

		$q = "UPDATE `#__cleverdine_reservation` SET `bill_value`=$total_bill_value WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$std = new stdClass;
		$std->item_id = $args['id'];
		$std->item_name = $args['name'];
		$std->price = $entry_total_cost;
		$std->quantity = $quantity;
		
		// return : array( success, index, std item, total bill value )
		echo json_encode(array(1, $insert_id, $std, $total_bill_value));
		exit;
		
	}

	public function remove_item_from_res() {
		$input = JFactory::getApplication()->input;

		$id_assoc 	= $input->get('id_assoc', 0, 'uint');
		$id_res 	= $input->get('id_res', 0, 'uint');
		
		$dbo = JFactory::getDbo();

		// get total bill

		$total_bill_value = 0;

		$q = "SELECT `bill_value` FROM `#__cleverdine_reservation` WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			$total_bill_value = $dbo->loadResult();
		}
		
		$q = "SELECT `price` FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$id_assoc LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() == 0 ) {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}

		$total_bill_value -= (float)$dbo->loadResult();

		$total_bill_value = max(array(0, $total_bill_value));
		
		$q = "DELETE FROM `#__cleverdine_res_prod_assoc` WHERE `id`=$id_assoc LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$q = "UPDATE `#__cleverdine_reservation` SET `bill_value`=$total_bill_value WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		// return : array( success, total bill value )
		echo json_encode(array(1, $total_bill_value));
		exit;
	}

	// take-away cart

	public function tkadditem() {

		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();

		$id_product = $input->get('id_product', 0, 'int');
		$id_assoc 	= $input->get('id_assoc', 0, 'int');
		
		$item = array();
		
		$q = "SELECT `e`.`id`, `e`.`name`, `e`.`price`, `o`.id AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice` 
		FROM `#__cleverdine_takeaway_menus_entry` AS `e` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		WHERE `e`.`id`=$id_product ORDER BY `o`.`ordering` ASC;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$item['id'] 		= $rows[0]['id'];
			$item['name'] 		= $rows[0]['name'];
			$item['price'] 		= $rows[0]['price'];
			$item['quantity'] 	= 1;
			$item['notes'] 		= "";
			$item['id_var'] 	= 0;
			$item['variations'] = array();
			$item['selected_toppings'] = array();
			foreach( $rows as $r ) {
				if( !empty($r['oid']) ) {
					array_push($item['variations'], array(
						'id' => $r['oid'],
						'name' => $r['oname'],
						'price' => $r['oprice'],
					));
				}
			}
		} else {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}
		
		if( $id_assoc > 0 ) {
			$q = "SELECT `i`.`quantity`, `i`.`price`, `i`.`notes`, `i`.`id_product_option`, `a`.`id_topping`, `a`.`id_group` 
			FROM `#__cleverdine_takeaway_res_prod_assoc` AS `i`
			LEFT JOIN `#__cleverdine_takeaway_res_prod_topping_assoc` AS `a` ON `i`.`id`=`a`.`id_assoc` 
			WHERE `i`.`id`=$id_assoc;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$rows = $dbo->loadAssocList();
				$item['price'] 		= $rows[0]['price'];
				$item['quantity'] 	= $rows[0]['quantity'];
				$item['notes'] 		= $rows[0]['notes'];
				$item['id_var'] 	= $rows[0]['id_product_option'];
				
				$last_group_id = -1;
				foreach( $rows as $r ) {
					if( !empty($r['id_group']) && $last_group_id != $r['id_group']) {
						$item['selected_toppings'][$r['id_group']] = array();
						$last_group_id = $r['id_group'];
					}
					if( !empty($r['id_topping']) ) {
						array_push($item['selected_toppings'][$r['id_group']], $r['id_topping']);
					}
				}
			}
		}
		
		$entry_groups = array();
		$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
		FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
		LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
		WHERE `g`.`id_entry`=$id_product 
		ORDER BY `g`.`ordering` ASC, `t`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
			
			$last_group_id = -1;
			foreach( $app as $group ) {
				$group['toppings'] = array();
				if( $group['id'] != $last_group_id ) {
					array_push($entry_groups, $group);
					$last_group_id = $group['id'];
				}
				
				if( !empty($group['topping_group_assoc_id']) ) {
					array_push($entry_groups[count($entry_groups)-1]['toppings'], array(
						"assoc_id" => $group['topping_group_assoc_id'],
						"id" => $group['id_topping'],
						"name" => $group['topping_name'],
						"rate" => $group['topping_rate'],
						"ordering" => $group['topping_ordering'],
						"checked" => (!empty($item['selected_toppings'][$group['id']]) && in_array($group['id_topping'], $item['selected_toppings'][$group['id']]) ? 1 : 0),
					));
				}
			}
		}
		
		// HTML
		
		$curr_symb = cleverdine::getCurrencySymb(true);
		$symb_pos = cleverdine::getCurrencySymbPosition(true);
		$_symb_arr = array( '', '' );
		if( $symb_pos == 1 ) {
			$_symb_arr[1] = ' '.$curr_symb;
		} else {
			$_symb_arr[0] = $curr_symb.' ';
		}
		
		$toppings_map_costs = array();
		
		$vik = new VikApplication(VersionListener::getID());
		
		$html = '<div class="control-group"><h4>'.$item['name'].'</h4></div>
			'.$vik->openControl(JText::_('VRMANAGETKRES20').':').'
				<input type="number" name="quantity" value="'.$item['quantity'].'" size="4" min="1"/>
			'.$vik->closeControl();

		$in_stock = null;

		if( cleverdine::isTakeAwayStockEnabled(true) ) {

			if( count($item['variations']) == 0 ) {
				$in_stock = cleverdine::getTakeawayItemRemainingInStock($item['id'], 0, -1, $dbo);

				$html .= $vik->openControl(JText::_('VRMANAGETKSTOCK7').':');
				$html .= '<span class="control-html-value">'.$in_stock.'</span>';
				$html .= $vik->closeControl();
			} else {
				$in_stock = array();
				foreach( $item['variations'] as $var ) {
					$in_stock[$var['id']] = cleverdine::getTakeawayItemRemainingInStock($item['id'], $var['id'], -1, $dbo);
				}

				$html .= $vik->openControl(JText::_('VRMANAGETKSTOCK7').':');
				$html .= '<span class="control-html-value vr-items-avail">'.$in_stock[$item['variations'][0]['id']].'</span>';
				$html .= $vik->closeControl();
			}

		}
			
		if( count($item['variations']) > 0 ) {
			$html .= $vik->openControl(JText::_('VRTKCARTOPTION5').":").'
					<select name="id_option" class="vrtk-variations-reqselect">';
			foreach( $item['variations'] as $var ) {
				$html .= '<option value="'.$var['id'].'" '.($item['id_var'] == $var['id'] ? 'selected="selected"' : '').'>'.$var['name'].'</option>';
			}
			$html .= '</select>
				'.$vik->closeControl();

			// handle variation change
			if( $in_stock !== null && is_array($in_stock) ) {
				$html .= '<script type="text/javascript">
					var VARS_STOCK_MAP = '.json_encode($in_stock).';
					jQuery(".vrtk-variations-reqselect").on("change", function(){
						var val = jQuery(this).val();
						var stock = "";
						if( VARS_STOCK_MAP.hasOwnProperty(val) ) {
							stock = VARS_STOCK_MAP[val];
						}

						jQuery(".vr-items-avail").text(stock);
					});
				</script>';
			}
		}
		
		foreach( $entry_groups as $group ) {
			$html .= $vik->openControl($group['title'].":").'
					<select name="topping['.$group['id'].'][]" '.($group['multiple'] ? 'multiple' : '').' 
						class="'.($group['min_toppings'] == 0 ? 'vrtk-toppings-select' : 'vrtk-toppings-reqselect').'">';
			foreach( $group['toppings'] as $topping ) {
				$html .= '<option value="'.$topping['assoc_id'].'" '.($topping['checked'] ? 'selected="selected"' : '').'>'.$topping['name'].'</option>';
			}
			$html .= '</select>
				'.$vik->closeControl();
		}
			
		$html .= $vik->openControl(JText::_('VRMANAGETKRESTITLE4').':').'
				<textarea name="notes" maxlength="256" style="width: 80%;height:100px;">'.$item['notes'].'</textarea>
			'.$vik->closeControl().'
			<div class="control-group">
				<button type="button" class="vrtk-addtocart-button" onClick="vrPostTakeAwayItem();">
					'.JText::_($id_assoc >= 0 ? "VRSAVE" : "VRADDTOCART").'
				</button>
			</div>
			<input type="hidden" name="item_index" value="'.$id_assoc.'"/>
			<input type="hidden" name="id_entry" value="'.$item['id'].'" />';
		
		echo json_encode(array(1, $html));
		exit;

	} 

	public function add_item_to_cart() {

		$input = JFactory::getApplication()->input;
		
		$id_entry 			= $input->get('id_entry', 0, 'int');
		$id_option 			= $input->get('id_option', 0, 'int');
		$id_res 			= $input->get('id', 0, 'int');
		$item_cart_index 	= $input->get('item_index', 0, 'int');
		
		$quantity 	= $input->get('quantity', 1, 'uint');
		$notes 		= $input->get('notes', '', 'string');
		$toppings 	= $input->get('topping', array(), 'array');
		
		if( $quantity <= 0 ) {
			$quantity = 1;
		}

		$use_taxes = cleverdine::isTakeAwayTaxesUsable(true);
		
		$order_details = cleverdine::fetchTakeawayOrderDetails($id_res);
		$total_net_price = $order_details['total_to_pay']-$order_details['pay_charge']-$order_details['delivery_charge'];

		if( $use_taxes ) {
			// if taxes are not incldued, subtract them from the order total
			$total_net_price -= $order_details['taxes'];
		}
		
		$dbo = JFactory::getDbo();
		
		$entry = array();
		
		$q = "SELECT `e`.*, `o`.id AS `oid`, `o`.`name` AS `oname`, `o`.`inc_price` AS `oprice`, `m`.`taxes_type`, `m`.`taxes_amount` 
		FROM `#__cleverdine_takeaway_menus_entry` AS `e` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id`=`o`.`id_takeaway_menu_entry` 
		LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`e`.`id_takeaway_menu` 
		WHERE `e`.`id`=$id_entry ".($id_option > 0 ? "AND `o`.`id`=$id_option " : "")." LIMIT 1;";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$entry = $dbo->loadAssoc();
		} else {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}

		// taxes
		if( $entry['taxes_type'] == 0 ) {
			$entry['taxes_amount'] = cleverdine::getTakeAwayTaxesRatio();
		}
		
		$entry_groups = array();
		$q = "SELECT `g`.*, `t`.`id` AS `topping_group_assoc_id`, `t`.`id_topping`, `t`.`rate` AS `topping_rate`, `t`.`ordering` AS `topping_ordering`, `t2`.`name` AS `topping_name` 
		FROM `#__cleverdine_takeaway_entry_group_assoc` AS `g` 
		LEFT JOIN `#__cleverdine_takeaway_group_topping_assoc` AS `t` ON `g`.`id`=`t`.`id_group`
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `t2` ON `t`.`id_topping`=`t2`.`id` 
		WHERE `g`.`id_entry`=$id_entry 
		ORDER BY `g`.`ordering` ASC, `t`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$app = $dbo->loadAssocList();
			
			$last_group_id = -1;
			foreach( $app as $group ) {
				$group['toppings'] = array();
				if( $group['id'] != $last_group_id ) {
					array_push($entry_groups, $group);
					$last_group_id = $group['id'];
				}
				
				if( !empty($group['topping_group_assoc_id']) ) {
					array_push($entry_groups[count($entry_groups)-1]['toppings'], array(
						"assoc_id" => $group['topping_group_assoc_id'],
						"id" => $group['id_topping'],
						"name" => $group['topping_name'],
						"rate" => $group['topping_rate'],
						"ordering" => $group['topping_ordering']
					));
				}
			}
		}
		
		$insert_id = -1;

		$msg = null;

		// CHECK IN STOCK
		$in_stock = cleverdine::getTakeawayItemRemainingInStock($id_entry, $id_option, $item_cart_index, $dbo);
		if( $in_stock != -1 ) { // if -1, stocks are disabled
			
			if( $in_stock-$quantity < 0 ) {
				$removed_items = $quantity-$in_stock;

				$msg = new stdClass;
				if( $quantity == $removed_items ) {
					$msg->text = JText::sprintf('VRTKSTOCKNOITEMS', $entry['name'].(empty($entry['oname']) ? '' : ' - '.$entry['oname']) );
					$msg->status = 0;
					echo json_encode(array(0, $msg->text));
					exit;
				} else {
					$msg->text = JText::sprintf('VRTKSTOCKREMOVEDITEMS', $entry['name'].(empty($entry['oname']) ? '' : ' - '.$entry['oname']), $removed_items);
					$msg->status = 2;
				}

				$quantity -= $removed_items;
			}
		}
		//
		
		// create take-away cart item
		if( $item_cart_index <= 0 ) {
			$q = "INSERT INTO `#__cleverdine_takeaway_res_prod_assoc`(`id_product`,`id_res`,`id_product_option`,`quantity`,`notes`) VALUES(
			".$id_entry.",
			".$id_res.",
			".(!empty($id_option) ? $id_option : -1).",
			".$quantity.",
			".$dbo->quote($notes).
			");";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$insert_id = $dbo->insertid();
			if( $insert_id <= 0 ) {
				echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
				exit;
			}
		} else {
			$q = "UPDATE `#__cleverdine_takeaway_res_prod_assoc` SET 
			`id_product_option`=".(!empty($id_option) ? $id_option : -1).", 
			`quantity`=$quantity,
			`notes`=".$dbo->quote($notes)." 
			WHERE `id`=$item_cart_index LIMIT 1;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			$insert_id = $item_cart_index;
			
			$q = "SELECT `price`, `taxes` FROM `#__cleverdine_takeaway_res_prod_assoc` WHERE `id`=$item_cart_index LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$item_stored = $dbo->loadAssoc();
				$total_net_price -= floatval($item_stored['price']);
				$order_details['taxes'] -= floatval($item_stored['taxes']);
			}
		}
		
		$groups_to_add = array();
		
		// validate toppings against groups
		foreach( $entry_groups as $group ) {
			
			// create take-away cart item group
			$item_group = array(
				'id' => $group['id'], 
				'title' => $group['title'], 
				'multiple' => $group['multiple'],
				'toppings' => array()
			);
			
			if( empty($toppings[$group['id']]) ) {
				$toppings[$group['id']] = array();
			}
			
			$to_remove = array();
			// validate selected toppings
			for( $i = 0; $i < count($toppings[$group['id']]); $i++ ) {
				$found = false;
				for( $j = 0; $j < count($group['toppings']) && !$found; $j++ ) {
					if( $toppings[$group['id']][$i] == $group['toppings'][$j]['assoc_id'] ) {
						$found = true;
					}
				}
				if( !$found ) {
					array_push($to_remove, $i);
				}
			}
			
			// check repeated toppings
			for( $i = 0; $i < count($toppings[$group['id']])-1; $i++ ) {
				for( $j = $i+1; $j < count($toppings[$group['id']]); $j++ ) {
					if( $toppings[$group['id']][$i] == $toppings[$group['id']][$j] ) {
						array_push($to_remove, $j);
					}
				}
			}
			
			// remove wrong toppings
			foreach( $to_remove as $rm ) {
				if( !empty($toppings[$group['id']][$rm]) ) {
					unset($toppings[$group['id']][$rm]);
				}
			}
			
			// get toppings objects
			for( $i = 0; $i < count($toppings[$group['id']]); $i++ ) {
				$found = false;
				for( $j = 0; $j < count($group['toppings']) && !$found; $j++ ) {
					if( $toppings[$group['id']][$i] == $group['toppings'][$j]['assoc_id'] ) {
							
						// create take-away cart item group
						$item_group_topping = array(
							"id" => $group['toppings'][$j]['id'], 
							"id_assoc" => $group['toppings'][$j]['assoc_id'], 
							"name" => $group['toppings'][$j]['name'], 
							"rate" => $group['toppings'][$j]['rate']
						);
						array_push($item_group['toppings'], $item_group_topping);
						
						$found = true;
					}
				}
			}

			array_push($groups_to_add, $item_group);
			
		}

		$entry_total_cost = $entry['price']+$entry['oprice'];
		
		if( $item_cart_index < 0 ) {
			foreach( $groups_to_add as $group ) {
				foreach( $group['toppings'] as $topping ) {
					$q = "INSERT INTO `#__cleverdine_takeaway_res_prod_topping_assoc` (`id_assoc`,`id_group`,`id_topping`) VALUES(
					".$insert_id.",
					".$group['id'].",
					".$topping['id'].");";
					$dbo->setQuery($q);
					$dbo->execute();
					
					$entry_total_cost += $topping['rate'];
				}
			}
		} else {
			$existing_toppings = array();
			$app = array();
			$q = "SELECT * FROM `#__cleverdine_takeaway_res_prod_topping_assoc` WHERE `id_assoc`=$item_cart_index;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$existing_toppings = $dbo->loadAssocList();
			}
			
			foreach( $existing_toppings as $tp_stored ) {
				$found = false;
				for( $i = 0; $i < count($groups_to_add) && !$found; $i++ ) {
					for( $j = 0; $j < count($groups_to_add[$i]['toppings']) && !$found; $j++ ) {
						$found = ( $groups_to_add[$i]['id'] == $tp_stored['id_group'] && 
						$groups_to_add[$i]['toppings'][$j]['id'] == $tp_stored['id_topping'] );
					}
				}
				
				if( !$found ) {
					$q = "DELETE FROM `#__cleverdine_takeaway_res_prod_topping_assoc` WHERE `id`=".$tp_stored['id']." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
				} else {
					array_push($app, $tp_stored);
				}
			}
			
			$existing_toppings = $app;
			
			foreach( $groups_to_add as $group ) {
				foreach( $group['toppings'] as $topping ) {
					$found = false;
					for( $j = 0; $j < count($existing_toppings) && !$found; $j++ ) {
						$found = ( $group['id'] == $existing_toppings[$j]['id_group'] && 
						$topping['id'] == $existing_toppings[$j]['id_topping'] );
					}
					
					if( !$found ) {
						$q = "INSERT INTO `#__cleverdine_takeaway_res_prod_topping_assoc` (`id_assoc`,`id_group`,`id_topping`) VALUES(
						".$insert_id.",
						".$group['id'].",
						".$topping['id'].");";
						$dbo->setQuery($q);
						$dbo->execute();
					}
					$entry_total_cost += $topping['rate'];
				
				}
			}
			
		}
		
		$entry_total_cost *= $quantity;
		$entry_taxes = $entry_total_cost*$entry['taxes_amount']/100.0;
		
		$total_net_price += $entry_total_cost;
		$taxes = $order_details['taxes'] + $entry_taxes;
		$total_order_cost = $total_net_price+$order_details['pay_charge']+$order_details['delivery_charge'];

		if( $use_taxes ) {
			// if taxes are not incldued, sum them from the order total
			$total_order_cost += $taxes;
		} else {
			// if taxes are included, subtract them from the total net
			$total_net_price -= $taxes;
		}

		$q = "UPDATE `#__cleverdine_takeaway_res_prod_assoc` SET 
		`price`=$entry_total_cost,
		`taxes`=$entry_taxes 
		WHERE `id`=$insert_id LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$q = "UPDATE `#__cleverdine_takeaway_reservation` SET 
		`total_to_pay`=$total_order_cost, 
		`taxes`=$taxes 
		WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$std = new stdClass;
		$std->item_id = $id_entry;
		$std->item_name = $entry['name'];
		$std->var_name = (!empty($entry['oname']) ? $entry['oname'] : '');
		$std->price = $entry_total_cost;
		$std->quantity = $quantity;

		//$total_net_price = $total_order_cost-$taxes;
		
		// return : array( success, index, std item, total cost, total net, taxes, warning message )
		echo json_encode(array(1, $insert_id, $std, $total_order_cost, $total_net_price, $taxes, $msg));
		exit;
		
	}

	public function remove_item_from_cart() {
		$input = JFactory::getApplication()->input;

		$id_assoc 	= $input->get('id_assoc', 0, 'uint');
		$id_res 	= $input->get('id_res', 0, 'uint');
		
		$use_taxes = cleverdine::isTakeAwayTaxesUsable(true);

		$order_details = cleverdine::fetchTakeawayOrderDetails($id_res);
		$total_net_price = $order_details['total_to_pay']-$order_details['pay_charge']-$order_details['delivery_charge'];

		if( $use_taxes ) {
			// if taxes are not incldued, subtract them from the order total
			$total_net_price -= $order_details['taxes'];
		}
		
		$dbo = JFactory::getDbo();

		$entry = array();
		
		$q = "SELECT `price`, `taxes` FROM `#__cleverdine_takeaway_res_prod_assoc` WHERE `id`=$id_assoc LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$entry = $dbo->loadAssoc();
			$total_net_price -= (float)$entry['price'];
		} else {
			echo json_encode(array(0, JText::_('VRTKCARTROWNOTFOUND')));
			exit;
		}
		
		$q = "DELETE FROM `#__cleverdine_takeaway_res_prod_topping_assoc` WHERE `id_assoc`=$id_assoc LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$q = "DELETE FROM `#__cleverdine_takeaway_res_prod_assoc` WHERE `id`=$id_assoc LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		
		$taxes = $order_details['taxes'] - $entry['taxes'];
		$total_order_cost = $total_net_price+$order_details['pay_charge']+$order_details['delivery_charge'];

		if( $use_taxes ) {
			// if taxes are not incldued, sum them from the order total
			$total_order_cost += $taxes;
		} else {
			// if taxes are included, subtract them from the total net
			$total_net_price -= $taxes;
		}

		if( $total_net_price == 0 ) {
			$taxes = 0;
		}
		
		$q = "UPDATE `#__cleverdine_takeaway_reservation` SET 
		`total_to_pay`=$total_order_cost,
		`taxes`=$taxes 
		WHERE `id`=$id_res LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		//$total_net_price = $total_order_cost-$taxes;
		
		// return : array( success, total cost, total net, taxes )
		echo json_encode(array(1, $total_order_cost, $total_net_price, $taxes));
		exit;
	}
	
	// REMOVE
	
	public function deleteTablesByIds($ids) {
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_table` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	}
	
	public function deleteTables() {
		
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
		
		$this->deleteTablesByIds($ids);
		
		$this->cancelTable();
	}
	
	public function deleteRooms() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "DELETE FROM `#__cleverdine_room` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_table` WHERE `id_room`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_room_closure` WHERE `id_room`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelRoom();
	}
	
	
	public function deleteRoomClosures() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_room_closure` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelRoomClosure();
	}
	
	public function deleteOperators() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "DELETE FROM `#__cleverdine_operator` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_operator_log` WHERE `id_operator`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelOperator();
	}
	
	public function deleteOperatorLogs() {
	
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;

		$ids = $input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_operator_log` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		$df = $input->get('datefilter', '', 'string');
		$ks = $input->get('keysearch', '', 'string');
		
		$mainframe->redirect("index.php?option=com_cleverdine&task=operatorlogs&id=".$input->get('id', 0, 'int').(empty($df) ? '' : "&datefilter=$df").(empty($ks) ? '' : "&keysearch=$ks"));
	}
	
	public function deleteReservations() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "DELETE FROM `#__cleverdine_reservation` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_res_menus_assoc` WHERE `id_reservation`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelReservation();
	}

	public function deleteCustomers() {
		
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
		
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "DELETE FROM `#__cleverdine_user_delivery` WHERE `id_user`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "DELETE FROM `#__cleverdine_users` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

			}
		}
		
		$this->cancelCustomer();
	}
	
	public function deleteShifts() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_shifts` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelShift();
	}
	
	public function deleteMenusProducts() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "DELETE FROM `#__cleverdine_section_product` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_section_product_assoc` WHERE `id_product`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();

			}
		}
	
		$this->cancelMenusProduct();
	}
	
	public function deleteMenus() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "SELECT `id` FROM `#__cleverdine_menus_section` WHERE `id_menu`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $row ) {
						$q = "DELETE FROM `#__cleverdine_section_product_assoc` WHERE `id_section`=".$row['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				
				$q = "DELETE FROM `#__cleverdine_menus` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_menus_section` WHERE `id_menu`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_sd_menus` WHERE `id_menu`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_res_menus_assoc` WHERE `id_menu`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelMenu();
	}
	
	public function deleteSpecialDays() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_specialdays` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_sd_menus` WHERE `id_spday`=".intval($id).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelSpecialDay();
	}
	
	
	public function deletePayments() {
		
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
		
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_gpayments` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
		
		$this->cancelPayment();
	}
	
	public function deleteCustomf() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_custfields` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelCustomf();
	}

	public function deleteReviews() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_reviews` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelReview();
	}
	
	public function deleteCoupons() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_coupons` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelCoupon();
	}
	
	public function deleteResCodes() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "DELETE FROM `#__cleverdine_res_code` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "UPDATE `#__cleverdine_reservation` SET `rescode`=0 WHERE `rescode`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "UPDATE `#__cleverdine_takeaway_reservation` SET `rescode`=0 WHERE `rescode`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelResCode();
	}

	public function deleteResCodesOrder() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "DELETE FROM `#__cleverdine_order_status` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelResCodeOrder();
	}
	
	public function deleteMedia() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'string');
		
		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;
	
		if( count( $ids ) ) {
			foreach( $ids as $id ) {
				if( file_exists($basepath.'media'.DIRECTORY_SEPARATOR.$id) ) {
					unlink($basepath.'media'.DIRECTORY_SEPARATOR.$id);
				}

				if( file_exists($basepath.'media@small'.DIRECTORY_SEPARATOR.$id) ) {
					unlink($basepath.'media@small'.DIRECTORY_SEPARATOR.$id);
				}
			}
		}
	
		$this->cancelMedia();
	}

	public function deleteInvoices() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'string');
		
		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR;
		
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$invoice = explode('::', $id);
				if( $invoice[0] == 1 ) {
					
					$q = "SELECT * FROM `#__cleverdine_invoice` WHERE `id`=".intval($invoice[1])." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$row = $dbo->loadAssoc();

						if( file_exists($basepath.$row['file']) ) {
							unlink($basepath.$row['file']);
						}

						$q = "DELETE FROM `#__cleverdine_invoice` WHERE `id`=".$row['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();

					}

				}
			}
		}
	
		$this->cancelInvoice();
	}

	public function deleteApiusers() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "DELETE FROM `#__cleverdine_api_login` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "DELETE FROM `#__cleverdine_api_login_logs` WHERE `id_login`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelApiuser();
	}

	public function deleteApiplugins() {

		$ids = JFactory::getApplication()->input->get('cid', array(), 'string');
	
		if( count( $ids ) ) {
			
			cleverdine::loadFrameworkApis();

			$apis = FrameworkAPIs::getInstance();
			$path = $apis->getEventPath();

			foreach( $ids as $id ) {

				if( file_exists($path.$id.'.php') ) {
					unlink($path.$id.'.php');
				}

			}

		}
	
		$this->cancelApiplugin();

	}

	public function deleteApilogs() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();

			$count = 0;

			$q = "SELECT COUNT(1) FROM `#__cleverdine_api_login_logs` LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() ) {
				$count = $dbo->loadResult();
			}

			if( $count == count($ids) ) {

				$this->truncateApilogs($dbo, false);

			} else {

				foreach( $ids as $id ) {
					$id = intval($id);

					$q = "DELETE FROM `#__cleverdine_api_login_logs` WHERE `id`=$id LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
				}
				
			}
			
		}
	
		$this->cancelApilog();
	}

	public function truncateApilogs($dbo = null, $redirect = true) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$q = "TRUNCATE TABLE `#__cleverdine_api_login_logs`;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $redirect ) {
			$this->cancelApilog();
		}

	}

	public function deleteApibans() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "DELETE FROM `#__cleverdine_api_ban` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelApiban();
	}

	public function deleteTkmenus() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);

				$q = "SELECT `id` AS `eid` FROM `#__cleverdine_takeaway_menus_entry` WHERE `id_takeaway_menu`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				if( $dbo->getNumRows() > 0 ) {
					$_entries = $dbo->loadAssocList();

					$id_list = array();
					foreach( $_entries as $e ) {
						array_push($id_list, intval($e['eid']));
					}

					$this->deleteTkentries($id_list);
				}
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelTkmenu();
	}

	public function deleteTkmenuattributes() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_attribute` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_attribute`=".intval($id).";";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTkmenuattribute();
	}

	public function deleteTkentries($ids = null) {

		$can_redirect = false;

		if( $ids === null ) {
			$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
			$can_redirect = true;
		}
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$id = intval($id);
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_entry_option` WHERE `id_takeaway_menu_entry`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_attr_assoc` WHERE `id_menuentry`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "DELETE FROM `#__cleverdine_takeaway_stock_override` WHERE `id_takeaway_entry`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_menus_entry` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "SELECT `id` FROM `#__cleverdine_takeaway_entry_group_assoc` WHERE `id_entry`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $group ) {
						$q = "DELETE FROM `#__cleverdine_takeaway_group_topping_assoc` WHERE `id_group`=".intval($group['id'])." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}

				$q = "DELETE FROM `#__cleverdine_takeaway_entry_group_assoc` WHERE `id_entry`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		if( $can_redirect ) {
			$this->cancelTkproduct();
		}
	}

	public function deleteTktoppings() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_topping` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "DELETE FROM `#__cleverdine_takeaway_group_topping_assoc` WHERE `id_topping`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();

				// language

				$q = "DELETE FROM `#__cleverdine_lang_takeaway_topping` WHERE `id_topping`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTktopping();
	}
	
	public function deleteTktoppingsSeparators() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_topping_separator` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "UPDATE `#__cleverdine_takeaway_topping` SET `id_separator`=-1 WHERE `id_separator`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTktoppingSeparator();
	}

	public function deleteTkdeals() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_deal_product_assoc` WHERE `id_deal`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_deal_free_assoc` WHERE `id_deal`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_deal_day_assoc` WHERE `id_deal`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_takeaway_deal` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();

				// language

				$q = "DELETE FROM `#__cleverdine_lang_takeaway_deal` WHERE `id_deal`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTkdeal();
	}

	public function deleteTkareas() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_delivery_area` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTkarea();
	}
	
	public function deleteTkreservations() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				
				$q = "DELETE FROM `#__cleverdine_takeaway_reservation` WHERE `id`=$id LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "SELECT `id` FROM `#__cleverdine_takeaway_res_prod_assoc` WHERE `id_res`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $topping ) {
						$q = "DELETE FROM `#__cleverdine_takeaway_res_prod_topping_assoc` WHERE `id_assoc`=".$topping['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
					}
				}
				
				$q = "DELETE FROM `#__cleverdine_takeaway_res_prod_assoc` WHERE `id_res`=$id;";
				$dbo->setQuery($q);
				$dbo->execute();

				$q = "DELETE FROM `#__cleverdine_order_status` WHERE `id_order`=$id AND `group`=2;";
				$dbo->setQuery($q);
				$dbo->execute();
				
			}
		}
	
		$this->cancelTkreservation();
	}
	
	public function deleteLangMenus() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_menus` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "SELECT `id` FROM `#__cleverdine_lang_menus_section` WHERE `id_parent`=".intval($id).";";
				$dbo->setQuery($q);
				$dbo->execute();
				if( $dbo->getNumRows() > 0 ) {
					foreach( $dbo->loadAssocList() as $section ) {
							
						$q = "DELETE FROM `#__cleverdine_lang_menus_section` WHERE `id`=".$section['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->execute();
						
						$q = "SELECT `id` FROM `#__cleverdine_lang_section_product` WHERE `id_parent`=".$section['id'].";";
						$dbo->setQuery($q);
						$dbo->execute();
						if( $dbo->getNumRows() > 0 ) {
							foreach( $dbo->loadAssocList() as $product ) {
									
								$q = "DELETE FROM `#__cleverdine_lang_section_product` WHERE `id`=".$product['id']." LIMIT 1;";
								$dbo->setQuery($q);
								$dbo->execute();
											
								$q = "DELETE FROM `#__cleverdine_lang_section_product_option` WHERE `id_parent`=".$product['id'].";";
								$dbo->setQuery($q);
								$dbo->execute();
								
							}    
						}
						
					}    
				}
				
			}
		}
	
		$this->cancelLangMenu();
	}

	public function deleteLangTkproducts() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_menus_entry` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_menus_entry_option` WHERE `id_parent`=".intval($id).";";
				$dbo->setQuery($q);
				$dbo->execute();
				
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_menus_entry_topping_group` WHERE `id_parent`=".intval($id).";";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangTkproduct();
	}
	
	public function deleteLangTkmenus() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_menus` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangTkmenu();
	}

	public function deleteLangTktoppings() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_topping` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangTktopping();
	}
	
	public function deleteLangTkattributes() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_menus_attribute` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangTkattribute();
	}
	
	public function deleteLangTkdeals() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_takeaway_deal` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangTkdeal();
	}

	public function deleteLangPayments() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_payments` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangPayment();
	}

	public function deleteLangCustomf() {
	
		$ids = JFactory::getApplication()->input->get('cid', array(), 'uint');
	
		if( count( $ids ) ) {
			$dbo = JFactory::getDbo();
			foreach( $ids as $id ) {
				$q = "DELETE FROM `#__cleverdine_lang_customf` WHERE `id`=".intval($id)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
		}
	
		$this->cancelLangCustomf();
	}
	
	// CANCEL
	
	public function dashboard() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine");
	}
	
	public function cancelTable() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tables");
	}
	
	public function cancelRoom() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=rooms");
	}
	
	public function cancelRoomClosure() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=roomclosures");
	}
	
	public function cancelMap() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=maps");
	}
	
	public function cancelOperator() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=operators");
	}
	
	public function cancelReservation() {
		$mainframe = JFactory::getApplication();
		
		$from = $mainframe->input->get('from');
		if( empty($from) ) {
			$from = 'reservations';
		}
		$url = "index.php?option=com_cleverdine&task=$from";
		
		$mainframe->redirect($url);
	}
	
	public function cancelCustomer() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=customers");
	}
	
	public function cancelShift() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=shifts");
	}
	
	public function cancelMenusProduct() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=menusproducts");
	}
	
	public function cancelMenu() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=menus");
	}
	
	public function cancelSpecialDay() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=specialdays");
	}
	
	public function cancelPayment() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=payments");
	}

	public function cancelReview() {
		$mainframe = JFactory::getApplication();

		$filters = array();
		$filters['key'] 	= $mainframe->input->get('key', '', 'string');
		$filters['stars'] 	= $mainframe->input->get('stars', '', 'string');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}

		$mainframe->redirect("index.php?option=com_cleverdine&task=revs$qs");
	}
	
	public function cancelCoupon() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=coupons");
	}
	
	public function cancelCustomf() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=customf");
	}
	
	public function cancelResCode() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=rescodes");
	}

	public function cancelResCodeOrder() {
		$mainframe = JFactory::getApplication();

		$filters = $mainframe->input->get('filters', array(), 'array');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=rescodesorder".$qs);		
	}

	public function cancelMedia() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=media");
	}

	public function cancelInvoice() {
		$mainframe = JFactory::getApplication();

		$filters = array();
		$filters['year'] 		= $mainframe->input->get('year', '', 'string');
		$filters['month'] 		= $mainframe->input->get('month', '', 'string');
		$filters['keysearch'] 	= $mainframe->input->get('keysearch', '', 'string');
		$filters['group'] 		= $mainframe->input->get('group', '', 'string');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}

		$mainframe->redirect("index.php?option=com_cleverdine&task=invoices$qs");
	}

	public function cancelConfig() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=editconfig");
	}

	public function cancelApiuser() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=apiusers");
	}

	public function cancelApiplugin() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=apiplugins");
	}

	public function cancelApilog() {
		$mainframe = JFactory::getApplication();

		$filters = array();
		$filters['id_login'] 	= $mainframe->input->get('id_login', '', 'int');
		$filters['keysearch'] 	= $mainframe->input->get('keysearch', '', 'string');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=apilogs$qs");
	}

	public function cancelApiban() {
		$mainframe = JFactory::getApplication();

		$filters = array();
		$filters['type'] 		= $mainframe->input->get('type', '', 'string');
		$filters['keysearch'] 	= $mainframe->input->get('keysearch', '', 'string');

		$qs = "";
		foreach( $filters as $k => $v ) {
			if( strlen($v) ) {
				$qs .= "&$k=$v";
			}
		}

		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=apibans$qs");
	}
	
	public function cancelTkmenu() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tkmenus");
	}
	
	public function cancelTkmenuattribute() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tkmenuattr");
	}
	
	public function cancelTkproduct() {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=com_cleverdine&task=tkproducts&id_menu=".$mainframe->input->get('id_menu', 0, 'uint'));
	}
	
	public function cancelTktopping() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tktoppings");
	}
	
	public function cancelTktoppingSeparator() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tktopseparators");
	}
	
	public function cancelTkdeal() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tkdeals");
	}

	public function cancelTkarea() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tkareas");
	}

	public function cancelTkreservation() {
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=tkreservations");
	}
	
	public function cancelTkrescart() {
		$mainframe = JFactory::getApplication();

		$return_url = "index.php?option=com_cleverdine&task=edittkreservation";
		$id = $mainframe->input->get('id', 0, 'uint');
		if( !empty($id) ) {
			$return_url .= "&cid[]=$id";
		}

		$mainframe->redirect($return_url);
	}
	
	public function cancelLangMenu() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langmenus&id=".$mainframe->input->get('id_menu', 0, 'uint'));
	}
	
	public function cancelLangTkmenu() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langtkmenus&id=".$mainframe->input->get('id_menu', 0, 'uint'));
	}
	
	public function cancelLangTkproduct() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langtkproducts&id=".$mainframe->input->get('id_product', 0, 'uint')."&id_menu=".$mainframe->input->get('id_menu', 0, 'uint'));
	}
	
	public function cancelLangTktopping() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langtktoppings&id=".$mainframe->input->get('id_topping', 0, 'uint'));
	}
	
	public function cancelLangTkattribute() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langtkattributes&id=".$mainframe->input->get('id_attribute', 0, 'uint'));
	}
	
	public function cancelLangTkdeal() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langtkdeals&id=".$mainframe->input->get('id_deal', 0, 'uint'));
	}

	public function cancelLangPayment() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langpayments&id=".$mainframe->input->get('id_payment', 0, 'uint'));
	}

	public function cancelLangCustomf() {
		$mainframe = JFactory::getApplication();
		JFactory::getApplication()->redirect("index.php?option=com_cleverdine&task=langcustomf&id=".$mainframe->input->get('id_customf', 0, 'uint'));
	}
	
	////// SMS //////
	
	public function sendsms() {
		$this->sendsmsaction(0);
	} 
	
	public function tksendsms() {
		$this->sendsmsaction(1);
	}
	
	private function sendsmsaction($action=0) {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		$sms_api_name = cleverdine::getSmsApi(true);
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$sms_api_name;

		if( file_exists( $sms_api_path ) && strlen($sms_api_name) > 0 ) {
			require_once($sms_api_path);

			$sms_api_params = cleverdine::getSmsApiFields(true);

			$sms_api = new VikSmsApi( array(), $sms_api_params );

			$ids = $input->get('cid', array(), 'uint');

			$error = false;

			if( count( $ids ) > 0 ) {
				
				for( $i = 0; $i < count($ids) && !$error; $i++ ) {
					$id = $ids[$i];
					$q = "SELECT `checkin_ts`, `people`, `purchaser_phone` FROM `#__cleverdine_reservation` WHERE `id`=".$id." LIMIT 1;";
					if( $action == 1 ) {
						$q = "SELECT `checkin_ts`, `purchaser_phone` FROM `#__cleverdine_takeaway_reservation` WHERE `id`=".$id." LIMIT 1;";
					}

					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$row = $dbo->loadAssoc();
						
						$response_obj = NULL;
						
						$_str = cleverdine::getSmsCustomerTextMessage($row, $action, true);
						$response_obj = $sms_api->sendMessage($row['purchaser_phone'], $_str);
						
						if( !$sms_api->validateResponse($response_obj) ) {
							$error = true;
							$mainframe->enqueueMessage(JText::_('VRSMSMESSAGESENT0'), 'error');
						}
					}
				}
				
			} else {
				$error = true; // don't show ok message
			}

		} else {
			$error = true; // don't show ok message
			$mainframe->enqueueMessage(JText::_('VRSMSESTIMATEERR1'), 'error');
		}
		
		$return_task = 'reservations';
		if( $action == 1 ) {
			$return_task = 'tkreservations';
		}
		
		if( !$error ) {
			$mainframe->enqueueMessage(JText::_('VRSMSMESSAGESENT1'));
		}
		
		$mainframe->redirect('index.php?option=com_cleverdine&task='.$return_task);
		
	}

	public function sendcustsms() {
		
		$mainframe = JFactory::getApplication();
		$input = $mainframe->input;
		$dbo = JFactory::getDbo();

		$sms_api_name = cleverdine::getSmsApi(true);
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$sms_api_name;
		
		$return_url = 'index.php?option=com_cleverdine&task=customers';

		if( file_exists( $sms_api_path ) && strlen($sms_api_name) > 0 ) {
			require_once($sms_api_path);

			$sms_api_params = cleverdine::getSmsApiFields(true);

			$sms_api = new VikSmsApi( array(), $sms_api_params );

			$id_cust = $input->get('id_cust', 0, 'uint');

			$q = "SELECT `c`.`phone_prefix`, `u`.`billing_phone` 
			FROM `#__cleverdine_users` AS `u`
			LEFT JOIN `#__cleverdine_countries` AS `c` ON `u`.`country_code`=`c`.`country_2_code`
			WHERE `u`.`id`=$id_cust LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() == 0 ) {
				$mainframe->enqueueMessage(JText::_('VRCUSTSMSSENT0'), 'error');
				$mainframe->redirect($return_url);
				exit;
			}
			
			$row = $dbo->loadAssoc();
			
			$response_obj = NULL;
			
			$_str = $input->get('msg', '', 'string');
			$response_obj = $sms_api->sendMessage($row['phone_prefix'].$row['billing_phone'], $_str);
			
			if( !$sms_api->validateResponse($response_obj) ) {
				$mainframe->enqueueMessage(JText::_('VRCUSTSMSSENT0'), 'error');
			} else {
				$mainframe->enqueueMessage(JText::_('VRCUSTSMSSENT1'));
			}
			
			$keep = $input->get('keepdef', 0, 'uint');
			if( $keep ) {
				$q = "UPDATE `#__cleverdine_config` SET `setting`=".$dbo->quote($_str)." WHERE `param`='smstextcust' LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
			}
			
		} else {
			$mainframe->enqueueMessage(JText::_('VRSMSESTIMATEERR1'), 'error');
		}
		
		$mainframe->redirect($return_url);
		
	}

	public function search_users() {
		
		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();
		
		$search = $input->getString('term');
		$id 	= $input->getUint('id');
		
		if( $id > 0 ) {
			$q = "SELECT `id`, `billing_name` FROM `#__cleverdine_users` WHERE `id`=$id LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() > 0 ) {
				echo json_encode($dbo->loadAssoc());
			} else {
				echo "[]";
			}
			
			exit;
		}
		
		$q = "SELECT `c`.`id`, `c`.`billing_name`, `c`.`billing_mail`, `c`.`billing_phone`, `c`.`country_code`, `c`.`fields`, `c`.`tkfields`,
		`d`.`state`, `d`.`city`, `d`.`address`, `d`.`zip` 
		FROM `#__cleverdine_users` AS `c`
		LEFT JOIN `#__cleverdine_user_delivery` AS `d` ON `c`.`id`=`d`.`id_user` 
		WHERE `c`.`billing_name` LIKE ".$dbo->quote("%$search%")." ORDER BY `c`.`billing_name` ASC, `d`.`ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$users = array();
			$last_id = -1;

			foreach( $dbo->loadAssocList() as $u ) {
				if( $u['id'] != $last_id ) {
					array_push($users, array(
						'id' => $u['id'],
						'billing_name' => $u['billing_name'],
						'billing_mail' => $u['billing_mail'],
						'billing_phone' => $u['billing_phone'],
						'country_code' => $u['country_code'],
						'fields' => strlen($u['fields']) ? json_decode($u['fields']) : array(),
						'tkfields' => strlen($u['tkfields']) ? json_decode($u['tkfields']) : array(),
						'delivery' => array()
					));

					$last_id = $u['id'];
				}

				if( !empty($u['address']) && !empty($u['zip']) ) {
					array_push($users[count($users)-1]['delivery'], cleverdine::deliveryAddressToStr($u));
				}
			}

			echo json_encode($users);
		} else {
			echo "[]";
		}
		
		exit;
	}

	public function search_jusers() {
		
		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();
		
		$search = $input->getString('term');
		$id 	= $input->getUint('id');
		
		$q = "SELECT `u`.`id`, `u`.`name`, `u`.`email`, (
			`id`<>$id AND EXISTS (
				SELECT 1 FROM `#__cleverdine_users` AS `a` WHERE `a`.`jid`=`u`.`id`
			)
		) AS `disabled` 
		FROM `#__users` AS `u` 
		WHERE `name` LIKE ".$dbo->quote("%$search%")." ORDER BY `u`.`name`;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			echo json_encode($dbo->loadAssocList());
		} else {
			echo "[]";
		}
		
		exit;
	}

	public function tkstocks_get_tree_request() {

		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();

		$eid = $input->getUint('id_product');
		$oid = $input->getUint('id_option');

		$start_ts = cleverdine::createTimestamp($input->get('start', '', 'string'), 0, 0, true);
		$end_ts = cleverdine::createTimestamp($input->get('end', '', 'string'), 23, 59, true);

		$q = "SELECT `e`.`id` AS `eid`, `e`.`name` AS `ename`, `o`.`id` AS `oid`, `o`.`name` AS `oname`,
			DATE_FORMAT( FROM_UNIXTIME(`r`.`checkin_ts`),'%w') AS `weekday`,
			DATE_FORMAT( FROM_UNIXTIME(`r`.`checkin_ts`),'%c') AS `month`,
			DATE_FORMAT( FROM_UNIXTIME(`r`.`checkin_ts`),'%Y') AS `year`,
			SUM(`i`.`quantity`) AS `products_used`
			FROM `#__cleverdine_takeaway_reservation` AS `r`
			LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `r`.`id`=`i`.`id_res` 
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `i`.`id_product`=`e`.`id`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `i`.`id_product_option` = `o`.`id` 
			WHERE `i`.`id_product`=$eid AND ($oid=0 OR `i`.`id_product_option`=$oid) AND (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `r`.`checkin_ts` BETWEEN $start_ts AND $end_ts
			GROUP BY `weekday`, `month`, `year`
			ORDER BY `year` ASC, `month` ASC, `weekday` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			echo json_encode(array(0, 'no items to fetch'));
			exit;
		}

		$rows = $dbo->loadAssocList();

		$tree = array(
			'eid' => $rows[0]['eid'],
			'oid' => $rows[0]['oid'],
			'ename' => $rows[0]['ename'],
			'oname' => $rows[0]['oname'],
			'years' => array(),
			'months' => array(),
			'weekdays' => array(),
			'children' => array(),
		);

		$last_year = $last_month = -1;
		$year_node = null;
		$month_node = null;

		foreach( $rows as $r ) {
			if( $r['year'] != $last_year ) {
				// update node
				$tree['children'][$r['year']] = array("used" => 0, "children" => array());
				$year_node = &$tree['children'][$r['year']];

				$last_year = $r['year'];
			}

			if( $r['month'] != $last_month ) {
				// update node
				$year_node['children'][$r['month']] = array("used" => 0, "children" => array());
				$month_node = &$year_node['children'][$r['month']];

				$last_month = $r['month'];
			}

			$month_node['children'][$r['weekday']] = $r['products_used'];
			
			$year_node['used'] += $r['products_used'];

			$month_node['used'] += $r['products_used'];

			// update root total
			if( empty($tree['years'][$r['year']]) ) {
				$tree['years'][$r['year']] = 0;
			}
			$tree['years'][$r['year']] += $r['products_used'];

			if( empty($tree['months'][$r['month']]) ) {
				$tree['months'][$r['month']] = 0;
			}
			$tree['months'][$r['month']] += $r['products_used'];

			if( empty($tree['weekdays'][$r['weekday']]) ) {
				$tree['weekdays'][$r['weekday']] = 0;
			}
			$tree['weekdays'][$r['weekday']] += $r['products_used'];
		}
		
		echo json_encode(array(1, $tree));
		exit;
	}
	
	////// EXPORT //////
	
	public function exportReservations() {

		$input = JFactory::getApplication()->input;
		
		$filename 		= $input->getString('filename');
		$export_class 	= $input->getString('export_type');
		$dstart 		= $input->getString('date_start');
		$dend 			= $input->getString('date_end');
		$ids 			= $input->getUint('ids', array());
		
		$type = $input->getUint('type');
		
		if( strlen($filename) == 0 ) {
			$filename = "name";
		}
		
		$file_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'export'.DIRECTORY_SEPARATOR.$export_class.'.php';
		
		if( file_exists($file_path) ) {
			require_once($file_path);
			
			$vik_exp = new VikExporter( cleverdine::createTimestamp($dstart,0,0), cleverdine::createTimestamp($dend, 23, 59), $ids );
			$str = $vik_exp->getString($type);
			$vik_exp->export($str, $filename.'.'.$export_class);
		} 
	}

	////// INVOICES //////

	public function loadinvoices() {
		
		$vik = new VikApplication(VersionListener::getID());
		
		$input = JFactory::getApplication()->input;
		$dbo = JFactory::getDbo();
		
		$year 	= $input->getInt('year');
		$month 	= $input->getInt('month');
		$lim0 	= $input->getUint('start_limit');
		$lim 	= $input->getUint('limit');
		
		$filters = array();
		$filters['group'] 		= $input->getString('group');
		$filters['keysearch'] 	= $input->getString('keysearch');

		// get invoices
		$invoices	= array();
		$not_all 	= false;
		$max_lim	= 0;

		$start_ts = mktime(0, 0, 0, $month, 1, $year);
		$end_ts = mktime(0, 0, 0, $month+1, 1, $year)-1;

		$q = "SELECT SQL_CALC_FOUND_ROWS * 
		FROM `#__cleverdine_invoice` 
		WHERE `inv_date` BETWEEN $start_ts AND $end_ts 
		".(strlen($filters['group']) ? " AND `group`=".intval($filters['group']) : "")."
		".(!empty($filters['keysearch']) ? " AND (`file` LIKE ".$dbo->quote("%".$filters['keysearch']."%")." OR `inv_number` LIKE ".$dbo->quote("%".$filters['keysearch']."%").")" : "")." 
		ORDER BY `inv_date` ASC, `id_order` ASC";

		$dbo->setQuery($q, $lim0, $lim);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$invoices = $dbo->loadAssocList();

			$not_all = true;

			$dbo->setQuery('SELECT FOUND_ROWS();');
			if( ($max_lim = $dbo->loadResult()) <= $lim0 + count($invoices) ) {
				$not_all = false;
			}
		}
		
		$invoices_html = array();
		
		$cont = $lim0;
		$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);

		$now = time();

		foreach( $invoices as $inv ) {
			$cont++; 
			
			$html = '
				<div class="vr-archive-fileblock">
					<div class="vr-archive-fileicon">
						<img src="'.JUri::root().'administrator/components/com_cleverdine/assets/images/invoice@big.png'.'"/>
					</div>
					<div class="vr-archive-filename">
						<a href="'.JUri::root().'components/com_cleverdine/helpers/library/pdf/archive/'.$inv['file'].'?t='.$now.'" target="_blank">
							'.substr($inv['file'], 0, strrpos($inv['file'], '.')).'<br />'.$inv['inv_number'].'
						</a>
					</div>
					<input type="hidden" name="cid[]" value="0::'.$inv['id'].'" class="cid"/>
				</div>
			';
			
			array_push($invoices_html, $html);
		}
		
		if( count($invoices_html) == 0 ) {
			array_push($invoices_html, '<p>'.JText::_('VRNOINVOICESONARCHIVE').'</p>');
		}
		
		echo json_encode(array(1, $cont, $not_all, $invoices_html, $max_lim));
		exit; 
		
	}

	public function downloadInvoices() {
		$mainframe = JFactory::getApplication();
		$dbo = JFactory::getDbo();

		$ids = $mainframe->input->get('cid', array(), 'string');
		
		$pool = array();

		$basepath = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR;
		
		if( count( $ids ) ) {
			foreach( $ids as $id ) {
				$invoice = explode('::', $id);
				if( $invoice[0] == 1 ) {
					
					$q = "SELECT * FROM `#__cleverdine_invoice` WHERE `id`=".intval($invoice[1])." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						array_push($pool, $dbo->loadAssoc());
					}

				}
			}
		}
		
		if( count($pool) == 0 ) {
			$this->cancelInvoice();
			exit;
		} else if( count($pool) == 1 ) {
			header("Content-disposition: attachment; filename=".$pool[0]['file']);
			header("Content-type: application/pdf");
			readfile($basepath.$pool[0]['file']);
			exit;
		} else {
			if( !class_exists('ZipArchive') ) {
				$mainframe->enqueueMessage('The ZipArchive class is not installed on your server.', 'error');
				$this->cancelInvoice();
				exit;
			}
			
			$zipname = $basepath.'invoices'.time().'.zip';
			$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
			foreach( $pool as $file ) {
			  $zip->addFile($basepath.$file['file'], $file['file']);
			}
			$zip->close();
			
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename=invoices.zip');
			header('Content-Length: '.filesize($zipname));
			readfile($zipname);
			unlink($zipname);
			exit;
		}
	}

	////// VERSION //////

	private function getSoftwareParams() {
		$config = UIFactory::getConfig();

		$params = new stdClass;
		$params->version 	= $config->get('version');
		$params->alias 		= CLEVERAPP;

		return $params;
	}

	public function check_version_listener() {

		$params = $this->getSoftwareParams();

		JPluginHelper::importPlugin('e4j');
		$dispatcher = JEventDispatcher::getInstance();

		$result = $dispatcher->trigger('checkVersion', array(&$params));

		if( !count($result) ) {
			$result = new stdClass;
			$result->status = 0;
		} else {
			$result = $result[0];
		}

		echo json_encode($result);
		exit;
	}

	public function launch_update() {

		$params = $this->getSoftwareParams();

		JPluginHelper::importPlugin('e4j');
		$dispatcher = JEventDispatcher::getInstance();

		$json = new stdClass;
		$json->status = false;

		try {

			$result = $dispatcher->trigger('doUpdate', array(&$params));

			if( count($result) ) {
				$json->status = (bool) $result[0];
			} else {
				$json->error = 'plugin disabled.';
			}

		} catch(Exception $e) {

			$json->status = false;
			$json->error = $e->getMessage();

		}

		echo json_encode($json);
		exit;
	}

}
?>