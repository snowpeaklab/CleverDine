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

/**
 * cleverdine component helper.
 */
abstract class cleverdine {
	
	public static function getRestaurantName( $skipsession = false ) {
		return self::getFieldFromConfig( 'restname', 'vrGetRestaurantName', $skipsession );
	}
	
	public static function getAdminMailList( $skipsession = false ) {
		$admin_mail_list = self::getFieldFromConfig( 'adminemail', 'vrGetAdminMail', $skipsession );
		if( strlen($admin_mail_list) > 0 ) {
			$admin_mail_list = explode(',', $admin_mail_list);
			for( $i = 0; $i < count($admin_mail_list); $i++ ) {
				$admin_mail_list[$i] = trim($admin_mail_list[$i]);
			}
			return $admin_mail_list;
		}
		return array();
	}
	
	public static function getAdminMail( $skipsession = false ) {
		return self::getFieldFromConfig( 'adminemail', 'vrGetAdminMail', $skipsession );
	}
	
	public static function getSenderMail( $skipsession = false ) {
		$sender = self::getFieldFromConfig( 'senderemail', 'vrGetSenderMail', $skipsession );
		if( empty($sender) ) {
			$list = self::getAdminMailList($skipsession);
			if( count($list) ) {
				$sender = $list[0];
			}
		}

		return $sender;
	}
	
	public static function getSendMailWhen( $skipsession = false ) {
		return array(
			"customer" => intval(self::getFieldFromConfig( 'mailcustwhen', 'vrGetSendMailCustomerWhen', $skipsession )),
			"operator" => intval(self::getFieldFromConfig( 'mailoperwhen', 'vrGetSendMailOperatorWhen', $skipsession )),
			"admin" => intval(self::getFieldFromConfig( 'mailadminwhen', 'vrGetSendMailAdminWhen', $skipsession ))
		);
	}

