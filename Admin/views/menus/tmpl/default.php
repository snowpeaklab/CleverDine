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

$rows = $this->rows;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$filters = $this->filters;

$ordering = $this->ordering;

$vik = new VikApplication(VersionListener::getID());

$_DAYS = array(
	mb_substr( JText::_('VRDAYSUN'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYMON'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYTUE'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYWED'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYTHU'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYFRI'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYSAT'), 0, 3, 'UTF-8' ),
);

$multi_lang = cleverdine::isMultilanguage(true);

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'ordering');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'menus', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'menus', JText::_('VRMANAGEMENU1'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'menus', JText::_('VRMANAGEMENU19'), 'ordering', $ordering['ordering'], 1, $filters, 'vrheadcolactive'.(($ordering['ordering'] == 2) ? 1 : 2) ),
);

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		
		<div class="btn-group pull-left">
		   <input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
		</div>
	
		<div class="btn-group pull-left hidden-phone">
			<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
			<button type="button" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRCLEARFILTER'); ?>" onClick="clearFilter();">
				<i class="icon-remove"></i>
			</button>
		</div>
	
	</div>
	
	<br clear="all" /> <br />

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOMENU');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU26');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU31');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU2');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="250" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU3');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="200" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU4');?></th>
				<?php if( $multi_lang ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU33');?></th>
				<?php } ?>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU14');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEMENU18');?></th>
				<?php if( $core_edit ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;">
						<?php echo $links[2]; ?>
						<a href="javascript: saveSort();" class="vrorderingsavelink">
							<i class="fa fa-floppy-o big"></i>
						</a>
					</th>
				<?php } ?>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$icon_type = 1;
			if( empty($row['image']) ) {
				$icon_type = 2; // ICON NOT UPLOADED
			} else if( !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$row['image']) ) {
				$icon_type = 0;
			}
			
			$img_title = JText::_('VRIMAGESTATUS'.$icon_type);
			
			$working_shifts = JText::_('VRMANAGEMENU24');
			if( $row['special_day'] ) {
				$working_shifts = '/';
			} else if( !empty($row['working_shifts']) ) {
				$working_shifts = '';
				$_arr = explode( ', ', $row['working_shifts'] );
				for( $j = 0; $j < count($_arr); $j++ ) {
					$_app = explode( '-', $_arr[$j] );
					
					$_fh = intval(intval($_app[0])/60);
					$_fm = intval($_app[0])%60;
					if( $_fm < 10 ) {
						$_fm = '0'.$_fm;
					}
					
					$_th = intval(intval($_app[1])/60);
					$_tm = intval($_app[1])%60;
					if( $_tm < 10 ) {
						$_tm = '0'.$_tm;
					}
					
					$working_shifts .= $_fh.':'.$_fm.'-'.$_th.':'.$_tm;
					if( $j < count($_arr)-1 ) {
						$working_shifts .= ', ';
					}
				}
			}
			
			if( strlen($row['days_filter']) > 0 ) {
				$_df = explode(', ', $row['days_filter']);
				$row['days_filter'] = '';
				foreach( $_df as $day ) {
					if( !empty($row['days_filter']) ) {
						$row['days_filter'] .= ', ';
					}
					$row['days_filter'] .= $_DAYS[$day];
				}
			}
			
			$days_filter = (!empty($row['days_filter']) ? $row['days_filter'] : JText::_('VRMANAGEMENU25'));
			if( $row['special_day'] ) {
				$days_filter = '/';
			} 
					
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editmenu&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;">
					<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=menus&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=menus">
						<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					</a>
				</td>
				<td style="text-align: center;">
					<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=menus&column_db=choosable&val=<?php echo $row['choosable']; ?>&id=<?php echo $row['id']; ?>&return_task=menus">
						<?php echo intval($row['choosable']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					</a>
				</td>
				<td style="text-align: center;">
					<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=menus&column_db=special_day&val=<?php echo $row['special_day']; ?>&id=<?php echo $row['id']; ?>&return_task=menus">
						<?php echo intval($row['special_day']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					</a>
				</td>
				<td style="text-align: center;"><?php echo $working_shifts; ?></td>
				<td style="text-align: center;"><?php echo $days_filter; ?></td>
				<?php if( $multi_lang ) { ?>
					<td style="text-align: center;">
						<a href="index.php?option=com_cleverdine&task=langmenus&id=<?php echo $row['id']; ?>">
							<?php foreach( $row['languages'] as $lang ) { ?>
								<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower(substr($lang, 3, 2)).'.png'; ?>"/>
							<?php } ?>
						</a>
					</td>
				<?php } ?>
				<td style="text-align: center;">
					<a href="javascript: void(0);" onclick="SELECTED_MENU='<?php echo $row['id']; ?>';vrOpenJModal('sneakmenu', null, true); return false;">
						<i class="fa fa-search big"></i>
					</a>
				</td>
				<td style="text-align: center;">
					<?php if( $icon_type == 1) { ?>
						<a href="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$row['image']; ?>" class="modal" target="_blank">
							<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imagepreview.png"; ?>" title="<?php echo $img_title ?>"/>
						</a>
					<?php } else if( $icon_type == 0 ) { ?>
						<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imagenotfound.png"; ?>" title="<?php echo $img_title ?>"/>
					<?php } else { ?>
						<img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/imageno.png"; ?>" title="<?php echo $img_title ?>"/>
					<?php } ?>
				</td>
				<?php if( $core_edit ) { ?>
					<td style="text-align: center;">

						<?php if( $ordering['ordering'] > 0 ) { ?>
							<?php if( $row['ordering'] > $this->constraints['min'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=up&db_table=menus&return_task=menus">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'up' : 'down'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>

							<?php if( $row['ordering'] < $this->constraints['max'] ) { ?>
								<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=down&db_table=menus&return_task=menus">
									<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'down' : 'up'); ?>"></i>
								</a>
							<?php } else { ?>
								<i class="empty"></i>
							<?php } ?>
						<?php } ?>

						<input type="text" size="4" style="margin-bottom: 0;text-align: center;" value="<?php echo $row['ordering']; ?>" name="row_ord[<?php echo $row['id']; ?>][]"/>
					
					</td>
				<?php } ?>
			</tr>
			
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="menus"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<div class="modal hide fade" id="jmodal-sneakmenu" style="width:70%;height:80%;margin-left:-35%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRMANAGEMENU14'); ?></h3>
	</div>
	<div id="jmodal-box-sneakmenu"></div>
</div>

<script type="text/javascript">
	function saveSort() {
		jQuery('input[name=task]').val('saveMenusSort');
		document.adminForm.submit();
	}

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

	// JMODAL

	var SELECTED_MENU = -1;
	
	function vrOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
	jQuery(document).ready(function(){
		jQuery('#jmodal-sneakmenu').on('show', function() {
			sneakMenuOnShow();
		});
	});
	
	function sneakMenuOnShow() {
		var href = 'index.php?option=com_cleverdine&task=sneakmenu&id='+SELECTED_MENU+'&tmpl=component';
		var size = {
			width: jQuery('#jmodal-sneakmenu').width(), //940,
			height: jQuery('#jmodal-sneakmenu').height(), //590
		}
		appendModalContent('jmodal-box-sneakmenu', href, size);
	}
	
	function appendModalContent(id, href, size) {
		jQuery('#'+id).html('<div class="modal-body" style="max-height:'+(size.height-20)+'px;">'+
		'<iframe class="iframe" src="'+href+'" width="'+size.width+'" height="'+size.height+'" style="max-height:'+(size.height-100)+'px;"></iframe>'+
		'</div>');
	}
</script>