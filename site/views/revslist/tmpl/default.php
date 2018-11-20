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

$item = $this->item;

$date_format = cleverdine::getDateFormat();

$is_comment_required 	= cleverdine::isReviewsCommentRequired();
$min_comment_length 	= cleverdine::getReviewsCommentMinLength();
$max_comment_length 	= cleverdine::getReviewsCommentMaxLength();

$review_leave_mode 	= cleverdine::getReviewsLeaveMode();
$can_leave_review 	= cleverdine::canLeaveTakeAwayReview($item['id']);
$submit_rev 		= JFactory::getApplication()->input->get('submit_rev', 0, 'uint');

// get reviews
UILoader::import('library.reviews.handler');

$reviewsHandler = new ReviewsHandler();

if( $this->request->sortby == 1 ) {
	// from latest to oldest
	$reviewsHandler->setOrdering('timestamp', 2);

} else if( $this->request->sortby == 2 ) {
	// from oldest to latest
	$reviewsHandler->setOrdering('timestamp', 1);

} else if( $this->request->sortby == 3 ) {

	// from most rated to worst rated
	$reviewsHandler->setOrdering('rating', 2)
		->addOrdering('verified', 2)
		->addOrdering('timestamp', 2);

}

$reviews = $reviewsHandler->takeaway()
	->setLimit($this->request->limitstart, $this->request->limit)
	->setRatingFilter($this->request->filterstar)
	->setLangTag($this->request->filterlang)
	->allowEmptyComment()
	->getReviews($item['id']);

$reviewsStats = $reviewsHandler->takeaway()
	->getAverageRatio($item['id']);

$ratingsCount = $reviewsHandler->takeaway()
	->getRatingsCount($item['id']);

?>

<div class="vrtk-itemdet-category">

	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway'); ?>">
		<?php echo JText::_('VRTAKEAWAYALLMENUS'); ?>
	</a>
	<span class="arrow-separator">></span>
	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeaway&takeaway_menu='.$item['id_menu']); ?>">
		<?php echo $item['menu_title']; ?>
	</a>
	<span class="arrow-separator">></span>
	<a href="<?php echo JRoute::_('index.php?option=com_cleverdine&view=takeawayitem&takeaway_item='.$item['id']); ?>">
		<?php echo $item['name']; ?>
	</a>

</div>

