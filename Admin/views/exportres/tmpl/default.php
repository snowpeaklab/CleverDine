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

$ids = $this->ids;
$dates = $this->dates;
$type = $this->type;

$allf = glob('./components/com_cleverdine/export/*.php');
$classfiles = array();

$exp_select = "";
if( @count( $allf ) > 0 ) {
	
	foreach( $allf as $af ) {
		$name = str_replace('./components/com_cleverdine/export/', '', $af);
		$name = explode('.', $name);
		$classfiles[] = $name[0];
	}
	sort( $classfiles );
	
	$exp_select = '<select class="" name="export_type" id="vrexptypesel" onChange="exportTypeChanged()">';
	foreach( $classfiles as $cf ) {
		$exp_select .= '<option value="'.$cf.'">'.strtoupper($cf).'</option>';
	}
	$exp_select.='</select>';
}

$date_format = cleverdine::getDateFormat(true);

$vik = new VikApplication(VersionListener::getID());

?>

<?php if( count($classfiles) == 0 ) { ?>
	<p><?php echo JText::_('VREXPORTNOFILESERR'); ?></p>
<?php } else { ?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span6">
		<?php echo $vik->openEmptyFieldset(); ?>
		
			<!-- NAME - Text -->
			<?php echo $vik->openControl(JText::_('VREXPORTRES1').':'); ?>
				<input type="text" name="filename" value="" id="vrfilename" placeholder="name" /> 
				&nbsp;<span for="vrfilename" id="vrfnlabel"><?php echo '.'.$classfiles[0]; ?></span>
			<?php echo $vik->closeControl(); ?>
		
			<!-- EXPORT CLASS - Select -->
			<?php echo $vik->openControl(JText::_('VREXPORTRES2').':'); ?>
				<?php echo $exp_select; ?>
			<?php echo $vik->closeControl(); ?>
		
			<?php if( count($dates) > 0 ) { ?>
				
				<!-- START DATE - Calendar -->
				<?php echo $vik->openControl(JText::_('VREXPORTRES3').':'); ?>
					<?php echo $vik->calendar(date($date_format, $dates[0]), 'date_start', 'vrdatestart'); ?>
				<?php echo $vik->closeControl(); ?>
				
				<!-- END DATE - Calendar -->
				<?php echo $vik->openControl(JText::_('VREXPORTRES4').':'); ?>
					<?php echo $vik->calendar(date($date_format, $dates[1]), 'date_end', 'vrdateend'); ?>
				<?php echo $vik->closeControl(); ?>

			<?php } else { ?>
				<?php echo $vik->openControl(JText::_('VREXPORTRES5').':'); ?>
					<span>[ <?php echo count($ids); ?> ]</span>
				<?php echo $vik->closeControl(); ?>
			<?php } ?>

		<?php echo $vik->closeEmptyFieldset(); ?>
	</div>
	
	<?php for( $i = 0; $i < count($ids); $i++ ) { ?> 
		<input type="hidden" name="ids[]" value="<?php echo $ids[$i]; ?>" />
	<?php } ?>
	
	<input type="hidden" name="type" value="<?php echo $type; ?>" />
	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script type="text/javascript">

	jQuery(document).ready(function(){

		jQuery('#vrexptypesel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150
		});

	});

	function exportTypeChanged() {
		jQuery('#vrfnlabel').text('.'+jQuery('#vrexptypesel').val());
	}
</script>

<?php } ?>