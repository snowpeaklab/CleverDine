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

JHtml::_('behavior.calendar');

$rows = $this->rows;
$all = $this->all;
$shifts = $this->shifts;
$filters = $this->filters;

$shift_select = '';
if( count($shifts) > 0 ) {
	$shift_select = '<select name="shift" id="vrshiftselect">';
	$shift_select .= '<option></option>';
	foreach( $shifts as $s ) {
		$shift_select .= '<option value="'.intval($s['from']/60).'-'.intval($s['to']/60).'"'.((intval($s['from']/60).'-'.intval($s['to']/60) == $filters['shift']) ? 'selected="selected"' : '').'>'.$s['name'].'</option>';
	}
	$shift_select .= '</select>';
}

$nowdf = cleverdine::getDateFormat(true);
$nowdf = str_replace( 'd', '%d', $nowdf );
$nowdf = str_replace( 'm', '%m', $nowdf );
$nowdf = str_replace( 'Y', '%Y', $nowdf );

$curr_symb = cleverdine::getCurrencySymb(true);
$symb_pos = cleverdine::getCurrencySymbPosition(true);
$symb_pos_arr = array('','');
if( $symb_pos == 1 ) {
	$symb_pos_arr[0] = $curr_symb." ";
} else {
	$symb_pos_arr[1] = " ".$curr_symb;
}

$old_mon = -1;
$old_year = -1;

$vik = new VikApplication(VersionListener::getID());

?>

<style>
	.vrstatmontd {
		background-color: #DDD !important;
		font-weight: bold; 
		border-bottom: 1px solid #aaa;
		border-right: 1px solid #aaa;
	}
	.vrstat-limit td{
		border-bottom: 1px solid #aaa;
	}
</style>

