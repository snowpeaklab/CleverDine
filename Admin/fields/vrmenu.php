<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

defined('_JEXEC') OR die('Restricted Area');

jimport('joomla.form.formfield');

class JFormFieldVrmenu extends JFormField { 
	protected $type = 'vrmenu';
	
	function getInput() {
		//$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		//$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		
		$m_opt = "";
		$categories="";
		
		$dbo = JFactory::getDbo();
		
		$q="SELECT `m`.`id` AS `id`, `m`.`name` AS `name` FROM `#__cleverdine_menus` AS `m` ORDER BY `m`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus=$dbo->loadAssocList();
			
			foreach( $menus as $m ) {
				$m_opt .= '<option value="'.$m['id'].'"'.($this->value == $m['id'] ? " selected=\"selected\"" : "").'>'.$m['name'].'</option>';
			}
		}
		$html = '<select class="inputbox" name="' . $this->name . '">';
		$html .= $m_opt;
		$html .='</select>';
		return $html;
	}
}

?>