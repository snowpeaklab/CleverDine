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

$old_section_id = -1;
$last_ordering = 0;

$constraints = array();

$vik = new VikApplication(VersionListener::getID());

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">
	
	<div class="span12">
		
		<?php echo $vik->openFieldset($rows[0]['menu_name'], 'form-horizontal'); ?>
			
			<div class="vrmenu-section-head">
				<a href="javascript: void(0);" onClick="jQuery('.vrmenuordproducts').slideDown();" class="vrmenuheadbutton"><?php echo JText::_('VRMANAGEMENU29'); ?></a>
				<a href="javascript: void(0);" onClick="jQuery('.vrmenuordproducts').slideUp();" class="vrmenuheadbutton"><?php echo JText::_('VRMANAGEMENU30'); ?></a>
			</div>
		
			<div class="vrmenuordcont">
				<?php foreach( $rows as $s ) {
					if( $old_section_id != $s['id'] ) { ?>
						<?php if($old_section_id != -1 ) { ?>
								</div>
							</div>
						<?php } ?>
						<div class="vrmenuordblock" id="vrsec<?php echo $s['id']; ?>">
							<div class="vrmenuordsection">
								<i class="fa fa-ellipsis-v"></i>
								<span class="vrmenuordsectionname" onClick="changeSectionStatus(<?php echo $s['id']; ?>);"><?php echo $s['name']; ?></span>
								<input type="hidden" name="section[]" value="<?php echo $s['id']; ?>" />
							</div>
								
							<div class="vrmenuordproducts" id="vrmenuordproducts<?php echo $s['id']; ?>">
					<?php } ?>
					<?php if( !empty($s['pid']) ) { ?>
						<div class="vrmenuordprod" id="vrprod<?php echo $s['aid']; ?>">
							<i class="fa fa-ellipsis-v"></i>
							<span class="vrmenuordprodname"><?php echo $s['pname']; ?></span>
							<input type="hidden" name="product[]" value="<?php echo $s['aid']; ?>" />
						</div>
					<?php }
					
					$old_section_id = $s['id']; 
					$last_ordering = $s['ordering'];
				} ?>
				<!-- close products container tags -->
				<?php if( count($rows) > 0 ) { ?>
					   </div>
				   </div> 
				<?php } ?>
				<!-- end close -->
			</div>
			
		<?php echo $vik->closeFieldset(); ?>
	</div>
	
	<input type="hidden" name="id" value="<?php echo $this->menu_id; ?>"/>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="option" value="com_cleverdine"/>
</form>

<script>

	function changeSectionStatus(id) {
		if( jQuery('#vrmenuordproducts'+id).is(':visible') ) {
			jQuery('#vrmenuordproducts'+id).slideUp();
		} else {
			jQuery('#vrmenuordproducts'+id).slideDown();
		}
	}

	jQuery(document).ready(function(){
		makeSortable();
	});

	function makeSortable() {
		jQuery(".vrmenuordcont, .vrmenuordproducts").sortable({
			revert: true
		});
		//jQuery( ".vrmenuordprod").disableSelection();
	}
	
</script>