<form action="index.php?option=com_cleverdine" method="post" name="adminForm" id="adminForm">
	
	<div class="btn-toolbar vr-btn-toolbar" id="filter-bar">
		<div class="btn-group pull-left vr-toolbar-setfont">
			<?php echo $vik->calendar($filters['datestart'], 'datestart', 'vrdatestart'); ?>
		</div>
		<div class="btn-group pull-left vr-toolbar-setfont">
			<?php echo $vik->calendar($filters['dateend'], 'dateend', 'vrdateend'); ?>
		</div>
		
		<?php if( count($shifts) > 0 ) { ?>
			<div class="btn-group pull-left">
				<div class="vr-toolbar-setfont">
					<?php echo $shift_select; ?>
				</div>
			</div>
		<?php } ?>
		
		<div class="btn-group pull-right">
			<button type="submit" class="btn"><?php echo JText::_('VRMANAGESTAT2'); ?></button>
		</div>
		<div class="btn-group pull-right">
			<button type="button" class="btn" onClick="toggleStatsList();"><?php echo JText::_('VRMANAGESTAT3'); ?></button>
		</div>
	</div>
	
	<?php if( count($all) > 0 && $all['num_res'] > 0 ) { ?>
		<div class="vrstatheader">
			<h4><?php echo JText::sprintf('VRSTATISTICSHEADER', $filters['datestart'], $filters['dateend']); ?></h4>
		</div>
		
		<div class="vrstattotalinfodiv">
			<span class="vrstattotalearn">
				<?php echo JText::sprintf('VRSTATISTICSTOTALEARN', '<strong>'.$all['total_earn'].' '.$curr_symb.'</strong>'); ?>
			</span>
			<span class="vrstattotalres">
				<?php echo JText::sprintf('VRSTATISTICSTOTALRES', '<strong>'.$all['num_res'].'</strong>'); ?>
			</span>
		</div>
		
		<div id="vr-stats-list">
		<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>">
			<?php echo $vik->openTableHead(); ?>
				<tr>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRSTATISTICSTH1'); ?></th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRSTATISTICSTH2'); ?></th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRSTATISTICSTH3'); ?></th>
					<th class="<?php echo $vik->getAdminThClass(); ?>" width="150" style="text-align: center;"><?php echo JText::_('VRSTATISTICSTH4'); ?></th>
				</tr>
			<?php echo $vik->closeTableHead(); ?>
			<?php
			$kk = 0;
			for( $i = 0, $n = count($rows); $i < $n; $i++ ) {
				$row = $rows[$i];
				
				?>
				<tr class="row<?php echo $kk; ?>">
					<td class="vrstatmontd" rowspan="<?php echo (count($row['weekdays'])+1); ?>"><?php echo $row['label'].', '.$row['year']; ?></td>
					<td style="text-align: center;">&nbsp;</td>
					<td style="text-align: center;"><?php echo $row['num_res']; ?></td>
					<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($row['tot_earn'], $curr_symb, $symb_pos); ?></td>
				</tr>
				
				<?php 
				end($row['weekdays']);
				$last_key = key($row['weekdays']);
				foreach( $row['weekdays'] as $wday => $attr ) { ?>
					<tr class="row<?php echo $kk; ?> <?php echo ($wday == $last_key ? 'vrstat-limit' : ''); ?>">
						<td style="text-align: center;"><?php echo $attr['label']; ?></td>
						<td style="text-align: center;"><?php echo $attr['num_res']; ?></td>
						<td style="text-align: center;"><?php echo cleverdine::printPriceCurrencySymb($attr['tot_earn'], $curr_symb, $symb_pos); ?></td>
					</tr>
				<?php } ?>
				
				<?php
				$kk = 1 - $kk;
			} ?>
	   </table></div>
	<?php
	
	$max_line_chart_value = -1;
	$min_line_chart_value = -1;
	
	$line_chart_labels = array();
	$line_chart_data = array(
		"global" => array( "label" => JText::_('VRMANAGECONFIGTITLE0'), "datas" => array(), "published" => @in_array("global", $filters['chart'])),
	);
	for( $i = 0; $i < 7; $i++ ) {
		$line_chart_data[$i] = array( "label" => JText::_('VRDAY'.($i > 0 ? $i : 7)), "datas" => array(), "published" => in_array($i, $filters['chart']) );
	}
	
	foreach( $rows as $i => $row ) {
		array_push($line_chart_labels, mb_substr($row['label'], 0, 3, 'UTF-8')." ".$row['year']);
		
		array_push($line_chart_data['global']['datas'], array(
			"tot_earn" => $row['tot_earn'],
			"num_res" => $row['num_res'],
		));
		
		if( $line_chart_data['global']['published'] && ( $max_line_chart_value == -1 || $row['tot_earn'] > $max_line_chart_value ) ) {
			$max_line_chart_value = $row['tot_earn'];
		}
		if( $line_chart_data['global']['published'] && ( $min_line_chart_value == -1 || $row['tot_earn'] < $min_line_chart_value ) ) {
			$min_line_chart_value = $row['tot_earn'];
		}
		
		for( $w = 0; $w < 7; $w++ ) {
			$tot_earn = (empty($row['weekdays'][$w]) ? 0.0 : $row['weekdays'][$w]['tot_earn']);
			
			array_push($line_chart_data[$w]['datas'], array(
				"tot_earn" => $tot_earn,
				"num_res" => empty($row['weekdays'][$w])? 0 : $row['weekdays'][$w]['num_res'],
			));
			
			if( $line_chart_data[$w]['published'] && ( $max_line_chart_value == -1 || $tot_earn > $max_line_chart_value ) ) {
				$max_line_chart_value = $tot_earn;
			}
			
			if( $line_chart_data[$w]['published'] && ( $min_line_chart_value == -1 || $tot_earn < $min_line_chart_value ) ) {
				$min_line_chart_value = $tot_earn;
			}
		}
	}
	?>
	
	<?php
	$elements = array(
		$vik->initOptionElement('global', JText::_('VRMANAGECONFIGTITLE0'), @in_array('global', $filters['chart'])),
	);
	for( $i = 0; $i < 7; $i++ ) {
		array_push($elements, $vik->initOptionElement("$i", JText::_('VRDAY'.($i>0 ? $i : 7)), @in_array("$i", $filters['chart'])));
	}
	?>
	<div class="vr-stats-chartfilters">
		<div class="vr-stats-chartfilters-input">
			<?php echo $vik->dropdown('chart_filters[]', $elements, 'vr-chart-filters', '', 'multiple style="width: 100%;"'); ?>
		</div>
		<div class="vr-stats-chartfilters-submit">
			<button type="submit" class="btn"><?php echo JText::_('VRMANAGESTAT2'); ?></button>
		</div>
	</div>
	
	<div class="vr-linechart-container">
		<div class="vr-linechart-wrapper">
			<canvas id="vr-linechart" class="linechart-graphics"></canvas>
			<div id="vr-linechart-legend"></div>
		</div>
	</div>
		
	<?php } else { 
		echo JText::_('VRNORESERVATION');
	} ?>
	
	<input type="hidden" name="type" value="<?php echo $this->type; ?>"/>
	<input type="hidden" name="task" value="<?php echo (($this->type==1)?'statistics':'tkstatistics'); ?>"/>
	<input type="hidden" name="option" value="com_cleverdine" />
</form>

