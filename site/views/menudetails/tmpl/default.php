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

$menu = $this->menu;

$curr_symb = cleverdine::getCurrencySymb();
$symb_pos = cleverdine::getCurrencySymbPosition();

$image_path = JUri::root().'components/com_cleverdine/assets/media/';

$old_section_id = $last_section_highlighted = -1;

$sections = array(
	array("id" => 0, "name" => JText::_("VRMENUDETAILSALLSECTIONS"), "selected" => 1)
);
if( !empty($menu['id']) ) {
	foreach( $menu['sections'] as $s ) {
		if( $sections[count($sections)-1]['id'] != $s['id'] && $s['highlight'] ) {
			array_push($sections, array("id" => $s['id'], "name" => $s['name'], "selected" => 0));
		}
	}
}

$back_q_string = (!empty($this->lastValues['date']) ? '&date='.$this->lastValues['date'] : '').(!empty($this->lastValues['shift']) ? '&shift='.$this->lastValues['shift'] : '');

?>

<div class="vrmenu-detailsmain">
	
	<?php if( strlen($back_q_string) > 0 ) { ?>
		<div class="vrmenu-backdiv">
			<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=menuslist'.$back_q_string, false); ?>">
				<?php echo JText::_('VRBACK'); ?>
			</a>
		</div>
	<?php } ?>
	
	<?php if( !empty($menu['id']) ) { ?>
	
		<div class="vrmenu-detailshead" >
			<h3><?php echo $menu['name']; ?></h3>
			<div class="vrmenu-detailsheadsub">
				<?php if( !empty($menu['image']) ) { ?>
					<div class="vrmenu-detailsheadsubimage">
						<a href="javascript: void(0);" onClick="vreOpenModalImage('<?php echo $image_path.$menu['image']; ?>');" class="vremodal">
							<img src="<?php echo $image_path.$menu['image']; ?>"/>
						</a>
					</div>
				<?php } ?>
				<?php if( !empty($menu['description']) ) { ?>
					<div class="vrmenu-detailsheadsubdesc"><?php echo $menu['description']; ?></div>
				<?php } ?>
			</div>
		</div>
		
		<div class="vrmenu-sectionsbar">
			<?php foreach( $sections as $s ) { ?>
				<span class="vrmenu-sectionsp">
					<a href="javascript: void(0);" class="vrmenu-sectionlink <?php echo ($s['selected'] ? 'vrmenu-sectionlight' : ''); ?>" onClick="vrFadeSection(<?php echo $s['id']; ?>);" id="vrmenuseclink<?php echo $s['id']; ?>">
						<?php echo $s['name']; ?>
					</a>
				</span>
			<?php } ?>
		</div>
		
		<div class="vrmenu-detailslist">
			
			<?php foreach( $menu['sections'] as $s ) {
			
				if( $s['highlight'] ) {
					$last_section_highlighted = $s['id'];
				}
				?>
				
				<div class="vrmenu-detailssection <?php echo 'vrmenusubsection'.$last_section_highlighted; ?>" id="vrmenusection<?php echo $s['id']; ?>">
					<h3><?php echo $s['name']; ?></h3>
					<div class="vrmenu-detailssectionsub">
						<?php if( !empty($s['image']) ) { ?>
							<div class="vrmenu-detailssectionsubimage">
								<a href="javascript: void(0);" onClick="vreOpenModalImage('<?php echo $image_path.$s['image']; ?>');" class="vremodal">
									<img src="<?php echo $image_path.$s['image']; ?>"/>
								</a>
							</div>
						<?php } ?>
						<?php if( !empty($s['description']) ) { ?>
							<div class="vrmenu-detailssectionsubdesc"><?php echo $s['description']; ?></div>
						<?php } ?>
					</div>
						
					<div class="vrmenu-detailsprodlist">
						
						<?php foreach( $s['products'] as $p ) { ?>
							<div class="vrmenu-detailsprod">
								<div class="vrmenu-detailsprodsub">
									<div class="vrmenu-detailsprodsubleft">
										<?php if( $p['image'] ) { ?>
											<div class="vrmenu-detailsprodsubimage">
												<a href="javascript: void(0);" onClick="vreOpenModalImage('<?php echo $image_path.$p['image']; ?>');" class="vremodal">
													<img src="<?php echo $image_path.$p['image']; ?>"/>
												</a>
											</div>
										<?php } ?>
										<div class="vr-menudetailsprodsubnamedesc">
											<h3><?php echo $p['name']; ?></h3>
											<?php if( !empty($p['description']) ) { ?>
												<div class="vrmenu-detailsprodsubdesc"><?php echo $p['description']; ?></div>
											<?php } ?>
										</div>
									</div>
									<div class="vrmenu-detailsprodsubright">
										<?php if( count($p['options']) > 0 ) { ?>
											<div class="vrmenu-detailsprod-optionslist">
												<?php foreach( $p['options'] as $o ) { ?>
													<div class="vrmenu-detailsprod-option">
														<div class="option-name"><?php echo $o['name']; ?></div>
														<?php if( ($p['price']+$o['price']) > 0 ) { ?>
															<div class="option-price"><?php echo cleverdine::printPriceCurrencySymb($p['price']+$o['price'], $curr_symb, $symb_pos); ?></div>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
										<?php } else if( $p['price'] > 0 ) { ?>
											<div class="vrmenu-detailsprodsubprice">
												<span class="vrmenu-detailsprodsubpricesp"><?php echo cleverdine::printPriceCurrencySymb($p['price'], $curr_symb, $symb_pos); ?></span>
											</div>
										<?php } ?>
									</div>
								</div>
								
							</div>
						<?php } ?>
											 
					</div>
				</div>
				
			<?php } ?>
		</div>
	 
	 <?php } ?>
</div>

<script>
	
	function vrFadeSection(id_menu) {
		jQuery('.vrmenu-sectionlink').removeClass('vrmenu-sectionlight');
		
		jQuery('#vrmenuseclink'+id_menu).addClass('vrmenu-sectionlight');
		
		if( id_menu == 0 ) {
			jQuery('.vrmenu-detailssection').fadeIn('fast');
		} else {
			jQuery('.vrmenu-detailssection').hide();
			jQuery('#vrmenusection'+id_menu).fadeIn('fast');
			jQuery('.vrmenusubsection'+id_menu).fadeIn('fast');
		}
	}
	
</script>

