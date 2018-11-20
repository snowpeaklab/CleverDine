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

$items = $this->items;
$menus = $this->menus;
$selected_menu = $this->selectedMenu;

$config = UIFactory::getConfig();

// START MENUS SELECT
$select_menus = '<select name="selected_menu" onChange="vrMenuChanged();" id="vrtkselectmenu" class="vre-tinyselect">';
$select_menus .= '<option value="-1">'.JText::_('VRTAKEAWAYALLMENUS').'</option>';
foreach( $menus as $_m ) {
	$_m['title'] = cleverdine::translate($_m['id'], $_m, $this->menusTranslations, 'title', 'name');
	$select_menus .= '<option value="'.$_m['id'].'" '.(($_m['id'] == $selected_menu) ? 'selected="selected"' : '').'>'.$_m['title'].'</option>';
}
$select_menus .= '</select>';
// END SELECT

$date_format = cleverdine::getDateFormat();

$special_days = $this->specialDays;

for( $i = 0, $n = count( $special_days ); $i < $n; $i++ ) {
	if( $special_days[$i]['start_ts'] != -1 ) {
		$special_days[$i]['start_ts'] = date( $date_format, $special_days[$i]['start_ts'] );
		$special_days[$i]['end_ts'] = date( $date_format, $special_days[$i]['end_ts'] );
	}	
	
	$special_days[$i]['days_filter'] = (strlen($special_days[$i]['days_filter']) > 0 ? explode( ', ', $special_days[$i]['days_filter'] ) : array() );
}

$curr_symb = cleverdine::getCurrencySymb();
$_symb_arr = array( '', '' );
$symb_pos = cleverdine::getCurrencySymbPosition();
if( $symb_pos == 1 ) {
	$_symb_arr[1] = ' '.$curr_symb;
} else {
	$_symb_arr[0] = $curr_symb.' ';
}

$last_item_menu = -1;
$last_item_id = -1;

$is_date_allowed 	= cleverdine::isTakeAwayDateAllowed();
$is_live_orders 	= ($is_date_allowed ? false : cleverdine::isTakeAwayLiveOrders());
$is_currently_avail = (!$is_live_orders ? true : cleverdine::isTakeAwayCurrentlyAvailable());

$use_overlay = cleverdine::getTakeAwayUseOverlay();

$orders_allowed = cleverdine::isTakeAwayReservationsAllowedOn( $this->cart->getCheckinTimestamp() );

// jquery datepicker

if ($is_date_allowed)
{
	// jQuery datepicker
	$vik = new VikApplication();
	$vik->attachDatepickerRegional();
}

?>

