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
* cleverdine - Take-Away Cancellation E-Mail Template
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
	width: 70%;
	font-family: "Century Gothic", Tahoma, Arial;
}

.separator {
	margin: 7px 0;
}

/* head */
.head-wrapper {
	width: 80%;
}
.head {
	padding: 10px;
	border: 1px solid #bbb;
	background-color: #fafafa;
	text-align: center;
}
.head .logo {
	margin-bottom: 15px;
}
.head .content {
	border: 1px solid #bbb;
	background-color: #fefefe;
	padding: 10px;
	border-bottom: 0;
}
.head .order-link {
	border: 1px solid #bbb;
	background-color: #f8f8f8;
	padding: 10px;
	word-break: break-all;
	word-wrap: break-word;
}
.head .order-link-empty {
	border-top: 1px solid #bbb;
}
.head .cancellation-reason {
	margin-top: 5px;
	border: 1px solid #bbb;
	background-color: #fefefe;
	padding: 10px;
}

/* order details */
.order-details {
	width: 80%;
}
.order {
	margin-bottom: 10px;
}
.order .content {
	border: 1px solid #bbb;
	padding: 10px;
	border-bottom: 0;
	background-color: #f8f8f8;
}
.order .content .left {
	display: inline-block;
	width: 70%;
	text-align: left;
}
.order .content .right {
	display: inline-block;
	width: 29%;
	text-align: right;
	font-weight: bold;
	text-transform: uppercase;
	color: #F01B17;
}
.order .subcontent {
	border: 1px solid #bbb;
	padding: 10px;
	border-bottom: 0;
	background-color: #fafafa;
}
.order .subcontent .left {
	display: inline-block;
	width: 33%;
	text-align: left;
}
.order .subcontent .center {
	display: inline-block;
	width: 33%;
	text-align: center;
}
.order .subcontent .right {
	display: inline-block;
	width: 33%;
	text-align: right;
}
.order .link {
	border: 1px solid #bbb;
	padding: 5px;
	background-color: #e5e5e5;
	word-break: break-all;
	word-wrap: break-word;
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

@media screen and (max-width : 1024px) {
	.container {
		width: 100%;
	}
	.head-wrapper, .order-details, .customer-details-wrapper {
		width: 99% !important;
	}
	.separator {
		margin: 0 !important;
	}
	.customer-details .info .label {
		width: 110px !important;
	}
}

</style>

<div class="container">

	<div class="head-wrapper">

		<div class="head">

			<div class="logo">{logo}</div>

			<div class="content">{cancellation_content}</div>

			{order_link}

			{cancellation_reason}

		</div>

	</div>

	<div class="separator">&nbsp;</div>

	<div class="order-details">
		{order_summary}
	</div>	

	<div class="separator">&nbsp;</div>

	<div class="customer-details-wrapper">
		<div class="title"><?php echo JText::_('VRPERSONALDETAILS'); ?></div>
		<div class="customer-details">
			{customer_details}
		</div>
	</div>
	
</div>

<?php
/**
* @var string|null	{logo}					the logo image of your company.
* @var string|null 	{cancellation_content}	the content specified in the language file at VRORDERCANCELLEDCONTENT.
* @var string 		{cancellation_reason}	the cancellation reason specified from the customer.
* @var string		{order_link}			the direct url to the details page of the order.
* @var string|null 	{company_name}			the name of the company.
* @var string|null 	{customer_details}		the custom fields specified from the customer.
* @var string 		{order_summary}			the summary details of the order
*/
?>