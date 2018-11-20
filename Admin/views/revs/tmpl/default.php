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

$date_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);

$vik = new VikApplication(VersionListener::getID());

$core_edit = (JFactory::getUser()->authorise('core.edit', 'com_cleverdine'));

// ORDERING LINKS

$COLUMNS_TO_ORDER = array('id', 'timestamp', 'rating', 'published', 'verified');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

$links = array(
	OrderingManager::getLinkColumnOrder( 'revs', JText::_('VRMANAGEREVIEW1'), 'id', $ordering['id'], 1, $filters, 'vrheadcolactive'.(($ordering['id'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'revs', JText::_('VRMANAGEREVIEW4'), 'timestamp', $ordering['timestamp'], 1, $filters, 'vrheadcolactive'.(($ordering['timestamp'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'revs', JText::_('VRMANAGEREVIEW5'), 'rating', $ordering['rating'], 1, $filters, 'vrheadcolactive'.(($ordering['rating'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'revs', JText::_('VRMANAGEREVIEW7'), 'published', $ordering['published'], 1, $filters, 'vrheadcolactive'.(($ordering['published'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'revs', JText::_('VRMANAGEREVIEW12'), 'verified', $ordering['verified'], 1, $filters, 'vrheadcolactive'.(($ordering['verified'] == 2) ? 1 : 2) ),
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
				<?php
				$all_ratings = array(
					$vik->initOptionElement('', '', empty($filters['stars'])) 
				);
				for( $i = 5; $i >= 1; $i-- ) {
					array_push($all_ratings, $vik->initOptionElement($i, $i.' '.strtolower(JText::_($i > 1 ? 'VRSTARS' : 'VRSTAR')), $filters['stars'] == $i));
				}
				echo $vik->dropdown('stars', $all_ratings, 'vr-rating-sel');
				?>
			</diV>
		</div>
	</div>

<?php if( count( $rows ) == 0 ) { ?>
	<p><?php echo JText::_('VRNOREVIEW'); ?></p>
<?php } else { ?>

	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<th width="20">
					<?php echo $vik->getAdminToggle(count($rows)); ?>
				</th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="50" style="text-align: left;"><?php echo $links[0]; ?></th>
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;"><?php echo JText::_('VRMANAGEREVIEW2'); ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEREVIEW3');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[1]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo $links[2]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="100" style="text-align: center;"><?php echo JText::_('VRMANAGEREVIEW6');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo $links[3]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo $links[4]; ?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEREVIEW9');?></th>
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="75" style="text-align: center;"><?php echo JText::_('VRMANAGEREVIEW8');?></th>
			</tr>
		<?php echo $vik->closeTableHead(); ?>
		<?php
		$kk = 0;
		for( $i = 0; $i < count($rows); $i++ ) {
			$row = $rows[$i];
			
			$country = explode('-', $row['langtag']);
			$country = $country[1];
			
			?>
			<tr class="row<?php echo $kk; ?>">
				<td><input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row['id']; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>"></td>
				<td style="text-align: left;"><?php echo $row['id']; ?></td>
				<td style="text-align: left;"><a href="index.php?option=com_cleverdine&amp;task=editrev&amp;cid[]=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></td>
				<td style="text-align: center;"><?php echo $row['name']; ?></td>
				<td style="text-align: center;"><?php echo cleverdine::formatTimestamp($date_format, $row['timestamp']); ?></td>
				<td style="text-align: center;">
					<?php for( $j = 1; $j <= $row['rating']; $j++ ) { ?>
						<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star.png'; ?>" style="width: 16px;height:16px;"/>
					<?php } ?>
				</td>
				<td style="text-align: center;"><?php echo $row['takeaway_product_name']; ?></td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
					   <a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=reviews&column_db=published&val=<?php echo $row['published']; ?>&id=<?php echo $row['id']; ?>&return_task=revs">
						  <?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					   </a>
					<?php } else { ?>
						<?php echo intval($row['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<?php if( $core_edit ) { ?>
					   <a href="index.php?option=com_cleverdine&task=changeStatusColumn&table_db=reviews&column_db=verified&val=<?php echo $row['verified']; ?>&id=<?php echo $row['id']; ?>&return_task=revs">
						  <?php echo intval($row['verified']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					   </a>
					<?php } else { ?>
						<?php echo intval($row['verified']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<?php if( strlen($row['comment']) > 0 ) {
						$comment = $row['comment'];
						if( strlen($comment) > 1800 ) { 
							$comment = mb_substr($comment, 0, 1500, 'UTF-8').'...';
						}
						?>
						<a href="javascript: void(0);">
							<i title="<?php echo $comment; ?>" class="fa fa-commenting big vr-comment"></i>
						</a>
					<?php } else { ?>
						<i class="fa fa-comment-o big"></i>
					<?php } ?>
				</td>
				<td style="text-align: center;">
					<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower($country).'.png'; ?>"/>
				</td>
			</tr>
			<?php
			$kk = 1 - $kk;
		}		
		?>
	</table>
	<?php } ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="revs"/>
	<?php echo JHTML::_( 'form.token' ); ?>
	<?php echo $navbut; ?>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vr-rating-sel').select2({
			minimumResultsForSearch: -1,
			placeholder: '<?php echo addslashes("- ".JText::_('VRMANAGEREVIEW5')." -"); ?>',
			allowClear: true,
			width: 200,
			formatResult: format,
			formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});

		jQuery('#vr-rating-sel').on('change', function(){
			document.adminForm.submit();
		});

		jQuery('.vr-comment').tooltip();

	});

	function format(opt) {
		if(!opt.id) return opt.text; // optgroup

		var stars_html = '';

		for( var i = 0; i < 5; i++ ) {
			stars_html += (i > 0 ? ' ' : '')+'<i class="fa fa-star'+(i < opt.id ? '' : '-o')+'" style="color: #e7c33c;"></i>';
		}

		return stars_html;
	}

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

</script>
