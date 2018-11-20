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
* cleverdine - Take-Away Admin E-Mail Template
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

/* cart details */
.cart-details {
	width: 80%;
}
.cart-product {
	margin-bottom: 10px;
}
.cart-product .item {
	border: 1px solid #bbb;
	padding: 10px;
	background-color: #f8f8f8;
}
.cart-product .item .item-name {
	width: 70%;
	display: inline-block;
	text-align: left;
}
.cart-product .item .item-quantity {
	width: 10%;
	display: inline-block;
	text-align: center;
}
.cart-product .item .item-price {
	width: 17%;
	display: inline-block;
	text-align: right;
}
.cart-product .toppings-container {
	border: 1px solid #bbb;
	padding: 10px 10px 10px 35px;
	border-top: 0;
	background-color: #fafafa;
	font-size: small;
}
.cart-product .toppings-container .toppings-group {
	margin: 6px 0;
}
.cart-product .toppings-container .toppings-group .title {
	width: 200px;
	display: inline-block;
}
.cart-product .toppings-container .toppings-group .toppings {
	vertical-align: top;
	display: inline-block;
	margin-left: 10px;
}
.cart-product .notes {
	border: 1px solid #bbb;
	padding: 10px 20px;
	border-top: 0;
	background-color: #efefef;
	font-size: small;
}

/* cart total */
.cart-total {
	width: 80%;
}
.cart-total .total-row {
	border: 1px solid #bbb;
	border-top: 0;
	border-bottom: 0;
	padding: 2px 10px;
	background-color: #d8e8e8;
	text-align: right;
	font-size: small;
}
.cart-total .total-row.grand-total {
	font-size: 16px;
}
.cart-total .total-row.red {
	color: #930;
}
.cart-total .total-row:first-child {
	border-top: 1px solid #bbb;
	padding-top: 10px;
}
.cart-total .total-row:last-child {
	border-bottom: 1px solid #bbb;
	padding-bottom: 10px;
}
.cart-total .total-row div {
	display: inline-block;
}
.cart-total .total-row .amount {
	width: 120px;
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
	.head, .order-details, .cart-details, .cart-total, .customer-details-wrapper, .order-link {
		width: 99% !important;
	}
}

@media screen and (max-width : 1024px) {
	.container {
		width: 100%;
	}
	.head, .order-details, .cart-details, .cart-total, .customer-details-wrapper, .order-link {
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
		<div class="box left">
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
					<span class="label"><?php echo JText::_('VRTKORDERDELIVERYSERVICE'); ?>:</span>
					<span class="value">{order_delivery_service}</span>
				</div>
			</div>
		</div>

		<div class="box right">
			<h3><?php echo JText::_('VRCUSTMAILPAYDETAILS'); ?></h3>
			<div class="info-container">
				<div class="field" id="order-payment">
					<span class="label"><?php echo JText::_('VRORDERPAYMENT'); ?>:</span>
					<span class="value">{order_payment}</span>
				</div>

				<div class="field" id="order-total-cost">
					<span class="label"><?php echo JText::_('VRTKORDERTOTALTOPAY'); ?>:</span>
					<span class="value">{order_total_cost}</span>
				</div>

				<div class="field" id="order-coupon-code">
					<span class="label"><?php echo JText::_('VRORDERCOUPON'); ?>:</span>
					<span class="value">{order_coupon_code}</span>
				</div>
			</div>
		</div>
	</div>

	<div class="separator">&nbsp;</div>

	<div class="cart-details">
		{cart_details}
	</div>

	<div class="cart-total">
		{cart_grand_total}
	</div>

	<div class="separator">&nbsp;</div>

	<div class="customer-details-wrapper">
		<div class="title"><?php echo JText::_('VRPERSONALDETAILS'); ?></div>
		<div class="customer-details">
			{customer_details}
		</div>
	</div>

	<div class="separator">&nbsp;</div>

	<div class="order-link">
		<div class="title"><?php echo JText::_('VRORDERLINK'); ?></div>
		<div class="content">
			<a href="{order_link}">{order_link}</a>
		</div>
	</div>

	{confirmation_link}
	
</div>


<?php
/**
* @var string|null	{logo}						the logo image of your company. Null if not specified.
* @var int 			{order_number}				the unique ID of the reservation.
* @var string 		{order_key}					the serial key of the reservation.
* @var string 		{order_date_time}			the checkin date and time of the reservation.
* @var string 		{order_status}				the status of the order [CONFIRMED, PENDING, REMOVED or CANCELLED].
* @var string 		{order_status_class}		the status of the order [confirmed, pending, removed or cancelled].
* @var string|null	{order_payment}				the name of the payment processor selected (*), otherwise NULL.
* @var string|null 	{order_payment_notes}		the notes of the payment processor selected, otherwise NULL.
* @var float 		{order_total_cost}			the total cost of the order.
* @var float 		{order_delivery_service}	the service of the order: delivery or pickup.
* @var string 		{order_coupon_code}			the coupon code used for the order.
* @var string		{order_link}				the direct url to the page of the order.
* @var string		{confirmation_link}			the direct url to confirm the order. Available only if status of the order is PENDING.
* @var string|null 	{company_name}				the name of the company.
* @var string|null 	{customer_details}			the custom fields specified from the customer.
* @var string|null 	{user_details}				the details of the Joomla user.
* @var string 		{cart_details}				the details of all the items purchased in this order.
* @var string 		{cart_grand_total}			the details of the grand total of this order: net price, taxes, discount, delivery charge, pay charge and grand total.
*/
?>