<div class="vrtkitemspagediv">
	
	<div class="vrtkstartnotediv">
		<?php echo cleverdine::getTakeAwayNotes(); ?>
	</div>
	
	<?php if( count( $items ) > 0 ) { ?>
		
		<div class="vrtk-menus-filter-head">
			<?php if( count($menus) > 1 ) { ?>
				<div class="vrtkselectmenudiv vre-tinyselect-wrapper">
					<?php echo $select_menus; ?>
				</div>
			<?php } ?>

			<div class="vrtk-menus-date-block">
				<?php 
				$str_date = '';

				$cart_date_arr = getdate($this->cart->getCheckinTimestamp());
				$today_arr = getdate();
				if( $cart_date_arr['mday'] == $today_arr['mday'] && $cart_date_arr['mon'] == $today_arr['mon'] && $cart_date_arr['year'] == $today_arr['year'] ) {
					$str_date = JText::_('VRJQCALTODAY');
				} else {
					$str_date = date($date_format, $cart_date_arr[0]);
				}
				?>

				<input type="text" class="vrtk-menus-filter-date<?php echo ($is_date_allowed ? ' enabled' : ''); ?>" id="vrtk-menus-filter-date" value="<?php echo $str_date; ?>" size="10" readonly="readonly"/>
			</div>

		</div>
		
		<div class="vrtkitemsdiv">
			
			<?php foreach( $items as $it ) {
				
				$it['title'] = cleverdine::translate($it['id'], $it, $this->menusTranslations, 'title', 'name');
				$it['description'] = cleverdine::translate($it['id'], $it, $this->menusTranslations, 'description', 'description');
				
				$menu_active = $orders_allowed && $is_currently_avail && $this->availableTakeawayMenus !== false && ( count($this->availableTakeawayMenus) == 0 || in_array($it['id'], $this->availableTakeawayMenus) );
				
				?>
				
				<div class="vrtkmenuheader">
					<div class="vrtkmenutitlediv <?php echo (!$menu_active ? 'disabled' : ''); ?>">
						<div class="vrtk-menu-title"><?php echo $it['title']; ?></div>
						
						<?php if( !$menu_active ) { ?>
							<div class="vrtk-menusubtitle-notactive">
								<?php 
								if( !$orders_allowed ) {
									// orders are stopped
									echo JText::_('VRTKMENUNOTAVAILABLE3');
								} else if( $is_currently_avail ) {
									// menu is not available
									echo JText::_('VRTKMENUNOTAVAILABLE'); 
								} else {
									// restaurant is closed
									echo JText::_('VRTKMENUNOTAVAILABLE2'); 
								} ?>
							</div>
						<?php } ?>

					</div>
					<div class="vrtkmenudescdiv">
						<?php echo $it['description']; ?> 
					</div>
				</div>
				<div class="vrtkitemsofmenudiv">
					
					<?php foreach( $it['entries'] as $entry ) {
						$entry['name'] = cleverdine::translate($entry['id'], $entry, $this->entriesTranslations, 'name', 'name');
						$entry['description'] = cleverdine::translate($entry['id'], $entry, $this->entriesTranslations, 'description', 'description');

						$max_desc_len = cleverdine::getTakeAwayProductsDescriptionLength();

						$edesc = $entry['description'];
						$more_button = 0;
						if( strlen(strip_tags($edesc)) > $max_desc_len ) {
							$more_button = 1;
							$edesc = mb_substr(strip_tags($edesc), 0, $max_desc_len, 'UTF-8');
						}
						
						?>
						
						<div class="vrtksingleitemdiv">
							<div class="vrtkitemleftdiv">
								<?php if (strlen($entry['image']) > 0 
									&& $config->getBool('tkshowimages')
									&& file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$entry['image'])
								) { ?>
									<div class="vrtkitemimagediv">
										<a href="javascript: void(0);" class="vremodal" onClick="vreOpenModalImage('<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$entry['image']; ?>');">
											<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media@small/'.$entry['image']; ?>"/>
										</a>
									</div>
								<?php } ?>
								<div class="vrtkiteminfodiv">
									<div class="vrtkitemtitle">
										<span class="vrtkitemnamesp">
											<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeawayitem&takeaway_item='.$entry['id']); ?>">
												<?php echo $entry['name']; ?>
											</a>
										</span>
										<?php if( count($entry['attributes']) > 0 ) { ?>
											<span class="vrtkitemattributes">
												<?php foreach( $entry['attributes'] as $attr ) { 
													if( !empty($this->allAttributes[$attr]['icon']) ) {
														// attribute published
														?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$this->allAttributes[$attr]['icon']; ?>"/><?php 
													}
												} ?>
											</span>
										<?php } ?>
									</div>
									<span class="vrtkitemdescsp" id="vrtkitemshortdescsp<?php echo $entry['id']; ?>">
										<?php echo $edesc . (($more_button) ? '...' : ''); ?>
										<?php if( $more_button ) { ?>
											<a href="javascript: void(0);" onClick="showMoreDesc(<?php echo $entry['id'] ?>);"><strong><?php echo JText::_('VRTAKEAWAYMOREBUTTON'); ?></strong></a>
										<?php } ?>
									</span>
									<?php if( $more_button ) { ?>
										<span class="vrtkitemdescsp" id="vrtkitemlongdescsp<?php echo $entry['id']; ?>" style="display: none;">
											<?php echo $entry['description']; ?>
											<a href="javascript: void(0);" onClick="showLessDesc(<?php echo $entry['id'] ?>);"><strong><?php echo JText::_('VRTAKEAWAYLESSBUTTON'); ?></strong></a>
										</span>
									<?php } ?>
								</div>
							</div>
							
							<div id="vrtkitemoptions<?php echo $entry['id']; ?>" class="vrtkitemvardiv">
								
								<?php if( count($entry['options']) ) { ?>
								
									<?php foreach( $entry['options'] as $option ) {
										$option['name'] = cleverdine::translate($option['id'], $option, $this->optionsTranslations, 'name', 'name');
										
										$is_discounted = DealsHandler::isProductInDeals(array(
											"id_product" => $entry['id'],
											"id_option" => $option['id'],
											"quantity" => 1
										), $this->discountDeals);
										
										$price = $entry['price']+$option['price'];
										if( $is_discounted !== false ) {
											if( $this->discountDeals[$is_discounted]['percentot'] == 1 ) {
												$price -= $price*$this->discountDeals[$is_discounted]['amount']/100.0;
											} else {
												$price -= $this->discountDeals[$is_discounted]['amount'];
											}
										}
										?>
										
										<div class="vrtksinglevar">
											<span class="vrtkvarnamesp"><?php echo $option['name']; ?></span>
											<div class="vrtkvarfloatrdiv">
												<?php if( $is_discounted !== false ) { ?>
													<span class="vrtk-itemprice-stroke">
														<s><?php echo cleverdine::printPriceCurrencySymb($entry['price']+$option['price'], $curr_symb, $symb_pos); ?></s>
													</span>
												<?php } ?>
												<span class="vrtkvarpricesp">
													<?php echo cleverdine::printPriceCurrencySymb($price, $curr_symb, $symb_pos); ?>
												</span>
												<?php if( $menu_active ) { ?>
													<div class="vrtkvaraddbuttondiv">
														<?php if( $use_overlay == 2 || ( $use_overlay == 1 && cleverdine::hasItemToppings($entry['id'], $option['id']) ) ) { ?>
															<button type="button" class="vrtkvaraddbutton" onClick="vrOpenOverlay('vrnewitemoverlay', '<?php echo addslashes($entry['name']." - ".$option['name']); ?>', <?php echo $entry['id']; ?>, <?php echo $option['id']; ?>, -1);"></button>
														<?php } else { ?>
															<button type="button" class="vrtkvaraddbutton" onClick="vrInsertTakeAwayItem(<?php echo $entry['id']; ?>, <?php echo $option['id']; ?>);"></button>
														<?php } ?>
													</div>
												<?php } ?>
											</div>
										</div>
										
									<?php } ?>
								
								<?php } else { 
										$is_discounted = DealsHandler::isProductInDeals(array(
											"id_product" => $entry['id'],
											"quantity" => 1
										), $this->discountDeals);
										
										$price = $entry['price'];
										if( $is_discounted !== false ) {
											if( $this->discountDeals[$is_discounted]['percentot'] == 1 ) {
												$price -= $price*$this->discountDeals[$is_discounted]['amount']/100.0;
											} else {
												$price -= $this->discountDeals[$is_discounted]['amount'];
											}
										}
									?>
									
									<div class="vrtksinglevar">
										<span class="vrtkvarnamesp">&nbsp;</span>
										<div class="vrtkvarfloatrdiv">
											<?php if( $is_discounted !== false ) { ?>
												<span class="vrtk-itemprice-stroke">
													<s><?php echo cleverdine::printPriceCurrencySymb($entry['price'], $curr_symb, $symb_pos); ?></s>
												</span>
											<?php } ?>
											<span class="vrtkvarpricesp">
												<?php echo cleverdine::printPriceCurrencySymb($price, $curr_symb, $symb_pos); ?>
											</span>
											<?php if( $menu_active ) { ?>
												<div class="vrtkvaraddbuttondiv">
													<?php if( $use_overlay == 2 || ( $use_overlay == 1 && cleverdine::hasItemToppings($entry['id']) ) ) { ?>
														<button type="button" class="vrtkvaraddbutton" onClick="vrOpenOverlay('vrnewitemoverlay', '<?php echo addslashes($entry['name']); ?>', <?php echo $entry['id']; ?>, 0, -1);"></button>
													<?php } else { ?>
														<button type="button" class="vrtkvaraddbutton" onClick="vrInsertTakeAwayItem(<?php echo $entry['id']; ?>, 0);"></button>
													<?php } ?>
												</div>
											<?php } ?>
										</div>
									</div>
									
								<?php } ?>
																
							</div>
						</div>
					<?php } ?>
										
				</div>
				
			<?php } ?>
		</div>
	<?php } ?>
	
	<div class="vrtkgotopaydiv">
		<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&task=takeawayconfirm'); ?>" class="vrtkgotopaybutton">
			<?php echo JText::_('VRTAKEAWAYORDERBUTTON'); ?>
		</a>
	</div>
