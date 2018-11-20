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

$now = time();

$vik = new VikApplication(VersionListener::getID());

?>


<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left">
			<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $this->filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRMENUPRODKEYSEARCH'); ?>"/>
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
				<?php echo RestaurantsHelper::buildGroupDropdown('group', $this->filters['group'], 'vr-group-sel', null, '', true); ?>
			</div>
		</div>
		
		<div class="btn-group pull-right">
			<button type="button" class="btn" onClick="selectAll(1);">
				<?php echo JText::_('VRINVSELECTALL'); ?>
			</button>
			<button type="button" class="btn" onClick="selectAll(0);">
				<?php echo JText::_('VRINVSELECTNONE'); ?>
			</button>
		</div>
	</div>

	<div class="vr-archive-main">

		<div class="vr-archive-filestree">
			<ul class="root">
				<?php 
				foreach( $this->tree as $year => $months ) { ?>
					<li class="year <?php echo ($this->seek['year'] == $year ? 'expanded' : 'wrapped' ); ?>">
						<div class="year-node"><?php echo ($year != -1 ? $year : JText::_('VRINVOICESOTHERS')); ?></div>
						<ul class="monthslist" style="<?php echo ($this->seek['year'] != $year ? 'display: none;' : '' ); ?>">
							<?php foreach( $months as $mon ) { ?>
								<li class="month <?php echo ($this->seek['year'] == $year && $this->seek['month'] == $mon ? 'picked' : '' ); ?>">
									<div class="month-node">
										<a href="javascript: void(0);" onClick="loadInvoiceOn(<?php echo $year; ?>,<?php echo $mon; ?>,this);">
											<?php echo ($mon != -1 ? JText::_('VRMONTH'.$mon) : JText::_('VRINVOICESOTHERSALL')); ?>
										</a>
									</div>
								</li>
							<?php 
								$first_month = false;
							} ?>
						</ul>
					</li>
				<?php 
					$first_year = false;
				} ?>
			</ul>
		</div>
	
		<div class="vr-archive-filespool">
			
			<?php if( count( $this->invoices ) == 0 ) { ?>
				<p><?php echo JText::_('VRNOINVOICESONARCHIVE'); ?></p>
			<?php } else {
	
				$cont = 0;
				$dt_format = cleverdine::getDateFormat(true).' '.cleverdine::getTimeFormat(true);
				foreach( $this->invoices as $invoice ) {
					$cont++;
					?>
					
					<div class="vr-archive-fileblock">
						<div class="vr-archive-fileicon">
							<img src="<?php echo JUri::root().'administrator/components/com_cleverdine/assets/images/invoice@big.png'; ?>"/>
						</div>
						<div class="vr-archive-filename">
							<a href="<?php echo JUri::root().'components/com_cleverdine/helpers/library/pdf/archive/'.$invoice['file']; ?>?t=<?php echo $now; ?>" target="_blank">
								<?php echo substr($invoice['file'], 0, strrpos($invoice['file'], '.')); ?><br /><?php echo $invoice['inv_number']; ?>
							</a>
						</div>
						<input type="hidden" name="cid[]" value="0::<?php echo $invoice['id']; ?>" class="cid"/>
					</div>
					
				<?php } ?>
			<?php } ?>
		</div>
		
	</div>
	
	<input type="hidden" name="year" value="<?php echo $this->seek['year']; ?>" id="vrseekyear"/>
	<input type="hidden" name="month" value="<?php echo $this->seek['month']; ?>" id="vrseekmonth"/>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="invoices"/>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<div class="vr-archive-footer">
	<div class="vr-archive-loadbuttons" style="<?php echo ($this->loadedAll ? "display: none;" : ""); ?>">
		<button type="button" class="btn btn-success" onClick="loadMoreInvoices(<?php echo $this->limit; ?>);"><?php echo JText::_('VRLOADMOREINVOICES'); ?></button>
		<button type="button" class="btn btn-success" onClick="loadMoreInvoices(-1);"><?php echo JText::_('VRLOADALLINVOICES'); ?></button>
	</div>
	<div class="vr-archive-wait" style="display: none;">
		<img src="<?php echo JUri::root().'administrator/components/com_cleverdine/assets/images/loading.gif'; ?>"/>
	</div>
