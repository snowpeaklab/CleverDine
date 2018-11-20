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

JHtml::_('behavior.modal');

$rows = $this->rows;
$menus = $this->menus;
$lim0 = $this->lim0;
$navbut = $this->navbut;

$filters = $this->filters;

$ordering = $this->ordering;

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

// STATUS SELECT

$status_select = '<select name="status" id="vr-status-select" onchange="document.adminForm.submit();">';
$status_select .= '<option value=""></option>';
$status_select .= '<option value="1" '.($filters['status'] == 1 ? 'selected="selected"' : '').'>'.JText::_('VRSYSPUBLISHED1').'</option>';
$status_select .= '<option value="2" '.($filters['status'] == 2 ? 'selected="selected"' : '').'>'.JText::_('VRSYSPUBLISHED0').'</option>';
$status_select .= '<option value="3" '.($filters['status'] == 3 ? 'selected="selected"' : '').'>'.JText::_('VRSYSHIDDEN').'</option>';
$status_select .= '</select>';

// MENUS SELECT

$menus_select = '<select name="id_menu" id="vr-menu-select" onChange="document.adminForm.submit();" '.($filters['status'] == 3 ? 'disabled="disabled"' : '').'>';
$menus_select .= '<option value=""></option>';
foreach( $menus as $m ) {
	$menus_select .= '<option value="'.$m['id'].'" '.($m['id'] == $filters['id_menu'] ? 'selected="selected"' : '').'>'.$m['name'].'</option>';
}
$menus_select .= '</select>';

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'ordering');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'menusproducts', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'menusproducts', JText::_('VRMANAGEMENUSPRODUCT2'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'menusproducts', JText::_('VRMANAGEMENUSPRODUCT7'), 'ordering', $ordering['ordering'], 1, $filters, 'vrheadcolactive'.(($ordering['ordering'] == 2) ? 1 : 2) ),
);

$vik = new VikApplication(VersionListener::getID());

$is_searching = false;
foreach( $filters as $k => $v ) {
	$is_searching = $is_searching || ($k != 'tools' && strlen($v));
}

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		
		<div class="btn-group pull-left input-append">
		   <input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
			
		   <button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
				<i class="icon-search"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn <?php echo ($filters['tools'] ? 'btn-primary' : ''); ?>" onclick="toggleSearchToolsButton(this);">
				<?php echo JText::_('VRSEARCHTOOLS'); ?>&nbsp;<i class="fa fa-caret-<?php echo ($filters['tools'] ? 'up' : 'down'); ?>" id="vr-tools-caret"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn" onClick="clearFilters();">
				<?php echo JText::_('VRCLEARFILTER'); ?>
			</button>
		</div>
	
	</div>

	<div class="btn-toolbar vr-btn-toolbar" id="vr-search-tools" style="<?php echo ($filters['tools'] ? '' : 'display: none;'); ?>">

		<div class="btn-group pull-left">
			
			<div class="vr-toolbar-setfont">
				 <?php echo $status_select; ?>
			</div>

		</div>

		<div class="btn-group pull-left">
			
			<div class="vr-toolbar-setfont">
				 <?php echo $menus_select; ?>
			</div>

		</div>

	</div>
	
<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOMENUSPRODUCT');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="200" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="300" style="text-align: center;"><?php echo JText::_('VRMANAGEMENUSPRODUCT3'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGEMENUSPRODUCT4'); ?></th>
				
				<?php if( $filters['status'] != 3 ) { ?>

					<!-- NOT HIDDEN PRODUCTS -->

					<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRMANAGEMENUSPRODUCT6'); ?></th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRMANAGEMENUSPRODUCT5'); ?></th>
					<?php if( $core_edit ) { ?>
						<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;">
							<?php echo $links[2]; ?>
							<a href="javascript: saveSort();" class="vrorderingsavelink">
								<i class="fa fa-floppy-o big"></i>
							</a>
						</th>
					<?php } ?>

					<!-- END -->

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

			$desc = strip_tags($row['description']);
			if( strlen($desc) > 150 ) {
				$desc = mb_substr($desc, 0, 128, 'UTF-8')."...";
			}
			 
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editmenusproduct&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td><?php echo $desc; ?></td>
				<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($row['price'], $curr_symb, $symb_pos); ?></td>

				<?php if( $filters['status'] != 3 ) { ?>

					<!-- NOT HIDDEN PRODUCTS -->

					<td style="text-align: center;">
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=section_product&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=menusproducts">
							<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
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
									<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=up&db_table=section_product&return_task=menusproducts&params[hidden]=0">
										<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'up' : 'down'); ?>"></i>
									</a>
								<?php } else { ?>
									<i class="empty"></i>
								<?php } ?>

								<?php if( $row['ordering'] < $this->constraints['max'] ) { ?>
									<a href="index.php?option=com_cleverdine&amp;task=sortfield&cid[]=<?php echo $row['id']; ?>&mode=down&db_table=section_product&return_task=menusproducts&params[hidden]=0">
										<i class="fa fa-arrow-<?php echo ($ordering['ordering'] == 2 ? 'down' : 'up'); ?>"></i>
									</a>
								<?php } else { ?>
									<i class="empty"></i>
								<?php } ?>
							<?php } ?>

							<input type="text" size="4" style="margin-bottom: 0;text-align: center;" value="<?php echo $row['ordering']; ?>" name="row_ord[<?php echo $row['id']; ?>][]"/>
						
						</td>
					<?php } ?>

					<!-- END -->

				<?php } ?>

			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>

	<input type="hidden" name="tools" value="<?php echo $filters['tools']; ?>" />

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="menusproducts"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-status-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRFILTERSELECTSTATUS')); ?>',
			allowClear: true,
			width: 200
		});

		jQuery('#vr-menu-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRFILTERSELECTMENU')); ?>',
			allowClear: true,
			width: 300
		});

	});

	function saveSort() {
		jQuery('input[name=task]').val('saveMenusProductsSort');
		document.adminForm.submit();
	}
	
	var _LAST_SEARCH_ = <?php echo ($is_searching ? 1 : 0); ?>;
	
	function clearFilters() {
		jQuery('#vrkeysearch').val('');

		jQuery('#vr-status-select').select2('val', '');

		jQuery('#vr-menu-select').attr('disabled', false); // remove disabled attr to corectly POST id_menu filter
		jQuery('#vr-menu-select').select2('val', '');

		jQuery('input[name="tools"]').val(0);

		if( _LAST_SEARCH_ ) {
			document.adminForm.submit();
		}
	}

	function toggleSearchToolsButton(btn) {

		var tools = 0;

		if( jQuery(btn).hasClass('btn-primary') ) {
			jQuery('#vr-search-tools').slideUp();

			jQuery(btn).removeClass('btn-primary');
			
			jQuery('#vr-tools-caret').removeClass('fa-caret-up').addClass('fa-caret-down');
		} else {
			jQuery('#vr-search-tools').slideDown();

			jQuery(btn).addClass('btn-primary');

			jQuery('#vr-tools-caret').removeClass('fa-caret-down').addClass('fa-caret-up');

			tools = 1;
		}

		jQuery('input[name="tools"]').val(tools);

	}

</script>