</div>

<?php if( count($this->allAttributes) > 0 ) { ?>
	<div class="vrtk-attributes-legend">
		<?php foreach( $this->allAttributes as $k => $attr ) { 
			$attr['name'] = cleverdine::translate($k, $attr, $this->attributesTranslations, 'name', 'name');
			?>
			<div class="vrtk-attribute-box">
				<img src="<?php echo JUri::root().'components/com_cleverdine/assets/media/'.$attr['icon']; ?>"/>
				<span><?php echo $attr['name']; ?></span>
			</div>
		<?php } ?>
	</div>
<?php } ?>

<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway'); ?>" method="POST" name="vrmenuform" id="vrmenuform">
	<input type="hidden" value="-1" name="takeaway_menu" id="vrtkselectedmenu"/>
	<input type="hidden" value="com_cleverdine" name="option"/>
	<input type="hidden" value="takeaway" name="view"/>
</form>

<div class="vr-overlay" id="vrnewitemoverlay" style="display: none;">
	<div class="vr-modal-box" style="width: 90%;max-width: 960px;height: 90%;margin-top:10px;">
		<div class="vr-modal-head">
			<div class="vr-modal-head-title">
				<h3></h3>
			</div>
			<div class="vr-modal-head-dismiss">
				<a href="javascript: void(0);" onClick="vrCloseOverlay('vrnewitemoverlay');">×</a>
			</div>
		</div>
		<div class="vr-modal-body" style="height:90%;overflow:scroll;">
			
		</div>
	</div>
