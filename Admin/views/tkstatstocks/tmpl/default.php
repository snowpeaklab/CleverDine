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

$vik = new VikApplication(VersionListener::getID());

$nowdf = cleverdine::getDateFormat(true);
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

$filters = $this->filters;

$ordering = $this->ordering;

$COLUMNS_TO_ORDER = array('concat_name', 'products_used');
foreach( $COLUMNS_TO_ORDER as $c ) {
	if( empty($ordering[$c]) ) {
		$ordering[$c] = 0;
	}
}

// ORDERING LINKS

$links = array(
	OrderingManager::getLinkColumnOrder( 'tkstatstocks', JText::_('VRMANAGETKSTOCK1'), 'concat_name', $ordering['concat_name'], 1, $filters, 'vrheadcolactive'.(($ordering['concat_name'] == 2) ? 1 : 2) ),
	OrderingManager::getLinkColumnOrder( 'tkstatstocks', JText::_('VRMANAGETKSTOCK8'), 'products_used', $ordering['products_used'], 1, $filters, 'vrheadcolactive'.(($ordering['products_used'] == 2) ? 1 : 2) ),
);

// CHARTS LABLES
$months_labels = array();
for( $i = 1; $i <= 12; $i++ ) {
	array_push($months_labels, JText::_('VRMONTH'.$i));
}

$weekdays_labels = array();
for( $i = 0; $i < 7; $i++ ) {
	array_push($weekdays_labels, JText::_('VRDAY'.($i == 0 ? 7 : $i)));
}

