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
* cleverdine - Take-Away Review E-Mail Template
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
.head .review-product {
	border: 1px solid #bbb;
	background-color: #f8f8f8;
	padding: 10px;
	word-break: break-all;
	word-wrap: break-word;
}
.head .review-product .prod-left {
	text-align: left;
	width: 30%;
	display: inline-block;
	vertical-align: top;
}
.head .review-product .prod-left img {
	max-width: 90%;
}
.head .review-product .prod-center {
	text-align: left;
	width: 70%;
	display: inline-block;
	vertical-align: top;
}
.head .review-product .prod-center .prod-desc {
	margin-top: 10px;
	border-top: 1px solid #bbb;
	padding-top: 10px;
	font-size: smaller;
}

/* review summary */
.review-summary {
	width: 80%;
}
.review-summary-wrapper {
	border: 1px solid #bbb;
}
.review-summary .review-top {
	padding: 10px 20px;
	background-color: #eee;
}
.review-summary .review-bottom {
	padding: 10px 20px;
	background-color: #e2e2e2;
}
.review-summary .review-top .top-head {
	height: 30px;
}
.review-summary .review-top .top-head > div {
	float: left;
	margin-right: 20px;
}
.review-summary .review-top .top-head .title {
	margin-top: 3px;
}
.review-summary .review-top .top-subhead {
	margin-top: 5px;
	font-size: smaller;
	color: #ef6a29;
}

/* confirmation link */
.confirmation-link {
	width: 80%;
	margin-bottom: 5px;
}
.confirmation-link .title {
	border: 1px solid #bbb;
	padding: 5px;
	border-bottom: 0;
	background-color: #f8f8f8;
}
.confirmation-link .content {
	border: 1px solid #bbb;
	padding: 10px;
	background-color: #fafafa;
	word-break: break-all;
	word-wrap: break-word;
}

@media screen and (max-width : 1024px) {
	.container {
		width: 100%;
	}
	.head-wrapper, .review-summary, .confirmation-link {
		width: 99% !important;
	}
	.separator {
		margin: 0 !important;
	}
}

</style>

<div class="container">

	<div class="head-wrapper">

		<div class="head">

			<div class="logo">{logo}</div>

			<div class="content">{review_content}</div>

			{review_product}

		</div>

	</div>

	<div class="separator">&nbsp;</div>

	<div class="review-summary">
		<div class="review-summary-wrapper">
			<div class="review-top">
				<div class="top-head">
					<div class="rating">{review_rating}</div>
					<div class="title">{review_title}</div>
				</div>
				<div class="top-subhead">
					{review_verified}
				</div>
			</div>

			<div class="review-bottom">
				{review_comment}
			</div>
		</div>
	</div>	

	<div class="separator">&nbsp;</div>

	<div class="confirmation-link" style="{confirmation_link_style}">
		<div class="title"><?php echo JText::_('VRCONFIRMATIONLINK'); ?></div>
		<div class="content">
			<a href="{confirmation_link}">{confirmation_link}</a>
		</div>
	</div>
	
</div>

<?php
/**
* @var string|null	{logo}					the logo image of your company.
* @var string|null 	{cancellation_content}	the content specified in the language file at VRORDERCANCELLEDSUBJECT.
* @var string		{order_link}			the direct url to the details page of the order.
* @var string|null 	{company_name}			the name of the company.
* @var string|null 	{customer_details}		the custom fields specified from the customer.
* @var string 		{order_summary}			the summary details of the order
*/
?>