	public static function getMailTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'mailtmpl', 'vrGetMailTemplateName', $skipsession );
	}

	public static function getMailAdminTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'adminmailtmpl', 'vrGetMailAdminTemplateName', $skipsession );
	}

	public static function getMailCancellationTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'cancmailtmpl', 'vrGetMailCancellationTemplateName', $skipsession );
	}

	public static function getAdminMailTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'adminmailtmpl', 'vrGetAdminMailTemplateName', $skipsession );
	}
	
	public static function getCompanyLogoPath( $skipsession = false ) {
		return self::getFieldFromConfig( 'companylogo', 'vrGetCompanyLogo', $skipsession );
	}

	public static function isRestaurantEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'enablerestaurant', 'vrGetEnableRestaurant', $skipsession ) );
	}

	public static function isTakeAwayEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'enabletakeaway', 'vrGetEnableTakeaway', $skipsession ) );
	}
	
	public static function getDateFormat( $skipsession = false ) {
		return self::getFieldFromConfig( 'dateformat', 'vrGetDateFormat', $skipsession );
	}
	
	public static function getTimeFormat( $skipsession = false ) {
		return self::getFieldFromConfig( 'timeformat', 'vrGetTimeFormat', $skipsession );
	}
	
	public static function isMultilanguage( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'multilanguage', 'vrGetMultilanguage', $skipsession ));
	}
	
	public static function getCurrencySymb( $skipsession = false ) {
		return self::getFieldFromConfig( 'currencysymb', 'vrGetCurrencySymb', $skipsession );
	}
	
	public static function getCurrencyName( $skipsession = false ) {
		return self::getFieldFromConfig( 'currencyname', 'vrGetCurrencyName', $skipsession );
	}
	
	public static function getCurrencySymbPosition( $skipsession = false ) {
		return self::getFieldFromConfig( 'symbpos', 'vrGetCurrencySymbPosition', $skipsession );
	}

	public static function getCurrencyDecimalSeparator( $skipsession = false ) {
		return self::getFieldFromConfig( 'currdecimalsep', 'vrGetCurrencyDecimalSeparator', $skipsession );
	}

	public static function getCurrencyThousandsSeparator( $skipsession = false ) {
		return self::getFieldFromConfig( 'currthousandssep', 'vrGetCurrencyThousandsSeparator', $skipsession );
	}

	public static function getCurrencyDecimalDigits( $skipsession = false ) {
		return self::getFieldFromConfig( 'currdecimaldig', 'vrGetCurrencyDecimalDigits', $skipsession );
	}
	
	public static function isContinuosOpeningTime( $skipsession = false ) {
		return !intval( self::getFieldFromConfig( 'opentimemode', 'vrGetContinuosOpeningTime', $skipsession ) );
	}
	
	public static function getMinuteIntervals( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'minuteintervals', 'vrGetMinuteIntervals', $skipsession ) );
	}
	
	public static function getAverageTimeStay( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'averagetimestay', 'vrGetAverageTimeStay', $skipsession ) );
	}
	
	public static function getBookingMinutesRestriction( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'bookrestr', 'vrGetBookingMinutesRestriction', $skipsession ) );
	}
	
	public static function isLoadJQuery( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'loadjquery', 'vrGetLoadJQuery', $skipsession ) );
	}

	public static function getGoogleMapsApiKey( $skipsession = false ) {
		return self::getFieldFromConfig( 'googleapikey', 'vrGetGoogleMapsApiKey', $skipsession );
	}

	public static function isReviewsEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'enablereviews', 'vrGetReviewsEnabled', $skipsession) );
	}
	
	public static function isTakeAwayReviewsEnabled( $skipsession = false ) {
		return self::isReviewsEnabled($skipsession) && intval( self::getFieldFromConfig( 'revtakeaway', 'vrGetReviewsTakeAwayEnabled', $skipsession) );
	}
	
	public static function isReviewsCommentRequired( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revcommentreq', 'vrGetReviewsCommentRequired', $skipsession) );
	}
	
	public static function getReviewsCommentMinLength( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revminlength', 'vrGetReviewsCommentMinLength', $skipsession) );
	}
	
	public static function getReviewsCommentMaxLength( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revmaxlength', 'vrGetReviewsCommentMaxLength', $skipsession) );
	}
	
	public static function getReviewsListLimit( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revlimlist', 'vrGetReviewsListLimit', $skipsession) );
	}
	
	public static function isReviewsAutoPublished( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revautopublished', 'vrGetReviewsAutoPublished', $skipsession) );
	}

	public static function isReviewsLangFilter( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revlangfilter', 'vrGetReviewsLangFilter', $skipsession) );
	}
	
	public static function getReviewsLeaveMode( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'revleavemode', 'vapGetReviewsLeaveMode', $skipsession) );
	}
	
	public static function getFromOpeningHour( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'hourfrom', 'vrGetFromOpeningHour', $skipsession ) );
	}
	
	public static function getToOpeningHour( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'hourto', 'vrGetToOpeningHour', $skipsession ) );
	}
	
	public static function getMinimumPeople( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'minimumpeople', 'vrGetMinimumPeople', $skipsession ) );
	}
	
	public static function getMaximumPeople( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'maximumpeople', 'vrGetMaximumPeople', $skipsession ) );
	}
	
	public static function isShowLargePartyLabel( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'largepartylbl', 'vrGetLargePartyLabel', $skipsession ) );
	}
	
	public static function getLargePartyURL( $skipsession = false ) {
		return self::getFieldFromConfig( 'largepartyurl', 'vrGetLargePartyURL', $skipsession );
	}
	
	public static function getReservationRequirements( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'reservationreq', 'vrGetReservationRequirements', $skipsession ) );
	}

	public static function getTaxesRatio( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'taxesratio', 'vrGetTaxesRatio', $skipsession ) );
	}

	public static function isTaxesUsable( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'usetaxes', 'vrGetUseTaxes', $skipsession ) );	
	}
	
	public static function getDepositPerReservation( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'resdeposit', 'vrGetDepositReservation', $skipsession ) );
	}
	
	public static function getDepositPerPerson( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'costperperson', 'vrGetDepositPerPerson', $skipsession ) );
	}
	
	public static function getChooseMenu( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'choosemenu', 'vrGetChooseMenu', $skipsession ) );
	}
	
	public static function getTablesLockedTime( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tablocktime', 'vrGetTablesLockedTime', $skipsession ) );
	}

	public static function getLoginRequirements( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'loginreq', 'vrGetLoginRequirements', $skipsession ) );
	}

	public static function isRegistrationEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'enablereg', 'vrGetEnableRegistration', $skipsession ) );
	}

	public static function getDefaultStatus( $skipsession = false ) {
		return self::getFieldFromConfig( 'defstatus', 'vrGetDefaultStatus', $skipsession );
	}
	
	public static function isCancellationEnabled( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'enablecanc', 'vrGetEnableCancellation', $skipsession ));
	}

	public static function getCancellationReason( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'cancreason', 'vrGetCancellationReason', $skipsession ));
	}
	
	public static function getCancelBeforeTime( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'canctime', 'vrGetCancelBeforeTime', $skipsession ));
	}

	public static function getApplyCouponType( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'applycoupon', 'vrGetApplyCoupon', $skipsession ));
	}

	public static function isOnDashboard( $skipsession = false ) {
		return self::isRestaurantEnabled($skipsession) && intval( self::getFieldFromConfig( 'ondashboard', 'vrGetOnDashboard', $skipsession ) );
	}
	
	public static function getDashRefreshTime( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'refreshdash', 'vrGetDashboardRefreshTime', $skipsession ) );
	}

	public static function isFooterVisible( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'showfooter', 'vrGetShowFooter', $skipsession ) );
	}
	
	public static function isReservationsAllowed( $skipsession = false ) {
		return self::isReservationsAllowedOn(time(), $skipsession);
	}
	
	public static function isReservationsAllowedOn( $timestamp, $skipsession = false ) {
		// force always skipsession
		return ( intval(self::getFieldFromConfig( 'stopuntil', 'vrGetStopUntil', true )) <= $timestamp );
	}

	public static function isTakeAwayReservationsAllowed( $skipsession = false ) {
		return self::isTakeAwayReservationsAllowedOn(time(), $skipsession);
	}
	
	public static function isTakeAwayReservationsAllowedOn( $timestamp, $skipsession = false ) {
		// force always skipsession
		return ( intval(self::getFieldFromConfig( 'tkstopuntil', 'vrGetTakeAwayStopUntil', true )) <= $timestamp );
	}
	
	public static function getTakeAwayMinimumCostPerOrder( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'mincostperorder', 'vrGetTakeawayMinimumCostPerOrder', $skipsession ) );
	}
	
	public static function getTakeAwayMinuteInterval( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkminint', 'vrGetTakeawayMinuteInterval', $skipsession ) );
	}
	
	public static function getTakeAwayAsapAfter( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'asapafter', 'vrGetTakeawayAsapAfter', $skipsession ) );
	}
	
	public static function getTakeAwayMealsPerInterval( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'mealsperint', 'vrGetTakeawayMealsPerInterval', $skipsession ) );
	}

	public static function getTakeAwayMaxMeals( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkmaxitems', 'vrGetTakeawayMaxMeals', $skipsession ) );
	}

	public static function getTakeAwayProductsDescriptionLength( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkproddesclength', 'vrGetTakeawayProductsDescriptionLength', $skipsession ) );
	}

	public static function getTakeAwayUseOverlay( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkuseoverlay', 'vrGetTakeawayUseOverlay', $skipsession ) );
	}

	public static function isTakeAwayDateAllowed( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkallowdate', 'vrGetTakeawayDateAllowed', $skipsession ) );
	}

	public static function isTakeAwayLiveOrders( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkwhenopen', 'vrGetTakeawayLiveOrders', $skipsession ) );
	}

	public static function isTakeAwayDeliveryServiceEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'deliveryservice', 'vrGetTakeawayDeliveryService', $skipsession ) );
	}

	public static function getTakeAwayDeliveryServiceAddPrice( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'dsprice', 'vrGetTakeawayDeliveryServiceAddPrice', $skipsession ) );
	}
	
	public static function getTakeAwayDeliveryServicePercentOrTotal( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'dspercentot', 'vrGetTakeawayDeliveryServicePercentOrTotal', $skipsession ) );
	}

	public static function getTakeAwayPickupAddPrice( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'pickupprice', 'vrGetTakeawayPickupAddPrice', $skipsession ) );
	}
	
	public static function getTakeAwayPickupPercentOrTotal( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'pickuppercentot', 'vrGetTakeawayPickupPercentOrTotal', $skipsession ) );
	}
	
	public static function getTakeAwayFreeDeliveryService( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'freedelivery', 'vrGetTakeawayFreeDeliveryService', $skipsession ) );
	}

	public static function getTakeAwayConfirmItemID( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkconfitemid', 'vrGetTakeAwayConfirmItemID', $skipsession ) );
	}
	
	public static function getTakeAwayOrdersLockedTime( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'tklocktime', 'vrGetTakeawayOrdersLockedTime', $skipsession ) );
	}
	
	public static function getTakeAwayNotes( $skipsession = false ) {
		return self::getFieldFromConfig( 'tknote', 'vrGetTakeawayNotes', $skipsession );
	}
	
	public static function getTakeAwayTaxesRatio( $skipsession = false ) {
		return floatval( self::getFieldFromConfig( 'tktaxesratio', 'vrGetTakeawayTaxesRatio', $skipsession ) );
	}
	
	public static function isTakeAwayTaxesVisible( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkshowtaxes', 'vrGetTakeawayShowTaxes', $skipsession ) );
	}

	public static function isTakeAwayTaxesUsable( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkusetaxes', 'vrGetTakeawayUseTaxes', $skipsession ) );	
	}

	public static function getTakeAwayLoginRequirements( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkloginreq', 'vrGetTakeAwayLoginRequirements', $skipsession ) );
	}

	public static function isTakeAwayRegistrationEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkenablereg', 'vrGetTakeAwayEnableRegistration', $skipsession ) );
	}

	public static function getTakeAwayDefaultStatus( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkdefstatus', 'vrGetTakeAwayDefaultStatus', $skipsession );
	}
	
	public static function isTakeAwayCancellationEnabled( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'tkenablecanc', 'vrGetTakeAwayEnableCancellation', $skipsession ));
	}

	public static function getTakeAwayCancellationReason( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'tkcancreason', 'vrGetTakeAwayCancellationReason', $skipsession ));
	}
	
	public static function getTakeAwayCancelBeforeTime( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'tkcanctime', 'vrGetTakeAwayCancelBeforeTime', $skipsession ));
	}

	public static function getTakeAwayOriginAddresses( $skipsession = false ) {
		return json_decode(self::getFieldFromConfig( 'tkaddrorigins', 'vrGetTakeawayOriginAddresses', $skipsession ));
	}
	
	public static function getTakeawaySendMailWhen( $skipsession = false ) {
		return array(
			"customer" => intval(self::getFieldFromConfig( 'tkmailcustwhen', 'vrGetTakeawaySendMailCustomerWhen', $skipsession )),
			"operator" => intval(self::getFieldFromConfig( 'tkmailoperwhen', 'vrGetTakeawaySendMailOperatorWhen', $skipsession )),
			"admin" => intval(self::getFieldFromConfig( 'tkmailadminwhen', 'vrGetTakeawaySendMailAdminWhen', $skipsession ))
		);
	}

	public static function getTakeawayMailTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkmailtmpl', 'vrGetTakeawayMailTemplateName', $skipsession );
	}

	public static function getTakeawayMailAdminTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkadminmailtmpl', 'vrGetTakeawayMailAdminTemplateName', $skipsession );
	}

	public static function getTakeawayMailCancellationTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkcancmailtmpl', 'vrGetTakeawayMailCancellationTemplateName', $skipsession );
	}

	public static function getTakeawayMailReviewTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkreviewmailtmpl', 'vrGetTakeawayMailReviewTemplateName', $skipsession );
	}

	public static function getClosingDays( $skipsession = false ) {
		$_str = self::getFieldFromConfig( 'closingdays', 'vrGetClosingDays', true ); // always skip session
		if( strlen($_str) > 0 ) {
			$cd = explode( ';;', $_str );
			for( $i = 0, $n = count($cd); $i < $n; $i++ ) {
				$_app = explode( ':', $cd[$i] );
				$cd[$i] = array( 'ts' => $_app[0], 'date' => date( self::getDateFormat($skipsession), $_app[0] ), 'freq' => $_app[1] );
			}
			return $cd;
		}
		return array();
	}
	
	public static function getSmsApi( $skipsession = false ) {
		return self::getFieldFromConfig( 'smsapi', 'vrGetSmsApi', $skipsession );
	}
	
	public static function getSmsApiWhen( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'smsapiwhen', 'vrGetSmsApiWhen', $skipsession ));
	}
	
	public static function getSmsApiTo( $skipsession = false ) {
		return intval(self::getFieldFromConfig( 'smsapito', 'vrGetSmsApiTo', $skipsession ));
	}
	
	public static function getSmsApiAdminPhoneNumber( $skipsession = false ) {
		return self::getFieldFromConfig( 'smsapiadminphone', 'vrGetSmsApiAdminPhoneNumber', $skipsession );
	}
	
	public static function getSmsApiFields( $skipsession = false ) {
		$_str = self::getFieldFromConfig( 'smsapifields', 'vrGetSmsApiFields', $skipsession );
		if( !empty($_str) ) {
			return json_decode($_str, true);
		}
		return array();
	}
	
	public static function getSmsDefaultCustomersText( $skipsession = false ) {
		return self::getFieldFromConfig( 'smstextcust', 'vrGetSmsCustomersText', $skipsession );
	}
	
	public static function getListableFields( $skipsession = false ) {
		$str = self::getFieldFromConfig('listablecols', 'vrGetListableColumns', $skipsession);
		if( empty($str) ) {
			return array();
		}
		
		return explode(",", $str);
	}
	
	public static function getTakeAwayListableFields( $skipsession = false ) {
		$str = self::getFieldFromConfig('tklistablecols', 'vrGetTakeAwayListableColumns', $skipsession);
		if( empty($str) ) {
			return array();
		}
		
		return explode(",", $str);
	}

	public static function isTakeAwayStockEnabled( $skipsession = false ) {
		return intval( self::getFieldFromConfig( 'tkenablestock', 'vrGetEnableTakeAwayStock', $skipsession ) );
	}

	public static function getTakeawayStockMailTemplateName( $skipsession = false ) {
		return self::getFieldFromConfig( 'tkstockmailtmpl', 'vrGetTakeawayStockMailTemplateName', $skipsession );
	}

	public static function getPrintOrdersText( $skipsession = false ) {
		$str = self::getFieldFromConfig( 'printorderstext', 'vrGetPrintOrdersText', $skipsession );

		if( strlen($str) ) {
			return json_decode($str, true);
		}

		return array('header' => '', 'footer' => '');
	}

	public static function isApiFrameworkEnabled( $skipsession = false ) {
		// always skip session
		return intval(self::getFieldFromConfig( 'apifw', 'vrGetApiFrameworkEnabled', true ));
	}

	public static function getApiFrameworkMaxFailureAttempts( $skipsession = false ) {
		// always skip session
		return intval(self::getFieldFromConfig( 'apimaxfail', 'vrGetApiFrameworkMaxFailureAttempts', true ));
	}

	public static function getApiFrameworkLogMode( $skipsession = false ) {
		// always skip session
		return intval(self::getFieldFromConfig( 'apilogmode', 'vrGetApiFrameworkLogMode', true ));
	}

	public static function getApiFrameworkLogFlush( $skipsession = false ) {
		// always skip session
		return intval(self::getFieldFromConfig( 'apilogflush', 'vrGetApiFrameworkLogFlush', true ));
	}
	
	private static function getFieldFromConfig( $param, $session_key, $skipsession ) {

		/*
		if( $skipsession ) {
			$dbo = JFactory::getDbo();
			$q = "SELECT `setting` FROM `#__cleverdine_config` WHERE `param`=".$dbo->quote($param)." LIMIT 1;";
			$dbo->setQuery($q);
			$dbo->execute();
			return $dbo->loadResult();
		} else {
			$session = JFactory::getSession();
			if( !$session->has($session_key, 'vreconfig') ) {
				$dbo = JFactory::getDbo();
				$q = "SELECT `setting` FROM `#__cleverdine_config` WHERE `param`=".$dbo->quote($param)." LIMIT 1;";
				$dbo->setQuery($q);
				$dbo->execute();
				$sval = $dbo->loadResult();
				$session->set($session_key, $sval, 'vreconfig');
			} else {
				$sval = $session->get($session_key, '', 'vreconfig');
			}
			
			return $sval;
		}
		*/

		$cache = !$skipsession;

		$config = UIFactory::getConfig(0, $cache);

		return $config->get($param, '', 'string');
	}
	
	public static function loadCartLibrary() {

		/**
		 * @deprecated 	1.7 	Use TakeAwayCart::getInstance() to get cart object.
		 */
		UILoader::import('library.cart.core');

		UILoader::import('library.cart.cart');
		
		UILoader::import('library.cart.item');
		UILoader::import('library.cart.itemgroup');
		UILoader::import('library.cart.topping');
		
		UILoader::import('library.cart.deals');
		UILoader::import('library.cart.discount');

		//UILoader::import('library.deals.handler');
	}

	public static function loadDealsLibrary() {
		UILoader::import('library.deals.handler');
	}

	public static function loadBankingLibrary($files = array()) {
		if( !count($files) || in_array('creditcard', $files) ) {
			UILoader::import('library.banking.creditcard');
		}
	}

	public static function loadCryptLibrary() {
		UILoader::import('library.crypt.cipher');
	}

	public static function loadFrameworkApis() {
		UILoader::import('library.apislib.autoload');
		UILoader::import('library.apislib.framework');
		UILoader::import('library.apislib.login');
	}

	public static function flushApiLogs() {

		$factor = self::getApiFrameworkLogFlush();
		$now 	= time();

		if( $factor > 0 ) {

			$dbo = JFactory::getDbo();

			$q = "DELETE FROM `#__cleverdine_api_login_logs` WHERE (`createdon` + 86400 * $factor) < $now;";
			$dbo->setQuery($q);
			$dbo->execute();

		}

	}
	
	public static function canUserCancelOrder($checkin_ts, $type=0, $skip_session=false) {
		if( $type == 0 ) { // restaurant
			return (self::isCancellationEnabled($skip_session) && time()+self::getCancelBeforeTime($skip_session)*60*60*24 < $checkin_ts );
		}
		
		return (self::isTakeAwayCancellationEnabled($skip_session) && time()+self::getTakeAwayCancelBeforeTime($skip_session)*60*60*24 < $checkin_ts );
	}

	// FRONT BUILDING

	public static function prepareContent($page) {
		$menu = JFactory::getApplication()->getMenu()->getActive();
		if( isset($menu->params) ) {
		
			if( intval($menu->params->get('show_page_heading')) == 1 && strlen($menu->params->get('page_heading')) ) {
				echo '<div class="page-header'.(strlen($clazz = $menu->params->get('pageclass_sfx')) ? ' '.$clazz : '' ).'"><h1>'.$menu->params->get('page_heading').'</h1></div>';
			}

			if( strlen($menu->params->get('menu-meta_description')) ) {
				$page->document->setDescription($menu->params->get('menu-meta_description'));
			}

			if( strlen($menu->params->get('menu-meta_keywords')) ) {
				$page->document->setMetadata('keywords', $menu->params->get('menu-meta_keywords'));
			}

			if( strlen($menu->params->get('robots')) ) {
				$page->document->setMetadata('robots', $menu->params->get('robots'));
			}

		}
	}
	
	public static function userIsLogged () {
		$user = JFactory::getUser();
		if( $user->guest ) {
			return false;
		} else {
			return true;
		}
	}
	
	public static function printPriceCurrencySymb( $price, $curr_symb='', $pos='', $skip_session=false) {
		if( empty($curr_symb) ) {
			$curr_symb = self::getCurrencySymb($skip_session);
		}
		
		if( empty($pos) ) {
			$pos = self::getCurrencySymbPosition($skip_session);
		}

		$dec_separator = self::getCurrencyDecimalSeparator($skip_session);
		$tho_separator = self::getCurrencyThousandsSeparator($skip_session);
		$dec_digits = self::getCurrencyDecimalDigits($skip_session);
		
		$price = floatval($price);

		// AFTER PRICE
		if( $pos == 1 ) {
			return number_format($price, $dec_digits, $dec_separator, $tho_separator).' '.$curr_symb;
		}
		// BEFORE PRICE
		return $curr_symb.' '.number_format($price, $dec_digits, $dec_separator, $tho_separator);
	}
	
	public static function load_css_js() {
		$document = JFactory::getDocument();
		
		$vik = new VikApplication(VersionListener::getID());
		
		if( self::isLoadJQuery() ) {
			$vik->loadFramework('jquery.framework');
			$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery-1.11.1.min.js');
		}
		
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery-ui-1.11.1.min.js');
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/cleverdine.js'); 
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/jquery-ui.min.css');
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/cleverdine.css');
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/cleverdine-mobile.css');
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/vre-custom.css');
	}
	
	public static function load_fancybox() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		// $vik->loadFramework('jquery.framework'); decomment this line if don't load jQuery before this function
		
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/jquery.fancybox.js'); 
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/css/jquery.fancybox.css');
	}

	public static function load_complex_select() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		//$vik->loadFramework('jquery.framework');
		
		$vik->addScript(JUri::root().'components/com_cleverdine/assets/js/select2/select2.min.js');
		$document->addStyleSheet(JUri::root().'components/com_cleverdine/assets/js/select2/select2.css');
	}

	public static function load_googlemaps() {
		$document = JFactory::getDocument();
		$vik = new VikApplication(VersionListener::getID());
		
		// $vik->loadFramework('jquery.framework'); decomment this line if don't load jQuery before this function

		$key = self::getGoogleMapsApiKey(strpos(JUri::current(), '/administrator/') !== false);
		
		$vik->addScript('https://maps.google.com/maps/api/js'.(!empty($key) ? "?key=$key" : "")); 
	}

	public static function load_font_awesome() {
		JFactory::getDocument()->addStyleSheet( JUri::root() . 'administrator/components/com_cleverdine/assets/css/font-awesome/css/font-awesome.min.css' );
	}

	public static function getLoginReturnURL($url = '', $xhtml = false) {

		if( empty($url) ) {
			// get current URL
			$url = JUri::current();

			$qs = JFactory::getApplication()->input->server->get('QUERY_STRING', '', 'string');
			// concat query string is not empty
			return $url . (strlen($qs) ? '?'.$qs : '');
		}
		
		// parse given URL
		$parts = parse_url(JUri::root());
		// build host
		$host = (!empty($parts['scheme']) ? $parts['scheme'] . '://' : '') . (!empty($parts['host']) ? $parts['host'] : '');
		// concat host (use trailing slash if not exists) and routed URL (remove first slash if exists)
		return $host.(!strlen($host) || $host[strlen($host)-1] != '/' ? '/' : '').(strlen($route = JRoute::_($url, $xhtml)) && $route[0] == '/' ? substr($route, 1) : $route);

	}
	
	public static function isRequestReservationValid($args) {
		$hour = null;
		$min = null;
		
		if( empty($args['date']) ) {
			return 1;
		}
		
		if( empty($args['hourmin']) ) {
			return 2;
		} else {
			$app = explode(':',$args['hourmin']);
			if( count( $app ) != 2 ) {
				return 2;
			}
			
			$hour = intval($app[0]);
			$min = intval($app[1]);
			
			if( !self::isHourBetweenShifts($hour, $min, 1) || !self::isMinuteAnInterval($min) ) {
				return 3;
			}
		}
		
		if( empty($args['people']) || $args['people'] < self::getMinimumPeople() || $args['people'] > self::getMaximumPeople() ) {
			return 4;
		}
		
		// check date
		
		$now = time();
		
		$_date = self::createTimestamp($args['date'],$hour,$min);
		
		if( $now > $_date ) {
			return 5;
		}
		
		if( $now+self::getBookingMinutesRestriction()*60 > $_date ) {
			return 6;
		}
		
		return 0;
	}
	
	public static function getResponseFromReservationRequest($resp) {
		$msg = array( 
				'', 
				'VRRESERVATIONREQUESTMSG1', 
				'VRRESERVATIONREQUESTMSG2',
				'VRRESERVATIONREQUESTMSG3',
				'VRRESERVATIONREQUESTMSG4',
				'VRRESERVATIONREQUESTMSG5',
				JText::sprintf('VRRESERVATIONREQUESTMSG6', self::getBookingMinutesRestriction()),
		 );
		
		return $msg[$resp];
	}
	
	public static function formatTimestamp($dt_f, $ts) {
		
		$now = time();
		if( abs($now-$ts) < 60 ) {
			return JText::_('VRDFNOW');
		}
		
		$diff = ($now-$ts);
		
		$minutes = abs($diff)/60;
		if( $minutes < 60 ) {
			$minutes = floor($minutes);
			return JText::sprintf('VRDFMINS'.($diff>0 ? 'AGO' : 'AFT'), $minutes);
		}
		
		$hours = $minutes/60;
		if( $hours < 24 ) {
			$hours = floor($hours);
			if( $hours == 1 ) {
				return JText::_('VRDFHOUR'.($diff>0 ? 'AGO' : 'AFT'));
			}
			return JText::sprintf('VRDFHOURS'.($diff>0 ? 'AGO' : 'AFT'), $hours);
		}
		
		$days = $hours/24;
		if( $days < 7 ) {
			$days = floor($days);
			if( $days == 1 ) {
				return JText::_('VRDFDAY'.($diff>0 ? 'AGO' : 'AFT'));
			}
			return JText::sprintf('VRDFDAYS'.($diff>0 ? 'AGO' : 'AFT'), $days);
		}
		
		$weeks = $days/7;
		if( $weeks < 3 ) {
			$weeks = floor($weeks);
			if( $weeks == 1 ) {
				return JText::_('VRDFWEEK'.($diff>0 ? 'AGO' : 'AFT'));
			}
			return JText::sprintf('VRDFWEEKS'.($diff>0 ? 'AGO' : 'AFT'), $weeks);
		}
		
		return date($dt_f, $ts);
	}

	public static function minutesToStr($minutes) {
		$min_str = array(JText::_('VRSHORTCUTMINUTE'));
		
		$hours_str = array( JText::_('VRFORMATHOUR'), JText::_('VRFORMATHOURS') );
		$days_str = array( JText::_('VRFORMATDAY'), JText::_('VRFORMATDAYS') );
		$weeks_str = array( JText::_('VRFORMATWEEK'), JText::_('VRFORMATWEEKS') );
		
		$comma_char = JText::_('VRFORMATCOMMASEP');
		$and_char = JText::_('VRFORMATANDSEP');
		
		$is_negative = ($minutes < 0 ? 1 : 0);
		$minutes = abs($minutes);
		
		$format = "";

		while( $minutes >= 60 ) {
			$app_str = "";
			if( $minutes >= 10080 ) { // weeks
				$val = intval($minutes/10080);
				$app_str = $val.' '.$weeks_str[($val > 1)]; // if greater than 1 -> multiple, otherwise single
				$minutes = $minutes%10080;
			} 
			else if( $minutes >= 1440 ) { // days
				$val = intval($minutes/1440);
				$app_str = $val.' '.$days_str[$val > 1]; // if greater than 1 -> multiple, otherwise single
				$minutes = $minutes%1440;
			} else { // hours
				$val = intval($minutes/60);
				$app_str = $val.' '.$hours_str[$val > 1]; // if greater than 1 -> multiple, otherwise single
				$minutes = $minutes%60;
			}
			
			$sep = '';
			if( $minutes > 0 ) {
				$sep = $comma_char;
			} else if( $minutes == 0 ) {
				$sep = " $and_char";
			}
			
			$format .= (!empty($format) ? $sep.' ' : '').$app_str;
		}
		
		if( $minutes > 0 ) {
			$format .= (!empty($format) ? " $and_char " : '').$minutes.' '.$min_str[0];
		}
		
		if( $is_negative ) {
			$format = '-'.$format;
		}
			
		return $format;
	}

	public static function getPeopleAt($ts) {
		$dbo = JFactory::getDbo();
		
		$avg = self::getAverageTimeStay()*60;
		
		$q = "SELECT SUM(`r`.`people`) FROM `#__cleverdine_reservation` AS `r` 
		WHERE (
			( `r`.`checkin_ts` < $ts AND $ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $ts+$avg AND $ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` < $ts AND $ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` > $ts AND $ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
			( `r`.`checkin_ts` = $ts AND $ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
		);";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			return $dbo->loadResult();
		}
		
		return 0;
	}

	public static function getCssGradientFromStatuses($list = array(), $direction = 'right') {
		if( count(array_keys($list)) <= 1 ) {
			return false;
		}

		arsort($list);

		$total_count = 0;

		foreach( $list as $status => $count ) {
			$total_count += $count;
		}

		$rgb_css = '';

		foreach( $list as $status => $count ) {
			 $rgba = array();

			 if( $status == 'CONFIRMED' ) {
			 	$rgba = array(56, 200, 112, 1);
			 } else {
			 	$rgba = array(233, 184, 44, 1);
			 }

			 $perc = $count * 100 / $total_count;

			 $rgb_css .= (!empty($rgb_css) ? ',' : '').'rgba('.$rgba[0].','.$rgba[1].','.$rgba[2].','.$rgba[3].') '.$perc.'%';
		}

		return 	"background: -webkit-linear-gradient($direction,$rgb_css);".
				"background: -o-linear-gradient($direction,$rgb_css);".
				"background: -moz-linear-gradient($direction,$rgb_css);".
				"background: linear-gradient(to $direction,$rgb_css);";


	}
	
	/**
	 * args = array( date, hourmin, people, table, menus )
	 */
	public static function validateSelectedMenus($args) {
		$hourmin = explode(':', $args['hourmin']);
		$args['hour'] = $hourmin[0];
		$args['min'] = $hourmin[1];
		
		$menus = self::getAllAvailableMenusOn($args);
		
		if( count($menus) == 0 ) {
			return true;
		}
		
		$tot_q = 0;
		foreach( $args['menus'] as $id => $quantity ) {
			$ok = false;
			for( $i = 0; $i < count($menus) && !$ok; $i++ ) {
				if( $id == $menus[$i]['id'] ) {
					$ok = true;
				}
			}
			
			if( !$ok ) {
				return false;
			}
			
			$tot_q += $quantity;
		}
		
		return ( $tot_q == $args['people'] );
	}
	
	public static function createTimestamp($date, $hour, $min, $skip_session=false) {
		$date_format = self::getDateFormat($skip_session);

		if (JFactory::getDbo()->getNullDate() == $date) {
			return -1;
		}

		$df_separator = $date_format[1]; // second char of $date_format can be only ['/', '.', '-']

		$formats = explode($df_separator, $date_format);
		$d_exp = explode($df_separator, $date);
		
		if( count($d_exp) != 3 ) {
			return -1;
		}
		
		$_attr = array();
		for( $i = 0, $n = count( $formats ); $i < $n; $i++ ) {
			$_attr[$formats[$i]] = $d_exp[$i];
		}
		
		return mktime(intval( $hour ), intval( $min ), 0, intval( $_attr['m'] ), intval( $_attr['d'] ), intval( $_attr['Y'] ) );
		
	}
	
	public static function isHourBetweenShifts($hour, $min, $group=1, $skip_session=false) {
		
		$isValid = false;
		
		if( self::isContinuosOpeningTime() ) {
			if( self::getFromOpeningHour() <= self::getToOpeningHour() ) {
				if( $hour >= self::getFromOpeningHour() && $hour <= self::getToOpeningHour() ) {
					return true;
				}
			} else {
				if( ( $hour >= 0 && $hour <= self::getToOpeningHour() ) || ( $hour >= self::getFromOpeningHour() && $hour <= 23 ) ) {
					return true;
				}
			}
		} else {
			
			$_app = $hour*60+$min;
			
			$shifts = self::getWorkingShifts( $group, $skip_session );
			foreach( $shifts as $s ) {
				if( $s['from'] <= $_app && $_app <= $s['to'] ) {
					return true;
				}
			}
			
		}
		
		return false;
	}
	
	public static function getFirstAvailableHour() {
		if( self::isContinuosOpeningTime() ) {
			return self::getFromOpeningHour().':0';
		} else {
			$dbo = JFactory::getDbo();
			$q = "SELECT MIN(`from`) AS `from` FROM `#__cleverdine_shifts`;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
				$h = intval($row['from']/60);
				$m = $row['from']%60;
				return $h.':'.$m;
			} 
		}
		
		return -1;
	}
	
	public static function isMinuteAnInterval($minute) {
		$min = self::getMinuteIntervals();
		for( $i = 0; $i < 60; $i+=$min ) {
			if( $i == $minute ) {
				return true;
			}
		}
		return false;
	}
	
	public static function isTakeAwayMinuteAnInterval($minute) {
		$min = self::getTakeAwayMinuteInterval();
		for( $i = 0; $i < 60; $i+=$min ) {
			if( $i == $minute ) {
				return true;
			}
		}
		return false;
	}
	
	public static function isRequestTakeAwayOrderValid($args) {
		$hour = null;
		$min = null;
		
		if( empty($args['date']) ) {
			return 1;
		}
		
		if( empty($args['hourmin']) ) {
			return 2;
		} else {
			$app = explode(':',$args['hourmin']);
			if( count( $app ) != 2 ) {
				return 2;
			}
			
			$hour = intval($app[0]);
			$min = intval($app[1]);
			
			if( !self::isHourBetweenShifts($hour, $min, 2) || !self::isTakeAwayMinuteAnInterval($min) ) {
				return 3;
			}
		}
		
		return 0;
	}
	
	public static function getResponseFromTakeAwayOrderRequest($resp) {
		$msg = array( 
				'', 
				'VRTKORDERREQUESTMSG1', 
				'VRTKORDERREQUESTMSG2',
				'VRTKORDERREQUESTMSG3'
		 );
		
		return $msg[$resp];
	}
	
	public static function getTotalCostWithTaxes($total_cost, $taxes) {
		return floatval($total_cost+$total_cost*$taxes/100);
	}
	
	/**
	 * $group = 1 RESTAURANT
	 * $group = 2 TAKE-AWAY
	 * 
	 * return assoc list
	 * $array = [
	 * 		[id, name, from, to, from_hour, from_min, to_hour, to_min]
	 * ]
	 */
	public static function getWorkingShifts($group=0, $skip_session=false) {
		
		if( !self::isContinuosOpeningTime($skip_session) ) {
			
			$dbo = JFactory::getDbo();
			$q = "SELECT `id`, `name`, `showlabel`, `label`, `from`, `to`, FLOOR(`from`/60) AS `from_hour`, (`from`%60) AS `from_min`, FLOOR(`to`/60) AS `to_hour`, (`to`%60) AS `to_min` 
			FROM `#__cleverdine_shifts` ".(($group != 0)?"WHERE `group`=".$group:"")." ORDER BY `from` ASC, `id` ASC;";
			
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				return $dbo->loadAssocList();
			}
		
		}
		
		return array();
	}
	
	public static function getFirstAvailableDateTime($shifts=array(), $continuos=array()) {
		
		$hour = intval(date('H'))+1;
		$min = 0;
		
		$df = self::getDateFormat();
		
		$sel = array(
			"date" => date($df), "hourmin" => ($hour).':0', "people" => 2
		);
		
		if( count($shifts) == 0 ) {
			$shifts = array( array( "from_hour" => $continuos[0], "from_min" => 0, "to_hour" => $continuos[1], "to_min" => 0 ) );
		}
		
		$found = false;
		
		while( $hour < 24 && !$found ) {
			for( $i = 0; $i < count($shifts) && !$found; $i++ ) {
				if( $shifts[$i]['from_hour'] <= $hour && $hour <= $shifts[$i]['to_hour'] ) {
					$found = true;
					$min = $shifts[$i]['from_min'];
				}
			}
			
			if( !$found ) {
				$hour++;
			}
		}
		
		if( !$found ) {
			$hour = $shifts[0]['from_hour'];
			$min = $shifts[0]['from_min'];
			$sel['date'] = getdate();
			$sel['date'] = date( $df, mktime(0, 0, 0, $sel['date']['mon'], $sel['date']['mday']+1, $sel['date']['year']) );
		}
		
		$sel['hourmin'] = $hour.":".$min;
		
		return $sel;
		
	}
	
	public static function checkUserArguments($args, $ignore=false) {
		if( !self::userIsLogged() || $ignore) {
			return (!empty($args['firstname']) && !empty($args['lastname']) && !empty($args['username']) && !empty($args['password']) && self::validateUserEmail($args['email']) && $args['password'] == $args['confpassword'] );
		}
		
		return false;
	}
	
	public static function validateUserEmail($email='') {
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex +1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else
				if ($domainLen < 1 || $domainLen > 255) {
					// domain part length exceeded
					$isValid = false;
				} else
					if ($local[0] == '.' || $local[$localLen -1] == '.') {
						// local part starts or ends with '.'
						$isValid = false;
					} else
						if (preg_match('/\\.\\./', $local)) {
							// local part has two consecutive dots
							$isValid = false;
						} else
							if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
								// character not valid in domain part
								$isValid = false;
							} else
								if (preg_match('/\\.\\./', $domain)) {
									// domain part has two consecutive dots
									$isValid = false;
								} else
									if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\", "", $local))) {
										// character not valid in local part unless 
										// local part is quoted
										if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\", "", $local))) {
											$isValid = false;
										}
									}
			if ($isValid && !(checkdnsrr($domain, "MX") || checkdnsrr($domain, "A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}

	public static function createNewJoomlaUser($args) {

		$app = JFactory::getApplication();

		// load com_users site language
		JFactory::getLanguage()->load('com_users', JPATH_SITE, JFactory::getLanguage()->getTag(), true);
		
		// load UsersModelRegistration
		require_once JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_users'.DIRECTORY_SEPARATOR.'models'.DIRECTORY_SEPARATOR.'registration.php';
		
		$model = new UsersModelRegistration();

		// adapt data for model
		$args['name'] 		= $args['firstname'].' '.$args['lastname'];
		$args['email1'] 	= $args['email'];
		$args['password1'] 	= $args['password'];
		$args['block'] 		= 0;

		// register user
		$return = $model->register($args);

		if( $return === false ) {
			// impossible to save the user
			$app->enqueueMessage($model->getError(), 'error');
		} else if( $return === 'adminactivate' ) {
			// user saved -> admin activation required
			$app->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'));
		} else if( $return === 'useractivate' ) {
			// user saved -> self activation required
			$app->enqueueMessage(JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'));
		} else {
			// user saved -> can login immediately
			$app->enqueueMessage(JText::_('COM_USERS_REGISTRATION_SAVE_SUCCESS'));
		}

		return $return;

	}
	
	public static function getOperator() {
		$user = JFactory::getUser();
		if( $user->guest ) {
			return false;
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT * FROM `#__cleverdine_operator` WHERE `jid`=".$user->id." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			return $dbo->loadAssoc();
		}
		
		return array();
	}

	public static function removeExpiredCreditCards() {
		$session = JFactory::getSession();

		$now = time();

		// if not exists > get a time in the past
		$check = intval($session->get('cc-flush-check', $now-3600, 'vr'));

		if( $check < $now ) {

			$dbo = JFactory::getDbo();

			$q = "UPDATE `#__cleverdine_reservation` SET `cc_details`='' WHERE `checkin_ts`+86400 < $now;";
			$dbo->setQuery($q);
			$dbo->execute();

			$q = "UPDATE `#__cleverdine_takeaway_reservation` SET `cc_details`='' WHERE `checkin_ts`+86400 < $now;";
			$dbo->setQuery($q);
			$dbo->execute();

			$session->set('cc-flush-check', time()+15*60, 'vr');
		}

	}
	
	/**
	 * Queries
	 */
	
	public static function getQueryFindTable($args, $skip_session = false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = 'AND `t`.`published`=1';
		}
	
		return "SELECT `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` 
				FROM `#__cleverdine_table` AS `t`
				LEFT JOIN `#__cleverdine_room` AS `rm` ON `rm`.`id`=`t`.`id_room`
				WHERE `rm`.`published`=1 $table_published_where AND NOT EXISTS ( 
					SELECT `t`.`id` 
					FROM `#__cleverdine_reservation` AS `r` 
					WHERE `t`.`id` = `r`.`id_table` AND `r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
						( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
					)
				) AND (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`t`.`min_capacity` <= {$args['people']} AND {$args['people']} <= `t`.`max_capacity` 
				GROUP BY `t`.`id`
				ORDER BY `t`.`multi_res` ASC, `t`.`max_capacity` ASC, `rm`.`id` ASC;";
	}
	
	public static function getQueryFindTableMultiRes($args, $skip_session = false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		$l_m = $args['min']*60; // less minutes

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = 'AND `t`.`published`=1';
		}
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id` AS `tid`, `t`.`name` AS `tname`, 
		`t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` 
				FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_room` AS `rm`, `#__cleverdine_table` AS `t` 
				WHERE `t`.`id`=`r`.`id_table` $table_published_where AND `t`.`multi_res`=1 AND `t`.`id_room`=`rm`.`id` AND `rm`.`published`=1 AND (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
					( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
					( `r`.`checkin_ts` < $in_ts+$avg-$l_m AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
					( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
					( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg-$l_m > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
					( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg-$l_m = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
				) 
				GROUP BY `t`.`id` 
				HAVING {$args['people']} >= `t`.`min_capacity` AND SUM(`r`.`people`)+{$args['people']} <= `t`.`max_capacity` 
				ORDER BY `rid` ASC;";
	}

	public static function getQueryFindAvailableSharedTables($args, $skip_session = false) {

		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		$l_m = $args['min']*60; // less minutes

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = 'AND `t`.`published`=1';
		}

		return "SELECT `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname`, IFNULL(
				(
					SELECT SUM(`r`.`people`)
	    			FROM `#__cleverdine_reservation` AS `r`
    				WHERE `t`.`id`=`r`.`id_table` AND `r`.`status`<>'REMOVED' AND `r`.`status`<>'CANCELLED' AND ( 
						( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg-$l_m AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg-$l_m > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg-$l_m = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
    				)
				), 0
			) AS `curr_capacity`
			FROM `#__cleverdine_table` AS `t`, `#__cleverdine_room` AS `rm` 
			WHERE `t`.`id_room`=`rm`.`id` $table_published_where AND `t`.`multi_res`=1 AND `rm`.`published`=1 AND ( 
				SELECT COUNT(1) 
    			FROM `#__cleverdine_room_closure` AS `rc` 
    			WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
			)=0 
			GROUP BY `t`.`id` 
			HAVING {$args['people']} >= `t`.`min_capacity` AND `curr_capacity`+{$args['people']} <= `t`.`max_capacity` 
			ORDER BY `rid` ASC;";
	}

	
	public static function getQueryFindTableMultiResWithID($args, $skip_session = false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		$l_m = $args['min']*60; // less minutes

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = 'AND `t`.`published`=1';
		}
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` 
				FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_room` AS `rm`, `#__cleverdine_table` AS `t` 
				WHERE `t`.`id`=`r`.`id_table` $table_published_where AND `t`.`id`={$args['table']} AND `t`.`multi_res`=1 AND `t`.`id_room`=`rm`.`id` AND `rm`.`published`=1 AND  (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
					( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg-$l_m AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg-$l_m > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg-$l_m = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
				) 
				GROUP BY `t`.`id` 
				HAVING {$args['people']} >= `t`.`min_capacity` AND SUM(`r`.`people`)+{$args['people']} <= `t`.`max_capacity` 
				ORDER BY `rid` ASC;";
	}

	public static function getQueryFindTableMultiResWithIDExcludingRes($args, $res_id, $skip_session = false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		$l_m = $args['min']*60; // less minutes

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = 'AND `t`.`published`=1';
		}
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` 
				FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_room` AS `rm`, `#__cleverdine_table` AS `t` 
				WHERE `t`.`id`=`r`.`id_table` $table_published_where AND `r`.`id`<>$res_id AND `t`.`id`={$args['table']} AND `t`.`multi_res`=1 AND `t`.`id_room`=`rm`.`id` AND `rm`.`published`=1 AND  (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
					( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg-$l_m AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg-$l_m > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg-$l_m = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
				)
				GROUP BY `t`.`id` 
				HAVING {$args['people']} >= `t`.`min_capacity` AND SUM(`r`.`people`)+{$args['people']} <= `t`.`max_capacity` 
				ORDER BY `rid` ASC;";
	}
	
	public static function getQueryAllReservationsOnDate($args) {
		$_d = self::getOpeningTimeDelimiters($args);
		
		return "SELECT `t`.`id` AS `idt`, `rm`.`id` AS `rid`, `rm`.`name`, `r`.`checkin_ts`, `r`.`stay_time`  
				FROM `#__cleverdine_table` AS `t`, `#__cleverdine_room` AS `rm`, `#__cleverdine_reservation` AS `r` 
				WHERE `rm`.`id`=`t`.`id_room` AND `r`.`id_table`=`t`.`id` AND {$_d[0]} <= `r`.`checkin_ts` AND `r`.`checkin_ts` <= {$_d[1]} 
				AND `t`.`multi_res`=0 AND `t`.`min_capacity` <= {$args['people']} AND {$args['people']} <= `t`.`max_capacity` 
				AND `r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' 
				ORDER BY `idt` ASC, `r`.`checkin_ts` ASC;";
	} 
	
	public static function getQueryCountOccurrencyTableMultiRes($args, $skip_session = false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		$l_m = $args['min']*60; // less minutes
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id`, `t`.`multi_res` 
				FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` 
				WHERE `t`.`id` = `r`.`id_table` AND `t`.`multi_res` = 1 AND ( 
					( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg-$l_m AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg-$l_m < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg-$l_m > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg-$l_m = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
				) 
				GROUP BY `t`.`id`;";
	}
	
	public static function getQueryTableJustReserved($args, $skip_session = false) {
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = '`t`.`published`=1 AND ';
		}
		
		return "SELECT `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname`
				FROM `#__cleverdine_table` AS `t`, `#__cleverdine_room` AS `rm` 
				WHERE $table_published_where NOT EXISTS ( 
					SELECT `t`.`id` 
					FROM `#__cleverdine_reservation` AS `r` 
					WHERE `t`.`id` = `r`.`id_table` AND `t`.`multi_res` = 0 AND `r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
						( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
					) 
				) AND `t`.`id_room` = `rm`.`id` AND `rm`.`published`=1 AND  (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`t`.`min_capacity` <= {$args['people']} AND ".$args['people']." <= `t`.`max_capacity` AND `t`.`id`={$args['table']}
				ORDER BY `t`.`multi_res` ASC, `t`.`max_capacity` ASC, `rid` ASC;";
	}

	public static function getQueryTableJustReservedExcludingResId($args, $res_id, $skip_session = false) {
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;

		$table_published_where = '';
		if( !$skip_session ) {
			$table_published_where = '`t`.`published`=1 AND';
		}
		
		return "SELECT `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` 
				FROM `#__cleverdine_table` AS `t`, `#__cleverdine_room` AS `rm` 
				WHERE $table_published_where NOT EXISTS ( 
					SELECT `t`.`id` 
					FROM `#__cleverdine_reservation` AS `r` 
					WHERE `r`.`id` <> $res_id AND `t`.`id` = `r`.`id_table` AND `t`.`multi_res` = 0 AND `r`.`status` <> 'REMOVED' AND `r`.`status` <> 'CANCELLED' AND ( 
						( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts+$avg AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
						( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
					) 
				) AND `t`.`id_room` = `rm`.`id` AND `rm`.`published`=1 AND  (
					SELECT COUNT(1) FROM `#__cleverdine_room_closure` AS `rc` 
					WHERE `rc`.`id_room`=`rm`.`id` AND `rc`.`start_ts`<=$in_ts AND $in_ts<`rc`.`end_ts` LIMIT 1
				)=0 AND 
				`t`.`min_capacity` <= {$args['people']} AND {$args['people']} <= `t`.`max_capacity` AND `t`.`id`={$args['id_table']} 
				ORDER BY `t`.`multi_res` ASC, `t`.`max_capacity` ASC, `rid` ASC;";
	}

	public static function getQueryRemoveAllReservationsOutOfTime($args) {
		return "SELECT `id` FROM `#__cleverdine_reservation` WHERE `status` = 'PENDING' AND `locked_until` < ".time().";";
	}

	public static function getQueryAllReservationsRelativeTo($args, $skip_session = false) {
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		return "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `r`.`purchaser_nominative`, `r`.`purchaser_mail`,
			`p`.`name` AS `pname`, `p`.`charge` AS `pcharge`, `r`.`custom_f`, `r`.`notes`
			FROM `#__cleverdine_reservation` AS `r` 
			LEFT JOIN `#__cleverdine_gpayments` AS `p` ON `r`.`id_payment`=`p`.`id` 
			LEFT JOIN `#__cleverdine_table` AS `t` ON `r`.`id_table`=`t`.`id` 
			WHERE `r`.`id_table`={$args['table']} AND `t`.`min_capacity`<={$args['people']} AND {$args['people']}<=`t`.`max_capacity` AND ( 
				( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` < $in_ts+$avg AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
			);";
	}
	
	public static function getQueryAllReservationsRelativeToWithoutPayments($args, $skip_session = false) {
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
		
		return "SELECT `r`.`id`, `r`.`checkin_ts`, `r`.`people`, `r`.`custom_f`, `r`.`notes` 
			FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t`
			WHERE `r`.`id_table`=`t`.`id` AND `r`.`id_table`={$args['table']} AND `t`.`min_capacity`<={$args['people']} AND {$args['people']}<=`t`.`max_capacity` AND ( 
				( `r`.`checkin_ts` < $in_ts AND $in_ts < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` < $in_ts+$avg AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` < $in_ts AND $in_ts+$avg < `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` > $in_ts AND $in_ts+$avg > `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) OR 
				( `r`.`checkin_ts` = $in_ts AND $in_ts+$avg = `r`.`checkin_ts`+IF(`r`.`stay_time`>0, `r`.`stay_time`*60, $avg) ) 
			);";
	}
	
	public static function getOpeningTimeDelimiters($args) {
		$_app = $args['hour']*60+$args['min'];
		
		$start = self::getFromOpeningHour();
		$end = self::getToOpeningHour();
		$start_min = $end_min = 0;
		if( !self::isContinuosOpeningTime() ) {
				
			$dbo = JFactory::getDbo();
			$q = 'SELECT * FROM `#__cleverdine_shifts` WHERE `from`<='.$_app.' AND `to`>='.$_app.' LIMIT 1;';
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {
				$row = $dbo->loadAssoc();
				$start = intval($row["from"]/60);
				$end = intval($row["to"]/60);
				
				$start_min = $row["from"]%60;
				$end_min = $row["to"]%60;
			}
		}
		
		if( $end < $start ) {
			if( $args['hour'] >= 0 && $args['hour'] <= $end ) {
				$start = 0;
			} else {
				$end = 23;
			}
		}
		
		$_sd = max( array( self::createTimestamp($args['date'],0,0), self::createTimestamp($args['date'],$start,$start_min) ) );
		$_fd = min( array( self::createTimestamp($args['date'],23,59), self::createTimestamp($args['date'],$end,$end_min) ) );
		
		return array( $_sd, $_fd );
	}
	
	public static function getAvailableHoursFromInterval($_s, $_f) {
		$_avg = self::getAverageTimeStay();
		$_itv = self::getMinuteIntervals();
		$_available = array();
		for( $t = $_s; $t <= $_f-$_avg*60; $t+=$_itv*60 ) {
			$_available[count($_available)] = $t;
		}
		
		return $_available;
	}
	
	public static function validateCoupon($coupon, $people) {
		$_dates = explode( '-', $coupon['datevalid'] );
		$_today = time();
		
		return ( $coupon['group'] == 0 && ( ( count( $_dates ) != 2 ) || ( $_dates[0] <= $_today && $_today <= $_dates[1] ) ) && $coupon['minvalue'] <= $people );
	}
	
	public static function validateTakeawayCoupon($coupon, $cart) {
		$_dates = explode( '-', $coupon['datevalid'] );
		$_ts 	= $cart->getCheckinTimestamp();

		return ( $coupon['group'] == 1 && ( ( count( $_dates ) != 2 ) || ( $_dates[0] <= $_ts && $_ts <= $_dates[1] ) ) && $coupon['minvalue'] <= $cart->getTotalCost() );
	}
	
	public static function isCustomFieldValid($cf, $val, $is_delivery=false) {
		// VAL NOT EMPTY -> ok
		// FIELD OPTIONAL -> ok
		// FIELD REQUIRED ON DELIVERY and NOT DELIVERY -> ok
		return ( strlen( $val ) > 0 || $cf['required'] == 0 || (!$is_delivery && $cf['required_delivery']) );
	}
	
	public static function generateSerialCode($len=12) {
		$_TOKENS = array( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', '0123456789' );
		$_key = '';
		for( $i = 0; $i < $len; $i++ ) {
			$_row = rand( 0, count( $_TOKENS )-1 );
			$_col = rand( 0, strlen( $_TOKENS[$_row] )-1 );
			$_key .= '' . $_TOKENS[$_row][$_col];
		}
		return $_key;
	}
	
	public static function mergeArrays($a, $b) {
		$from = count($a);
		for( $i = $from, $n = $i+count($b); $i < $n; $i++ ) {
			$a[$i] = $b[$i-$from];
		}
		return $a;
	}

	/**
	 * function getSpecialDays 
	 *
	 * @deprecated
	 * 
	 * @see getSpecialDaysForDeposit
	 */
	
	public static function getSpecialDays($args='', $group=1, $skip_session=false) {
		
		$_h = $args['hour'];
		if( $_h == -1 ) {
			$_h = 0;
		}
		
		$_d = $args['date'];
		if( $_d == -1 ) {
			$_d = date( self::getDateFormat($skip_session), time() );
		}
		
		$_ts = self::createTimestamp($_d, $_h, $args['min'], $skip_session);
		
		$args['timestamp'] = $_ts;
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=".$group." AND `start_ts` <= ".$_ts." AND ".$_ts." <= `end_ts` ORDER BY `priority` DESC;";
		
		return self::_get_special_days_($args, $q, $skip_session);
	}
	
	public static function getSpecialDaysForDeposit($args='', $group=1, $skip_session=false) {
		
		$_h = $args['hour'];
		if( $_h == -1 ) {
			$_h = 0;
		}
		
		$_d = $args['date'];
		if( $_d == -1 ) {
			$_d = date( self::getDateFormat($skip_session), time() );
		}
		
		$_ts = self::createTimestamp($_d, $_h, $args['min'], $skip_session);
		
		$args['timestamp'] = $_ts;
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=".$group." AND ((`start_ts` <= ".$_ts." AND ".$_ts." <= `end_ts`) OR `start_ts`=-1) ORDER BY `priority` DESC;";
		
		return self::_get_special_days_($args, $q, $skip_session);
		
	}
	
	private static function _get_special_days_($args='', $query='', $skip_session=false) {
		if( empty($args) || empty($query) ) return;
		
		$dbo = JFactory::getDbo();
		
		$special_days = array();
		
		$q = $query;
		
		$current_days_index = intval( date( 'w', $args['timestamp'] ) );
		
		// working time shifts of 1.4 version
		$_hour_full = $args['hour']*60+$args['min'];
		
		$at_least_one_day = false;
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$sp_days = $dbo->loadAssocList();
			
			for( $i = 0, $n = count( $sp_days ); $i < $n; $i++ ) {
				$ok = false;
				if( strlen( $sp_days[$i]['days_filter'] ) == 0 || $args['date'] == -1 ) {
					$ok = true;
				}
				
				$_days = explode( ', ', $sp_days[$i]['days_filter'] );
				
				$_days_arr = array();
				for( $k = 0, $o = count($_days); $k < $o && !$ok; $k++ ) {
					if( $_days[$k] == $current_days_index ) {
						$ok = true;
					}
				}
				
				if( $ok ) {
					$at_least_one_day = true;
					
					$ok = false;
					if( strlen( $sp_days[$i]['working_shifts'] ) == 0 || $args['hour'] == -1 ) {
						$ok = true;
					}
					$shifts = explode( ', ', $sp_days[$i]['working_shifts'] );
				
					for( $j = 0, $m = count($shifts); $j < $m && !$ok; $j++ ) {
						$hm = explode( '-', $shifts[$j] );
						//if( $hm[0] <= $args['hour'] && $args['hour'] <= $hm[1] ) {
						if( $hm[0] <= $_hour_full && $_hour_full <= $hm[1] ) {
							$ok = true;
						} 
					}
		
					if( $ok ) {
						$special_days[count($special_days)] = $sp_days[$i];
						return $special_days; // limit to 1
					}
					
				}
				
			}
			
			if( $at_least_one_day ) {
				//return $special_days;
			}
		}
		
		return -1;
	}

	public static function getSpecialDaysOnDate($args, $group=1, $skip_session=false) {
		
		$dbo = JFactory::getDbo();
		
		$_h = $args['hour'];
		if( $_h == -1 ) {
			$_h = 0;
		}
		
		$_d = $args['date'];
		if( $_d == -1 ) {
			$_d = date( self::getDateFormat($skip_session), time() );
		}
		
		$_ts = self::createTimestamp($_d, $_h, $args['min'], $skip_session);
		
		$args['timestamp'] = $_ts;
		
		$special_days = array();
		
		$current_days_index = intval( date( 'w', $args['timestamp'] ) );
		
		$q = "SELECT * FROM `#__cleverdine_specialdays` WHERE `group`=".$group." AND ((`start_ts` <= ".$_ts." AND ".$_ts." <= `end_ts`) OR `start_ts`=-1) ORDER BY `priority` DESC;";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$sp_days = $dbo->loadAssocList();
			
			for( $i = 0, $n = count( $sp_days ); $i < $n; $i++ ) {
				
				$ok = false;
				if( strlen( $sp_days[$i]['days_filter'] ) == 0 || $args['date'] == -1 ) {
					$ok = true;
				}
				
				$_days = explode( ', ', $sp_days[$i]['days_filter'] );
				
				$_days_arr = array();
				for( $k = 0, $o = count($_days); $k < $o && !$ok; $k++ ) {
					if( $_days[$k] == $current_days_index ) {
						$ok = true;
					}
				}
	
				if ($ok)
				{
					$special_days[] = $sp_days[$i];

					/**
					 * @since 1.7.2 this method won't limit anymore the special days to 1
					 */
					// return $special_days; // limit to 1
				}
				
			}
			
			// return $special_days;

			/**
			 * @since 1.7.2 used to retrieve concurrent special days.
			 */
			if (count($special_days))
			{
				return $special_days;
			}
		}
		
		return -1;
		
	}

	public static function getWorkingShiftsFromSpecialDays($shifts, $special_days, $group = 1, $skip_session = false) {
		
		$_eval_shifts = array();
		
		foreach( $special_days as $sd ) {
			if( empty($sd['working_shifts']) ) {
				return $shifts;
			}
			
			$_ws = explode(', ', $sd['working_shifts']);
			for( $i = 0; $i < count($_ws); $i++ ) {
				$found = false;
				
				$_app = explode('-', $_ws[$i]);
				for( $j = 0; $j < count($shifts); $j++ ) {
					$found = ( $_app[0] == $shifts[$j]['from'] && $_app[1] == $shifts[$j]['to'] );
					if( $found ) {
						$_eval_shifts[$shifts[$j]['id']] = $shifts[$j];
					}
				}
		
			}
		}
		
		$_arr = array();
		$i = 0;
		foreach( $_eval_shifts as $k => $s ) {
			$_arr[$i++] = $s;
		}
		
		return $_arr;
		
	}
	
	public static function getAllAvailableMenusOn($args, $choosable=0) {
		$sp_d = self::getSpecialDaysForDeposit($args, 1);
		$closed = self::isClosingDayIgnoringDate($args);
		
		$dbo = JFactory::getDbo();
		$menus = array();
		
		if( $sp_d != -1 ) {
			
			if( count($sp_d) == 0 ) {
				$closed = true;
			}
			
			for( $i = 0, $n = count($sp_d); $i < $n; $i++ ) {
				$ok = true;
				if( $sp_d[$i]['ignoreclosingdays'] == 0 && $closed == 1 ) {
					$ok = false;
				}
				if( $ok ) {
					$q = "SELECT `m`.* FROM `#__cleverdine_specialdays` AS `s` LEFT JOIN  `#__cleverdine_sd_menus` AS  `sm` ON `s`.`id` = `sm`.`id_spday` 
					LEFT JOIN `#__cleverdine_menus` AS `m` ON  `m`.`id` = `sm`.`id_menu` WHERE `s`.`id`=".$sp_d[$i]['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						$rows = $dbo->loadAssocList();
						for( $j = 0, $m = count( $rows ); $j < $m; $j++ ) {
							//////////// START FIXED PART ////////////

							//$rows[$j]['working_shifts'] = $sp_d[$i]['working_shifts'];
							$rows[$j]['days_filter'] = $sp_d[$i]['days_filter'];

							$menu_ok = false;
							if( strlen( $rows[$j]['working_shifts'] ) == 0 || $args['hour'] == -1 ) {
								$menu_ok = true;
							} else {
							
								$shifts = explode( ', ', $rows[$j]['working_shifts'] );
								
								for( $k = 0; $k < count($shifts) && !$menu_ok; $k++ ) {
									$hm = explode( '-', $shifts[$k] );
									$_hour_min_full = $args['hour']*60+$args['min'];
									if( $hm[0] <= $_hour_min_full && $_hour_min_full <= $hm[1] ) {
										$menu_ok = true;
									}
								}
							}

							if( !empty($rows[$j]['id']) && $menu_ok ) {
								$menus[count($menus)] = $rows[$j];
							}

							//////////// END FIXED PART ////////////

						}
					} 
				}
			}
		}
		
		if( $sp_d == -1 && !$closed ) {
			$q = "SELECT * FROM `#__cleverdine_menus` WHERE `published`=1 ".($choosable ? "AND `choosable`=1 " : "")."ORDER BY `ordering` ASC;";
			$dbo->setQuery($q);
			$dbo->execute();
			if( $dbo->getNumRows() > 0 ) {  
				$rows = $dbo->loadAssocList();
				
				$current_days_index = intval( date( 'w', self::createTimestamp( $args['date'],0,0 ) ) );
					
				for( $i = 0, $n = count( $rows ); $i < $n; $i++ ) {
					if( $rows[$i]['special_day'] == 0 ) { 
					
						$ok = false;
						if( strlen( $rows[$i]['working_shifts'] ) == 0 || $args['hour'] == -1 ) {
							$ok = true;
						}
						
						$shifts = explode( ', ', $rows[$i]['working_shifts'] );
						
						for( $j = 0, $m = count($shifts); $j < $m && !$ok; $j++ ) {
							$hm = explode( '-', $shifts[$j] );
							$_hour_min_full = $args['hour']*60+$args['min'];
							if( $hm[0] <= $_hour_min_full && $_hour_min_full <= $hm[1] ) {
								$ok = true;
							}
						}
						
						if( $ok ) {
							
							$ok = false;
							if( strlen( $rows[$i]['days_filter'] ) == 0 ) {
								$ok = true;
							}
							
							$_days = explode( ', ', $rows[$i]['days_filter'] );
				
							$_days_arr = array();
							for( $k = 0, $o = count($_days); $k < $o && !$ok; $k++ ) {
								if( $_days[$k] == $current_days_index ) {
									$ok = true;
								}
							}
				
							if( $ok ) {
								$menus[count($menus)] = $rows[$i];
							}
							
						}
						
					}
				}
				
			}

		}
		
		return $menus;
		
	}

	public static function isMenusChoosable($args, $group=1, $skip_session=false) {
		$sp_days = self::getSpecialDaysOnDate($args, $group, $skip_session);
		
		if( $sp_days == -1 || count($sp_days) == 0 ) {
			return self::getChooseMenu($skip_session);
		}
		
		foreach( $sp_days as $s ) {
			if( $s['choosemenu'] ) {
				return true;
			}
		}
		
		return 0;
	}
	
	public static function getAllTakeawayMenusOn($args) {
		$sp_d = self::getSpecialDaysForDeposit($args, 2);
		
		$dbo = JFactory::getDbo();
		$menus = array();

		$is_cd = self::isClosingDay($args);
		
		if( $sp_d != -1 ) {
			
			for( $i = 0, $n = count($sp_d); $i < $n; $i++ ) {

				if( !$is_cd || $sp_d[$i]['ignoreclosingdays'] ) {
				
					$q = "SELECT `m`.`id` FROM `#__cleverdine_specialdays` AS `s` 
					LEFT JOIN  `#__cleverdine_sd_menus` AS  `sm` ON `s`.`id` = `sm`.`id_spday` 
					LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON  `m`.`id` = `sm`.`id_menu` 
					WHERE `s`.`id`=".$sp_d[$i]['id'].";";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() > 0 ) {
						foreach( $dbo->loadAssocList() as $row ) {
							if( !empty($row['id']) ) {
								array_push($menus, $row['id']);
							}
						}
					}

				}
				
			}

			if( !count($menus) ) {
				// none menu available > current special days don't include menus
				return false;
			}
		} else if( $is_cd ) {
			// none menu availabel > current day is closed
			return false;
		}
		
		return $menus;
		
	}

	public static function isClosingDayIgnoringDate($args, $skip_session=false) {
		if( $args['date'] == -1 ) {
			return false;
		}
		
		return self::isClosingDay($args, $skip_session);
	}
	
	public static function isClosingDay($args, $skip_session=false) {
		$cd = self::getClosingDays($skip_session);
		
		$_h = $args['hour'];
		if( $_h == -1 ) {
			$_h = 0;
		}
		
		$ts = self::createTimestamp($args['date'], $_h, $args['min'], $skip_session);
		
		$dmy = explode( '/', date( 'd/m/Y', $ts ) );
		$day_of_week = date( 'D', $ts );
		
		foreach( $cd as $v ) {
			$app = explode( '/', date( 'd/m/Y', $v['ts'] ) );
			$app_d = date( 'D', $v['ts'] );
			
			if( $v['freq'] == 0 ) {
				if( $dmy[0] == $app[0] && $dmy[1] == $app[1] && $dmy[2] == $app[2] ) {
					return true;
				}
			} else if( $v['freq'] == 1) {
				if( $day_of_week == $app_d ) {
					return true;
				}
			} else if( $v['freq'] == 2 ) {
				if( $dmy[0] == $app[0] ) {
					return true;
				}
			} else if( $v['freq'] == 3 ) {
				if( $dmy[0] == $app[0] && $dmy[1] == $app[1] ) {
					return true;
				}
			}
		}
		
		return false;
	}

	public static function isTakeAwayCurrentlyAvailable() {
		
		$date = getdate();

		if( !self::isContinuosOpeningTime() ) {

			$dt_args = array(
				'date' => date(self::getDateFormat()), 
				'hourmin' => $date['hours'].':'.$date['minutes'], 
				'hour' => $date['hours'], 
				'min' => $date['minutes']
			);

			$shifts = self::getWorkingShifts(2);
			$special_days = self::getSpecialDaysOnDate($dt_args, 2);
			
			if( $special_days != -1 && count($special_days) > 0 ) {
				$shifts = self::getWorkingShiftsFromSpecialDays( $shifts, $special_days, 2 );
			}

			foreach( $shifts as $sh ) {
				if( $sh['from'] <= ($x = $date['hours']*60+$date['minutes']) && $x <= $sh['to'] ) {
					return true;
				}
			}

		} else {
			$continuos = array( self::getFromOpeningHour(), self::getToOpeningHour() );

			return $continuos[0]*60 <= ($x = $date['hours']*60+$date['minutes']) && $x <= $continuos[1]*60;
		}

		return false;
	}

	public static function hasItemToppings($id_entry, $id_option = 0, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$id_entry = intval($id_entry);
		$id_option = intval($id_option);

		$q = "SELECT 1 FROM `#__cleverdine_takeaway_entry_group_assoc` 
		WHERE `id_entry`=$id_entry AND (`id_variation`<=0 OR `id_variation`=$id_option) LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		return $dbo->getNumRows();
	}

	// MEDIA

	public static function getMediaProperties() {
		$str = self::getFieldFromConfig('mediaprop', 'vrGetMediaProperties', true);
		if( empty($str) ) {
			return array(
				"resize" 		=> 0,
				"resize_value" 	=> 512,				
				"thumb_value" 	=> 128,
			);
		}

		return json_decode($str, true);
	}

	public static function storeMediaProperties(&$prop) {
		$dbo = JFactory::getDbo();

		if( empty($prop['resize_value']) ) {
			$prop['resize_value'] = 512;
		}

		if( empty($prop['thumb_value']) ) {
			$prop['thumb_value'] = 128;
		}

		$q = "UPDATE `#__cleverdine_config` SET `setting`=".$dbo->quote(json_encode($prop))." WHERE `param`='mediaprop' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
	}

	public static function uploadMedia($name, $prop = null, $overwrite = false) {
				
		$base_path = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR;

		$resp = self::uploadFile($name, $base_path.'media'.DIRECTORY_SEPARATOR, 'jpeg,jpg,png,gif', $overwrite);

		UILoader::import('library.image.resizer');

		if( $resp->esit ) {

			if( $prop === null ) {
				$prop = self::getMediaProperties();
			}
			
			if( $prop['resize'] == 1 ) {
				
				$crop_dest = str_replace($resp->name, '$_' . $resp->name, $resp->path);
				
				ImageResizer::proportionalImage( $resp->path,  $crop_dest, $prop['resize_value'], $prop['resize_value'] );
				copy( $crop_dest, $resp->path );
				unlink( $crop_dest );
			}

			$thumb_dest = $base_path.'media@small'.DIRECTORY_SEPARATOR.$resp->name;
			ImageResizer::proportionalImage( $resp->path, $thumb_dest,  $prop['thumb_value'],  $prop['thumb_value'] );

		}

		return $resp;

	}

	public static function uploadFile($name, $dest, $filters = '*', $overwrite = false) {
		$file = JFactory::getApplication()->input->files->get($name, null, 'array');

		$dest .= ($dest[strlen($dest)-1] == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR);
		
		$obj = new stdClass;
		$obj->esit = 0;
		$obj->errno = null;
		$obj->path = '';
		
		if( isset($file) && strlen( trim( $file['name'] ) ) > 0 ) {
			jimport('joomla.filesystem.file');

			$filename = JFile::makeSafe(str_replace(" ", "_", $file['name']));
			$src = $file['tmp_name'];
			$j = "";
			while( file_exists($dest . $j . $filename) ) {
				if( !$overwrite || !unlink($dest . $j . $filename) ) {
					$j = rand(171, 1717);
				}
			}
			$obj->path 	= $dest . $j . $filename;
			$obj->src 	= $src;
			$obj->name 	= $j . $filename;
			
			if( ($fc = self::isFileTypeCompatible($file, $filters)) && ($fu = JFile::upload( $src, $obj->path, false, true )) ) {
				$obj->esit = 1;
			} else if( !$fc ) {
				$obj->errno = 1;
			} else {
				$obj->errno = 2;
			}
		}

		return $obj;
	}

	public static function isFileTypeCompatible($file, $filters) {
		if( strlen($filters) == 0 ) {
			return false;
		}
		
		$types = explode(',', $filters);
		for( $i = 0; $i < count($types); $i++ ) {
			$types[$i] = trim($types[$i]);
			if( strpos( $file['type'], $types[$i] ) !== false || $types[$i] == '*' ) {
				return true;
			}
		}
		
		return false;
	}
	
	// ORDER CONFIRM
	
	public static function fetchOrderDetails($order_id, $langtag = '') {
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `r`.*,`gp`.`name` AS `payment_name`,`gp`.`note` AS `payment_note`,`gp`.`prenote` AS `payment_prenote`,`gp`.`charge` AS `payment_charge`,
		`t`.`name` AS `table_name`,`t`.`id_room` AS `table_id_room`,`room`.`name` AS `room_name`,`room`.`description` AS `room_description`,
		`ma`.`id_menu`,`menu`.`name` AS `menu_name`,`ma`.`quantity` AS `menu_quantity`,
		`ju`.`name` AS `user_name`, `ju`.`username` AS `user_uname`, `ju`.`email` AS `user_email`
		FROM `#__cleverdine_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_gpayments` AS `gp` ON `gp`.`id`=`r`.`id_payment` 
		LEFT JOIN `#__cleverdine_table` AS `t` ON `t`.`id`=`r`.`id_table` 
		LEFT JOIN `#__cleverdine_room` AS `room` ON `room`.`id`=`t`.`id_room` 
		LEFT JOIN `#__cleverdine_res_menus_assoc` AS `ma` ON `ma`.`id_reservation`=`r`.`id` 
		LEFT JOIN `#__cleverdine_menus` AS `menu` ON `menu`.`id`=`ma`.`id_menu` 
		LEFT JOIN `#__cleverdine_users` AS `u` ON `u`.`id`=`r`.`id_user`
		LEFT JOIN `#__users` AS `ju` ON `ju`.`id`=`u`.`jid` 
		WHERE `r`.`id`=".intval($order_id).";";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$rows = $dbo->loadAssocList();
			$order = $rows[0];
			$order['menus_list'] = array();
			if( !empty($rows[0]['menu_name']) ) {
				foreach( $rows as $r ) {
					array_push($order['menus_list'], array(
						"id" => $r["id_menu"],
						"name" => $r["menu_name"],
						"quantity" => $r["menu_quantity"],
					));
				}
			}

			// translations
			if( empty($langtag) ) {
				$langtag = $order['langtag'];
				if( empty($langtag) ) {
					$langtag = JFactory::getLanguage()->getTag();
				}
			}
			$order['langtag'] = $langtag;

			if( !empty($order['id_payment']) && $order['id_payment'] > 0 ) {
				$payments_translations = self::getTranslatedPayments(array($order['id_payment']), $order['langtag']);

				$order['payment_name'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_name', 'name');
				$order['payment_note'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_note', 'note');
				$order['payment_prenote'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_prenote', 'prenote');
			}

			return $order;
		}
		return false;
	}

	public static function getFoodFromReservation($oid, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$q = "SELECT `a`.* 
		FROM `#__cleverdine_res_prod_assoc` AS `a`
		WHERE `a`.`id_reservation`=$oid 
		ORDER BY `a`.`id` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			return $dbo->loadAssocList();
		}

		return array();

	}

	// ADMIN / OPERATORS E-MAIL

	public static function loadAdminEmailTemplate($order_details = array()) {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."mail_tmpls".DIRECTORY_SEPARATOR.self::getMailAdminTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendAdminEmail($order_details, $skipsession = false) {
		if (!$order_details) return;

		self::loadLanguage(self::getDefaultLanguage());
		
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);

		$subject = JText::sprintf('VRADMINEMAILSUBJECT', $fromname);
		
		$tmpl = self::loadAdminEmailTemplate($order_details);
		$_html_content = self::parseAdminEmailTemplate($tmpl, $order_details, $skipsession);
		
		$send_when = self::getSendMailWhen();
		
		$vik = new VikApplication(VersionListener::getID());
		
		if( $send_when['admin'] != 0 && ( $send_when['admin'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			foreach( $admin_mail_list as $_m ) {
				$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
			}
		}
		
		if( $send_when['operator'] != 0 && ( $send_when['operator'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			foreach( self::getNotificationOperatorsMails(1) as $_m ) {
				$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
			}
		}
		
		return;
	}

	public static function parseAdminEmailTemplate($tmpl, $order_details, $skipsession = false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);
		$res_req 		= self::getReservationRequirements($skipsession);
		
		$head_css_style = '';

		$payment_box_visible_count = 0;

		// parse payment name

		$payment_name = "";
		if( !empty($order_details['payment_name']) ) {
			$payment_name = $order_details['payment_name'];

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-payment {display: none;}';
		}

		$payment_notes = '';
		if( $order_details['status'] == 'CONFIRMED' ) {
			$payment_notes = $order_details['payment_note'];
		} else if( $order_details['status'] == 'PENDING' ) {
			$payment_notes = $order_details['payment_prenote'];
		}

		// parse total deposit

		$total_deposit = "";
		if( $order_details['deposit'] > 0 ) {
			$total_deposit = self::printPriceCurrencySymb($order_details['deposit'], $curr_symb, $symb_pos, $skipsession);

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-deposit {display: none;}';
		}

		// parse coupon string

		$coupon_str = "";
		if( !empty($order_details['coupon_str']) ) {
			list($code, $value, $pt) = explode(';;', $order_details['coupon_str']);
			$coupon_str = $code." : ".($pt == 1 ? $value.'%' : self::printPriceCurrencySymb($value, $curr_symb, $symb_pos, $skipsession));

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-coupon-code {display: none;}';
		}

		// payments details box 

		if( !$payment_box_visible_count ) {
			$head_css_style .= '#order-payment-box {display: none;} #order-details-box {width: 99%;}';
		}

		// parse people

		$order_people = $order_details['people'].' '.strtolower(JText::_('VRORDER'.($order_details['people'] > 1 ? 'PEOPLE' : 'PERSON')));

		if( $res_req == 0 ) {
			$order_people .= ' (<strong>'.$order_details['room_name'].' - '.$order_details['table_name'].'</strong>)';
		} else if( $res_req == 1 ) {
			$order_people .= ' (<strong>'.$order_details['room_name'].'</strong>)';
		}

		// parse menu details

		$menu_details = '';
		foreach( $order_details['menus_list'] as $j => $item ) {
			
			$menu_details .= '<div class="menu-product">';

			$menu_details .= '<div class="item-name">'.$item['name'].'</div>';
			$menu_details .= '<div class="item-quantity">x'.$item['quantity'].'</div>';

			$menu_details .= '</div>';

		}

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// joomla user details
		$user_details = '';
		if( !empty($order_details['user_email']) > 0 ) {
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGFULLNAME').':</div>
				<div class="value">'.$order_details['user_name'].'</div>
			</div>';
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGUNAME').':</div>
				<div class="value">'.$order_details['user_uname'].'</div>
			</div>';
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGEMAIL').':</div>
				<div class="value">'.$order_details['user_email'].'</div>
			</div>';
		}

		if( empty($customer_details) ) {
			// fill customer details with juser info
			$customer_details = $user_details;
			// unset juser info
			$user_details = '';
		}

		// order link

		$order_link_href = JUri::root().'index.php?option=com_cleverdine&view=order&ordnum='.$order_details['id'].'&ordkey='.$order_details['sid'].'&ordtype=0';

		$confirmation_link_href = "";
		if( $order_details['status'] == 'PENDING' ) {
			$confirmation_link_href = JUri::root().'index.php?option=com_cleverdine&task=confirmord&oid='.$order_details['id'].'&conf_key='.$order_details['conf_key'].'&tid=0';
		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{order_number}', $order_details['id'], $tmpl);
		$tmpl = str_replace('{order_key}', $order_details['sid'], $tmpl);
		$tmpl = str_replace('{order_date_time}', date($date_format.' '.$time_format, $order_details['checkin_ts']), $tmpl );
		$tmpl = str_replace('{order_people}', $order_people, $tmpl);
		$tmpl = str_replace('{order_status_class}', strtolower($order_details['status']), $tmpl);
		$tmpl = str_replace('{order_status}', JText::_('VRRESERVATIONSTATUS'.$order_details['status']), $tmpl);
		$tmpl = str_replace('{order_payment}', $payment_name, $tmpl);
		$tmpl = str_replace('{order_payment_notes}', $payment_notes, $tmpl);
		$tmpl = str_replace('{order_deposit}', $total_deposit, $tmpl);
		$tmpl = str_replace('{order_coupon_code}', $coupon_str, $tmpl);
		$tmpl = str_replace('{menu_details}', $menu_details, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{user_details}', $user_details, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link_href, $tmpl);
		$tmpl = str_replace('{confirmation_link}', $confirmation_link_href, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{head_css_style}', $head_css_style, $tmpl);
		
		return $tmpl;

	}

	// RESTAURANT CUSTOMER E-MAIL
	
	public static function loadEmailTemplate($order_details = array()) {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."mail_tmpls".DIRECTORY_SEPARATOR.self::getMailTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendCustomerEmail($order_details, $skipsession = false) {
		if (!$order_details) return;

		self::loadLanguage($order_details['langtag']);
		
		$adminmail = self::getAdminMailList($skipsession);
		$adminmail = $adminmail[0];
		$sendermail = self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $adminmail;
		}
		$fromname = self::getRestaurantName($skipsession);
		
		$subject = JText::sprintf('VRCUSTOMEREMAILSUBJECT', $fromname);
		
		$tmpl = self::loadEmailTemplate($order_details);
		$_html_content = self::parseEmailTemplate($tmpl, $order_details, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		$vik->sendMail($sendermail, $fromname, $order_details['purchaser_mail'], $adminmail, $subject, $_html_content);
		
	}

	public static function parseEmailTemplate($tmpl, $order_details, $skipsession = false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);
		$res_req 		= self::getReservationRequirements($skipsession);
		

		$head_css_style = '';

		$payment_box_visible_count = 0;

		// parse payment name

		$payment_name = "";
		if( !empty($order_details['payment_name']) ) {
			$payment_name = $order_details['payment_name'];

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-payment {display: none;}';
		}

		$payment_notes = '';
		if( $order_details['status'] == 'CONFIRMED' ) {
			$payment_notes = $order_details['payment_note'];
		} else if( $order_details['status'] == 'PENDING' ) {
			$payment_notes = $order_details['payment_prenote'];
		}

		// parse total deposit

		$total_deposit = "";
		if( $order_details['deposit'] > 0 ) {
			$total_deposit = self::printPriceCurrencySymb($order_details['deposit'], $curr_symb, $symb_pos, $skipsession);

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-deposit {display: none;}';
		}

		// parse coupon string

		$coupon_str = "";
		if( !empty($order_details['coupon_str']) ) {
			list($code, $value, $pt) = explode(';;', $order_details['coupon_str']);
			$coupon_str = $code." : ".($pt == 1 ? $value.'%' : self::printPriceCurrencySymb($value, $curr_symb, $symb_pos, $skipsession));

			$payment_box_visible_count++;
		} else {
			$head_css_style .= '#order-coupon-code {display: none;}';
		}

		// payments details box 

		if( !$payment_box_visible_count ) {
			$head_css_style .= '#order-payment-box {display: none;} #order-details-box {width: 99%;}';
		}

		// parse people

		$order_people = $order_details['people'].' '.strtolower(JText::_('VRORDER'.($order_details['people'] > 1 ? 'PEOPLE' : 'PERSON')));

		if( $res_req == 0 ) {
			$order_people .= ' (<strong>'.$order_details['room_name'].' - '.$order_details['table_name'].'</strong>)';
		} else if( $res_req == 1 ) {
			$order_people .= ' (<strong>'.$order_details['room_name'].'</strong>)';
		}

		// parse menu details

		$menu_details = '';
		foreach( $order_details['menus_list'] as $j => $item ) {
			
			$menu_details .= '<div class="menu-product">';

			$menu_details .= '<div class="item-name">'.$item['name'].'</div>';
			$menu_details .= '<div class="item-quantity">x'.$item['quantity'].'</div>';

			$menu_details .= '</div>';

		}

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// joomla user details
		$user_details = '';
		if( !empty($order_details['user_email']) > 0 ) {
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGFULLNAME').':</div>
				<div class="value">'.$order_details['user_name'].'</div>
			</div>';
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGUNAME').':</div>
				<div class="value">'.$order_details['user_uname'].'</div>
			</div>';
			$user_details .= '<div class="info">
				<div class="label">'.JText::_('VRREGEMAIL').':</div>
				<div class="value">'.$order_details['user_email'].'</div>
			</div>';
		}

		if( empty($customer_details) ) {
			// fill customer details with juser info
			$customer_details = $user_details;
			// unset juser info
			$user_details = '';
		}

		// order link

		$order_link_href = JUri::root().'index.php?option=com_cleverdine&view=order&ordnum='.$order_details['id'].'&ordkey='.$order_details['sid'].'&ordtype=0';

		$cancellation_link_href = "";
		if( self::canUserCancelOrder($order_details['checkin_ts'], 0, $skipsession) && $order_details['status'] == 'CONFIRMED' ) {
			$cancellation_link_href = $order_link_href."#cancel";
		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{order_number}', $order_details['id'], $tmpl);
		$tmpl = str_replace('{order_key}', $order_details['sid'], $tmpl);
		$tmpl = str_replace('{order_date_time}', date($date_format.' '.$time_format, $order_details['checkin_ts']), $tmpl );
		$tmpl = str_replace('{order_people}', $order_people, $tmpl);
		$tmpl = str_replace('{order_status_class}', strtolower($order_details['status']), $tmpl);
		$tmpl = str_replace('{order_status}', JText::_('VRRESERVATIONSTATUS'.$order_details['status']), $tmpl);
		$tmpl = str_replace('{order_payment}', $payment_name, $tmpl);
		$tmpl = str_replace('{order_payment_notes}', $payment_notes, $tmpl);
		$tmpl = str_replace('{order_deposit}', $total_deposit, $tmpl);
		$tmpl = str_replace('{order_coupon_code}', $coupon_str, $tmpl);
		$tmpl = str_replace('{menu_details}', $menu_details, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{user_details}', $user_details, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link_href, $tmpl);
		$tmpl = str_replace('{cancellation_link}', $cancellation_link_href, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{head_css_style}', $head_css_style, $tmpl);
		
		return $tmpl;
	}

	// TAKEAWAY CANCELLATION E-MAIL (for admin)

	public static function loadCancellationEmailTemplate($order_details = array()) {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."mail_tmpls".DIRECTORY_SEPARATOR.self::getMailCancellationTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendCancellationEmail($order_details, $skipsession = false) {
		if( !$order_details ) return;

		// load default language
		self::loadLanguage(self::getDefaultLanguage());
	
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);
	
		$subject = JText::sprintf('VRORDERCANCELLEDSUBJECT', $fromname);
	
		$tmpl = self::loadCancellationEmailTemplate($order_details);
		$_html_content = self::parseCancellationEmailTemplate($tmpl, $order_details, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		
		foreach( $admin_mail_list as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
		}
		
		foreach( self::getNotificationOperatorsMails(1) as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
		}
		
	}
	
	public static function parseCancellationEmailTemplate($tmpl, $order_details, $skipsession=false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);

		// retrieve cancellation content

		$cancellation_content = JText::_('VRORDERCANCELLEDCONTENT');

		// build cancellation reason

		$cancellation_reason = '';
		if( !empty($order_details['cancellation_reason']) && strlen($order_details['cancellation_reason']) ) {
			$cancellation_reason = '<div class="cancellation-reason">'.JText::sprintf('VRCANCCUSTOMERSAID', $order_details['cancellation_reason']).'</div>';
		}

		// fetch order details

		$url = JUri::root().'administrator/index.php?option=com_cleverdine&task=editreservation&cid[]='.$order_details['id'];

		$order_summary = '<div class="order">';

		$order_summary .= '<div class="content">';
		$order_summary .= '<div class="left">'.$order_details['id'].' - '.$order_details['sid'].'</div>';
		$order_summary .= '<div class="right">'.JText::_('VRRESERVATIONSTATUSCANCELLED').'</div>';
		$order_summary .= '</div>';

		$order_summary .= '<div class="subcontent">';
		$order_summary .= '<div class="left">'.date($date_format.' '.$time_format, $order_details['checkin_ts']).', '.$order_details['people'].' '.strtolower(JText::_('VRORDER'.($order_details['people'] > 1 ? 'PEOPLE' : 'PERSON'))).'</div>';
		$order_summary .= '<div class="center">'.(!empty($order_details['purchaser_nominative']) ? $order_details['purchaser_nominative'] : $order_details['purchaser_mail']).'</div>';
		$order_summary .= '<div class="right">'.$order_details['room_name'].' - '.$order_details['table_name'].'</div>';
		$order_summary .= '</div>';
		$order_summary .= '<div class="link"><a href="'.$url.'">'.$url.'</a></div>';

		$order_summary .= '</div>';

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// order link

		$order_link_href = JUri::root().'administrator/index.php?option=com_cleverdine&task=reservations&tools=1&ordnum='.$order_details['sid'];
		$order_link = '<div class="order-link"><a href="'.$order_link_href.'">'.$order_link_href.'</a></div>';		

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{cancellation_content}', $cancellation_content, $tmpl);
		$tmpl = str_replace('{cancellation_reason}', $cancellation_reason, $tmpl);
		$tmpl = str_replace('{order_summary}', $order_summary, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link, $tmpl);

		return $tmpl;
		
	}

	public static function removeAllTakeAwayOrdersOutOfTime($dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$now = time();

		$q = "UPDATE `#__cleverdine_takeaway_reservation` 
		SET `status`='REMOVED' 
		WHERE `status`='PENDING' AND `locked_until`<$now;";

		$dbo->setQuery($q);
		$dbo->execute();
	} 
	
	public static function fetchTakeAwayOrderDetails($order_id, $langtag='') {
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `r`.*,`gp`.`name` AS `payment_name`,`gp`.`note` AS `payment_note`,`gp`.`prenote` AS `payment_prenote`,`gp`.`charge` AS `payment_charge`, 
		`rp`.`id` AS `id_res_prod_assoc`, `rp`.`id_product` AS `id_product`,`rp`.`quantity` AS `product_quantity`,`rp`.`id_product_option` AS `id_product_option`,`rp`.`price` AS `product_price`,`rp`.`notes` AS `product_notes`,
		`entry`.`name` AS `product_name`,`option`.`name` AS `option_name`,
		`group`.`id` AS `id_group`, `group`.`title` AS `group_title`, `topping`.`id` AS `id_topping`, `topping`.`name` AS `topping_name`,
		`ju`.`name` AS `user_name`, `ju`.`username` AS `user_uname`, `ju`.`email` AS `user_email`
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_gpayments` AS `gp` ON `gp`.`id`=`r`.`id_payment` 
		LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `rp` ON `r`.`id`=`rp`.`id_res` 
		LEFT JOIN `#__cleverdine_takeaway_res_prod_topping_assoc` AS `rpt` ON `rp`.`id`=`rpt`.`id_assoc` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `entry` ON `entry`.`id`=`rp`.`id_product` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `option` ON `option`.`id`=`rp`.`id_product_option` 
		LEFT JOIN `#__cleverdine_takeaway_entry_group_assoc` AS `group` ON `group`.`id`=`rpt`.`id_group` 
		LEFT JOIN `#__cleverdine_takeaway_topping` AS `topping` ON `topping`.`id`=`rpt`.`id_topping` 
		LEFT JOIN `#__cleverdine_users` AS `u` ON `u`.`id`=`r`.`id_user`
		LEFT JOIN `#__users` AS `ju` ON `ju`.`id`=`u`.`jid` 
		WHERE `r`.`id`=".intval($order_id).";";
		
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$record = $dbo->loadAssocList();
			
			$order = $record[0];
			$order['items'] = array();
			
			$last_item = $last_group = -1;
			
			$entries_ids = array();
			$options_ids = array();
			$groups_ids = array();
			$toppings_ids = array();
			
			foreach( $record as $r ) {
				if( $last_item != $r['id_res_prod_assoc'] && !empty($r['id_res_prod_assoc']) ) {
					array_push($order['items'], array(
						"id_assoc" => $r['id_res_prod_assoc'],
						"id" => $r['id_product'],
						"id_option" => $r['id_product_option'],
						"name" => $r['product_name'],
						"option_name" => $r['option_name'],
						"price" => $r['product_price'],
						"quantity" => $r['product_quantity'],
						"notes" => $r['product_notes'],
						"toppings_groups" => array()
					));
					
					$last_item = $r['id_res_prod_assoc'];
					$last_group = -1;
					
					if( !in_array($r['id_product'], $entries_ids) ) {
						array_push($entries_ids, $r['id_product']);
					}
					if( !in_array($r['id_product_option'], $options_ids) ) {
						array_push($options_ids, $r['id_product_option']);
					}
				}
				
				if( $last_group != $r['id_group'] ) {
					if( $r['id_group'] > 0 ) {
						array_push($order['items'][count($order['items'])-1]['toppings_groups'], array(
							"id" => $r['id_group'],
							"title" => $r['group_title'],
							"toppings" => array()
						));
						
						if( !in_array($r['id_group'], $groups_ids) ) {
							array_push($groups_ids, $r['id_group']);
						}
					}
						
					$last_group = $r['id_group'];
				}
				
				if( $r['id_topping'] > 0 ) {
					array_push($order['items'][count($order['items'])-1]['toppings_groups'][count($order['items'][count($order['items'])-1]['toppings_groups'])-1]['toppings'], array(
						"id" => $r['id_topping'],
						"name" => $r['topping_name']
					));
					
					if( !in_array($r['id_topping'], $toppings_ids) ) {
						array_push($toppings_ids, $r['id_topping']);
					}
				}
				
			}

			// translations
			if( empty($langtag) ) {
				$langtag = $order['langtag'];
				if( empty($langtag) ) {
					$langtag = JFactory::getLanguage()->getTag();
				}
			}
			$order['langtag'] = $langtag;

			if( cleverdine::isMultilanguage() ) {

				// items

				$entries_translations 	= self::getTranslatedTakeawayProducts($entries_ids, $order['langtag']);
				$options_translations 	= self::getTranslatedTakeawayOptions($options_ids, $order['langtag']);
				$groups_translations 	= self::getTranslatedTakeawayGroups($groups_ids, $order['langtag']);
				$toppings_translations 	= self::getTranslatedTakeawayToppings($toppings_ids, $order['langtag']);
				
				for( $i = 0; $i < count($order['items']); $i++ ) {
					$item =& $order['items'][$i];
					
					$item['name'] = self::translate($item['id'], $item, $entries_translations, "name", "name");
					$item['option_name'] = self::translate($item['id_option'], $item, $options_translations, "option_name", "name");
					
					for( $j = 0; $j < count($item['toppings_groups']); $j++ ) {
						$group =& $item['toppings_groups'][$j];
						
						$group['title'] = self::translate($group['id'], $group, $groups_translations, "title", "name");
						
						for( $k = 0; $k < count($group['toppings']); $k++ ) {
							$topping =& $group['toppings'][$k];
							
							$topping['name'] = self::translate($topping['id'], $topping, $toppings_translations, "name", "name");
						}
					}
				}

				// payment

				if( !empty($order['id_payment']) && $order['id_payment'] > 0 ) {
					$payments_translations = self::getTranslatedPayments(array($order['id_payment']), $order['langtag']);

					$order['payment_name'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_name', 'name');
					$order['payment_note'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_note', 'note');
					$order['payment_prenote'] = self::translate($order['id_payment'], $order, $payments_translations, 'payment_prenote', 'prenote');
				}

				// custom fields

				$custom_fields_original 	= json_decode($order['custom_f'], true);
				$custom_fields_translated 	= array();

				foreach( $custom_fields_original as $k => $val ) {

					$q = "SELECT `id` FROM `#__cleverdine_custfields` WHERE `name`=".$dbo->quote($k)." LIMIT 1;";
					$dbo->setQuery($q);
					$dbo->execute();
					if( $dbo->getNumRows() ) {
						$id = $dbo->loadResult();

						$translation = cleverdine::getTranslatedCustomFields(array($id), $order['langtag']);

						if( !empty($translation[$id]['name']) ) {
							$custom_fields_translated[$translation[$id]['name']] = $val;
						} else {
							$custom_fields_translated[$k] = $val;
						}
					}

				}

				$order['custom_f'] = json_encode($custom_fields_translated);

			}
			
			return $order;
		}
		return false;
	}

	// TAKEAWAY ADMIN / OPERATORS E-MAIL

	public static function loadTakeAwayAdminEmailTemplate() {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."tk_mail_tmpls".DIRECTORY_SEPARATOR.self::getTakeawayMailAdminTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendAdminEmailTakeAway($order_details, $skipsession = false) {
		if( !$order_details ) return;

		// load default language
		self::loadLanguage(self::getDefaultLanguage());
	
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);
	
		$subject = JText::sprintf('VRTKADMINEMAILSUBJECT', $fromname);
	
		$tmpl = self::loadTakeAwayAdminEmailTemplate();
		$_html_content = self::parseTakeAwayAdminEmailTemplate($tmpl, $order_details, $skipsession);
		
		$send_when = self::getTakeawaySendMailWhen($skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		if( $send_when['admin'] != 0 && ( $send_when['admin'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			foreach( $admin_mail_list as $_m ) {
				$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
			}
		}
		
		if( $send_when['operator'] != 0 && ( $send_when['operator'] == 2 || $order_details['status'] == 'CONFIRMED' ) ) {
			foreach( self::getNotificationOperatorsMails(2) as $_m ) {
				$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
			}
		}
		
	}
	
	public static function parseTakeAwayAdminEmailTemplate($tmpl, $order_details, $skipsession=false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);

		$head_css_style = '';

		// parse payment name

		$payment_name = "";
		if( !empty($order_details['payment_name']) ) {
			$payment_name = $order_details['payment_name'];
		} else {
			$head_css_style .= '#order-payment {display: none;}';
		}

		$payment_notes = '';
		if( $order_details['status'] == 'CONFIRMED' ) {
			$payment_notes = $order_details['payment_note'];
		} else if( $order_details['status'] == 'PENDING' ) {
			$payment_notes = $order_details['payment_prenote'];
		}

		$total_cost = "";
		if( $order_details['total_to_pay'] > 0 ) {
			$total_cost = self::printPriceCurrencySymb($order_details['total_to_pay'], $curr_symb, $symb_pos, $skipsession);
		} else {
			$head_css_style .= '#order-total-cost {display: none;}';
		}

		// parse coupon string

		$coupon_str = "";
		if( !empty($order_details['coupon_str']) ) {
			list($code, $value, $pt) = explode(';;', $order_details['coupon_str']);
			$coupon_str = $code." : ".($pt == 1 ? $value.'%' : self::printPriceCurrencySymb($value, $curr_symb, $symb_pos, $skipsession));
		} else {
			$head_css_style .= '#order-coupon-code {display: none;}';
		}

		// parse service string

		$service_str = JText::_( ($order_details['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION') );

		// fetch cart details

		$cart_details = "";
		foreach( $order_details['items'] as $j => $item ) {
			
			$cart_details .= '<div class="cart-product">';

			$cart_details .= '<div class="item">';
			$cart_details .= '<div class="item-name">'.$item['name'].(!empty($item['option_name']) ? ' - '.$item['option_name'] : '').'</div>';
			$cart_details .= '<div class="item-quantity">x'.$item['quantity'].'</div>';
			if( $item['price'] > 0 ) {
				$cart_details .= '<div class="item-price">'.self::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, $skipsession).'</div>';
			}
			$cart_details .= '</div>';

			if( count($item['toppings_groups']) ) {

				$cart_details .= '<div class="toppings-container">';

				foreach( $item['toppings_groups'] as $group ) {
					
					$cart_details .= '<div class="toppings-group">';
					$cart_details .= '<div class="title">'.$group['title'].':</div>';
					$cart_details .= '<div class="toppings">';
					foreach( $group['toppings'] as $k => $topping ) {
						if( $k > 0 ) {
							$cart_details .= ', ';
						}
						$cart_details .= "<i>".$topping['name']."</i>";
					}
					$cart_details .= '</div>';
					$cart_details .= '</div>';
				}

				$cart_details .= '</div>';

			}

			if( strlen($item['notes']) ) {
				$cart_details .= '<div class="notes">'.$item['notes'].'</div>';
			}

			$cart_details .= '</div>';

		}

		// fetch cart grand total

		$cart_grand_total = '';

		if( $order_details['total_to_pay'] > 0 ) {

			// net
			$net = $order_details['total_to_pay']-$order_details['taxes']-$order_details['pay_charge']-$order_details['delivery_charge'];

			$cart_grand_total .= '<div class="total-row">
				<div class="label">'.JText::_('VRTKCARTTOTALNET').'</div>
				<div class="amount">'.self::printPriceCurrencySymb($net, $curr_symb, $symb_pos, $skipsession).'</div>
			</div>';

			// delivery charge
			if( $order_details['delivery_charge'] != 0 ) {
				$cart_grand_total .= '<div class="total-row">
					<div class="label">'.JText::_('VRTKCARTTOTALSERVICE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['delivery_charge'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// payment charge
			if( $order_details['pay_charge'] != 0 ) {
				$cart_grand_total .= '<div class="total-row">
					<div class="label">'.JText::_('VRTKCARTTOTALPAYCHARGE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['pay_charge'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// taxes
			if( $order_details['taxes'] > 0 ) {
				$cart_grand_total .= '<div class="total-row red">
					<div class="label">'.JText::_('VRTKCARTTOTALTAXES').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['taxes'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// discount
			if( $order_details['discount_val'] > 0 ) {
				$cart_grand_total .= '<div class="total-row red">
					<div class="label">'.JText::_('VRTKCARTTOTALDISCOUNT').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['discount_val'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// grand total
			if( $order_details['taxes'] > 0 ) {
				$cart_grand_total .= '<div class="total-row grand-total">
					<div class="label">'.JText::_('VRTKCARTTOTALPRICE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['total_to_pay'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

		}

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// joomla user details
		$user_details = '';
		if( strlen($order_details['user_email']) > 0 ) {
			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::_('VRUSERDETAILS').'</div>
				<div class="customer-details">
					<div class="info">
						<div class="label">'.JText::_('VRREGFULLNAME').':</div>
						<div class="value">'.$order_details['user_name'].'</div>
					</div>
					<div class="info">
						<div class="label">'.JText::_('VRREGUNAME').':</div>
						<div class="value">'.$order_details['user_uname'].'</div>
					</div>
					<div class="info">
						<div class="label">'.JText::_('VRREGEMAIL').':</div>
						<div class="value">'.$order_details['user_email'].'</div>
					</div>
				</div>
			</div>';
		}

		// order link

		$order_link_href = JUri::root().'index.php?option=com_cleverdine&view=order&ordnum='.$order_details['id'].'&ordkey='.$order_details['sid'].'&ordtype=1';

		$confirmation_link = '';
		if( $order_details['status'] == 'PENDING' ) {
			$order_conf_link = JUri::root().'index.php?option=com_cleverdine&task=confirmord&oid='.$order_details['id'].'&conf_key='.$order_details['conf_key'].'&tid=1';

			$confirmation_link .= '<div class="order-link">';
			$confirmation_link .= '<div class="title">'.JText::_('VRCONFIRMATIONLINK').'</div>';
			$confirmation_link .= '<div class="content">';
			$confirmation_link .= '<a href="'.$order_conf_link.'">'.$order_conf_link.'</a>';
			$confirmation_link .= '</div>';
			$confirmation_link .= '</div>';
		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{order_number}', $order_details['id'], $tmpl);
		$tmpl = str_replace('{order_key}', $order_details['sid'], $tmpl);
		$tmpl = str_replace('{order_date_time}', date($date_format.' '.$time_format, $order_details['checkin_ts']), $tmpl );
		$tmpl = str_replace('{order_status_class}', strtolower($order_details['status']), $tmpl);
		$tmpl = str_replace('{order_status}', JText::_('VRRESERVATIONSTATUS'.$order_details['status']), $tmpl);
		$tmpl = str_replace('{order_payment}', $payment_name, $tmpl);
		$tmpl = str_replace('{order_payment_notes}', $payment_notes, $tmpl);
		$tmpl = str_replace('{order_delivery_service}', $service_str, $tmpl);
		$tmpl = str_replace('{order_total_cost}', $total_cost, $tmpl);
		$tmpl = str_replace('{order_coupon_code}', $coupon_str, $tmpl);
		$tmpl = str_replace('{cart_details}', $cart_details, $tmpl);
		$tmpl = str_replace('{cart_grand_total}', $cart_grand_total, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{user_details}', $user_details, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link_href, $tmpl);
		$tmpl = str_replace('{confirmation_link}', $confirmation_link, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{head_css_style}', $head_css_style, $tmpl);

		return $tmpl;
	}

	// TAKEAWAY CUSTOMER E-MAIL
	
	public static function loadTakeAwayEmailTemplate() {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."tk_mail_tmpls".DIRECTORY_SEPARATOR.self::getTakeawayMailTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendCustomerEmailTakeAway($order_details, $skipsession=false) {
		if (!$order_details) return;

		self::loadLanguage($order_details['langtag']);
	
		$adminmail = self::getAdminMailList($skipsession);
		$adminmail = $adminmail[0];
		$sendermail = self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $adminmail;
		}
		$fromname = self::getRestaurantName($skipsession);
	
		$subject = JText::sprintf('VRCUSTOMEREMAILTKSUBJECT', $fromname);
	
		$tmpl = self::loadTakeAwayEmailTemplate();
		$_html_content = self::parseTakeAwayEmailTemplate($tmpl, $order_details, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		$vik->sendMail($sendermail, $fromname, $order_details['purchaser_mail'], $adminmail, $subject, $_html_content);
		
	}
	
	public static function parseTakeAwayEmailTemplate($tmpl, $order_details, $skipsession=false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);

		$head_css_style = '';

		// parse payment name

		$payment_name = "";
		if( !empty($order_details['payment_name']) ) {
			$payment_name = $order_details['payment_name'];
		} else {
			$head_css_style .= '#order-payment {display: none;}';
		}

		$payment_notes = '';
		if( $order_details['status'] == 'CONFIRMED' ) {
			$payment_notes = $order_details['payment_note'];
		} else if( $order_details['status'] == 'PENDING' ) {
			$payment_notes = $order_details['payment_prenote'];
		}

		$total_cost = "";
		if( $order_details['total_to_pay'] > 0 ) {
			$total_cost = self::printPriceCurrencySymb($order_details['total_to_pay'], $curr_symb, $symb_pos, $skipsession);
		} else {
			$head_css_style .= '#order-total-cost {display: none;}';
		}

		// parse coupon string

		$coupon_str = "";
		if( !empty($order_details['coupon_str']) ) {
			list($code, $value, $pt) = explode(';;', $order_details['coupon_str']);
			$coupon_str = $code." : ".($pt == 1 ? $value.'%' : self::printPriceCurrencySymb($value, $curr_symb, $symb_pos, $skipsession));
		} else {
			$head_css_style .= '#order-coupon-code {display: none;}';
		}

		// parse service string

		$service_str = JText::_( ($order_details['delivery_service'] ? 'VRTKORDERDELIVERYOPTION' : 'VRTKORDERPICKUPOPTION') );

		// parse track order link

		$track_order_link = '';

		if( $order_details['status'] == 'CONFIRMED' ) {
			$track_order_link = JText::sprintf(
				'VRTRACKORDERCHECKLINK', 
				JUri::root().'index.php?option=com_cleverdine&task=trackorder&oid='.$order_details['id'].'&sid='.$order_details['sid'].'&tid=1'
			);
		} else {
			$head_css_style .= '.track-order-box {display: none;}';
		}

		// fetch cart details

		$cart_details = "";
		foreach( $order_details['items'] as $j => $item ) {
			
			$cart_details .= '<div class="cart-product">';

			$cart_details .= '<div class="item">';
			$cart_details .= '<div class="item-name">'.$item['name'].(!empty($item['option_name']) ? ' - '.$item['option_name'] : '').'</div>';
			$cart_details .= '<div class="item-quantity">x'.$item['quantity'].'</div>';
			if( $item['price'] > 0 ) {
				$cart_details .= '<div class="item-price">'.self::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos, $skipsession).'</div>';
			}
			$cart_details .= '</div>';

			if( count($item['toppings_groups']) ) {

				$cart_details .= '<div class="toppings-container">';

				foreach( $item['toppings_groups'] as $group ) {
					
					$cart_details .= '<div class="toppings-group">';
					$cart_details .= '<div class="title">'.$group['title'].':</div>';
					$cart_details .= '<div class="toppings">';
					foreach( $group['toppings'] as $k => $topping ) {
						if( $k > 0 ) {
							$cart_details .= ', ';
						}
						$cart_details .= "<i>".$topping['name']."</i>";
					}
					$cart_details .= '</div>';
					$cart_details .= '</div>';
				}

				$cart_details .= '</div>';

			}

			if( strlen($item['notes']) ) {
				$cart_details .= '<div class="notes">'.$item['notes'].'</div>';
			}

			$cart_details .= '</div>';

		}

		// fetch cart grand total

		$cart_grand_total = '';

		if( $order_details['total_to_pay'] > 0 ) {

			// net
			$net = $order_details['total_to_pay']-$order_details['taxes']-$order_details['pay_charge']-$order_details['delivery_charge'];

			$cart_grand_total .= '<div class="total-row">
				<div class="label">'.JText::_('VRTKCARTTOTALNET').'</div>
				<div class="amount">'.self::printPriceCurrencySymb($net, $curr_symb, $symb_pos, $skipsession).'</div>
			</div>';

			// delivery charge
			if( $order_details['delivery_charge'] != 0 ) {
				$cart_grand_total .= '<div class="total-row">
					<div class="label">'.JText::_('VRTKCARTTOTALSERVICE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['delivery_charge'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// payment charge
			if( $order_details['pay_charge'] != 0 ) {
				$cart_grand_total .= '<div class="total-row">
					<div class="label">'.JText::_('VRTKCARTTOTALPAYCHARGE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['pay_charge'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// discount
			/*
			if( $order_details['discount_val'] > 0 ) {
				$cart_grand_total .= '<div class="total-row red">
					<div class="label">'.JText::_('VRTKCARTTOTALDISCOUNT').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['discount_val'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}
			*/

			// taxes
			if( $order_details['taxes'] > 0 ) {
				$cart_grand_total .= '<div class="total-row red">
					<div class="label">'.JText::_('VRTKCARTTOTALTAXES').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['taxes'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

			// grand total
			if( $order_details['taxes'] > 0 ) {
				$cart_grand_total .= '<div class="total-row grand-total">
					<div class="label">'.JText::_('VRTKCARTTOTALPRICE').'</div>
					<div class="amount">'.self::printPriceCurrencySymb($order_details['total_to_pay'], $curr_symb, $symb_pos, $skipsession).'</div>
				</div>';
			}

		}

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// joomla user details
		$user_details = '';
		if( strlen($order_details['user_email']) > 0 ) {
			$user_details = '<div class="separator"> </div>
			<div class="customer-details-wrapper">
				<div class="title">'.JText::_('VRUSERDETAILS').'</div>
				<div class="customer-details">
					<div class="info">
						<div class="label">'.JText::_('VRREGFULLNAME').':</div>
						<div class="value">'.$order_details['user_name'].'</div>
					</div>
					<div class="info">
						<div class="label">'.JText::_('VRREGUNAME').':</div>
						<div class="value">'.$order_details['user_uname'].'</div>
					</div>
					<div class="info">
						<div class="label">'.JText::_('VRREGEMAIL').':</div>
						<div class="value">'.$order_details['user_email'].'</div>
					</div>
				</div>
			</div>';
		}

		// order link

		$order_link_href = JUri::root().'index.php?option=com_cleverdine&view=order&ordnum='.$order_details['id'].'&ordkey='.$order_details['sid'].'&ordtype=1';

		$cancellation_link = "";
		if( self::canUserCancelOrder($order_details['checkin_ts'], 1, $skipsession) && $order_details['status'] == 'CONFIRMED' ) {
			$cancellation_link_href = $order_link_href."#cancel";

			$cancellation_link .= '<div class="order-link">';
			$cancellation_link .= '<div class="title">'.JText::_('VRCANCELORDERTITLE').'</div>';
			$cancellation_link .= '<div class="content">';
			$cancellation_link .= '<a href="'.$cancellation_link_href.'">'.$cancellation_link_href.'</a>';
			$cancellation_link .= '</div>';
			$cancellation_link .= '</div>';
		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{order_number}', $order_details['id'], $tmpl);
		$tmpl = str_replace('{order_key}', $order_details['sid'], $tmpl);
		$tmpl = str_replace('{order_date_time}', date($date_format.' '.$time_format, $order_details['checkin_ts']), $tmpl );
		$tmpl = str_replace('{order_status_class}', strtolower($order_details['status']), $tmpl);
		$tmpl = str_replace('{order_status}', JText::_('VRRESERVATIONSTATUS'.$order_details['status']), $tmpl);
		$tmpl = str_replace('{order_payment}', $payment_name, $tmpl);
		$tmpl = str_replace('{order_payment_notes}', $payment_notes, $tmpl);
		$tmpl = str_replace('{order_delivery_service}', $service_str, $tmpl);
		$tmpl = str_replace('{order_total_cost}', $total_cost, $tmpl);
		$tmpl = str_replace('{order_coupon_code}', $coupon_str, $tmpl);
		$tmpl = str_replace('{cart_details}', $cart_details, $tmpl);
		$tmpl = str_replace('{cart_grand_total}', $cart_grand_total, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{user_details}', $user_details, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link_href, $tmpl);
		$tmpl = str_replace('{cancellation_link}', $cancellation_link, $tmpl);
		$tmpl = str_replace('{track_order_link}', $track_order_link, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{head_css_style}', $head_css_style, $tmpl);

		return $tmpl;
	}

	// TAKEAWAY CANCELLATION E-MAIL (for admin)

	public static function loadTakeAwayCancellationEmailTemplate() {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."tk_mail_tmpls".DIRECTORY_SEPARATOR.self::getTakeawayMailCancellationTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendCancellationEmailTakeAway($order_details, $skipsession = false) {
		if( !$order_details ) return;

		// load default language
		self::loadLanguage(self::getDefaultLanguage());
	
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);
	
		$subject = JText::sprintf('VRORDERCANCELLEDSUBJECT', $fromname);
	
		$tmpl = self::loadTakeAwayCancellationEmailTemplate();
		$_html_content = self::parseTakeAwayCancellationEmailTemplate($tmpl, $order_details, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		
		foreach( $admin_mail_list as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
		}
		
		foreach( self::getNotificationOperatorsMails(2) as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
		}
		
	}
	
	public static function parseTakeAwayCancellationEmailTemplate($tmpl, $order_details, $skipsession=false) {

		// get settings

		$curr_symb 		= self::getCurrencySymb($skipsession);
		$symb_pos 		= self::getCurrencySymbPosition($skipsession);
		$date_format 	= self::getDateFormat($skipsession);
		$time_format 	= self::getTimeFormat($skipsession);

		// retrieve cancellation content

		$cancellation_content = JText::_('VRORDERCANCELLEDCONTENT');

		// build cancellation reason

		$cancellation_reason = '';
		if( !empty($order_details['cancellation_reason']) && strlen($order_details['cancellation_reason']) ) {
			$cancellation_reason = '<div class="cancellation-reason">'.JText::sprintf('VRCANCCUSTOMERSAID', $order_details['cancellation_reason']).'</div>';
		}

		// fetch order details

		$url = JUri::root().'administrator/index.php?option=com_cleverdine&task=edittkreservation&cid[]='.$order_details['id'];

		$order_summary = '<div class="order">';

		$order_summary .= '<div class="content">';
		$order_summary .= '<div class="left">'.$order_details['id'].' - '.$order_details['sid'].'</div>';
		$order_summary .= '<div class="right">'.JText::_('VRRESERVATIONSTATUSCANCELLED').'</div>';
		$order_summary .= '</div>';

		$order_summary .= '<div class="subcontent">';
		$order_summary .= '<div class="left">'.date($date_format.' '.$time_format, $order_details['checkin_ts']).'</div>';
		$order_summary .= '<div class="center">'.(!empty($order_details['purchaser_nominative']) ? $order_details['purchaser_nominative'] : $order_details['purchaser_mail']).'</div>';
		$order_summary .= '<div class="right">'.self::printPriceCurrencySymb($order_details['total_to_pay'], $curr_symb, $symb_pos, $skipsession).'</div>';
		$order_summary .= '</div>';
		$order_summary .= '<div class="link"><a href="'.$url.'">'.$url.'</a></div>';

		$order_summary .= '</div>';

		// customer details
		
		$custom_fields = json_decode($order_details['custom_f'], true);

		$customer_details = "";
		foreach( $custom_fields as $kc => $vc ) {
			if( strlen($vc) ) {
				$customer_details .= '<div class="info">';
				$customer_details .= '<div class="label">'.JText::_($kc).':</div>';
				$customer_details .= '<div class="value">'.$vc.'</div>';
				$customer_details .= '</div>';
			}
		}

		// order link

		$order_link_href = JUri::root().'administrator/index.php?option=com_cleverdine&task=tkreservations&tools=1&ordnum='.$order_details['sid'];
		$order_link = '<div class="order-link"><a href="'.$order_link_href.'">'.$order_link_href.'</a></div>';		

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{cancellation_content}', $cancellation_content, $tmpl);
		$tmpl = str_replace('{cancellation_reason}', $cancellation_reason, $tmpl);
		$tmpl = str_replace('{order_summary}', $order_summary, $tmpl);
		$tmpl = str_replace('{customer_details}', $customer_details, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{order_link}', $order_link, $tmpl);

		return $tmpl;
		
	}

	// TAKEAWAY REVIEW E-MAIL (for admin)

	public static function fetchReview($id, $conf_key = '', $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$conf_claus = '';
		if( !empty($conf_key) ) {
			if( strlen($conf_key) == 12 ) {
				$conf_claus = "AND `r`.`conf_key`=".$dbo->quote($conf_key);
			} else {
				return null;
			}
		}

		$q = "SELECT `r`.*, `e`.`name` AS `takeaway_product_name`, `e`.`description` AS `takeaway_product_desc`, `e`.`img_path` AS `takeaway_product_image`
		FROM `#__cleverdine_reviews` AS `r` 
		LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `e`.`id`=`r`.`id_takeaway_product`
		WHERE `r`.`id`=$id $conf_claus LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() == 0 ) {
			return null;
		}

		return $dbo->loadAssoc();
	}

	public static function loadTakeAwayReviewEmailTemplate() {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."tk_mail_tmpls".DIRECTORY_SEPARATOR.self::getTakeawayMailReviewTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	public static function sendReviewEmailTakeAway($review, $skipsession = false) {
		if( !$review ) return;

		// load default language
		self::loadLanguage(self::getDefaultLanguage());
	
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);
	
		$subject = JText::sprintf('VRREVIEWSUBJECT', $fromname);
	
		$tmpl = self::loadTakeAwayReviewEmailTemplate();
		$_html_content = self::parseTakeAwayReviewEmailTemplate($tmpl, $review, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		
		foreach( $admin_mail_list as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $review['email'], $subject, $_html_content);
		}
		
	}
	
	public static function parseTakeAwayReviewEmailTemplate($tmpl, $review, $skipsession=false) {

		// get settings

		$date_format = self::getDateFormat($skipsession);
		$time_format = self::getTimeFormat($skipsession);

		// retrieve review content

		$review_content = JText::sprintf('VRREVIEWCONTENT', $review['email'], $review['name']);

		// fetch product details

		$review_product = '';
		if( $review['id_takeaway_product'] > 0 ) {

			$review_product .= '<div class="review-product">';
			if( !empty($review['takeaway_product_image']) ) {
				$review_product .= '<div class="prod-left">
					<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$review['takeaway_product_image'].'"/>
				</div>';
			}
			$review_product .= '<div class="prod-center">
					<div class="prod-name">'.$review['takeaway_product_name'].'</div>
					'.(!empty($review['takeaway_product_desc']) ? '<div class="prod-desc">'.$review['takeaway_product_desc'].'</div>' : '').'
				</div>
			</div>';

		}

		// fetch review summary

		$review_rating = '';
		for( $i = 1; $i <= $review['rating']; $i++ ) {
			$review_rating .= '<img src="'.JUri::root().'components/com_cleverdine/assets/css/images/rating-star.png">';
		}

		$review_title = $review['title'];

		$review_comment = (!empty($review['comment']) ? $review['comment'] : '<small>'.JText::_('VRREVIEWNOCOMMENT').'</small>');

		$review_verified = ($review['verified'] ? JText::_('VRREVIEWVERIFIED') : '');

		// confirmation link
		$conf_link_href = '';
		$conf_link_style = 'display:none;';
		if( !$review['published'] ) {
			$conf_link_style = '';
			$conf_link_href = JUri::root().'index.php?option=com_cleverdine&task=approve_review&id='.$review['id'].'&conf_key='.$review['conf_key'];
		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{review_content}', $review_content, $tmpl);
		$tmpl = str_replace('{review_product}', $review_product, $tmpl);
		$tmpl = str_replace('{review_rating}', $review_rating, $tmpl);
		$tmpl = str_replace('{review_title}', $review_title, $tmpl);
		$tmpl = str_replace('{review_comment}', $review_comment, $tmpl);
		$tmpl = str_replace('{review_verified}', $review_verified, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);
		$tmpl = str_replace('{confirmation_link}', $conf_link_href, $tmpl);
		$tmpl = str_replace('{confirmation_link_style}', $conf_link_style, $tmpl);

		return $tmpl;
		
	}

	/*
	 * ACTION = int
	 * - 0 : restaurant
	 * - 1 : take-away 
	 */
	 
	public static function sendSmsAction( $phone_number, $order_info, $action=0, $skip_session=false ) {
		
		$_str = '';
		
		$sms_api_name = self::getSmsApi($skip_session);
		$sms_api_path = JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'smsapi'.DIRECTORY_SEPARATOR.$sms_api_name;
		
		if( file_exists( $sms_api_path ) && strlen($sms_api_name) > 0 ) {
			require_once $sms_api_path;
			
			$sms_api_when = self::getSmsApiWhen($skip_session);
			$sms_api_to = self::getSmsApiTo($skip_session);
			$sms_api_params = self::getSmsApiFields($skip_session);
			
			$sms_api = new VikSmsApi( $order_info, $sms_api_params );
			
			$response_obj = NULL;
			
			if( $sms_api_when == 2 || $sms_api_when == $action ) {
				
				if( $sms_api_to == 0 || $sms_api_to == 2 ) {
					// send message to customer
					$_str = self::getSmsCustomerTextMessage($order_info, $action);
					$response_obj = $sms_api->sendMessage($phone_number, $_str);
				}
				
				if( $sms_api_to == 1 || $sms_api_to == 2 ) {
					// send message to administrator
					$_str = self::getSmsAdminTextMessage($order_info, $action);
					$response_obj = $sms_api->sendMessage(self::getSmsApiAdminPhoneNumber($skip_session), $_str);
				}
				
				if( !$sms_api->validateResponse($response_obj) ) {
					self::sendAdminMailSmsFailed($sms_api->getLog());
				}
				
			}

		}
		
	}

	public static function getSmsCustomerTextMessage($order_info, $action=0, $skipsession=false) {
		$def_lang = JFactory::getLanguage()->getTag();
		$sms_map = array();
		if( $action == 0 ) {
			$sms_map = json_decode(self::getFieldFromConfig('smstmplcust', 'vrGetSMSTmplCust', $skipsession), true);
		} else {
			$sms_map = json_decode(self::getFieldFromConfig('smstmpltkcust', 'vrGetSMSTmplTakeawayCust', $skipsession), true);
		}
		
		$sms = "";
		if( !empty($sms_map[$def_lang]) ) {
			$sms = $sms_map[$def_lang];
		} else {
			if( $action == 0 ) {
				$sms = JText::_('VRSMSMESSAGECUSTOMER');
			} else {
				$sms = JText::_('VRSMSMESSAGETKCUSTOMER');
			}
		}
		
		return self::parseContentSMS($order_info, $action, $sms);
	}

	public static function getSmsAdminTextMessage($order_info, $action=0, $skipsession=false) {
		$sms = "";
		if( $action == 0 ) {
			$sms = self::getFieldFromConfig('smstmpladmin', 'vrGetSMSTmplAdmin', $skipsession);
		} else {
			$sms = self::getFieldFromConfig('smstmpltkadmin', 'vrGetSMSTmplTakeawayAdmin', $skipsession);
		}
		
		if( empty($sms) ) {
			if( $action == 0 ) {
				$sms = JText::_('VRSMSMESSAGEADMIN');
			} else {
				$sms = JText::_('VRSMSMESSAGETKADMIN');
			}
		}
		
		return self::parseContentSMS($order_info, $action, $sms);
	}
	
	private static function parseContentSMS($order_info, $action=0, $sms) {
		if( $action == 0 ) {
			$sms = str_replace('{total_cost}', self::printPriceCurrencySymb($order_info['deposit']), $sms);
			$sms = str_replace('{checkin}', date(self::getDateFormat().' '.self::getTimeFormat(), $order_info['checkin_ts']), $sms);
			$sms = str_replace('{people}', $order_info['people'], $sms);
			$sms = str_replace('{company}', self::getRestaurantName(), $sms);
			$sms = str_replace('{customer}', $order_info['purchaser_nominative'], $sms);
			$sms = str_replace('{created_on}', date(self::getDateFormat().' '.self::getTimeFormat(), $order_info['created_on']), $sms);
		} else {
			$sms = str_replace('{total_cost}', self::printPriceCurrencySymb($order_info['total_to_pay']), $sms);
			$sms = str_replace('{checkin}', date(self::getDateFormat().' '.self::getTimeFormat(), $order_info['checkin_ts']), $sms);
			$sms = str_replace('{company}', self::getRestaurantName(), $sms);
			$sms = str_replace('{customer}', $order_info['purchaser_nominative'], $sms);
			$sms = str_replace('{created_on}', date(self::getDateFormat().' '.self::getTimeFormat(), $order_info['created_on']), $sms);
		}
		
		return $sms;
	}
	
	public static function sendAdminMailSmsFailed($text) {
		$vik = new VikApplication(VersionListener::getID());
					
		$admin_mail_list = self::getAdminMailList();
		
		$subject = JText::sprintf('VRSMSFAILEDSUBJECT', self::getRestaurantName());
		
		foreach( $admin_mail_list as $_m ) {
			$vik->sendMail($_m, $_m, $_m, $_m, $subject, $text);
		}
	}
	
	/**
	 * Check for deals
	 */
	 
	public static function checkForDeals(&$cart) {
		self::loadDealsLibrary();
		$deals = DealsHandler::getAvailableFullDeals( $cart->getCheckinTimestamp() );
		
		if( count($deals) == 0 || $cart->getCartRealLength() == 0 ) {
			return;
		}

		// clear deals to always replace
		$sample = new TakeAwayDiscount(0, 0, 0, 0);
		$sample->setType(6);
		$cart->deals()->removeAt(
			$cart->deals()->indexOfType($sample)
		);
		////
		
		$items_list = $cart->getItemsList();
		
		foreach( $deals as $deal ) {
				
			// total discount on combinations
			if( $deal['type'] == 1 ) {
				$required_min_occurrency = -1;
				$atleast_count = 0;
				foreach( $deal['products'] as $prod ) {
					$occurrency = 0;
					foreach( $items_list as $item_index => $item ) {
						$opt_id = $item->getVariationID();
						if( empty($opt_id) ) {
							$opt_id = -1;
						}
						
						if( 
							$item->getPrice() > 0 && 
							$item->getItemID() == $prod['id_product'] && 
							( $opt_id == $prod['id_option'] || $opt_id == -1 ) && 
							$item->getQuantity() >= $prod['quantity']
						) {
							$occurrency += intval($item->getQuantity()/$prod['quantity']);
						}
					}
					
					if( $prod['required'] == 1 ) {
						if( $required_min_occurrency == -1 || $occurrency < $required_min_occurrency ) {
							$required_min_occurrency = $occurrency;
						}
					} else {
						$atleast_count += $occurrency;
					}
				}
				
				if( $required_min_occurrency == -1 ) {
					$required_min_occurrency = $atleast_count;
				} 
				
				/*
				 * needed to accept deals without AT_LEAST products.
				 * @since 	1.7
				 */
				else if( $required_min_occurrency > 0 && $atleast_count == 0 ) {
					$atleast_count = $required_min_occurrency;
				}
				//

				$min_occurrency = min(array($required_min_occurrency, $atleast_count));
				
				$MIN_QUANTITY_TO_PUSH = $deal['min_quantity'];
				$min_occurrency = intval($min_occurrency/$MIN_QUANTITY_TO_PUSH);
				
				if( $deal['max_quantity'] != -1 && $min_occurrency > $deal['max_quantity'] ) {
					$min_occurrency = $deal['max_quantity'];
				}
				
				$discount = new TakeAwayDiscount($deal['id'], $deal['amount'], $deal['percentot'], $min_occurrency);
				if( $min_occurrency > 0 ) {
					$index = $cart->deals()->indexOf($discount);
					if( $index != -1 ) {
						$discount->removeQuantity($cart->deals()->get($index)->getQuantity());
					}
					$cart->deals()->insert($discount);
				} else {
					$cart->deals()->remove($discount);
				}
				
			} 
			
			// product discount
			else if( $deal['type'] == 2 ) {
				$deal_curr_quantity = 0;
				foreach( $items_list as $item_index => $item ) {
					$found = false;
					$cart_item = $cart->getItemAt($item_index);
					
					// skip this element -> probably has been removed from another one item
					if( $cart_item === null ) {
						continue;
					}
					
					if( $cart_item->getDealID() == $deal['id'] ) {
						$cart_item->setDealQuantity(0);
						$cart_item->setPrice($cart_item->getOriginalPrice());
						$item->setDealID(-1);
					}
					
					for( $k = 0; $k < count($deal['products']) && !$found; $k++ ) {
						
						$prod = $deal['products'][$k];
						$found = (
							$prod['id_product'] == $item->getItemID() &&
							( $prod['id_option'] == -1 || $prod['id_option'] == $item->getVariationID() ) && 
							$prod['quantity'] <= $item->getQuantity()
						);
						
						if( $found ) {
							// apply discount to item
							$cart_item->setDealQuantity( intval($cart_item->getQuantity()/$prod['quantity']) );
							if( $deal['percentot'] == 1 ) {
								$cart_item->setPrice($cart_item->getPrice()-$cart_item->getPrice()*$deal['amount']/100.0);
							} else {
								$cart_item->setPrice($cart_item->getPrice()-$deal['amount']);
							}
							$cart_item->setDealID($deal['id']);
							
							$deal_curr_quantity++;
						}
						
					}

					if( $deal['max_quantity'] != -1 && $deal_curr_quantity >= $deal['max_quantity'] ) {
						// no more deals
						break;
					}
					
				}
			} 
			
			// free item(s) with combinations
			else if( $deal['type'] == 3 ) {
				$required_min_occurrency = -1;
				$atleast_count = 0;
				foreach( $deal['products'] as $prod ) {
					$occurrency = 0;
					foreach( $items_list as $item_index => $item ) {
						$opt_id = $item->getVariationID();
						if( empty($opt_id) ) {
							$opt_id = -1;
						}
						
						if( 
							//$item->getPrice() > 0 && @deprecated
							($item->getPrice() > 0 || $item->getDealID() != -1) && 
							$item->getItemID() == $prod['id_product'] && 
							( $opt_id == $prod['id_option'] || $opt_id == -1 || $prod['id_option'] <= 0 ) && 
							$item->getQuantity() >= $prod['quantity']
						) {
							$occurrency += intval($item->getQuantity()/$prod['quantity']);
						}
					}
					
					if( $prod['required'] == 1 ) {
						if( $required_min_occurrency == -1 || $occurrency < $required_min_occurrency ) {
							$required_min_occurrency = $occurrency;
						}
					} else {
						$atleast_count += $occurrency;
					}
				}
				
				if( $required_min_occurrency == -1 ) {
					$required_min_occurrency = $atleast_count;
				}
				$min_occurrency = min(array($required_min_occurrency, $atleast_count));
				
				$MIN_QUANTITY_TO_PUSH = $deal['min_quantity'];
				$min_occurrency = intval($min_occurrency/$MIN_QUANTITY_TO_PUSH);
				
				$gift_count = 0;
				foreach( $items_list as $item_index => $item ) {
					$found = false;
					for( $k = 0; $k < count($deal['gifts']) && !$found; $k++ ) {
						$prod = $deal['gifts'][$k];
						$found = (
							$prod['id_product'] == $item->getItemID() &&
							( $prod['id_option'] == -1 || $prod['id_option'] == $item->getVariationID() ) && 
							$prod['quantity'] <= $item->getQuantity()
						);
						
						if( $found ) {
							$units_to_add = $item->getQuantity();
							if( $units_to_add <= 0 || $units_to_add > $min_occurrency-$gift_count) {
								$units_to_add = max(array(1, $min_occurrency-$gift_count));
							}
							
							if( $item->getDealID() == $deal['id'] ) {
								if( $min_occurrency-($gift_count+$units_to_add) >= 0 && ($deal['max_quantity'] == -1 || $gift_count+$units_to_add <= $deal['max_quantity']) ) {
									$item->setDealQuantity($units_to_add);
								} else {
									$item->setPrice($item->getOriginalPrice());
									$item->setDealQuantity(0);
									$item->setDealID(-1);
									$item->setRemovable(true);
								}
							} else if( $min_occurrency-($gift_count+$units_to_add) >= 0 && ($deal['max_quantity'] == -1 || $gift_count+$units_to_add <= $deal['max_quantity']) ) {
								$item->setDealQuantity($units_to_add);
								$item->setDealID($deal['id']);
								$item->setPrice(0.0);
							}
							$gift_count += $item->getDealQuantity();
						}
					}
				}

				if( $deal['auto_insert'] ) {
					for( $k = 0; $k < count($deal['gifts']) && ($min_occurrency-$gift_count > 0); $k++ ) {
						$gift = $deal['gifts'][$k];
						
						$units = intval(($min_occurrency-$gift_count)/$gift['quantity']);
					   
						$new_item = new TakeAwayItem(
							$gift['id_takeaway_menu'],
							$gift['id_product'], 
							$gift['id_option'], 
							$gift['product_name'], 
							$gift['option_name'], 
							floatval($gift['product_price'])+floatval($gift['option_price']), 
							$units, 
							$gift['ready'],
							0, // no taxes
							"" // notes
						);
							
						if( $deal['max_quantity'] == -1 || $gift_count+$units <= $deal['max_quantity'] ) {
							$new_item->setDealID($deal['id']);
							$new_item->setDealQuantity($units);
							$new_item->setPrice(0.0);
							$new_item->setRemovable(false);
							
							$gift_count += $units;
						
							$cart->addItem($new_item);
						}
				   }
				}
			} 
			
			// free item(s) with total cost
			else if( $deal['type'] == 4 ) {
				
				$deal_count = 0;
				foreach( $items_list as $item_index => $item ) {
					if( $item->getDealID() == $deal['id'] && $cart->getTotalCost() >= $deal['amount'] && $deal['max_quantity'] > $deal_count ) {
						$deal_count += $item->getDealQuantity();
						
						$free_space = $deal['max_quantity']-$deal_count;
						if( $item->getQuantity()-$item->getDealQuantity() <= $free_space ) {
							$deal_count += ($item->getQuantity()-$item->getDealQuantity());
							
							$item->setDealQuantity($item->getQuantity());
						} else if( $free_space > 0 ) {
							$deal_count += min(array($item->getQuantity(), $free_space));
							
							$item->setDealQuantity($item->getDealQuantity() + min(array($item->getQuantity(), $free_space)));
						}
						
					} else {
						$found = $to_remove = false;
						
						if( $item->getDealID() == $deal['id'] ) {
							$item->setDealQuantity(0);
							$item->setPrice($item->getOriginalPrice());
							$item->setDealID(-1);
							$to_remove = true;
						}
				
						for( $k = 0; $k < count($deal['gifts']) && !$found; $k++ ) {
							$prod = $deal['gifts'][$k];
							$found = (
								$prod['id_product'] == $item->getItemID() &&
								( $prod['id_option'] == -1 || $prod['id_option'] == $item->getVariationID() ) && 
								$prod['quantity'] <= $item->getQuantity()
							);
							
							if( $found && $deal['max_quantity'] > $deal_count && $cart->getTotalCost()-$item->getPrice()*$prod['quantity'] >= $deal['amount'] ) {
								// apply discount to item
								$item->setDealID($deal['id']);
								$item->setDealQuantity($prod['quantity']);
								$item->setPrice(0.0);
								
								$deal_count += $item->getDealQuantity();
								
								$free_space = $deal['max_quantity']-$deal_count;
								if( $item->getQuantity()-$item->getDealQuantity() <= $free_space ) {
									$deal_count += ($item->getQuantity()-$item->getDealQuantity());
									
									$item->setDealQuantity($item->getQuantity());
								} else if( $free_space > 0 ) {
									$deal_count += min(array($item->getQuantity(), $free_space));
									
									$item->setDealQuantity($item->getDealQuantity() + min(array($item->getQuantity(), $free_space)));
								}
							}
							
						}

						if( $to_remove && $item->getDealID() == -1 ) {
							$item->setQuantity(0);
						}
						
					}
				}
				
				if( $cart->getTotalCost() >= $deal['amount'] && $deal['auto_insert'] && $deal['max_quantity'] > $deal_count ) {
					foreach( $deal['gifts'] as $gift ) {
						$new_item = new TakeAwayItem(
							$gift['id_takeaway_menu'],
							$gift['id_product'], 
							$gift['id_option'], 
							$gift['product_name'], 
							$gift['option_name'], 
							floatval($gift['product_price'])+floatval($gift['option_price']), 
							$gift['quantity'], 
							$gift['ready'], 
							0, // no taxes
							"" // notes
						);
							
						if( $deal['max_quantity'] > $deal_count ) {
							$new_item->setDealID($deal['id']);
							$new_item->setDealQuantity($gift['quantity']);
							$new_item->setPrice(0.0);
							$new_item->setRemovable(false);
							$deal_count += $gift['quantity'];
						
							$cart->addItem($new_item);
						}
					}
				}
					
			}

			// discount with total cost
			else if( $deal['type'] == 6 ) {

				if( ($total_cost = $cart->getTotalCost()) >= $deal['cart_tcost'] ) {

					$discount = new TakeAwayDiscount($deal['id'], $deal['amount'], $deal['percentot'], 1);
					$discount->setType($deal['type']);

					if( ($index = $cart->deals()->indexOfType($discount)) !== -1 ) {

						$discount_2 = $cart->deals()->get($index);

						$off_1 = $discount->getAmount();
						if( $discount->getPercentOrTotal() == 1 ) {
							$off_1 = $total_cost * $off_1 / 100;
						}

						$off_2 = $discount_2->getAmount();
						if( $discount_2->getPercentOrTotal() == 1 ) {
							$off_2 = $total_cost * $off_2 / 100;
						}

						if( $off_1 > $off_2 ) {
							$cart->deals()->set($discount, $index);
						}
					} else {
						$cart->deals()->insert($discount);
					}

				}
			}
			
		}
		
	}

	public static function resetDealsInCart(&$cart) {
		$available_tk_menus = self::getAllTakeawayMenusOn(array(
			"date" => date(self::getDateFormat(), $cart->getCheckinTimestamp()),
			"hour" => -1,
			"min" => 0,
			"hourmin" => "-1:0"
		));
		
		$cart->deals()->emptyDiscounts();
		
		foreach( $cart->getItemsList() as $item ) {
			$item->setDealID(-1);
			$item->setPrice($item->getOriginalPrice());
			$item->setDealQuantity(0);
			$item->setRemovable(true);
			
			if( $available_tk_menus === false || ( count($available_tk_menus) > 0 && !in_array($item->getMenuID(), $available_tk_menus) ) ) {
				$item->setQuantity(0);
			}
		}
	}

	// STOCKS

	public static function getTakeawayItemRemainingInStock($eid, $oid, $db_item_index, $dbo='') {
		if( empty($dbo) ) {
			$dbo = JFactory::getDbo();
		}

		if( !self::isTakeAwayStockEnabled() ) {
			return -1;
		}

		$eid = intval($eid);
		$oid = intval($oid);

		$where_db_item_index = '';
		if( $db_item_index > 0 ) {
			$where_db_item_index = "AND `i`.`id`<>".intval($db_item_index)." ";
		}

		if( $oid <= 0 ) {
			$q = "SELECT IFNULL(
					(
						SELECT SUM(`so`.`items_available`) 
						FROM `#__cleverdine_takeaway_stock_override` AS `so` 
						WHERE `so`.`id_takeaway_entry`=`e`.`id`
					), `e`.`items_in_stock`
				) AS `products_in_stock`, IFNULL(
					(
						SELECT SUM(`i`.`quantity`)
						FROM `#__cleverdine_takeaway_reservation` AS `r` 
						LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
						WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` $where_db_item_index
					), 0
				) AS `products_used`
				FROM `#__cleverdine_takeaway_menus_entry` AS `e`
				WHERE `e`.`id`=$eid LIMIT 1;";
		} else {
			$q = "SELECT IFNULL(
					(
						SELECT SUM(`so`.`items_available`) 
						FROM `#__cleverdine_takeaway_stock_override` AS `so` 
						WHERE `so`.`id_takeaway_entry`=`e`.`id` AND `so`.`id_takeaway_option`=`o`.`id`
					), `o`.`items_in_stock`
				) AS `products_in_stock`, IFNULL(
					(
						SELECT SUM(`i`.`quantity`)
						FROM `#__cleverdine_takeaway_reservation` AS `r` 
						LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
						WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` AND `i`.`id_product_option`=`o`.`id` $where_db_item_index
					), 0
				) AS `products_used`
				FROM `#__cleverdine_takeaway_menus_entry` AS `e`
				LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id` = `o`.`id_takeaway_menu_entry`
				WHERE `e`.`id`=$eid AND `o`.`id`=$oid LIMIT 1;";
		}

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$row = $dbo->loadAssoc();
			return ( $row['products_in_stock']-$row['products_used'] );
		}

		return 0;

	}

	public static function checkCartStockAvailability(&$cart) {

		if( !self::isTakeAwayStockEnabled() ) {
			return true;
		}

		$ok = true;

		foreach( $cart->getItemsList() as $item ) {

			$in_stock = self::getTakeawayItemRemainingInStock($item->getItemID(), $item->getVariationID(), -1);

			$stock_item_quantity = $cart->getQuantityItems($item->getItemID(), $item->getVariationID());
		
			if( $in_stock-$stock_item_quantity < 0 ) {
				$removed_items = $stock_item_quantity-$in_stock;
				$item->remove($removed_items);

				if( $stock_item_quantity == $removed_items ) {
					JFactory::getApplication()->enqueueMessage(JText::sprintf('VRTKSTOCKNOITEMS', $item->getFullName()), 'error');
				} else {
					JFactory::getApplication()->enqueueMessage(JText::sprintf('VRTKSTOCKREMOVEDITEMS', $item->getFullName(), $removed_items), 'notice');
				}

				$ok = false;
			}

		}

		return $ok;

	}

	public static function loadTakeAwayStockEmailTemplate() {
		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR."components".DIRECTORY_SEPARATOR."com_cleverdine".DIRECTORY_SEPARATOR."helpers".DIRECTORY_SEPARATOR."tk_mail_tmpls".DIRECTORY_SEPARATOR.self::getTakeawayStockMailTemplateName();
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public static function notifyAdminLowStocks($skipsession=false) {

		if( !self::isTakeAwayStockEnabled($skipsession) ) {
			return false;
		}

		$dbo = JFactory::getDbo();

		$q = "SELECT SQL_CALC_FOUND_ROWS `e`.`id` AS `eid`, `e`.`name` AS `ename`, `o`.`id` AS `oid`, `o`.`name` AS `oname`, `m`.`id` AS `id_menu`, `m`.`title` AS `menu_title`,
			IF(`o`.`id` IS NULL, `e`.`notify_below`, `o`.`notify_below` ) AS `product_notify_below`,
			IF(`o`.`id` IS NULL, `e`.`items_in_stock`, `o`.`items_in_stock` ) AS `product_original_stock`,
			IF(`o`.`id` IS NULL, 
				(
					IFNULL(
						(
							SELECT SUM(`so`.`items_available`) 
							FROM `#__cleverdine_takeaway_stock_override` AS `so` 
							WHERE `so`.`id_takeaway_entry`=`e`.`id` AND `so`.`id_takeaway_option` IS NULL
						), `e`.`items_in_stock`
					)
				), (
					IFNULL(
						(
							SELECT SUM(`so`.`items_available`) 
							FROM `#__cleverdine_takeaway_stock_override` AS `so` 
							WHERE `so`.`id_takeaway_entry`=`e`.`id` AND `so`.`id_takeaway_option`=`o`.`id`
						), `o`.`items_in_stock`
					)
				)
			) AS `products_in_stock`,
			IF(`o`.`id` IS NULL, 
				(
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` AND `o`.`id` IS NULL
						), 0
					)
				), (
					IFNULL(
						(
							SELECT SUM(`i`.`quantity`)
							FROM `#__cleverdine_takeaway_reservation` AS `r` 
							LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `i`.`id_res`=`r`.`id`
							WHERE (`r`.`status`='CONFIRMED' OR `r`.`status`='PENDING') AND `i`.`id_product`=`e`.`id` AND `i`.`id_product_option`=`o`.`id`
						), 0
					)
				)
			) AS `products_used`
			FROM `#__cleverdine_takeaway_menus_entry` AS `e`
			LEFT JOIN `#__cleverdine_takeaway_menus_entry_option` AS `o` ON `e`.`id` = `o`.`id_takeaway_menu_entry`
			LEFT JOIN `#__cleverdine_takeaway_menus` AS `m` ON `m`.`id`=`e`.`id_takeaway_menu` 
			HAVING (`products_in_stock`-`products_used`) <= `product_notify_below` 
			ORDER BY `m`.`ordering` ASC, (`products_in_stock`-`products_used`) ASC;";

		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() == 0 ) {
			return false;
		}

		$list = array();
		$last_id = -1;
		foreach( $dbo->loadAssocList() as $i ) {
			if( $i['id_menu'] != $last_id ) {
				array_push($list, array(
					"id" => $i['id_menu'],
					"title" => $i['menu_title'],
					"products" => array()
				));

				$last_id = $i['id_menu'];
			}

			array_push($list[count($list)-1]['products'], $i);
		}
	
		$subject = JText::sprintf('VRTKADMINLOWSTOCKSUBJECT', self::getRestaurantName($skipsession));
		
		$admin_mail_list 	= self::getAdminMailList($skipsession);
		$sendermail 		= self::getSenderMail($skipsession);
		if( empty($sendermail) ) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = self::getRestaurantName($skipsession);
		
		$tmpl = self::loadTakeAwayStockEmailTemplate();
		$_html_content = self::parseTakeAwayStockEmailTemplate($tmpl, $list, $skipsession);
		
		$vik = new VikApplication(VersionListener::getID());
		foreach( $admin_mail_list as $_m ) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $_html_content);
		}
		
		return true;

	}

	public static function parseTakeAwayStockEmailTemplate($tmpl, $list, $skipsession) {

		// get settings

		$date_format = self::getDateFormat($skipsession);

		// fetch list

		$list_details = "";
		foreach( $list as $m ) {
			
			$list_details .= '<div class="menu">';

			$list_details .= '<div class="menu-title">'.$m['title'].'</div>';

			foreach( $m['products'] as $p ) {

				$list_details .= '<div class="product">';
				$list_details .= '<div class="left">'.$p['ename'].(strlen($p['oname']) ? " - ".$p['oname'] : "").'</div>';
				$list_details .= '<div class="right">'.JText::sprintf('VRTKADMINLOWSTOCKREMAINING', $p['products_in_stock']-$p['products_used']).'</div>';
				$list_details .= '</div>';

			}

			$list_details .= '</div>';

		}

		// logo

		$c_logo = self::getCompanyLogoPath($skipsession);
		$logo_str = '';
		if( strlen($c_logo) > 0 && file_exists( JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$c_logo ) ) { 
			$logo_str = '<img src="'.JUri::root().'components/com_cleverdine/assets/media/'.$c_logo.'"/>';
		}
		$tmpl = str_replace( '{logo}', $logo_str, $tmpl );

		// replace tags from template

		$tmpl = str_replace('{company_name}', self::getRestaurantName($skipsession), $tmpl);
		$tmpl = str_replace('{mail_content}', JText::_('VRTKADMINLOWSTOCKCONTENT'), $tmpl);	
		$tmpl = str_replace('{list_details}', $list_details, $tmpl);
		$tmpl = str_replace('{logo}', $logo_str, $tmpl);

		return $tmpl;

	}

	public static function deliveryAddressToStr($address, $exceptions=array()) {

		$str = array();

		// route + street number
		$app = '';
		if( !empty($address['address']) && !@in_array('address', $exceptions) ) {
			$app .= trim($address['address']);
		}
		// info address
		if( !empty($address['address_2']) && !@in_array('address_2', $exceptions) ) {
			$app .= (strlen($app) ? ' ' : '').trim($address['address_2']);
		}

		if( strlen($app) ) {
			$str[] = $app;
		}

		// zip
		$app = '';
		if( !empty($address['zip']) && !@in_array('zip', $exceptions) ) {
			$app .= trim($address['zip']);
		}
		// city
		if( !empty($address['city']) && !@in_array('city', $exceptions) ) {
			$app .= (strlen($app) ? ' ' : '').trim($address['city']);
		}
		// state
		if( !empty($address['state']) && !@in_array('state', $exceptions) ) {
			$app .= (strlen($app) ? ' ' : '').trim($address['state']);
		}

		if( strlen($app) ) {
			$str[] = $app;
		}

		// country name or country code
		if( !empty($address['country']) && !@in_array('country', $exceptions) ) {
			$str[] = empty($address['country_name']) ? $address['country'] : $address['country'];
		}

		//route street_number info, zip city state, country
		return implode(', ', $str);

	}

	public static function getCustomer($id=null) {
		$jid = null;
		if( $id === null ) {
			$juser = JFactory::getUser();

			if( $juser->guest ) {
				return null;
			}

			$jid = $juser->id;
		} else {
			$id = intval($id);
		}

		$dbo = JFactory::getDbo();

		$q = "SELECT `c`.*, `u`.`name` AS `jname`, `u`.`username` AS `jusername`, `u`.`email` AS `jemail`,
		`d`.`country` AS `delivery_country`, `d`.`state` AS `delivery_state`, `d`.`city` AS `delivery_city`, 
		`d`.`address` AS `delivery_address`, `d`.`address_2` AS `delivery_address_2`, `d`.`zip` AS `delivery_zip`,
		`country`.`country_name` AS `delivery_country_name` 
		FROM `#__cleverdine_users` AS `c`
		LEFT JOIN `#__cleverdine_user_delivery` AS `d` ON `c`.`id`=`d`.`id_user`
		LEFT JOIN `#__cleverdine_countries` AS `country` ON `country`.`country_2_code`=`d`.`country`
		LEFT JOIN `#__users` AS `u` ON `u`.`id`=`c`.`jid`
		WHERE ".($id === null ? "`u`.`id`=$jid" : "`c`.`id`=$id")." ORDER BY `d`.`ordering`;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( !$dbo->getNumRows() ) {
			return null;
		}

		$app = $dbo->loadAssocList();

		$user = array(
			'id' => $app[0]['id'],
			'billing_name' 		=> $app[0]['billing_name'],
			'billing_mail' 		=> $app[0]['billing_mail'],
			'billing_phone' 	=> $app[0]['billing_phone'],
			'country_code' 		=> $app[0]['country_code'],
			'billing_state' 	=> $app[0]['billing_state'],
			'billing_city' 		=> $app[0]['billing_city'],
			'billing_address' 	=> $app[0]['billing_address'],
			'billing_address_2' => $app[0]['billing_address_2'],
			'billing_zip' 		=> $app[0]['billing_zip'],
			'company' 			=> $app[0]['company'],
			'vatnum' 			=> $app[0]['vatnum'],
			'ssn' 				=> $app[0]['ssn'],
			'notes' 			=> $app[0]['notes'],
			'image' 			=> $app[0]['image'],
			'restaurant_fields' => json_decode($app[0]['fields'], true),
			'takeaway_fields' 	=> json_decode($app[0]['tkfields'], true),

			'joomla' => array(
				'id' 		=> $app[0]['jid'],
				'name' 		=> $app[0]['jname'],
				'username' 	=> $app[0]['jusername'],
				'email' 	=> $app[0]['jemail']
			), 

			'delivery' => array()
		);

		foreach( $app as $d ) {

			if( !empty($d['delivery_address']) ) {
				$addr = array(
					'country' 		=> $d['delivery_country'],
					'country_name' 	=> $d['delivery_country_name'],
					'state' 		=> $d['delivery_state'],
					'city' 			=> $d['delivery_city'],
					'address' 		=> $d['delivery_address'],
					'address_2' 	=> $d['delivery_address_2'],
					'zip' 			=> $d['delivery_zip'],
				);

				array_push($user['delivery'], $addr);
			}

		}

		return $user;

	}

	public static function canLeaveTakeAwayReview($id_product, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$mode = self::getReviewsLeaveMode();

		$user = JFactory::getUser();

		if( $mode > 0 ) {

			// check if user is logged
			if( $user->guest ) {
				return false;
			}

		}

		$jid 		= intval($user->id);
		$id_product = intval($id_product);

		// check if user has already made the review
		if( cleverdine::isAlreadyTakeAwayReviewed($id_product, $jid, $dbo) ) {
			return false;
		}

		if( $mode == 0 || $mode == 1 ) {

			// mode 0: guest can leave review (review not submitted for this product)
			// mode 1: logged user can leave review (review not submitted for this product)
			return true;

		} else if( $mode == 2 ) {

			// mode 2: verified purchaser can leave review
			// the review for this product haven't submitted yet
			// the logged user have already purchased the product and the date of the purchase is in the past

			if( self::isVerifiedTakeAwayReview($id_product, $user, $dbo) ) {
				return true;
			}

		}

		return false;

	}

	public static function isAlreadyTakeAwayReviewed($id_product, $jid = null, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		if( $jid === null ) {
			$jid = JFactory::getUser()->id;
		} else {
			$jid = intval($jid);
		}

		$id_product = intval($id_product);
		$ip_addr 	= $dbo->quote(JFactory::getApplication()->input->server->get('REMOTE_ADDR'));

		$q = "SELECT 1 FROM `#__cleverdine_reviews` WHERE ( (`jid`=$jid AND `jid`>0) OR ($jid=0 AND `ipaddr`=$ip_addr) ) AND `id_takeaway_product`=$id_product LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();
		return ( $dbo->getNumRows() > 0 );

	}

	public static function isVerifiedTakeAwayReview($id, $user = null, $dbo = null) {
		if( $user === null ) {
			$user = JFactory::getUser();
		}

		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		if( $user->guest ) {
			return false;
		}

		$jid = $user->id;
		$now = time();

		$q = "SELECT 1 
		FROM `#__cleverdine_takeaway_reservation` AS `r` 
		LEFT JOIN `#__cleverdine_takeaway_res_prod_assoc` AS `i` ON `r`.`id`=`i`.`id_res` 
		LEFT JOIN `#__cleverdine_users` AS `u` ON `r`.`id_user`=`u`.`id`
		WHERE `u`.`jid`=$jid AND `r`.`status`='CONFIRMED' AND `i`.`id_product`=$id AND `r`.`checkin_ts`<$now LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		return ( $dbo->getNumRows() > 0 );

	}

	// ORDER STATUS

	public static function insertOrderStatus($oid, $code_id, $group, $notes = null) {

		if( $code_id <= 0 ) {
			return null;
		}

		$dbo = JFactory::getDbo();

		$oid 		= intval($oid);
		$code_id 	= intval($code_id);
		$group 		= ($group == 1 ? 1 : 2);

		$q = "SELECT `id` FROM `#__cleverdine_order_status` 
		WHERE `id_order`=$oid AND `id_rescode`=$code_id AND `group`=$group LIMIT 1;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() > 0 ) {
			// update
			$lid = $dbo->loadResult();

			$q = "UPDATE `#__cleverdine_order_status` SET 
			`notes`=".($notes !== null ? $dbo->quote($notes) : '`notes`').",
			`createdby`=".JFactory::getUser()->id.",
			`createdon`=".time()." 
			WHERE `id`=$lid LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( !$dbo->getAffectedRows() ) {
				$lid = 0;
			}

		} else {
			// insert

			$q = "INSERT INTO `#__cleverdine_order_status` (`id_order`, `id_rescode`, `notes`, `createdby`, `createdon`, `group`) VALUES(".
			$oid.",".
			$code_id.",".
			$dbo->quote(($notes === null ? '' : $notes)).",".
			JFactory::getUser()->id.",".
			time().",".
			$group.
			");";

			$dbo->setQuery($q);
			$dbo->execute();

			$lid = $dbo->insertid();
		}

		return $lid;

	}

	public static function getOrderStatusList($oid, $sid, $group) {

		$dbo = JFactory::getDbo();

		$oid 	= intval($oid);
		$group 	= ($group == 1 ? 1 : 2);

		$q = "SELECT `os`.*, `rc`.`code`, `rc`.`notes` AS `code_notes`, `rc`.`icon` 
		FROM `#__cleverdine_order_status` AS `os`
		LEFT JOIN `#__cleverdine_res_code` AS `rc` ON `os`.`id_rescode`=`rc`.`id` 
		WHERE `os`.`id_order`=$oid AND `os`.`group`=$group ORDER BY `os`.`createdon` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {

			$status = $dbo->loadAssocList();

			$table = ($group == 1 ? '#__cleverdine_reservation' : '#__cleverdine_takeaway_reservation');

			$q = "SELECT `sid` FROM `$table` WHERE `id`=$oid LIMIT 1;";

			$dbo->setQuery($q);
			$dbo->execute();

			if( $dbo->getNumRows() ) {

				if( $dbo->loadResult() == $sid ) {
					return $status;
				}

			}

		}

		return null;

	}

	// GRAPHICS 2D

	public static function loadGraphics2D() {
		UILoader::import('library.graphics2d.graphics2d');
	}

	public static function hasDeliveryAreas(array $types = array())
	{
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true)
			->select(1)
			->from($dbo->qn('#__cleverdine_takeaway_delivery_area'))
			->where($dbo->qn('published') . ' = 1');

		if (count($types))
		{
			$types = array_map('intval', $types);

			$q->where($dbo->qn('type') . ' IN (' . implode(',', $types) . ')');
		}

		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		return (bool) $dbo->getNumRows();
	}

	public static function getAllDeliveryAreas($published=false) {

		$dbo = JFactory::getDbo();

		$q = "SELECT * FROM `#__cleverdine_takeaway_delivery_area` 
		".($published ? "WHERE `published`=1" : "")."
		ORDER BY `ordering` ASC;";

		$dbo->setQuery($q);
		$dbo->execute();

		$areas = array();

		if( $dbo->getNumRows() > 0 ) {

			foreach( $dbo->loadAssocList() as $r ) {

				$r['content'] = json_decode($r['content']);
				$r['attributes'] = json_decode($r['attributes']);

				$elem = null;

				if( $r['type'] == 1 ) {
					$elem = self::parsePolygonDeliveryArea($r);
				} else if( $r['type'] == 2 ) {
					$elem = self::parseCircleDeliveryArea($r);
				} else if( $r['type'] == 3 ) {
					$elem = self::parseZipDeliveryArea($r);
				}

				if( $elem !== null ) {
					array_push($areas, $elem);
				}

			}

		}

		return $areas;

	}

	public static function parsePolygonDeliveryArea($a) {

		$polygon = new Polygon();

		if( !is_array($a['content']) ) {
			return null;
		}

		foreach( $a['content'] as $p ) {
			if( isset($p->latitude) && isset($p->longitude) ) {
				$polygon->addPoint(
					new Point(
						floatval($p->longitude),
						floatval($p->latitude)
					)
				);
			}
		}

		if( $polygon->getNumPoints() < 3 ) {
			return null;
		}

		$a['shape'] = $polygon;

		return $a;
	}

	public static function parseCircleDeliveryArea($a) {
		if( !isset($a['content']->center->latitude) || !isset($a['content']->center->longitude) || !isset($a['content']->radius) ) {
			return null;
		}

		$a['shape'] = new Circle(
			floatval($a['content']->radius),
			floatval($a['content']->center->longitude),
			floatval($a['content']->center->latitude)
		);

		return $a;
	}

	public static function parseZipDeliveryArea($a) {
		if( !is_array($a['content']) ) {
			return null;
		}

		$a['shape'] = array();

		foreach( $a['content'] as $range ) {
			if( isset($range->from) && isset($range->to) ) {
				array_push($a['shape'], $range);
			}
		}

		if( !count($a['shape']) ) {
			return null;
		}

		return $a;
	}

	public static function getDeliveryAreaFromCoordinates($lat=null, $lng=null, $zip=null) {

		self::loadGraphics2D();

		$areas = self::getAllDeliveryAreas(true);

		foreach( $areas as $a ) {

			if( $a['type'] == 1 ) {

				if( $lat !== null && $lng !== null ) {
					
					if( Geom::isPointInsidePolygon($a['shape'], new Point($lng, $lat), Geom::WINDING_NUMBER) ) {
						return $a;
					}

				}

			} else if( $a['type'] == 2 ) {

				if( $lat !== null && $lng !== null ) {
					
					if( Geom::isPointInsideCircleOnEarth($a['shape'], new Point($lng, $lat)) ) {
						return $a;
					}

				}

			} else if( $a['type'] == 3 ) {

				if( $zip !== null && strlen($zip) ) {
					
					foreach( $a['shape'] as $range ) {
						if( $range->from <= $zip && $zip <= $range->to ) {
							return $a;
						}
					}

				}

			}

		}

		return null;

	}

	// INVOICE

	public static function loadFrameworkPDF() {
		return UILoader::import('library.pdf.tcpdf.tcpdf');
	}

	public static function getInvoiceObject() {
		$obj = self::getFieldFromConfig('invoiceobj', 'vrGetInvoiceObject', true);

		UILoader::import('library.pdf.constraints');

		if( !strlen($obj) ) {
			$obj = new stdClass;

			$obj->params = new stdClass;
			$obj->params->number 	= 1;
			$obj->params->suffix 	= date('Y');
			$obj->params->datetype 	= 1;
			$obj->params->legalInfo = '';

			$obj->constraints = new cleverdineConstraintsPDF;

		} else {
			$obj = json_decode($obj);
		}

		return $obj;
	}

	public static function buildInvoiceObject($prop) {
		$obj = self::getInvoiceObject();

		$obj->params->number 	= $prop['number'][0];
		$obj->params->suffix 	= $prop['number'][1];
		$obj->params->datetype 	= $prop['datetype'];
		$obj->params->legalInfo = $prop['legalinfo'];

		$obj->constraints->pageOrientation 	= $prop['pageorientation'];
		$obj->constraints->pageFormat 		= $prop['pageformat'];
		$obj->constraints->unit 			= $prop['unit'];
		$obj->constraints->imageScaleRatio 	= $prop['scale'];

		return $obj;
	}

	public static function storeInvoiceObject($obj) {
		$dbo = JFactory::getDbo();

		$q = "UPDATE `#__cleverdine_config` SET `setting`=".$dbo->quote(json_encode($obj))." WHERE `param`='invoiceobj' LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		return $dbo->getAffectedRows();
	}

	public static function getOrderInvoice($oid, $group = 0, $dbo = null) {
		if( $dbo === null ) {
			$dbo = JFactory::getDbo();
		}

		$oid 	= intval($oid);
		$group 	= intval($group);

		$q = "SELECT * FROM `#__cleverdine_invoice` WHERE `id_order`=$oid AND `group`=$group LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() ) {
			return $dbo->loadAssoc();
		}

		return null;
	}

	public static function generateInvoicePDF($order_details, $group, $obj = null) {
		if( $obj === null ) {
			$obj = self::getInvoiceObject();
		}

		$path_pdf = JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'archive'.DIRECTORY_SEPARATOR.($group == 1 ? 'tk-' : '').$order_details['id']."-".$order_details['sid'].".pdf";
		
		if( file_exists($path_pdf) ) @unlink($path_pdf); // unlink pdf if exists
		
		$usepdffont = 'courier';
		if( file_exists(JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'fonts'.DIRECTORY_SEPARATOR.'dejavusans.php') ) {
			$usepdffont = 'dejavusans';    
		}

		if( !class_exists('TCPDF') ) {
			self::loadFrameworkPDF();
		}
		
		$pdf = new TCPDF($obj->constraints->pageOrientation, $obj->constraints->unit, $obj->constraints->pageFormat, true, 'UTF-8', false);
		$title = JText::_('VRTITLEPDFINVOICE');
		$pdf->SetTitle($title);
		if( true ) { // hide header always
			$pdf->SetPrintHeader(false);
		} else {
			//$pdf->SetHeaderData($companylogo, $companylogowidth, $title, '');
		}
		//
		//header and footer fonts
		$pdf->setHeaderFont(array($usepdffont, '', $obj->constraints->fontSizes->header));
		$pdf->setFooterFont(array($usepdffont, '', $obj->constraints->fontSizes->footer));
		//default monospaced font
		//$pdf->SetDefaultMonospacedFont('courier');
		//margins
		$pdf->SetMargins($obj->constraints->margins->left, $obj->constraints->margins->top, $obj->constraints->margins->right);
		$pdf->SetHeaderMargin($obj->constraints->margins->header);
		$pdf->SetFooterMargin($obj->constraints->margins->footer);
		//
		$pdf->SetAutoPageBreak(true, $obj->constraints->margins->bottom);
		$pdf->setImageScale($obj->constraints->imageScaleRatio);
		$pdf->SetFont($usepdffont, '', $obj->constraints->fontSizes->body);
		
		if( true ) { // hide footer always
			$pdf->SetPrintFooter(false);
		} else {
			// print footer
		}
		
		$pdf_tmpl = self::parseInvoiceTemplatePDF($order_details, $group, $obj);

		$pdf->addPage();
		$pdf->writeHTML($pdf_tmpl, true, false, true, false, '');
		
		$pdf->Output($path_pdf, 'F');
		
		return $path_pdf;
	}

	public static function parseInvoiceTemplatePDF($order_details, $group, $obj) {

		defined('_cleverdineEXEC') or define('_cleverdineEXEC', '1');
		ob_start();
		include JPATH_SITE.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.($group == 0 ? 'restaurant' : 'takeaway').'_invoice_tmpl.php';

		$tmpl = ob_get_contents();
		ob_end_clean();
		
		$date_format 	= self::getDateFormat(true);
		$time_format 	= self::getTimeFormat(true);
		$curr_symb 		= self::getCurrencySymb(true);
		$symb_pos 		= self::getCurrencySymbPosition(true);
		
		// COMPANY LOGO
		$logo_name = self::getCompanyLogoPath(true);
		
		$c_path =  '..'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_cleverdine'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.$logo_name;
		
		$logo_str = "";
		if( file_exists( $c_path ) && !empty($logo_name) ) { 
			$logo_str = '<img src="'.$c_path.'"/>';
		}
		$tmpl = str_replace( '{company_logo}', $logo_str, $tmpl );
		
		// COMPANY INFO
		$tmpl = str_replace( '{company_info}', nl2br($obj->params->legalInfo), $tmpl );
		
		// INVOICE DETAILS
		$tmpl = str_replace( '{invoice_number}', $obj->params->number, $tmpl );
		$tmpl = str_replace( '{invoice_suffix}', (strlen($obj->params->suffix) ? '/' : '').$obj->params->suffix, $tmpl );
		$tmpl = str_replace( '{invoice_order_number}', $order_details['id']."-".$order_details['sid'], $tmpl );
		
		$invoice_date = date($date_format); 
		if( $obj->params->datetype == 2 ) {
			$invoice_date = date($date_format, $order_details['created_on']);
		}

		if( isset($obj->params->customDate) && strlen($obj->params->customDate) ) {
			$invoice_date = $obj->params->customDate;
		}

		$tmpl = str_replace( '{invoice_date}', $invoice_date, $tmpl );

		// INVOICE ORDERS
		$order_lines = '';

		if( $group == 0 ) {

			$order_details['items'] = self::getFoodFromReservation($order_details['id']);

			foreach( $order_details['items'] as $item ) {

				$order_lines .= '<tr>
					<td width="65%"><strong>'.$item['name'].'</strong></td>
					<td width="15%">x'.$item['quantity'].'</td>
					<td width="25%">'.self::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos).'</td>
				</tr>';

			}

		} else {
			
			foreach( $order_details['items'] as $item ) {

				$order_lines .= '<tr>
					<td width="65%"><strong>'.$item['name'].(!empty($item['option_name']) ? ' - '.$item['option_name'] : '').'</strong></td>
					<td width="15%">x'.$item['quantity'].'</td>
					<td width="25%">'.self::printPriceCurrencySymb($item['price'], $curr_symb, $symb_pos).'</td>
				</tr>';

				$toppings_groups_str = '';

				foreach( $item['toppings_groups'] as $i => $group ) {
					$toppings_str = '';
					foreach( $group['toppings'] as $topping ) {
						$toppings_str .= (strlen($toppings_str) ? ', ' : '').'<i>'.$topping['name'].'</i>';
					}

					$toppings_groups_str .= (strlen($toppings_groups_str) ? '<br />' : '').'<strong>'.$group['title'].':</strong> '.$toppings_str;
					
				}

				$order_lines .= '<tr>
					<td width="100%" valign="top" colspan="3" style="padding-left: 20px;"><small>'.$toppings_groups_str.'</small></td>
				</tr>';

			}
			
		}

		$tmpl = str_replace( '{invoice_order_details}', $order_lines, $tmpl );
		
		// CUSTOMER INFO
		$custinfo = "";
		$custdata = json_decode($order_details['custom_f'], true);
		foreach($custdata as $kc => $vc) {
			if( !empty($vc) ) {
				$custinfo .= JText::_($kc).': '.$vc."<br/>\n";
			}
		}
		$tmpl = str_replace( '{customer_info}', $custinfo, $tmpl );
		
		// BILLING INFO
		$billinginfo = "";
		if( $order_details['customer'] !== null ) {
			$custobj = $order_details['customer'];
			if( !empty($custobj['company']) ) $billinginfo .= $custobj['company'].' ';
			if( !empty($custobj['vatnum']) ) $billinginfo .= $custobj['vatnum'];
			if( !empty($billinginfo) ) $billinginfo .= '<br/>';
			
			if( !empty($custobj['billing_state']) ) $billinginfo .= $custobj['billing_state'].', ';
			if( !empty($custobj['billing_city']) ) $billinginfo .= $custobj['billing_city'].' ';
			if( !empty($custobj['billing_zip']) ) $billinginfo .= $custobj['billing_zip'];
			if( !empty($billinginfo) ) $billinginfo .= '<br/>';
			
			if( !empty($custobj['billing_address']) ) $billinginfo .= $custobj['billing_address'];
			if( !empty($custobj['billing_address_2']) ) $billinginfo .= ", ".$custobj['billing_address_2'];
			if( !empty($billinginfo) ) $billinginfo .= '<br/>';
		}
		$tmpl = str_replace( '{billing_info}', $billinginfo, $tmpl );
		
		// TOTAL SUMMARY

		$net = $discount = $pay_ch = $delivery_ch = $taxes = $grand_total = 0;

		if( $group == 0 ) {

			$tax_ratio = self::getTaxesRatio(true);
			$use_taxes = self::isTaxesUsable(true);

			//

			$grand_total = $order_details['bill_value'];

			if( $use_taxes == 0 ) {

				// included
				$net = $grand_total * 100.0 / ($tax_ratio + 100.0);

			} else {

				$net = $grand_total;
				
				// excluded
				$grand_total *= 1 + $tax_ratio / 100.0;

			}

			$taxes = $grand_total - $net;

			$discount = $order_details['discount_val'];

			$net += $discount_val;

			// TODO (pay charge)

		} else {
			$net			= $order_details['total_to_pay']-$order_details['taxes']-$order_details['pay_charge']-$order_details['delivery_charge'];
			$discount 		= $order_details['discount_val'];
			$pay_ch			= $order_details['pay_charge'];
			$delivery_ch	= $order_details['delivery_charge'];
			$taxes			= $order_details['taxes'];
			$grand_total	= $order_details['total_to_pay'];
		}

		$tmpl = str_replace( '{invoice_totalnet}', self::printPriceCurrencySymb($net+$discount, $curr_symb, $symb_pos), $tmpl );
		$tmpl = str_replace( '{invoice_discountval}', self::printPriceCurrencySymb($discount, $curr_symb, $symb_pos), $tmpl );
		$tmpl = str_replace( '{invoice_paycharge}', self::printPriceCurrencySymb($pay_ch, $curr_symb, $symb_pos), $tmpl );
		$tmpl = str_replace( '{invoice_deliverycharge}', self::printPriceCurrencySymb($delivery_ch, $curr_symb, $symb_pos), $tmpl );
		$tmpl = str_replace( '{invoice_totaltax}', self::printPriceCurrencySymb($taxes, $curr_symb, $symb_pos), $tmpl );
		$tmpl = str_replace( '{invoice_grandtotal}', self::printPriceCurrencySymb($grand_total, $curr_symb, $symb_pos), $tmpl );
		
		return $tmpl;
	}

	public static function sendInvoiceMail($order_details, $pdf) {
		$admin_mail_list 	= self::getAdminMailList(true);
		$sendermail 		= self::getSenderMail(true);
		$fromname 			= self::getRestaurantName(true);
		
		$subject = JText::sprintf('VRINVMAILSUBJECT', $fromname, $order_details['id']."-".$order_details['sid']);
		$content = JText::sprintf('VRINVMAILCONTENT', $fromname, $order_details['id']."-".$order_details['sid']);

		$content = "########################################\n\n$content\n\n########################################\n\n";
		
		$attachments = array($pdf);
		
		$vik = new VikApplication(VersionListener::getID());
		return $vik->sendMail($sendermail, $fromname, $order_details['purchaser_mail'], $admin_mail_list[0], $subject, nl2br($content), $attachments);
	}
	
	// LANGUAGE TRANSLATIONS
	public static function getDefaultLanguage($section='site') {
		return JComponentHelper::getParams('com_languages')->get('site');
	}
	
	public static function loadLanguage($tag) {
		if( !empty($tag) ) {
			JFactory::getLanguage()->load('com_cleverdine', JPATH_SITE, $tag, true);
		}
	}
	
	public static function getKnownLanguages() {
		$def_lang = self::getDefaultLanguage('site');
		$known_languages = JLanguage::getKnownLanguages();
		
		$languages = array();
		foreach( $known_languages as $k => $v ) {
			if( $k == $def_lang ) {
				array_unshift($languages, $k);
			} else {
				array_push($languages, $k);
			}
		}
		
		return $languages;
	}
	
	public static function getTranslatedMenus($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_menu`, `name`, `description` FROM `#__cleverdine_lang_menus` WHERE `id_menu` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = array();
			foreach( $dbo->loadAssocList() as $m ) {
				$menus[$m['id_menu']] = $m;
			}

			return $menus;
		}
		
		return false;
	}
	
	public static function getTranslatedSections($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_section`, `name`, `description` FROM `#__cleverdine_lang_menus_section` WHERE `id_section` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$sections = array();
			foreach( $dbo->loadAssocList() as $s ) {
				$sections[$s['id_section']] = $s;
			}

			return $sections;
		}
		
		return false;
	}
	
	public static function getTranslatedProducts($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_product`, `name`, `description` FROM `#__cleverdine_lang_section_product` WHERE `id_product` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$products = array();
			foreach( $dbo->loadAssocList() as $p ) {
				$products[$p['id_product']] = $p;
			}

			return $products;
		}
		
		return false;
	}
	
	public static function getTranslatedProductOptions($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_option`, `name` FROM `#__cleverdine_lang_section_product_option` WHERE `id_option` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$options = array();
			foreach( $dbo->loadAssocList() as $o ) {
				$options[$o['id_option']] = $o;
			}

			return $options;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayMenus($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_menu`, `name`, `description` FROM `#__cleverdine_lang_takeaway_menus` WHERE `id_menu` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$menus = array();
			foreach( $dbo->loadAssocList() as $m ) {
				$menus[$m['id_menu']] = $m;
			}

			return $menus;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayProducts($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_entry`, `name`, `description` FROM `#__cleverdine_lang_takeaway_menus_entry` WHERE `id_entry` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$products = array();
			foreach( $dbo->loadAssocList() as $p ) {
				$products[$p['id_entry']] = $p;
			}
			
			return $products;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayOptions($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_option`, `name` FROM `#__cleverdine_lang_takeaway_menus_entry_option` WHERE `id_option` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$options = array();
			foreach( $dbo->loadAssocList() as $o ) {
				$options[$o['id_option']] = $o;
			}

			return $options;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayGroups($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_group`, `name` FROM `#__cleverdine_lang_takeaway_menus_entry_topping_group` WHERE `id_group` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$groups = array();
			foreach( $dbo->loadAssocList() as $g ) {
				$groups[$g['id_group']] = $g;
			}

			return $groups;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayToppings($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_topping`, `name` FROM `#__cleverdine_lang_takeaway_topping` WHERE `id_topping` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$toppings = array();
			foreach( $dbo->loadAssocList() as $t ) {
				$toppings[$t['id_topping']] = $t;
			}

			return $toppings;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayAttributes($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_attribute`, `name` FROM `#__cleverdine_lang_takeaway_menus_attribute` WHERE `id_attribute` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$attributes = array();
			foreach( $dbo->loadAssocList() as $a ) {
				$attributes[$a['id_attribute']] = $a;
			}

			return $attributes;
		}
		
		return false;
	}
	
	public static function getTranslatedTakeawayDeals($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_deal`, `name`, `description` FROM `#__cleverdine_lang_takeaway_deal` WHERE `id_deal` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$deals = array();
			foreach( $dbo->loadAssocList() as $d ) {
				$deals[$d['id_deal']] = $d;
			}

			return $deals;
		}
		
		return false;
	}

	public static function getTranslatedPayments($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_payment`, `name`, `note`, `prenote` FROM `#__cleverdine_lang_payments` WHERE `id_payment` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payments = array();
			foreach( $dbo->loadAssocList() as $p ) {
				$payments[$p['id_payment']] = $p;
			}

			return $payments;
		}
		
		return false;
	}

	public static function getTranslatedCustomFields($ids = array(), $tag = '') {
		if( count($ids) == 0 ) {
			return false;
		}
		
		if( !self::isMultilanguage() ) {
			return false;
		}
		
		if( empty($tag) ) {
			$tag = JFactory::getLanguage()->getTag();
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "SELECT `id_customf`, `name`, `choose`, `poplink` FROM `#__cleverdine_lang_customf` WHERE `id_customf` IN (".implode(",", $ids).") AND `tag`=".$dbo->quote($tag).";";
		$dbo->setQuery($q);
		$dbo->execute();
		if( $dbo->getNumRows() > 0 ) {
			$payments = array();
			foreach( $dbo->loadAssocList() as $p ) {
				$payments[$p['id_customf']] = $p;
			}

			return $payments;
		}
		
		return false;
	}
	
	public static function translate($id, $ori, $new, $key1, $key2) {
		if( $new === false || empty($new[$id][$key2]) ) {
			return $ori[$key1];
		}
		
		return $new[$id][$key2];
	}
	
	// OPERATORS LOGS
	
	public static function getNotificationOperatorsMails($group = 0) {
		$dbo = JFactory::getDbo();

		$q = $dbo->getQuery(true);

		$q->select($dbo->quoteName('email'))
			->from($dbo->quoteName('#__cleverdine_operator'))
			->where($dbo->quoteName('mail_notifications') . ' = 1')
			->where($dbo->quoteName('email') . '<> ""');

		if( $group > 0 ) {
			$q->where($dbo->quoteName('group') . ' IN (0, '.$group.')');
		}

		$dbo->setQuery($q);
		$dbo->execute();

		if( $dbo->getNumRows() > 0 ) {
			$rows = array();
			foreach( $dbo->loadAssocList() as $r ) {
				array_push($rows, $r['email']);
			}
			
			return $rows;
		}
		
		return array();
	}
	
	public static function storeOperatorLog($id_operator, $id_order, $log, $group) {
		if( empty($log) || empty($id_operator) ) {
			return 0;
		}
		
		$dbo = JFactory::getDbo();
		
		$q = "INSERT INTO `#__cleverdine_operator_log` (`id_operator`,`id_reservation`,`log`,`createdon`,`group`) VALUES(".
		$id_operator.",".
		$id_order.",".
		$dbo->quote($log).",".
		time().",".
		$group."
		);";
		
		$dbo->setQuery($q);
		$dbo->execute();
		return $dbo->insertid();
	}
	
	public static function generateOperatorLog($operator, $id_order, $group, $action) {
		$log = "";
		
		if( $group == self::OPERATOR_RESTAURANT_LOG ) {
			
			if( $action == self::OPERATOR_RESTAURANT_INSERT ) {
				$log = JText::sprintf('VROPLOGRESTAURANTINSERT', $operator['code'], $id_order);
			} else if( $action == self::OPERATOR_RESTAURANT_UPDATE ) {
				$log = JText::sprintf('VROPLOGRESTAURANTUPDATE', $operator['code'], $id_order);
			} else if( $action == self::OPERATOR_RESTAURANT_CONFIRMED ) {
				$log = JText::sprintf('VROPLOGRESTAURANTCONFIRMED', $operator['code'], $id_order);
			} else if( $action == self::OPERATOR_RESTAURANT_TABLE_CHANGED ) {
				$log = JText::sprintf('VROPLOGRESTAURANTTABLECHANGED', $operator['code'], $id_order);
			}
			
		} else if( $group == self::OPERATOR_TAKEAWAY_LOG ) {
			
			if( $action == self::OPERATOR_TAKEAWAY_INSERT ) {
				$log = JText::sprintf('VROPLOGTAKEAWAYINSERT', $operator['code'], $id_order);
			} else if( $action == self::OPERATOR_TAKEAWAY_UPDATE ) {
				$log = JText::sprintf('VROPLOGTAKEAWAYUPDATE', $operator['code'], $id_order);
			} else if( $action == self::OPERATOR_TAKEAWAY_CONFIRMED ) {
				$log = JText::sprintf('VROPLOGTAKEAWAYCONFIRMED', $operator['code'], $id_order);
			}
			
		}
		
		return $log;
	}
	
	const OPERATOR_GENERIC_LOG = 0;
	const OPERATOR_RESTAURANT_LOG = 1;
	const OPERATOR_TAKEAWAY_LOG = 2;
	
	const OPERATOR_GENERIC_ACTION = 0;
	
	const OPERATOR_RESTAURANT_UNDEFINED = 0;
	const OPERATOR_RESTAURANT_INSERT = 1;
	const OPERATOR_RESTAURANT_UPDATE = 2;
	const OPERATOR_RESTAURANT_CONFIRMED = 3;
	const OPERATOR_RESTAURANT_TABLE_CHANGED = 4;
	
	const OPERATOR_TAKEAWAY_UNDEFINED = 0;
	const OPERATOR_TAKEAWAY_INSERT = 1;
	const OPERATOR_TAKEAWAY_UPDATE = 2;
	const OPERATOR_TAKEAWAY_CONFIRMED = 3;
	
	/* OPERATORS AREA */
	
	public static function getToolbarLiveMap($operator) {
		
		$itemid = JFactory::getApplication()->input->get('Itemid', 0, 'int');
			
		$html = '<div class="vr-livemap-rcont">
				<div class="vr-livemap-rbox">
					<div class="vr-livemap-rtitle">
						<a href="javascript: void(0);">'.$operator['firstname'].' '.$operator['lastname'].'</a>
					</div>
				</div>
				
				<div class="vr-livemap-modal" style="display: none;">
					<ul>
						'.($operator['group'] != 2 ? '<li><a href="'.JRoute::_("index.php?option=com_cleverdine&view=oversight").'">'.JText::_('VROVERSIGHTMENUITEM1').'</a></li>' : '').'
						'.($operator['group'] != 2 ? '<li><a href="'.JRoute::_("index.php?option=com_cleverdine&task=opdashboard&Itemid=$itemid").'">'.JText::_('VROVERSIGHTMENUITEM2').'</a></li>' : '').'
						'.($operator['group'] != 2 ? '<li class="separator"><a href="'.JRoute::_("index.php?option=com_cleverdine&task=opreservations&Itemid=$itemid").'">'.JText::_('VROVERSIGHTMENUITEM3').'</a></li>' : '').'
						'.($operator['group'] != 1 ? '<li class="separator"><a href="'.JRoute::_("index.php?option=com_cleverdine&view=oversight&group=2").'">'.JText::_('VROVERSIGHTMENUITEM5').'</a></li>' : '').'
						'.($operator['manage_coupon'] > 0 ? '<li class="separator"><a href="'.JRoute::_("index.php?option=com_cleverdine&task=opcoupons&Itemid=$itemid").'">'.JText::_('VROVERSIGHTMENUITEM4').'</a></li>' : '').'
						<li><a href="'.JRoute::_("index.php?option=com_cleverdine&task=oplogout").'">'.JText::_('VRLOGOUT').'</a></li>
					</ul>
				</div>
			</div>';
		
		$html .= '
		<script>
			jQuery(document).ready(function(){
				jQuery(\'html\').click(function(){
					jQuery(\'.vr-livemap-modal\').hide();
				});
				jQuery(\'.vr-livemap-rtitle\').click(function(event){
					event.stopPropagation();
					jQuery(\'.vr-livemap-modal\').toggle();
				});
			});
		</script>';
		
		return $html;
	}
	
	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0) {
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if( empty($Id) ){
			$assetName = 'com_cleverdine';
		} else {
			$assetName = 'com_cleverdine.message.'.(int) $Id;
		};

		$actions = JAccess::getActions('com_cleverdine', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		};

		return $result;
	}
	
	
	
	// OLD MULTI RESERVATION QUERIES
	
	
	/*
	public static function getQueryFindTableMultiRes($args,$skip_session=false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` ".
				"FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_room` AS `rm`, `#__cleverdine_table` AS `t` ".
				"WHERE `t`.`id` = `r`.`id_table` AND `t`.`multi_res` = 1 AND `t`.`id_room` = `rm`.`id` AND `rm`.`published` AND `r`.`status` <> 'REMOVED' AND ( ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . $in_ts . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . ($in_ts+$avg) . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` > " . $in_ts . " AND " . ($in_ts+$avg) . " > `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` = " . $in_ts . " AND " . ($in_ts+$avg) . " = `r`.`checkin_ts`+" . $avg . " ) ".
				") GROUP BY `t`.`id` HAVING " . $args['people'] . " >= `t`.`min_capacity` AND SUM(`r`.`people`)+" . $args['people'] . " <= `t`.`max_capacity` ".
				"ORDER BY `rid` ASC;";
	}
	*/
	
	/*
	public static function getQueryFindTableMultiResWithID($args,$skip_session=false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id` AS `tid`, `t`.`name` AS `tname`, `t`.`min_capacity`, `t`.`max_capacity`, `t`.`multi_res`, `rm`.`id` AS `rid`, `rm`.`name` AS `rname` ".
				"FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_room` AS `rm`, `#__cleverdine_table` AS `t` ".
				"WHERE `t`.`id` = `r`.`id_table` AND `t`.`id`=".$args['table']." AND `t`.`multi_res` = 1 AND `t`.`id_room` = `rm`.`id` AND `rm`.`published` AND `r`.`status` <> 'REMOVED' AND ( ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . $in_ts . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . ($in_ts+$avg) . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` > " . $in_ts . " AND " . ($in_ts+$avg) . " > `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` = " . $in_ts . " AND " . ($in_ts+$avg) . " = `r`.`checkin_ts`+" . $avg . " ) ".
				") GROUP BY `t`.`id` HAVING " . $args['people'] . " >= `t`.`min_capacity` AND SUM(`r`.`people`)+" . $args['people'] . " <= `t`.`max_capacity` ".
				"ORDER BY `rid` ASC;";
	}
	*/
	
	/*
	public static function getQueryCountOccurrencyTableMultiRes($args,$skip_session=false) {
	
		$in_ts = self::createTimestamp($args['date'], $args['hour'], $args['min']);
		$avg = self::getAverageTimeStay($skip_session)*60;
	
		return "SELECT SUM(`r`.`people`) AS `curr_capacity`, `t`.`id`, `t`.`multi_res` ".
				"FROM `#__cleverdine_reservation` AS `r`, `#__cleverdine_table` AS `t` ".
				"WHERE `t`.`id` = `r`.`id_table` AND `t`.`multi_res` = 1 AND ( ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . $in_ts . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . ($in_ts+$avg) . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` < " . $in_ts . " AND " . ($in_ts+$avg) . " < `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` > " . $in_ts . " AND " . ($in_ts+$avg) . " > `r`.`checkin_ts`+" . $avg . " ) OR ".
				"( `r`.`checkin_ts` = " . $in_ts . " AND " . ($in_ts+$avg) . " = `r`.`checkin_ts`+" . $avg . " ) ".
				") GROUP BY `t`.`id`;";
	}
	*/
	
}

?>