<!-- REVIEWS -->
<?php if( $reviews !== false ) { ?>
	<div class="vr-reviews-quickwrapper">

		<h3><?php echo JText::_('VRREVIEWSTITLE'); ?></h3>

		<?php if( $reviewsStats !== null ) { ?>
			<div class="rv-reviews-quickstats">
				<div class="rv-top">
					<div class="rv-average-stars">
						<?php for( $i = 1; $i <= $reviewsStats->halfRating; $i++ ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star.png'; ?>"/><?php
						}
						if( round($reviewsStats->halfRating) != $reviewsStats->halfRating ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star-middle.png'; ?>"/><?php
						}
						for( $i = round($reviewsStats->halfRating)+1; $i <= 5; $i++ ) {
							?><img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/rating-star-no.png'; ?>"/><?php
						} ?>
					</div>
					<div class="rv-count-reviews">
						<?php echo JText::sprintf('VRREVIEWSCOUNT', $reviewsStats->count); ?>
					</div>
					<?php if( $can_leave_review ) { ?>
						<div class="rv-submit-review">
							<button type="button" class="vr-review-btn" style="<?php echo ($submit_rev ? 'display: none;' : ''); ?>" onClick="vrDisplayPostReview(this);">
								<?php echo JText::_('VRREVIEWLEAVEBUTTON'); ?>
							</button>
						</div>
					<?php } else {

						$str = "";

						if( $review_leave_mode == 1 && JFactory::getUser()->guest ) {
							$str = JText::_('VRREVIEWLEAVENOTICE1');
						} else if( $review_leave_mode == 2 && !cleverdine::isVerifiedTakeAwayReview($item['id']) ) {
							$str = JText::_('VRREVIEWLEAVENOTICE2'); 
						}

						if( !empty($str) ) {
							?><div class="rv-submit-review info-message"><?php echo $str; ?></div><?php
						}

					} ?>
				</div>

				<div class="rv-average-ratings">
					<?php echo JText::sprintf(
						'VRREVIEWSAVG', 
						floatval(number_format($reviewsStats->rating, 2))+0
					); ?>
				</div>

			</div>
		<?php } ?>

		<?php if( $can_leave_review ) { ?>
			<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&task=submit_review'); ?>" method="POST" id="vrrevformpost">
				
				<div class="vr-new-review-wrapper" id="vr-new-review" style="<?php echo ($submit_rev ? '' : 'display: none;'); ?>">

					<?php if( JFactory::getUser()->guest ) { ?>

						<div class="rv-new-top">
						
							<div class="rv-new-field">
								<div class="rv-new-field-label"><?php echo JText::_('VRREVIEWSFIELDUSERNAME'); ?>*</div>
								<div class="rv-new-field-value">
									<input type="text" name="review_user_name" size="32" id="vrreviewusername" class="review-required" maxlength="128"/>
								</div>
							</div>

							<div class="rv-new-field">
								<div class="rv-new-field-label"><?php echo JText::_('VRREVIEWSFIELDUSERMAIL'); ?>*</div>
								<div class="rv-new-field-value">
									<input type="text" name="review_user_mail" size="32" id="vrreviewusermail" class="review-required" maxlength="128"/>
								</div>
							</div>

						</div>

					<?php } ?>

					<div class="rv-new-top">
						
						<div class="rv-new-field">
							<div class="rv-new-field-label"><?php echo JText::_('VRREVIEWSFIELDTITLE'); ?>*</div>
							<div class="rv-new-field-value">
								<input type="text" name="review_title" size="32" id="vrreviewtitle" class="review-required" maxlength="64"/>
							</div>
						</div>

						<div class="rv-new-field">
							<div class="rv-new-field-label"><?php echo JText::_('VRREVIEWSFIELDRATING'); ?>*</div>
							<div class="rv-new-field-value">
								<?php for( $i = 1; $i <= 5; $i++ ) { ?>
									<div class="vr-ratingstar-box rating-nostar" data-id="<?php echo $i; ?>"></div>
								<?php } ?>

								<div id="vr-newrating-desc"><?php echo JText::_('VRREVIEWSTARDESC0'); ?></div>
								<input type="hidden" name="review_rating" id="vrreviewrating" class="review-required" value=""/>
							</div>
						</div>

					</div>

					<div class="rv-new-middle">
						<div class="rv-new-field">
							<div class="rv-new-field-label"><?php echo JText::_('VRREVIEWSFIELDCOMMENT').($is_comment_required ? '*' : ''); ?></div>
							<div class="rv-new-field-value">
								<textarea name="review_comment" id="vrreviewcomment" class="<?php echo ($is_comment_required ? 'review-required' : ''); ?>" maxlength="<?php echo $max_comment_length; ?>"></textarea>

								<div class="rv-new-charsleft">
									<span><?php echo JText::_('VRREVIEWSCHARSLEFT'); ?>&nbsp;</span>
									<span id="vrcommentchars"><?php echo $max_comment_length; ?></span>
								</div>
								<?php if( $min_comment_length > 0 ) { ?>
									<div class="rv-new-minchars">
										<span><?php echo JText::_('VRREVIEWSMINCHARS'); ?>&nbsp;</span>
										<span id="vrcommentminchars"><?php echo $min_comment_length; ?></span>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div class="rv-new-submit">
						<button type="submit" class="vr-review-btn" onClick="return validateReviewOnSubmit();">
							<?php echo JText::_('VRREVIEWSUBMITBUTTON'); ?>
						</button>
					</div>

				</div>

				<?php foreach( $this->request as $k => $v ) { 
					if( !empty($v) ) { ?>
						<input type="hidden" name="request[<?php echo $k; ?>]" value="<?php echo $v; ?>"/>
					<?php }
				} ?>

				<input type="hidden" name="id_tk_prod" value="<?php echo $item['id']; ?>"/>
				<input type="hidden" name="option" value="com_cleverdine"/>
				<input type="hidden" name="task" value="submit_review"/>

			</form>
		<?php } ?>

		<div class="vr-reviews-counts">
			<?php if( $ratingsCount !== null && $ratingsCount->count > 0 ) { ?>

				<?php for( $i = 5; $i > 0; $i-- ) { 
					$ratio = round($ratingsCount->ratings[$i] / $ratingsCount->count * 100);
					?>
					<div class="rv-rating-count-box">
						<div class="rv-rating-title"><?php echo JText::_('VRREVIEWSTAR'.$i); ?></div>
						<div class="rv-rating-progress">
							<div class="rv-rating-bar" data-width="<?php echo $ratio; ?>%"></div>
						</div>
						<div class="rv-rating-ratio"><?php echo $ratio; ?>%</div>
					</div>
				<?php } ?>

			<?php } ?>
		</div>

		<form action="<?php echo JRoute::_('index.php?option=com_cleverdine&view=revslist'); ?>" method="POST" name="vrrevsform" id="vrrevsform">

			<div class="vr-reviews-quicklist">

				<div class="vr-reviews-toolbar">

					<div class="rv-toolbar-field left">
						<div class="rv-toolbar-field-label"><?php echo JText::_('VRREVIEWSSORTBY'); ?></div>
						<div class="vre-tinyselect-wrapper rv-toolbar-field-value">
							<select name="sortby" class="vre-tinyselect" id="vrsortby">
								<option value="1" <?php echo ($this->request->sortby == '1' ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRREVIEWSORTBY1'); ?></option>
								<option value="2" <?php echo ($this->request->sortby == '2' ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRREVIEWSORTBY2'); ?></option>
								<option value="3" <?php echo ($this->request->sortby == '3' ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRREVIEWSORTBY3'); ?></option>
							</select>
						</div>
					</div>

					<div class="rv-toolbar-field right">
						<div class="rv-toolbar-field-label"><?php echo JText::_('VRREVIEWSFILTERBY'); ?></div>
						<div class="vre-tinyselect-wrapper rv-toolbar-field-value">
							<select name="filterstar" class="vre-tinyselect" id="vrfilterstar">
								<option value="0" <?php echo ($this->request->filterstar == '0' ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRREVIEWFILTERSTAR0'); ?></option>
								<?php for( $i = 5; $i > 0; $i-- ) { ?>
									<option value="<?php echo $i; ?>" <?php echo ($this->request->filterstar == $i ? 'selected="selected"' : ''); ?>>
										<?php echo JText::_('VRREVIEWFILTERSTAR'.$i); ?>
									</option>
								<?php } ?>
							</select>
						</div>
						<?php
						$all_langs = cleverdine::getKnownLanguages();
						?>
						<?php if (cleverdine::isReviewsLangFilter()) { ?>
							<div class="vre-tinyselect-wrapper rv-toolbar-field-value">
								<select name="filterlang" class="vre-tinyselect" id="vrfilterlang">
									<option value="" <?php echo ($this->request->filterlang == '' ? 'selected="selected"' : ''); ?>><?php echo JText::_('VRREVIEWSLANGSALL'); ?></option>
									<?php foreach ($all_langs as $langtag) { ?>
										<option value="<?php echo $langtag; ?>" <?php echo ($this->request->filterlang == $langtag ? 'selected="selected"' : ''); ?>><?php echo $langtag; ?></option>
									<?php } ?>
								</select>
							</div>
						<?php } ?>
					</div>

				</div>

				<?php if( !count($reviews) ) { ?>
					<div class="no-review"><?php echo JText::_('VRREVIEWSNOLEFT'); ?></div>
				<?php } else { ?>

					<?php foreach( $reviews as $review ) { 
						$ts_str = cleverdine::formatTimestamp('', $review['timestamp']);
						if( empty($ts_str) ) {
							$ts_str = JText::sprintf('VRDFWHEN', date($date_format, $review['timestamp']));
						}
						?>

						<div class="review-block">

							<div class="rv-top">

								<div class="rv-head-up">
									<div class="rv-rating">
										<?php for( $i = 1; $i <= 5; $i++ ) { ?>
											<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/images/'.($i <= $review['rating'] ? 'rating-star.png' : 'rating-star-no.png'); ?>"/>
										<?php } ?>
									</div>
									<div class="rv-title"><?php echo $review['title']; ?></div>
									<div class="rv-lang">
										<?php $country = explode('-', $review['langtag']); ?>
										<img src="<?php echo JUri::root().'components/com_cleverdine/assets/css/flags/'.strtolower($country[1]).'.png'; ?>"/>
									</div>
								</div>

								<div class="rv-head-down">
									<?php echo JText::sprintf(
										(!empty($review['comment']) ? 'VRREVIEWSUBHEAD' : 'VRREVIEWSUBHEAD2'), 
										'<strong>'.$review['name'].'</strong>', 
										$ts_str
									); ?>

									<?php if( $review['verified'] ) { ?>
										<div class="rv-verified"><?php echo JText::_('VRREVIEWVERIFIED'); ?></div>
									<?php } ?>
								</div>

							</div>

							<?php if( !empty($review['comment']) ) { ?>
								<div class="rv-middle">
									<?php echo $review['comment']; ?>
								</div>
							<?php } ?>

						</div>

					<?php }

				} ?>

			</div>

			<?php echo JHTML::_( 'form.token' ); ?>
			<div class="vr-list-pagination"><?php echo $reviewsHandler->getNavigationHTML($this->request); ?></div>
			
			<input type="hidden" name="id_tk_prod" value="<?php echo $this->request->id_tk_prod; ?>"/>
			<input type="hidden" name="option" value="com_cleverdine"/>
			<input type="hidden" name="view" value="revslist"/>
		</form>

	</div>
<?php } ?>

<script type="text/javascript">

	var RV_BOUNDS = {};
	var W_BOUNDS = {};

	var TO_RATE = true;
	var MAX_COMMENT_LENGTH = <?php echo $max_comment_length; ?>;
	var MIN_COMMENT_LENGTH = <?php echo $min_comment_length; ?>;
	var REVIEW_COMMENT_REQUIRED = <?php echo $is_comment_required ? 1 : 0; ?>;

	var STAR_DESC_MAP = [
		'<?php echo addslashes(JText::_('VRREVIEWSTARDESC1')); ?>',
		'<?php echo addslashes(JText::_('VRREVIEWSTARDESC2')); ?>',
		'<?php echo addslashes(JText::_('VRREVIEWSTARDESC3')); ?>',
		'<?php echo addslashes(JText::_('VRREVIEWSTARDESC4')); ?>',
		'<?php echo addslashes(JText::_('VRREVIEWSTARDESC5')); ?>'
	];

	var TIMER_START = null;

	jQuery(document).ready(function(){

		jQuery('#vrrevsform select').on('change', function(){
			jQuery('#vrrevsform').submit();
		});

		jQuery('.vr-ratingstar-box').on('click', function(){
			var id = jQuery(this).data('id');
			
			jQuery('.vr-ratingstar-box').removeClass('rating-nostar rating-hoverstar rating-yesstar');
			
			if( TO_RATE ) {
				jQuery(this).addClass('rating-yesstar');
				jQuery(this).siblings('.vr-ratingstar-box').each(function(){
					if( jQuery(this).data('id') < id ) {
						jQuery(this).addClass('rating-yesstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});
				
				jQuery('#vrreviewrating').val(id);
				jQuery('#vr-newrating-desc').text('<?php echo addslashes(JText::_('VRREVIEWSTARDESC0')); ?>');
			} else {
				jQuery(this).addClass('rating-hoverstar');
				jQuery(this).siblings('.vr-ratingstar-box').each(function(){
					if( jQuery(this).data('id') < id ) {
						jQuery(this).addClass('rating-hoverstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});
				
				jQuery('#vrreviewrating').val('');
				jQuery('#vr-newrating-desc').text(STAR_DESC_MAP[id-1]);
			}
			
			TO_RATE = !TO_RATE
		});
		
		jQuery('.vr-ratingstar-box').hover(function(){
			var id = jQuery(this).data('id');
			
			if( TO_RATE ) {
				jQuery('.vr-ratingstar-box').removeClass('rating-nostar rating-hoverstar rating-yesstar');
				
				jQuery(this).addClass('rating-hoverstar');
				jQuery(this).siblings('.vr-ratingstar-box').each(function(){
					if( jQuery(this).data('id') < id ) {
						jQuery(this).addClass('rating-hoverstar');
					} else {
						jQuery(this).addClass('rating-nostar');
					}
				});

				jQuery('#vr-newrating-desc').text(STAR_DESC_MAP[id-1]);
			}
			
		}, function(){
			
		});

		jQuery('#vrreviewcomment').on('keyup', function(e){
			jQuery('#vrcommentchars').text((MAX_COMMENT_LENGTH-jQuery(this).val().length));       
		});

		// animate ratings count

		RV_BOUNDS.y = jQuery('.vr-reviews-counts').offset().top;
		RV_BOUNDS.height = jQuery('.vr-reviews-counts').height();

		TIMER_START = new Date().getTime();

		jQuery(window).on('scroll', __debounce(
			windowScrollControl, 250
		));

		// fire scroll
		jQuery(window).trigger('scroll');

	});

	function windowScrollControl() {

		W_BOUNDS.y = jQuery(window).scrollTop();
		W_BOUNDS.height = jQuery(window).height();

		if( W_BOUNDS.y <= RV_BOUNDS.y && RV_BOUNDS.y + RV_BOUNDS.height <= W_BOUNDS.y + W_BOUNDS.height ) {

			jQuery(window).off('scroll');

			var delay = 1250;
			var diff = new Date().getTime() - TIMER_START;

			setTimeout(function(){
				jQuery('.vr-reviews-counts .rv-rating-progress .rv-rating-bar').each(function(){
					jQuery(this).css('width', jQuery(this).data('width'));
				});
			}, Math.max(0, delay-diff));

		}

	}

	function vrDisplayPostReview(btn) {
		jQuery(btn).remove();
		jQuery('#vr-new-review').fadeIn();
	}

	function validateReviewOnSubmit() {

		var ok = true;

		jQuery('#vrrevformpost .review-required').each(function(){
			if( jQuery(this).val().length == 0 ) {
				ok = false;
				jQuery(this).parent().prev().addClass('vrrequired');
			} else {
				jQuery(this).parent().prev().removeClass('vrrequired');
			}
		});
	
		var comment_length = jQuery('#vrreviewcomment').val().length;
		if( comment_length && ( comment_length < MIN_COMMENT_LENGTH || comment_length > MAX_COMMENT_LENGTH ) ) {
			ok = false;
			jQuery('#vrreviewcomment').parent().prev().addClass('vrrequired');
		}

		var rating_val = jQuery('#vrreviewrating').val();
		if( rating_val < 1 || rating_val > 5 ) {
			ok = false;
			jQuery('#vrreviewrating').parent().prev().addClass('vrrequired');
		}

		<?php if( JFactory::getUser()->guest ) { ?>

			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			if( !re.test(jQuery('#vrreviewusermail').val()) ) {
				ok = false;
				jQuery('#vrreviewusermail').parent().prev().addClass('vrrequired');
			}

		<?php } ?>

		return ok;
	}

</script>