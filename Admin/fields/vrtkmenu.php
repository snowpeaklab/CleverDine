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

class JFormFieldVrtkmenu extends JFormField { 
	protected $type = 'vrtkmenu';
	
	function getInput() {
		//$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		//$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		
		$tkm_opt = "";
		$categories="";
		
		$dbo = JFactory::getDbo();
		
		$q="SELECT `t`.`id` AS `id`, `t`.`title` AS `title` FROM `#__cleverdine_takeaway_menus` AS `t` ORDER BY `t`.`title` ASC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$tkmenus=$dbo->loadAssocList();
			
			foreach( $tkmenus as $tkmenu ) {
				$tkm_opt .= '<option value="'.$tkmenu['id'].'"'.($this->value == $tkmenu['id'] ? " selected=\"selected\"" : "").'>'.$tkmenu['title'].'</option>';
			}
		}
		$html = '<select class="inputbox" name="' . $this->name . '">';
		$html .= '<option value=""></option>';
		$html .= $tkm_opt;
		$html .='</select>';
		return $html;
	}
}

?>