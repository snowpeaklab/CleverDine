<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

/**
* cleverdine - Customer E-Mail Template
* @see the bottom of the page to check the available TAGS to use.
*/

defined('_JEXEC') OR die('Restricted Area');

defined('_cleverdineEXEC') OR die('Restricted Area');

?>

<style type="text/css">
.clearfix:after {
	content: ".";
	display: block;
	clear: both;
	visibility: hidden;
	line-height: 0;
	height: 0;
}

.clearfix {
	display: inline-block;
}

.container {
	width: 80%;
	font-family: "Century Gothic", Tahoma, Arial;
}

.separator {
	margin: 7px 0;
}

/* head title */
.head {
	width: 80%;
}
.head h3 {
	padding: 5px 10px;
	border-bottom: 1px solid #bbb;
}

/* order details boxes */
.order-details {
	width: 80%;
	text-align: center;
}
.order-details .box {
	display: inline-block;
	width: 49%;
	text-align: left;
	vertical-align: top;
	border: 1px solid #bbb;
	min-height: 150px;
}
.order-details .left {
	background: #d8e8e8;
}
.order-details .right {
	background: #f6f6f6;
}
.order-details .box h3 {
	padding: 15px 20px 10px;
	margin: 0;
}
.order-details .info-container {
	padding: 0 20px 15px;
	font-size: 0.9em;
}
.order-details .field {
	margin: 3px 0;
}
.order-details .field .label {
	font-weight: bold;
}
.order-details .field .value.confirmed {
	font-weight: bold;
	text-transform: uppercase;
	color: #006600;
}
.order-details .field .value.pending {
	font-weight: bold;
	text-transform: uppercase;
	color: #D9A300;	
}
.order-details .field .value.removed {
	font-weight: bold;
	text-transform: uppercase;
	color: #B20000;
}
.order-details .field .value.cancelled {
	font-weight: bold;
	text-transform: uppercase;
	color: #F01B17;
}

/* menu details */
.menu-details {
	width: 80%;
}
.menu-product {
	border: 1px solid #bbb;
	border-bottom: 0;
	padding: 10px;
	background-color: #f8f8f8;
}
.menu-product:last-child {
	border-bottom: 1px solid #bbb;
}
.menu-product .item-name {
	width: 70%;
	display: inline-block;
	text-align: left;
}
.menu-product .item-quantity {
	width: 10%;
	display: inline-block;
	text-align: center;
}

/* customer details */
.customer-details-wrapper {
	width: 80%;
}
.customer-details-wrapper .title {
	border: 1px solid #bbb;
	padding: 5px;
	border-bottom: 0;
	background-color: #f8f8f8;
}
.customer-details {
	padding: 10px;
	border: 1px solid #bbb;
	background-color: #fafafa;
}
.customer-details .info {
	padding: 2px 0;
}
.customer-details .info .label {
	display: inline-block;
	width: 180px;
}
.customer-details .info .value {
	display: inline-block;
}

/* order link */
.order-link {
	width: 80%;
	margin-bottom: 5px;
}
.order-link .title {
	border: 1px solid #bbb;
	padding: 5px;
	border-bottom: 0;
	background-color: #f8f8f8;
}
.order-link .content {
	border: 1px solid #bbb;
	padding: 10px;
	background-color: #fafafa;
	word-break: break-all;
	word-wrap: break-word;
}

@media print {
	.order-link {
		display: none !important;
	}

	.container {
		width: 100%;
	}
	.head, .order-details, .menu-details, .customer-details-wrapper, .order-link {
		width: 99% !important;
	}
}

@media screen and (max-width : 1024px) {
	.container {
		width: 100%;
	}
	.head, .order-details, .menu-details, .customer-details-wrapper, .order-link {
		width: 99% !important;
	}
	.order-details .box {
		width: initial;
		min-height: initial;
		display: block;
		border-bottom: 0;
	}
	.order-details .box:last-child {
		border-bottom: 1px solid #bbb;
	}
	.separator {
		margin: 0 !important;
	}
	.customer-details .info .label {
		width: 110px !important;
	}
}

{head_css_style}

</style>

<p>{logo}</p>

