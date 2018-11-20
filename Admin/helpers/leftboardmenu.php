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

require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'menu.php';

// LEFT BOARD MENU SHAPE

class LeftBoardMenu extends MenuShape {

	private $compressed = false;

	public function compress($compressed) {
		$this->compressed = $compressed;
	}

	protected function buildHtml($html) {
		return '<div class="vre-leftboard-menu '.($this->compressed ? 'compressed' : '').'">'.$html.'</div>';
	}

}

// LEFT BOARD MENU SEPARATOR SHAPE

class LeftBoardMenuSeparator extends SeparatorItemShape {

	protected function buildHtml($html) {
		$is_collapsed = $this->isCollapsed();

		$wrap = '<div class="parent">
			<div class="title '.($this->isSelected() ||  $is_collapsed ? 'selected' : '').' '.($is_collapsed ? 'collapsed' : '').'">';

		$angle = "";
		if( !strlen($this->getHref()) ) {
			$angle = '<i class="fa fa-angle-'.($is_collapsed ? 'up' : 'down').' vre-angle-dir"></i>';
		}

		$title = (strlen($this->getCustom()) ? '<i class="fa fa-'.$this->getCustom().'"></i>' : '').'<span>'.$this->getTitle().'</span>'.$angle;
        
        if( strlen($this->getHref()) ) {
            $wrap .= '<a href="'.$this->getHref().'">'.$title.'</a>';
        } else {
        	$wrap .= $title;
        }

        $wrap .= '</div>';
        
        if( strlen($html) ) {
	        $wrap .= '<div class="wrapper '.($is_collapsed || (strlen($this->getHref()) && $this->isSelected()) ? 'collapsed' : '').'">
	                '.$html.'
	            </div>';
    	}

    	$wrap .= '</div>';

        return $wrap;
	}

}

// LEFT BOARD MENU ITEM SHAPE

class LeftBoardMenuItem extends MenuItemShape {

	public function buildHtml() {
		return '<div class="item '.($this->isSelected() ? 'selected' : '').'">
			<a href="'.$this->getHref().'">'.(strlen($this->getCustom()) ? '<i class="fa fa-'.$this->getCustom().'"></i>' : '').'<span>'.$this->getTitle().'</span></a>
		</div>';
	}

}

// CUSTOM SHAPES

class LeftBoardMenuLine extends CustomShape {

	public function buildHtml() {
		return '<div class="separator-line custom"></div>';
	}

}

class LeftBoardMenuSplit extends CustomShape {

	public function buildHtml() {
		return '<div class="split-box custom">
			<a href="javascript: void(0);" onClick="leftBoardMenuToggle();">
				<i class="fa fa-exchange"></i>
			</a>
		</div>';
	}

}

class LeftBoardMenuVersion extends CustomShape {

	public function buildHtml() {

		JPluginHelper::importPlugin('e4j');

		$dispatcher = JEventDispatcher::getInstance();
		$callable 	= $dispatcher->trigger('isCallable');

		$html = '';

		$to_update = 0;

		if( count($callable) && $callable[0] ) {
			// PLUGIN ENABLED

			$document = JFactory::getDocument();
			$document->addScriptDeclaration('function callVersionChecker() {
				jQuery.noConflict();

				setVersionContent(\''.addslashes(JText::_('VRCHECKINGVERSION')).'\');

				var jqxhr = jQuery.ajax({
					type: "POST",
					url: "index.php?option=com_cleverdine&task=check_version_listener&tmpl=component",
					data: {}
				}).done(function(resp){
					var obj = jQuery.parseJSON(resp);

					console.log(obj);

					if( obj["status"] == 1 ) {

						if( obj.response.status == 1 ) {

							if( obj.response.compare == 1 ) {
								jQuery("#vr-versioncheck-link").attr("onclick", "");
								jQuery("#vr-versioncheck-link").attr("href", "index.php?option=com_cleverdine&task=updateprogram");

								obj.response.shortTitle += \'<i class="upd-avail fa fa-exclamation-triangle"></i>\';

								jQuery(".version-box.custom").addClass("upd-avail");
							}

							setVersionContent(obj.response.shortTitle, obj.response.title);

						} else {
							console.log(obj.response.error);
							setVersionContent(\''.addslashes(JText::_('VRERROR')).'\');
						}

					} else {
						console.log("plugin disabled");
						setVersionContent(\''.addslashes(JText::_('VRERROR')).'\');
					}

				}).fail(function(resp){
					console.log(resp);
					setVersionContent(\''.addslashes(JText::_('VRERROR')).'\');
				});
			}

			function setVersionContent(cont, title) {
				jQuery("#vr-version-content").html(cont);

				if( title === undefined ) {
					var title = "";
				}

				jQuery("#vr-version-content").attr("title", title);
			}');

			$config = UIFactory::getConfig();

			$params = new stdClass;
			$params->version 	= $config->get('version');
			$params->alias 		= CLEVERAPP; 

			$result = $dispatcher->trigger('getVersionContents', array(&$params));

			$menu_label = '';
			$menu_label_title = '';

			if( count($result) && $result[0] ) { 

				if( $result[0]->status ) {

					if( $result[0]->response->status ) {
						$menu_label = $result[0]->response->shortTitle;

						$menu_label_title = $result[0]->response->title;

						if( $result[0]->response->compare == 1 ) {
							$to_update = 1;
						}

					} else {
						$menu_label = JText::_('VRERROR');

						$menu_label_title = $result[0]->response->error;
					}

				} else {
					$menu_label = JText::_('VRERROR');
				}

			} else {

				$document = JFactory::getDocument();
				$document->addScriptDeclaration('jQuery(document).ready(function(){
					callVersionChecker();
				});');

			}

			$html = '<a href="'.($to_update ? 'index.php?option=com_cleverdine&task=updateprogram' : 'javascript: void(0);').'" onclick="'.($to_update ? '' : 'callVersionChecker();').'" id="vr-versioncheck-link">
				<i class="fa fa-joomla"></i>
				<span id="vr-version-content" title="'.$menu_label_title.'">'.
					$menu_label.					
					($to_update ? '<i class="upd-avail fa fa-exclamation-triangle"></i>' : '').
				'</span>
			</a>';

		} else {
			// PLUGIN DISABLED

			$document = JFactory::getDocument();
			$document->addScriptDeclaration('jQuery(document).ready(function(){
				jQuery("#vcheck").hover(function(){
					jQuery(this).attr("href", "");
				}, function(){

				});
			})');

			$html = '<a id="vcheck" href="" class="modal" rel="{handler: \'iframe\'}" target="_blank" onclick="this.href=\''.$this->get('url').'\'">
					<i class="fa fa-joomla"></i>
					<span>'.$this->get('label').'</span>
				</a>';

		}

		return '<div class="version-box custom'.($to_update ? ' upd-avail' : '').'">'.$html.'</div>';
	}

}

?>