<script>

	jQuery(document).ready(function(){
		
		jQuery('#vr-chart-filters').select2({
			width: 'resolve',
		});

		jQuery('#vrshiftselect').select2({
			placeholder: '<?php echo addslashes(JText::_('VRMANAGESTAT1')); ?>',
			allowClear: true,
			width: 200
		});
	});
	
	function toggleStatsList() {
		if( jQuery('#vr-stats-list').is(':visible') ) {
			jQuery('#vr-stats-list').hide();
		} else {
			jQuery('#vr-stats-list').show();
		}
	}
	
	<?php if( !empty($line_chart_data) && count($line_chart_data['global']['datas']) >= 2 ) { ?>
	
		// GLOBAL CHARTS
		Chart.defaults.global.responsive = true;
		
		var RGBs = [
			{r:151, g:187, b:220},
			{r:100, g:195, b:132},
			{r:205, g: 65, b: 13},
			{r:250, g:145, b:15},
			{r:187, g:187, b:187},
			{r:  0, g:200, b:215},
			{r:180, g:230, b: 15},
			{r: 65, g: 65, b: 65},
		];
					
		// LINE CHART
	
		var MIN_LINE_CHART_VALUE = parseFloat('<?php echo $min_line_chart_value; ?>');
		var MAX_LINE_CHART_VALUE = parseFloat('<?php echo $max_line_chart_value; ?>'); 
		var LINE_CHART_STEPS = 15;
		
		if( MIN_LINE_CHART_VALUE > 0 ) {
			MIN_LINE_CHART_VALUE = Math.max(0, MIN_LINE_CHART_VALUE-25);
		}
	
		var data = {
			labels: <?php echo json_encode($line_chart_labels); ?>,
			datasets: []
		};
		
		<?php foreach( $line_chart_data as $dataset ) { ?>
			<?php if( $dataset['published'] ) { ?>
				
				var d_data = new Array();
				
				<?php foreach( $dataset['datas'] as $d ) { ?>
					d_data.push(parseFloat('<?php echo $d['tot_earn']; ?>'));
				<?php } ?>
				
				var c = data.datasets.length%RGBs.length;
				
				data.datasets.push({
					label: "<?php echo addslashes($dataset['label']); ?>",
					fillColor: "rgba("+RGBs[c].r+","+RGBs[c].g+","+RGBs[c].b+",0.2)",
					strokeColor: "rgba("+RGBs[c].r+","+RGBs[c].g+","+RGBs[c].b+",1)",
					pointColor: "rgba("+RGBs[c].r+","+RGBs[c].g+","+RGBs[c].b+",1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba("+RGBs[c].r+","+RGBs[c].g+","+RGBs[c].b+",1)",
					data: d_data
				});
			
			<?php } ?>
		<?php } ?>
		
		var options = {
		
			///Boolean - Whether grid lines are shown across the chart
			scaleShowGridLines : true,
		
			//String - Color of the grid lines
			scaleGridLineColor : "rgba(0,0,0,.05)",
		
			//Number - Width of the grid lines
			scaleGridLineWidth : 1,
		
			//Boolean - Whether to show horizontal lines (except X axis)
			scaleShowHorizontalLines: true,
		
			//Boolean - Whether to show vertical lines (except Y axis)
			scaleShowVerticalLines: true,
		
			//Boolean - Whether the line is curved between points
			bezierCurve : true,
		
			//Number - Tension of the bezier curve between points
			bezierCurveTension : 0.4,
		
			//Boolean - Whether to show a dot for each point
			pointDot : true,
		
			//Number - Radius of each point dot in pixels
			pointDotRadius : 4,
		
			//Number - Pixel width of point dot stroke
			pointDotStrokeWidth : 1,
		
			//Number - amount extra to add to the radius to cater for hit detection outside the drawn point
			pointHitDetectionRadius : 20,
		
			//Boolean - Whether to show a stroke for datasets
			datasetStroke : true,
		
			//Number - Pixel width of dataset stroke
			datasetStrokeWidth : 2,
		
			//Boolean - Whether to fill the dataset with a color
			datasetFill : true,
			
			scaleOverride: true,
			scaleSteps: LINE_CHART_STEPS,
			//scaleStepWidth: Math.ceil(MAX_LINE_CHART_VALUE / LINE_CHART_STEPS),
			scaleStepWidth: Math.ceil((MAX_LINE_CHART_VALUE-MIN_LINE_CHART_VALUE) / LINE_CHART_STEPS),
			scaleStartValue: MIN_LINE_CHART_VALUE,
			
			tooltipTemplate: "<%if (label){%><%=label%>: <%}%><?php echo $symb_pos_arr[0]; ?><%=value%><?php echo $symb_pos_arr[1]; ?>",
			multiTooltipTemplate: "<?php echo $symb_pos_arr[0]; ?><%=value%><?php echo $symb_pos_arr[1]; ?> - <%=datasetLabel%>",
			scaleLabel: "<?php echo $symb_pos_arr[0]; ?><%=value%><?php echo $symb_pos_arr[1]; ?>",
			
		};
		
		var ctx = document.getElementById("vr-linechart").getContext("2d");
		var myLineChart = new Chart(ctx).Line(data, options);
		var legend = myLineChart.generateLegend();
		jQuery('#vr-linechart-legend').html( legend );
	
	<?php } ?>
	
</script>