</div>


<script>

	jQuery(document).ready(function(){
		jQuery('.year-node').on('click', function(){
			var mlist = jQuery(this).next();
			if( mlist.is(':visible') ) {
				jQuery(this).parent().addClass('wrapped');
				jQuery(this).parent().removeClass('expanded');
				mlist.slideUp();
			} else {
				jQuery(this).parent().addClass('expanded');
				jQuery(this).parent().removeClass('wrapped');
				mlist.slideDown();
			}
		});

		jQuery('#vr-group-sel').on('change', function(){
			document.adminForm.submit();
		});
		
		registerFileAction();
	});

	function registerFileAction() {
		jQuery('.vr-archive-fileicon').on('click', function(){
			var parent = jQuery(this).parent();
			if( !parent.hasClass('selected') ) {
				parent.addClass('selected');
				parent.find('.cid').val(parent.find('.cid').val().replace('0::', '1::'));
			} else {
				parent.removeClass('selected');
				parent.find('.cid').val(parent.find('.cid').val().replace('1::', '0::'));
			}
			<?php echo $vik->checkboxOnClick(); ?>
		});
	}

	function selectAll(is) {
		if( is ) {
			jQuery('.vr-archive-fileblock').addClass('selected');
			jQuery('.cid').each(function(){
				jQuery(this).val( jQuery(this).val().replace('0::', '1::') );
			});
		} else {
			jQuery('.vr-archive-fileblock').removeClass('selected');
			jQuery('.cid').each(function(){
				jQuery(this).val( jQuery(this).val().replace('1::', '0::') );
			});
		}
		<?php echo $vik->checkboxOnClick(); ?>
	}
	
	var QUERY_ARGS 	= <?php echo json_encode($this->seek); ?>;
	var RUNNING 	= false;
	
	function loadInvoiceOn(year, month, node) {
		if( RUNNING ) {
			return;
		}
		
		RUNNING = true;
		
		QUERY_ARGS['year'] = year;
		QUERY_ARGS['month'] = month;
		
		jQuery('#vrseekyear').val(year);
		jQuery('#vrseekmonth').val(month);
		
		START_LIMIT = 0;
		
		jQuery('.month').removeClass('picked');
		jQuery(node).parent().parent().addClass('picked');
		
		jQuery('.vr-archive-filespool').html('');
		
		loadMoreInvoices(LIMIT);
				
	}
	
	// LOAD MORE
	
	var START_LIMIT = <?php echo $this->limit; ?>;
	var LIMIT 		= START_LIMIT;
	var MAX_LIMIT 	= <?php echo $this->maxLimit; ?>
	
	function loadMoreInvoices(lim) {
		jQuery('.vr-archive-loadbuttons').hide();
		jQuery('.vr-archive-wait').show();
		
		if( lim <= 0 ) {
			lim = MAX_LIMIT;
		}
		
		jQuery.noConflict();
				
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=loadinvoices&tmpl=component",
			data: { 
				year: QUERY_ARGS['year'],
				month: QUERY_ARGS['month'],
				start_limit: START_LIMIT,
				limit: lim, 
				keysearch: _LAST_SEARCH_,
				group: _GROUP_
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			
			if( obj[0] ) {
				
				START_LIMIT = obj[1];
				
				for( var i = 0; i < obj[3].length; i++ ) {
					jQuery('.vr-archive-filespool').append(obj[3][i]);
				}
				
				registerFileAction();
				
			} else {
				alert(obj[1]);
			}
			
			jQuery('.vr-archive-wait').hide();
			if( obj[2] ) {
				jQuery('.vr-archive-loadbuttons').show();
			}
			
			MAX_LIMIT = parseInt(obj[4]);
			
			RUNNING = false;
			
		}).fail(function(resp){
			RUNNING = false;
		});
	}

	//////////////////////////////////////////////////////////
	
	var _LAST_SEARCH_ 	= '<?php echo addslashes($this->filters['keysearch']); ?>';
	var _GROUP_ 		= '<?php echo $this->filters['group']; ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}
	
</script>
