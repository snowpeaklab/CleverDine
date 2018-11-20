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

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

$curr_symb = cleverdine::getCurrencySymb(true);

$is_shifted = !cleverdine::isContinuosOpeningTime(true);

$_DAYS = array(
	mb_substr( JText::_('VRDAYSUN'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYMON'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYTUE'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYWED'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYTHU'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYFRI'), 0, 3, 'UTF-8' ),
	mb_substr( JText::_('VRDAYSAT'), 0, 3, 'UTF-8' ),
);

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'name', 'priority', 'group');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'specialdays', JText::_('VRMANAGEMENUSPRODUCT1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'specialdays', JText::_('VRMANAGESPDAY1'), 'name', $ordering['name'], 1, $filters, 'vrheadcolactive'.(($ordering['name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'specialdays', JText::_('VRMANAGESPDAY20'), 'priority', $ordering['priority'], 1, $filters, 'vrheadcolactive'.(($ordering['priority'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'specialdays', JText::_('VRMANAGESPDAY16'), 'group', $ordering['group'], 1, $filters, 'vrheadcolactive'.(($ordering['group'] == 2) ? 1 : 2) ),
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
		
		<div class="btn-group pull-right">
			<div class="vr-toolbar-setfont">
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $filters['group'], 'vr-group-sel', array(1, 2), '', true); ?> 
			</div>
		</div>
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOSPECIALDAY');?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY2');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY3');?></th>
				<?php if( $is_shifted ) { ?>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY4');?></th>
				<?php } ?>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY5');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY10');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGESPDAY12');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[3]; ?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$working_shifts = JText::_('VRMANAGEMENU24');
			if( !empty($row['working_shifts']) ) {
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
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td><?php echo $row['id']; ?></td>
				<td><a href="index.php?option=com_cleverdine&amp;task=editspecialday&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
				<td style="text-align: center;"><?php echo (($row['start_ts'] != -1 ) ? date( cleverdine::getDateFormat(true), $row['start_ts'] ) : ''); ?></td>
				<td style="text-align: center;"><?php echo (($row['end_ts'] != -1 ) ? date( cleverdine::getDateFormat(true), $row['end_ts'] ) : '' ); ?></td>
				<?php if( $is_shifted ) { ?>
					<td style="text-align: center;"><?php echo $working_shifts; ?></td>
				<?php } ?>
				<td style="text-align: center;"><?php echo (!empty($row['days_filter']) ? $row['days_filter'] : JText::_('VRMANAGEMENU25')); ?></td>
				<td style="text-align: center;">
					<a href="javascript: void(0);" onclick="SELECTED_ID=<?php echo $row['id']; ?>;vrOpenJModal('menuslist', null, true); return false;">
						<i class="fa fa-search big"></i>
					</a>
				</td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
						<a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=specialdays&column_db=markoncal&val=<?php echo $row['markoncal']; ?>&id=<?php echo $row['id']; ?>&return_task=specialdays">
							<i class="fa fa-star<?php echo ($row['markoncal'] ? '' : '-o'); ?> big"></i>
						</a>
					<?php } else { ?>
						<i class="fa fa-star<?php echo ($row['markoncal'] ? '' : '-o'); ?> big"></i>
					<?php } ?>
				</td>
				<td style="text-align: center;"><?php echo JText::_('VRPRIORITY'.$row['priority']); ?></td>
				<td style="text-align: center;"><?php echo JText::_(($row['group'] == 1)?'VRSHIFTGROUPOPT1':'VRSHIFTGROUPOPT2'); ?></td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="specialdays"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<div class="modal hide fade" id="jmodal-menuslist" style="width:50%;height:40%;margin-left:-25%;top:20%;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3><?php echo JText::_('VRMANAGESPDAY10'); ?></h3>
	</div>
	<div id="jmodal-box-menuslist"></div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function(){
		jQuery('#vr-group-sel').on('change', function(){
			document.adminForm.submit();
		});
	});

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

	// JQUERY MODAL
	
	SELECTED_ID = -1;
	
	jQuery(document).ready(function(){
		// EMAIL TMPL
		jQuery('#jmodal-menuslist').on('show', function() {
			var href = 'index.php?option=com_cleverdine&task=menuslist&tmpl=component&id='+SELECTED_ID;
			var size = {
				width: jQuery('#jmodal-menuslist').width(), //940,
				height: jQuery('#jmodal-menuslist').height(), //590
			}
			appendModalContent('jmodal-box-menuslist', href, size);
		}); 
	});
	
	function vrOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
	function appendModalContent(id, href, size) {
		jQuery('#'+id).html('<div class="modal-body" style="max-height:'+(size.height-20)+'px;">'+
		'<iframe class="iframe" src="'+href+'" width="'+size.width+'" height="'+size.height+'" style="max-height:'+(size.height-100)+'px;"></iframe>'+
		'</div>');
	}

</script>