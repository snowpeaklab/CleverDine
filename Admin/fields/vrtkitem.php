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

class JFormFieldVrtkitem extends JFormField { 
	protected $type = 'vrtkitem';
	
	function getInput() {
		//$key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
		//$val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);

		$tkmenus = array();
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`, `e`.`id` AS `id`, `e`.`name` AS `name` 
		FROM `#__cleverdine_takeaway_menus_entry` AS `e` 
		LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`e`.`id_takeaway_menu`
		ORDER BY `m`.`ordering` ASC, `e`.`ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			
			$last_id = -1;
			foreach( $dbo->loadAssocList() as $r ) {
				if( $last_id != $r['id_menu'] ) {
					$tkmenus[] = array(
						'id' => $r['id_menu'],
						'title' => $r['menu_title'],
						'items' => array()
					);
					$last_id = $r['id_menu'];
				}

				if( !empty($r['id']) ) {
					$tkmenus[count($tkmenus)-1]['items'][] = array(
						'id' => $r['id'],
						'name' => $r['name']
					);
				}
			}
		}

		$html = '<select class="inputbox" name="'.$this->name.'">';
		foreach( $tkmenus as $menu ) {
			$html .= '<optgroup label="'.$menu['title'].'">';
			foreach( $menu['items'] as $item ) {
				$html .= '<option value="'.$item['id'].'"'.($this->value == $item['id'] ? " selected=\"selected\"" : "").'>'.$item['name'].'</option>';
			}
			$html .= '</optgroup>';
		}
		$html .='</select>';

		return $html;
	}
}

?>