?>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">

	<?php if( count($this->menus) > 0 ) { ?>

		<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">

			<div class="btn-group pull-left">
				<input type="text" name="keysearch" id="vrkeysearch" class="vrkeysearch" size="32" value="<?php echo $filters['keysearch']; ?>" placeholder="<?php echo JText::_('VRRESERVATIONKEYSEARCH'); ?>"/>
			</div>
			
			<div class="btn-group pull-left hidden-phone">
				<button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRRESERVATIONBUTTONFILTER'); ?>">
					<i class="icon-search"></i>
				</button>
				<button type="button" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_('VRCLEARFILTER'); ?>" onClick="clearFilter();">
					<i class="icon-remove"></i>
				</button>
			</div>

			<div class="btn-group pull-right vr-toolbar-setfont">
				<?php
				$attr = array();
				$attr['class'] 		= 'vrdatefilter';
				$attr['onChange'] 	= 'document.adminForm.submit();';
				echo $vik->calendar($filters['end_day'], 'end_day', 'vrdatefilterend', null, $attr);
				?>
			</div>

			<div class="btn-group pull-right vr-toolbar-setfont">
				<?php
				$attr = array();
				$attr['class'] 		= 'vrdatefilter';
				$attr['onChange'] 	= 'document.adminForm.submit();';
				echo $vik->calendar($filters['start_day'], 'start_day', 'vrdatefilterstart', null, $attr);
				?>
			</div>

			<div class="btn-group pull-right">
				<div class="vr-toolbar-setfont">
					<select name="id_menu" onChange="document.adminForm.submit();" id="vr-menu-select">
						<option value=""></option>
						<?php foreach( $this->menus as $m ) { ?>
							<option value="<?php echo $m['id']; ?>" <?php echo ($m['id'] == $filters['id_menu'] ? 'selected="selected"' : ''); ?>><?php echo $m['title']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

		</div>

	<?php } ?>

<?php 
	if( count( $this->rows ) == 0 ) {
		?>
		<p><?php echo JText::_('VRNOTKPRODUCT');?></p>
		<?php
	} else {
?>

	<div class="span6">	
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
			<?php echo $vik->openTableHead(); ?>
				<tr>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="200" style="text-align: left;"><?php echo $links[0]; ?></th>
					<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="150" style="text-align: left;">&nbsp;</th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo $links[1]; ?></th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="50" style="text-align: center;"><?php echo JText::_('VRMANAGETKSTOCK9'); ?></th>
				</tr>
			<?php echo $vik->closeTableHead(); ?>
			<?php
			$kk = 0;
			$i = 0;
			foreach( $this->rows as $r ) { 
				?>

				<tr class="row<?php echo $kk; ?>">
					<td><?php echo $r['ename']; ?></td>
					<td><?php echo (strlen($r['oname']) ? $r['oname'] : ' '); ?></td>
					<td style="text-align: center;">
						<?php echo $r['products_used']; ?>
					</td>
					<td style="text-align: center;">
						<a href="javascript: void(0);" onClick="loadChartsRequest(<?php echo intval($r['eid']); ?>, <?php echo intval($r['oid']); ?>);">
							<i class="icon-bars"></i>
						</a>
					</td>
				</tr>
				
				<?php $kk = ($kk+1)%2; ?>

			<?php } ?>
		</table>

		<?php echo $this->navbut; ?>

	</div>

	<div class="span5">

		<div class="vr-stocks-report-content">

			<div class="vr-stocks-report-content-tabs">
				<div class="vr-tab-button">
					<a href="javascript: void(0);" onClick="vrSwitchSection('weekdays', this);" class="active"><?php echo JText::_('VRTKSTATSTOCKSCHARTWEEKDAYS'); ?></a>
				</div>
				<div class="vr-tab-button">
					<a href="javascript: void(0);" onClick="vrSwitchSection('months', this);"><?php echo JText::_('VRTKSTATSTOCKSCHARTMONTHS'); ?></a>
				</div>
				<div class="vr-tab-button">
					<a href="javascript: void(0);" onClick="vrSwitchSection('years', this);"><?php echo JText::_('VRTKSTATSTOCKSCHARTYEARS'); ?></a>
				</div>
			</div>

			<div class="va-managefile-content-sections">

				<div id="vr-chart-weekdays-box" class="vr-chart-box">
					<div id="vr-chart-weekdays-container"><?php echo JText::_('VRTKSTATSTOCKSNOITEMSEL'); ?></div>
				</div>

				<div id="vr-chart-months-box" class="vr-chart-box" style="display: none;">
					<div id="vr-chart-months-container"><?php echo JText::_('VRTKSTATSTOCKSNOITEMSEL'); ?></div>
				</div>

				<div id="vr-chart-years-box" class="vr-chart-box" style="display: none;">
					<div id="vr-chart-years-container"><?php echo JText::_('VRTKSTATSTOCKSNOITEMSEL'); ?></div>
				</div>

			</div>

		</div>

	</div>

	<?php } ?>

	<input type="hidden" name="task" value="tkstatstocks"/>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<script>

	jQuery(document).ready(function(){

		jQuery('#vr-menu-select').select2({
			placeholder: '<?php echo addslashes(JText::_('VRALLMENUSOPTION')); ?>',
			allowClear: true,
			width: 200
		});

	});

	var _LAST_SEARCH_ = '<?php echo addslashes($filters['keysearch']); ?>';
	
	function clearFilter() {
		jQuery('#vrkeysearch').val('');
		if( _LAST_SEARCH_.length > 0 ) {
			document.adminForm.submit();
		}
	}

	var _SECTION_ACTIVE_ = 'weekdays';

	function vrSwitchSection(section, link) {
		if( _SECTION_ACTIVE_ == section ) {
			return;
		}

		jQuery('.vr-chart-box').hide();
		jQuery('#vr-chart-'+section+'-box').show();

		jQuery('.vr-tab-button a').removeClass('active');
		jQuery(link).addClass('active');

		_SECTION_ACTIVE_ = section;

		buildCharts(null);
	}

	//////// CHARTS ////////

	function loadChartsRequest(eid, oid) {

		jQuery.noConflict();
	
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_cleverdine&task=tkstocks_get_tree_request&tmpl=component",
			data: { id_product: eid, id_option: oid, start: '<?php echo $filters['start_day']; ?>', end: '<?php echo $filters['end_day']; ?>' }
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);
			if( obj[0] == 1 ) {
				buildCharts(obj[1]);
			} else {
				alert(obj[1]);
			}
		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRSYSTEMCONNECTIONERR')); ?>');
		});

	}

	var _TREE_ = null;

	function buildCharts(tree) {

		jQuery('#vr-chart-weekdays-container, #vr-chart-months-container, #vr-chart-years-container').remove();

		if( tree === null ) {
			if( _TREE_ !== null ) {
				tree = _TREE_;
			} else {
				jQuery('#vr-chart-'+_SECTION_ACTIVE_+'-box').append('<div id="vr-chart-'+_SECTION_ACTIVE_+'-container"><?php echo addslashes(JText::_('VRTKSTATSTOCKSNODATA')); ?></div>');
				return;
			}
		} else {
			_TREE_ = tree;
		}

		if( _SECTION_ACTIVE_ == 'weekdays' ) {

			for( var i = 0; i < 7; i++ ) {
				if( !tree.weekdays.hasOwnProperty(i) ) {
					tree.weekdays[i] = 0;
				}
			}
			buildWeekdaysChart(tree.weekdays);

		} else if( _SECTION_ACTIVE_ == 'months' ) {

			if( Object.keys(tree.months).length > 1 ) {
				for( var i = 1; i <= 12; i++ ) {
					if( !tree.months.hasOwnProperty(i) ) {
						tree.months[i] = 0;
					}
				}
				buildMonthsChart(tree.months);
			} else {
				jQuery('#vr-chart-months-box').append('<div id="vr-chart-months-container"><?php echo addslashes(JText::_('VRTKSTATSTOCKSNODATA')); ?></div>');
			}

		} else if( _SECTION_ACTIVE_ == 'years' ) {

			if( Object.keys(tree.years).length > 1 ) {
				buildYearsChart(tree.years);
			} else {
				jQuery('#vr-chart-years-box').append('<div id="vr-chart-years-container"><?php echo addslashes(JText::_('VRTKSTATSTOCKSNODATA')); ?></div>');
			}

		}
	}

	function buildYearsChart(arr) {

		jQuery('#vr-chart-years-box').show();
		jQuery('#vr-chart-years-box').append('<canvas id="vr-chart-years-container" class="barchart-graphics"></canvas>');

		var options = initChartOptions();

		var data = {
			labels: Object.keys(arr),
			datasets: [
				{
					label: "Dataset",
					fillColor: "rgba(151,187,205,0.5)",
					strokeColor: "rgba(151,187,205,0.8)",
					highlightFill: "rgba(151,187,205,0.75)",
					highlightStroke: "rgba(151,187,205,1)",
					data: Object.values(arr)
				},
			]
		};

		var ctx = document.getElementById("vr-chart-years-container").getContext("2d");
		var myBarChart = new Chart(ctx).Bar(data, options);
	} 

	function buildMonthsChart(arr) {
		jQuery('#vr-chart-months-box').show();
		jQuery('#vr-chart-months-box').append('<canvas id="vr-chart-months-container" class="barchart-graphics"></canvas>');

		var options = initChartOptions();

		var data = {
			labels: <?php echo json_encode($months_labels); ?>,
			datasets: [
				{
					label: "Dataset",
					fillColor: "rgba(151,187,205,0.5)",
					strokeColor: "rgba(151,187,205,0.8)",
					highlightFill: "rgba(151,187,205,0.75)",
					highlightStroke: "rgba(151,187,205,1)",
					data: Object.values(arr)
				},
			]
		};

		var ctx = document.getElementById("vr-chart-months-container").getContext("2d");
		var myBarChart = new Chart(ctx).Bar(data, options);
	} 

	function buildWeekdaysChart(arr) {
		jQuery('#vr-chart-weekdays-box').show();
		jQuery('#vr-chart-weekdays-box').append('<canvas id="vr-chart-weekdays-container" class="barchart-graphics"></canvas>');

		var options = initChartOptions();

		var data = {
			labels: <?php echo json_encode($weekdays_labels); ?>,
			datasets: [
				{
					label: "Dataset",
					fillColor: "rgba(151,187,205,0.5)",
					strokeColor: "rgba(151,187,205,0.8)",
					highlightFill: "rgba(151,187,205,0.75)",
					highlightStroke: "rgba(151,187,205,1)",
					data: Object.values(arr)
				},
			]
		};

		var ctx = document.getElementById("vr-chart-weekdays-container").getContext("2d");
		var myBarChart = new Chart(ctx).Bar(data, options);
	}

	function initChartOptions() {

		return {
			//Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
			scaleBeginAtZero : true,

			//Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines : true,

			//String - Colour of the grid lines
			scaleGridLineColor : "rgba(0,0,0,.05)",

			//Number - Width of the grid lines
			scaleGridLineWidth : 1,

			//Boolean - Whether to show horizontal lines (except X axis)
			scaleShowHorizontalLines: true,

			//Boolean - Whether to show vertical lines (except Y axis)
			scaleShowVerticalLines: true,

			//Boolean - If there is a stroke on each bar
			barShowStroke : true,

			//Number - Pixel width of the bar stroke
			barStrokeWidth : 2,

			//Number - Spacing between each of the X value sets
			barValueSpacing : 5,

			//Number - Spacing between data sets within X values
			barDatasetSpacing : 1,

			//String - A legend template
			legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
		};

	}

	Chart.defaults.global.responsive = true;

</script>
