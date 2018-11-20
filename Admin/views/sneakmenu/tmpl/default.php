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

$sections = $this->sections;

$old_section_id = -1;

?>

<div class="vrmenuprevcont">
	<?php foreach( $sections as $s ) {
		if( $old_section_id != $s['id'] ) {
			
			$icon_type = 1;
			$image_name = 'imagepreview.png';
			if( empty($s['image']) ) {
				$icon_type = 2; // ICON NOT UPLOADED
				$image_name = 'imageno.png';
			} else if( !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$s['image']) ) {
				$icon_type = 0;
				$image_name = 'imagenotfound.png';
			}
			
			$img_title = JText::_('VRIMAGESTATUS'.$icon_type);
			
			if($old_section_id != -1 ) { ?>
					</div>
				</div>
			<?php } ?>
			<div class="vrmenuprevblock" id="vrsec<?php echo $s['id']; ?>">
				<div class="vrmenuprevsection">
					<span class="vrmenuprevsectionname" onClick="changeSectionStatus(<?php echo $s['id']; ?>);"><?php echo $s['name']; ?></span>
					<span class="vrmenuprevsectionright">
						<span class="vrmenuprevsectionimg"><img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/".$image_name; ?>" title="<?php echo $img_title ?>"/></span>
						<span class="vrmenuprevsectionpubl">
							<?php echo intval($s['published']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>                    
						</span>
					</span>
				</div>
					
				<div class="vrmenuprevproducts" id="vrmenuprevproducts<?php echo $s['id']; ?>">
		<?php 
			$constraints[$s['id']] = array('min' => 99999999, 'max' => 0);
		} 
		?>
		<?php if( !empty($s['pid']) ) { 
			$icon_type = 1;
			$image_name = 'imagepreview.png';
			if( empty($s['image']) ) {
				$icon_type = 2; // ICON NOT UPLOADED
				$image_name = 'imageno.png';
			} else if( !file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$s['pimage']) ) {
				$icon_type = 0;
				$image_name = 'imagenotfound.png';
			}
			
			$img_title = JText::_('VRIMAGESTATUS'.$icon_type);
			?>
			<div class="vrmenuprevprod" id="vrprod<?php echo $s['aid']; ?>">
				<span class="vrmenuprevprodname"><?php echo $s['pname']; ?></span>
				<span class="vrmenuprevprodright">
					<span class="vrmenuprevprodimg"><img src="<?php echo JUri::root()."administrator/components/com_cleverdine/assets/images/".$image_name; ?>" title="<?php echo $img_title ?>"/></span>
					<span class="vrmenuprevprodpubl">
						<?php echo intval($s['ppublished']) == 1 ? "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/ok.png\"/>" : "<img src=\"".JUri::root()."administrator/components/com_cleverdine/assets/images/no.png\"/>"; ?>                    
					</span>
				</span>
			</div>
		<?php }
		$old_section_id = $s['id']; 
	} ?>
	<!-- close products container tags -->
	<?php if( count($sections) > 0 ) { ?>
		   </div>
	   </div> 
	<?php } ?>
	<!-- end close -->
</div>
		
<script>
	
	function changeSectionStatus(id) {
		if( jQuery('#vrmenuprevproducts'+id).is(':visible') ) {
			jQuery('#vrmenuprevproducts'+id).slideUp();
		} else {
			jQuery('#vrmenuprevproducts'+id).slideDown();
		}
	}
	
</script>