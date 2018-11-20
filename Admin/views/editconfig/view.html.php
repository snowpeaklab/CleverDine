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
class cleverdineVieweditconfig extends JViewUI {
	
	/**
	 * Restaurants view display method
	 * @return void
	 */
	function display($tpl = null) {

		RestaurantsHelper::load_css_js();
		RestaurantsHelper::load_complex_select();
		RestaurantsHelper::load_font_awesome();
		cleverdine::load_fancybox();
		
		// Set the toolbar
		$this->addToolBar();
		
		$dbo = JFactory::getDbo();
		
		$config_params = null;
		$params = array();
		
		$q = "SELECT * FROM `#__cleverdine_config`;";
		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {
			$config_params = $dbo->loadAssocList();

			foreach ($config_params as $row) {
				$params[$row['param']] = $row['setting'];
			}
		}

		$countries = array();
		$q = "SELECT * FROM `#__cleverdine_countries` WHERE `published`=1 ORDER BY `phone_prefix` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {
			$countries = $dbo->loadAssocList();
		}

		$def_country = '';
		$q = "SELECT `choose` FROM `#__cleverdine_custfields` WHERE `rule`=3 AND `choose`<>'' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if ($dbo->getNumRows() > 0) {
			$def_country = $dbo->loadResult();
		}

		// params
		
		$this->params 			= &$params;
		$this->countries 		= &$countries;
		$this->defaultCountry 	= &$def_country;

		// vik application

		$vik = new VikApplication(VersionListener::getID());

		$this->vikApplication = &$vik;

		// media manager

		$image_path = JUri::root().'components/com_cleverdine/assets/media/';

		$media_manager = new MediaManagerHTML(RestaurantsHelper::getAllMedia(), $image_path, $vik, 'vre');

		$this->mediaManager = &$media_manager;

		// Display the template
		parent::display($tpl);

	}

	/**
	 * Setting the toolbar
	 */
	private function addToolBar() {
		//Add menu title and some buttons to the page
		JToolbarHelper::title(JText::_('VRMAINTITLECONFIG'), 'generic.png');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_cleverdine')) {
			JToolbarHelper::apply('saveConfiguration', JText::_('VRSAVE'));
			JToolbarHelper::divider();
			
			JToolbarHelper::custom('truncateSession', 'trash', 'trash', JText::_('VRRENEWSESSION'), false, false);
			JToolbarHelper::divider();
		}
	
		JToolbarHelper::cancel('dashboard', JText::_('VRCANCEL'));
	}

}
?>