</div>

<div class="vr-toast-wrapper" id="vrtkmsgtoast">
	<div class="vr-takeaway-message">
		<div class="vr-takeaway-message-content"></div>
	</div>
</div>


<script type="text/javascript">

	function vrMenuChanged() {
		jQuery('#vrtkselectedmenu').val( jQuery('#vrtkselectmenu').val() );
		document.vrmenuform.submit();
	}

	function vrIsCartPublished() {
		return ( typeof cleverdine_CART_INSTANCE !== "undefined" );
	}

	function showMoreDesc(id) {
		setDescriptionVisible(id,true);
	}

	function showLessDesc(id) {
		setDescriptionVisible(id,false);
	}

	function setDescriptionVisible(id,status) {
		if( status ) {
			jQuery('#vrtkitemshortdescsp'+id).css('display', 'none');
			jQuery('#vrtkitemlongdescsp'+id).fadeIn('fast');
		} else {
			jQuery('#vrtkitemlongdescsp'+id).css('display', 'none');
			jQuery('#vrtkitemshortdescsp'+id).fadeIn('fast');
		}
	}

	var TOAST_HANDLER = null;

	function vrDispatchMessage(msg) {
		var toast = jQuery('#vrtkmsgtoast');
		var content = toast.find('.vr-takeaway-message-content');

		if (TOAST_HANDLER) {
			clearTimeout(TOAST_HANDLER);

			toast.removeClass('vr-do-shake').delay(200).queue(function(next){
				jQuery(this).addClass('vr-do-shake');
				next();
			});
		}

		content.html(msg.text);
		content.removeClass('error');
		content.removeClass('success');
		content.removeClass('warning');

		var delay = 0;

		switch (msg.status) {
			case 0:
				content.addClass('error');
				delay = 4500;
				break;
			case 1:
				content.addClass('success');
				delay = 2500;
				break;
			case 2:
				content.addClass('warning');
				delay = 3500;
				break;
		}

		toast.addClass('toast-slide-in');

		if (msg.hasOwnProperty('delay')) {
			delay = msg.delay;
		}

		TOAST_HANDLER = setTimeout(function(){
			toast.removeClass('toast-slide-in').removeClass('vr-do-shake');

			TOAST_HANDLER = null;
		}, delay);
	}

	// DATEPICKER

	<?php if( $is_date_allowed ) { ?>

		jQuery(function(){

			var specialDays = <?php echo json_encode($special_days); ?>;
			var closingDays = <?php echo json_encode(cleverdine::getClosingDays()); ?>;

			var sel_format = "<?php echo $date_format; ?>";
			var df_separator = sel_format[1];

			sel_format = sel_format.replace(new RegExp("\\"+df_separator, 'g'), "");

			if( sel_format == "Ymd") {
				Date.prototype.format = "yy"+df_separator+"mm"+df_separator+"dd";
			} else if( sel_format == "mdY" ) {
				Date.prototype.format = "mm"+df_separator+"dd"+df_separator+"yy";
			} else {
				Date.prototype.format = "dd"+df_separator+"mm"+df_separator+"yy";
			}

			var today = new Date();

			var today_no_hour_no_min = getDate('<?php echo date($date_format, time()); ?>');

			jQuery("#vrtk-menus-filter-date:input").datepicker({
				minDate: today,
				currentText: "Now",
				dateFormat: today.format,
				beforeShowDay: setupCalendar,
				onSelect: vrDateChanged
			});

			function setupCalendar(date) {
					
				var enabled = false;
				var clazz = "";
				var ignore_cd = 0;
				
				if( today_no_hour_no_min.valueOf() > date.valueOf() ) {
					return [false,""];
				}

				for( var i = 0; i < specialDays.length && !enabled; i++ ) {
					if( specialDays[i]['start_ts'] == -1 ) {
						if( specialDays[i]['days_filter'].length == 0 ) {
							if( specialDays[i]['markoncal'] == 1 ) {
								clazz = "vrtdspecialday";
							}
							ignore_cd = specialDays[i]['ignoreclosingdays'];
						} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
							if( specialDays[i]['markoncal'] == 1 ) {
								clazz = "vrtdspecialday";
							}
							ignore_cd = specialDays[i]['ignoreclosingdays'];
						}
					}
					
					_ds = getDate(specialDays[i]['start_ts']);
					_de = getDate(specialDays[i]['end_ts']);
					
					if( _ds.valueOf() <= date.valueOf() && date.valueOf() <= _de.valueOf() ) {
						if( specialDays[i]['days_filter'].length == 0 ) {
							if( specialDays[i]['markoncal'] == 1 ) {
								clazz = "vrtdspecialday";
							}
							ignore_cd = specialDays[i]['ignoreclosingdays'];
						} else if( contains( specialDays[i]['days_filter'], date.getDay() ) ) {
							if( specialDays[i]['markoncal'] == 1 ) {
								clazz = "vrtdspecialday";
							}
							ignore_cd = specialDays[i]['ignoreclosingdays'];
						}
					}
				}
				
				enabled = true;
				if( ignore_cd == 0 ) {
					for( var i = 0; i < closingDays.length; i++ ) {
						var _d = getDate( closingDays[i]['date'] );
						
						if( closingDays[i]['freq'] == 0 ) {
							if( _d.valueOf() == date.valueOf() ) {
								return [false,""];
							}
						} else if( closingDays[i]['freq'] == 1 ) {
							if( _d.getDay() == date.getDay() ) {
								return [false,""];
							}
						} else if( closingDays[i]['freq'] == 2 ) {
							if( _d.getDate() == date.getDate() ) {
								return [false,""];
							} 
						} else if( closingDays[i]['freq'] == 3 ) {
							if( _d.getDate() == date.getDate() && _d.getMonth() == date.getMonth() ) {
								return [false,""];
							} 
						}
					}
				}
				
				return [enabled,clazz];
			}

			function getDate(day) {
				var formats = today.format.split(df_separator);
				var date_exp = day.split(df_separator);
				
				var _args = new Array();
				for( var i = 0; i < formats.length; i++ ) {
					_args[formats[i]] = parseInt( date_exp[i] );
				}
				
				return new Date( _args['yy'], _args['mm']-1, _args['dd'] );
			}

			function contains(arr,key) {
				for( var i = 0; i < arr.length; i++ ) {
					if( arr[i] == key ) {
						return true;
					}
				}
				
				return false;
			}

			function vrDateChanged() {
				jQuery('#vrmenuform').append('<input type="hidden" name="takeaway_date" value="'+jQuery('#vrtk-menus-filter-date').val()+'" />');
				jQuery('#vrmenuform').submit();
			} 

		});

	<?php } ?>

	// OVERLAY //

	function vrOpenOverlay(ref, title, id_entry, id_option, index) {
		
		jQuery('.vr-modal-head-title h3').text(title);
		
		jQuery('.vr-modal-body').html('<div class="vr-modal-overlay-loading"><img id="img-loading" src="<?php echo JUri::root(); ?>components/com_cleverdine/assets/css/images/hor-loader.gif"/></div>');
		jQuery('#'+ref).show();
		
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "index.php",
			data: { option: "com_cleverdine", task: "tkadditem", eid: id_entry, oid: id_option, index: index, tmpl: "component" }
		}).done(function(resp){
			resp = jQuery.parseJSON(resp)[0];

			jQuery('.vr-modal-body').html(resp);
		}).fail(function(){
			alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');
		});
		
	}

	function vrCloseOverlay(ref) {
		jQuery('#'+ref).hide();
		jQuery('.vr-modal-body').html('');
	}

	jQuery('.vr-modal-box').on('click', function(e){
		e.stopPropagation();
		// IGNORE OUTSIDE CLICK
	});
	jQuery('.vr-overlay').on('click', function(){
		vrCloseOverlay(jQuery(this).attr('id'));
	});

	// SKIP OVERLAY

	function vrInsertTakeAwayItem(id_entry, id_option) {
			
		jQuery.noConflict();
		
		var jqxhr = jQuery.ajax({
			type: "POST",
			url: "<?php echo JRoute::_('index.php?option=com_cleverdine&task=add_to_cart&tmpl=component'); ?>",
			data: {
				id_entry: id_entry,
				id_option: id_option,
				item_index: -1
			}
		}).done(function(resp){
			var obj = jQuery.parseJSON(resp);

			var msg = {
				status: 0,
				text: ''
			};

			if (obj[0]) {

				if (vrIsCartPublished()) {
					vrCartRefreshItems(obj[1], obj[2], obj[3], obj[4]);
				}

				if (obj[5]) {
					msg.status = obj[5].status;
					msg.text = obj[5].text;
				}

			} else {
				msg.text = obj[1];
			}

			// display a success message only if all the conditions below are satisfied:
			// - a message text is NOT set
			// - the status of the response is verified (item added)
			// - the cart is not published or the cart is not visible on the screen
			if (msg.text.length == 0 && obj[0] == 1 && (!vrIsCartPublished() || !vrIsCartVisibleOnScreen())) {
				msg.text = '<?php echo addslashes(JText::_("VRTKADDITEMSUCC")); ?>';
				msg.status = 1;
			}

			if (msg.text.length) {
				vrDispatchMessage(msg);
			}

		}).fail(function(resp){
			alert('<?php echo addslashes(JText::_('VRTKADDITEMERR2')); ?>');
		});
	}

</script>