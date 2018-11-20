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
class cleverdineViewmanagebill extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {
		
		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		
		// Set the toolbar
		$this->addToolBar();
		
		$input 	= JFactory::getApplication()->input;
		$dbo 	= JFactory::getDbo();
		
		$ids 	= $input->get('cid', array(0), 'uint');
		$id 	= intval($ids[0]);

		$row = array();
		
		$q = "SELECT `r`.`id`, `r`.`bill_closed`, `r`.`bill_value`, `r`.`coupon_str`, `r`.`discount_val`,
		`p`.`id` AS `id_assoc`, `p`.`id_product`, `p`.`name` AS `prod_name`, `p`.`quantity` AS `prod_quantity`, `p`.`price` AS `prod_price`
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_res_prod_assoc` AS `p` ON `r`.`id`=`p`.`id_reservation`
		WHERE `r`.`id`=$id ORDER BY `p`.`id`;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			JFactory::getApplication()->redirect('index.php?option=com_cleverdine&task=reservations');
			exit;
		}

		$app = $dbo->loadAssocList();

		$row = array(
			'id' 			=> $app[0]['id'],
			'bill_closed' 	=> $app[0]['bill_closed'],
			'bill_value' 	=> $app[0]['bill_value'],
			'coupon_str'	=> $app[0]['coupon_str'],
			'discount_val'	=> $app[0]['discount_val'],
			'products' 		=> array()
		);

		foreach( $app as $r ) {
			if( $r['id_product'] > 0 ) {
				$row['products'][] = array(
					'id' 		=> $r['id_product'],
					'id_assoc' 	=> $r['id_assoc'],
					'name' 		=> $r['prod_name'],
					'quantity' 	=> $r['prod_quantity'],
					'price' 	=> $r['prod_price']
				);
			}
		}

		// get products

		$products = array(
			'published' => array(),
			'unpublished' => array(),
			'hidden' => array()
		);

		$q = "SELECT `id`, `name`, `published`, `hidden` FROM `#__cleverdine_section_product` 
		ORDER BY `hidden` ASC, `published` DESC, `ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() ) {
			foreach( $dbo->loadAssocList() as $p ) {

				if( $p['hidden'] == 1 ) {
					$products['hidden'][] = $p;
				} else if( $p['published'] == 1 ) {
					$products['published'][] = $p;
				} else {
					$products['unpublished'][] = $p;
				}

			}
		}

		// get coupons

		$coupons = array();

		$q = "SELECT * FROM `#__cleverdine_coupons` WHERE `group`=0;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$coupons = $dbo->loadAssocList();
		}
		
		$this->row 		= &$row;
		$this->products = &$products;
		$this->coupons 	= &$coupons;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
	
		JToolbarHelper::title(JText::_('VRMAINTITLEEDITBILL'), 'restaurants');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveBill', JText::_('VRSAVE'));
			JToolbarHelper::save('saveAndCloseBill', JText::_('VRSAVEANDCLOSE'));
			JToolbarHelper::divider();
		}
		
		JToolbarHelper::cancel('cancelReservation', JText::_('VRCANCEL'));
	}

}
?>