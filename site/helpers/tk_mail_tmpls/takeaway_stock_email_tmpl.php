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
* cleverdine - Take-Away Stock E-Mail Template
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
	margin: 2px;
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
}

/* list details */
.list-details {
	width: 80%;
}
.menu {
	margin-bottom: 10px;
}
.menu .menu-title {
	border: 1px solid #bbb;
	padding: 10px;
	border-bottom: 0;
	background-color: #f8f8f8;
}
.menu .product {
	border: 1px solid #bbb;
	padding: 10px;
	border-bottom: 0;
	background-color: #fafafa;
}
.menu .product .left {
	display: inline-block;
	width: 65%;
	text-align: left;
	margin-left: 3%;
}
.menu .product .right {
	display: inline-block;
	width: 32%;
	text-align: right;
	font-weight: bold;
	text-transform: uppercase;
	color: #F01B17;
}
.menu .product:last-child {
	border-bottom: 1px solid #bbb;
}

@media screen and (max-width : 1024px) {
	.container {
		width: 100% !important;
	}
	.head-wrapper, .list-details {
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

			<div class="content">{mail_content}</div>

		</div>

	</div>

	<div class="separator">&nbsp;</div>

	<div class="list-details">
		{list_details}
	</div>	

	<div class="separator">&nbsp;</div>
	
</div>

<?php
/**
* @var string|null	{logo}					the logo image of your company.
* @var string|null 	{mail_content}			a short description about the stocks.
* @var string 		{order_summary}			the list of all the items which are going to finish.
*/
?>