<div class="container">

	<div class="head">
		<h3>{company_name}</h3>
	</div>

	<div class="order-details">
		<div class="box left" id="order-details-box">
			<h3><?php echo JText::_('VRCUSTMAILORDDETAILS'); ?></h3>
			<div class="info-container">
				<div class="field">
					<span class="label"><?php echo JText::_('VRORDERNUMBER'); ?>:</span>
					<span class="value">{order_number} - {order_key}</span>
				</div>

				<div class="field">
					<span class="label"><?php echo JText::_('VRORDERSTATUS'); ?>:</span>
					<span class="value {order_status_class}">{order_status}</span>
				</div>

				<div class="field">
					<span class="label"><?php echo JText::_('VRORDERDATETIME'); ?>:</span>
					<span class="value">{order_date_time}</span>
				</div>

				<div class="field">
					<span class="label"><?php echo JText::_('VRORDERPEOPLE'); ?>:</span>
					<span class="value">{order_people}</span>
				</div>
			</div>
		</div>

		<div class="box right" id="order-payment-box">
			<h3><?php echo JText::_('VRCUSTMAILPAYDETAILS'); ?></h3>
			<div class="info-container">
				<div class="field" id="order-payment">
					<span class="label"><?php echo JText::_('VRORDERPAYMENT'); ?>:</span>
					<span class="value">{order_payment}</span>
				</div>

				<div class="field" id="order-deposit">
					<span class="label"><?php echo JText::_('VRORDERDEPOSIT'); ?>:</span>
					<span class="value">{order_deposit}</span>
				</div>

				<div class="field" id="order-coupon-code">
					<span class="label"><?php echo JText::_('VRORDERCOUPON'); ?>:</span>
					<span class="value">{order_coupon_code}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="separator">&nbsp;</div>

	<?php if( count($order_details['menus_list']) ) { ?>

		<div class="menu-details">
			{menu_details}
		</div>

		<div class="separator">&nbsp;</div>

	<?php } ?>

	<div class="customer-details-wrapper">
		<div class="title"><?php echo JText::_('VRPERSONALDETAILS'); ?></div>
		<div class="customer-details">
			{customer_details}
		</div>
	</div>

	<div class="separator">&nbsp;</div>

	<?php if( !empty($order_details['user_email']) ) { ?>

		<?php /* decomment the code below if you need to display joomla users info
		<div class="separator">&nbsp;</div>

		<div class="customer-details-wrapper">
			<div class="title"><?php echo JText::_('VRUSERDETAILS'); ?></div>
			<div class="customer-details">
				{user_details}
			</div>
		</div>
		*/ ?>

	<?php } ?>

	<div class="order-link">
		<div class="title"><?php echo JText::_('VRORDERLINK'); ?></div>
		<div class="content">
			<a href="{order_link}">{order_link}</a>
		</div>
	</div>

	<?php if( $order_details['status'] == 'PENDING' ) { ?>

		<div class="order-link">
			<div class="title"><?php echo JText::_('VRCONFIRMATIONLINK'); ?></div>
			<div class="content">
				<a href="{confirmation_link}">{confirmation_link}</a>
			</div>
		</div>

	<?php } ?>
	
</div>


<?php
/**
* @var string|null	{logo}						the logo image of your company. Null if not specified.
* @var int 			{order_number}				the unique ID of the reservation.
* @var string 		{order_key}					the serial key of the reservation.
* @var string 		{order_date_time}			the checkin date and time of the reservation.
* @var string 		{order_people}				the party size of the reservaion.
* @var string 		{order_status}				the status of the order [CONFIRMED, PENDING, REMOVED or CANCELLED].
* @var string 		{order_status_class}		the status of the order [confirmed, pending, removed or cancelled].
* @var string|null	{order_payment}				the name of the payment processor selected (*), otherwise NULL.
* @var string|null 	{order_payment_notes}		the notes of the payment processor selected, otherwise NULL.
* @var float 		{order_deposit}				the deposit to leave for the reservation
* @var string 		{order_coupon_code}			the coupon code used for the order.
* @var string		{order_link}				the direct url to the page of the order.
* @var string		{confirmation_link}			the direct url to confirm the order. Available only if status of the order is PENDING.
* @var string|null 	{company_name}				the name of the company.
* @var string|null 	{customer_details}			the custom fields specified from the customer.
* @var string|null 	{user_details}				the details of the Joomla user.
* @var string 		{menu_details}				the details of all the menus chosen in this order.
*/
?>