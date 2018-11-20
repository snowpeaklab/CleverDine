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
 * cleverdine reviews class handler.
 *
 * @see 	JFactory 	Joomla Factory to access the database resource.
 * @see 	UIFactory 	Custom Factory to control the configuration of the software.
 *
 * @since  	1.6
 * @since 	1.7 	Renamed from VRReviewsHandler.
 */
class ReviewsHandler
{
	/**
	 * The language tag filter.
	 * Specify null to ignore it.
	 *
	 * @var string
	 */
	private $langtag = null;
	
	/**
	 * The rating filter between 1 to 5.
	 * Specify null to ignore it.
	 *
	 * @var integer
	 */
	private $rating = null;
	
	/**
	 * The array containig all the orderings to use.
	 * The first index (of each row) is the column to sort and the second is the direction to use.
	 *
	 * @var array
	 */
	private $ordering = null;

	/**
	 * The array containing the limit of the reviews to list.
	 * The first index if the start position and the second is the number of reviews to get.
	 * 
	 * @var array
	 */
	private $lim = null;

	/**
	 * True if the comment is required, otherwise is optional.
	 *
	 * @var boolean
	 */
	private $comment = false;

	/**
	 * The section of the program.
	 * Specify 1 for take-away reviews.
	 *
	 * @var integer
	 */
	private $type = null;

	/**
	 * The object that handle the navigation of the reviews.
	 *
	 * @var object
	 */
	private $navigation = null;

	/**
	 * Class constructor.
	 *
	 * @param 	string 		$langtag 	The langtag for filtering.
	 * @param 	integer 	$rating 	The rating for filtering.
	 *
	 * @uses 	setLangTag() 		Set lang tag. 
	 * @uses 	setRatingFilter() 	Set rating.
	 * @uses 	setOrdering() 		Set ordering.
	 * @uses 	setLimit() 			Set limit.
	 */
	public function __construct($langtag = null, $rating = null)
	{
		$config = UIFactory::getConfig();

		$this->setLangTag($langtag)
			->setRatingFilter($rating)
			->setOrdering('timestamp', 2)
			->setLimit(0, $config->getUint('revlimlist'));
	}

	/**
	 * Set the langtag filter option.
	 *
	 * @param 	string 	$langtag 	The lang tag.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function setLangTag($langtag)
	{
		$this->langtag = !empty($langtag) ? $langtag : null;

		return $this;
	}

	/**
	 * Set the rating filter option.
	 *
	 * @param 	integer 	$rating 	The rating.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function setRatingFilter($rating)
	{
		$this->rating = ($rating = intval($rating)) >= 1 && $rating <= 5 ? $rating : null;

		return $this;
	}

	/**
	 * Set the ordering filter option.
	 *
	 * @param 	string 		$col 	The column to sort.
	 * @param 	integer 	$mode 	The direction to use. Specify 1 for ascending sort, 
	 * 								2 for descending sort.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function setOrdering($col, $mode = 1)
	{
		$this->ordering = array(array($col, $mode));

		return $this;
	}

	/**
	 * Add the ordering filter option to the list.
	 *
	 * @param 	string 		$col 	The column to sort.
	 * @param 	integer 	$mode 	The direction to use. Specify 1 for ascending sort, 
	 * 								2 for descending sort.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function addOrdering($col, $mode = 1)
	{
		if ($this->ordering === null) {
			$this->setOrdering($col, $mode);
		} else {
			$this->ordering[] = array($col, $mode);
		}

		return $this;
	}

	/**
	 * Set the limit filter option.
	 *
	 * @param 	integer 	$lim0 	The start position.
	 * @param 	integer 	$lim 	The count of reviews.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function setLimit($lim0, $lim)
	{
		if ($this->lim === null) {
			$this->lim = array();
		}

		$this->lim[0] = $lim0;
		$this->lim[1] = $lim;

		return $this;
	}

	/**
	 * Specify if comments are required or optional.
	 *
	 * @param 	boolean 	$s 	True if required, otherwise false.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function allowEmptyComment($s = true)
	{
		$this->comment = $s;

		return $this;
	}

	/**
	 * Set the type to TAKEAWAY.
	 *
	 * @return 	ReviewsHandler 	This object to support chaining.
	 */
	public function takeaway()
	{
		$this->type = 1;

		return $this;
	}

	/**
	 * Get the reviews following all the queries specified.
	 *
	 * @param 	integer		$id 	The ID of the product.
	 * @param 	object 		$dbo 	The database object.
	 *
	 * @return 	array 	The reviews for the specified product.
	 */
	public function getReviews($id = 0, $dbo = null)
	{
		if ($dbo === null) {
			$dbo = JFactory::getDbo();
		}

		$this->navigation = null;

		if (!cleverdine::isReviewsEnabled()) {
			return false;
		}

		$more_filters = '';
		if (cleverdine::isReviewsLangFilter() || $this->langtag !== null) {
			if ($this->langtag === null) {
				$this->langtag = JFactory::getLanguage()->getTag();
			}

			$more_filters .= "AND `r`.`langtag`=".$dbo->quote($this->langtag)." ";
		}

		if ($this->rating !== null) {
			$more_filters .= "AND `r`.`rating`=".$this->rating." ";
		}

		if (!$this->comment) {
			$more_filters .= "AND `r`.`comment`<>'' ";
		}

		if ($this->type == 1) {

			if (!cleverdine::isTakeAwayReviewsEnabled()) {
				return false;
			}
		
			$id = intval($id);

			$q = "SELECT SQL_CALC_FOUND_ROWS `r`.*, `e`.`name` AS `product_name`, `u`.`image` 
			FROM `#__cleverdine_reviews` AS `r` 
			LEFT JOIN `#__cleverdine_takeaway_menus_entry` AS `e` ON `e`.`id`=`r`.`id_takeaway_product` 
			LEFT JOIN `#__cleverdine_users` AS `u` ON `u`.`jid`=`r`.`jid` 
			WHERE `r`.`published`=1 AND `r`.`id_takeaway_product`=$id $more_filters
			ORDER BY ";

			foreach ($this->ordering as $i => $ord) {
				$q .= ($i > 0 ? ", " : "")."`r`.".$dbo->quoteName($ord[0])." ".($ord[1] == 1 ? 'ASC' : 'DESC');
			}

			$dbo->setQuery($q, $this->lim[0], $this->lim[1]);
			$dbo->execute();

			if ($dbo->getNumRows() > 0) {
				$reviews = $dbo->loadAssocList();

				$dbo->setQuery('SELECT FOUND_ROWS();');
				jimport('joomla.html.pagination');
				$this->navigation = new JPagination( $dbo->loadResult(), $this->lim[0], $this->lim[1] );

				return $reviews;
			}

		}

		return array();
	}

	/**
	 * Get the HTML navigation retrieved only after a successful query.
	 *
	 * @param 	array	$params 	A list with the parameters to maintain.
	 *
	 * @return 	string 	The HTML code of the pagination.
	 */
	public function getNavigationHTML($params = array())
	{
		if ($this->navigation === null) {
			return '';
		}

		if (@is_array($params) || @is_object($params)) {
			foreach ($params as $k => $v) {
				$this->navigation->setAdditionalUrlParam($k, $v);
			}
		}

		return $this->navigation->getPagesLinks();
	}

	/**
	 * Get the average rating ratio of the specified product.
	 * The object contains the following attributes: count, rating, halfRating.
	 *
	 * @param 	integer		$id 	The ID of the product.
	 * @param 	object 		$dbo 	The database object.
	 *
	 * @return 	object 	The object containg rating info on success, otherwise null.
	 */
	public function getAverageRatio($id = 0, $dbo = null) {
		if ($dbo === null) {
			$dbo = JFactory::getDbo();
		}

		if (!cleverdine::isReviewsEnabled()) {
			return false;
		}

		if ($this->type == 0) {

		} else if ($this->type == 1) {

			if (!cleverdine::isTakeAwayReviewsEnabled()) {
				return false;
			}
		
			$id = intval($id);

			$q = "SELECT COUNT(1) AS `count`, AVG(`r`.`rating`) AS `rating`
			FROM `#__cleverdine_reviews` AS `r` 
			WHERE `r`.`published`=1 AND `r`.`id_takeaway_product`=$id;";

			$dbo->setQuery($q);
			$dbo->execute();

			if ($dbo->getNumRows() > 0) {
				$obj = $dbo->loadObject();
				$obj->halfRating = $this->roundHalfClosest($obj->rating);

				return $obj;
			}

		}

		return null;
	}

	/**
	 * Get the rating count of the specified product.
	 * The object contains the following attributes: count, ratings (array with count for each star).
	 *
	 * @param 	integer		$id 	The ID of the product.
	 * @param 	object 		$dbo 	The database object.
	 *
	 * @return 	object 	The object containg rating count info on success, otherwise null.
	 */
	public function getRatingsCount($id = 0, $dbo = null)
	{
		if ($dbo === null) {
			$dbo = JFactory::getDbo();
		}

		if (!cleverdine::isReviewsEnabled()) {
			return false;
		}

		if ($this->type == 0) {

		} else if ($this->type == 1) {

			if (!cleverdine::isTakeAwayReviewsEnabled()) {
				return false;
			}
		
			$id = intval($id);

			$obj = new stdClass;
			$obj->count = 0;
			$obj->ratings = array(
				"1" => 0, "2" => 0, "3" => 0, "4" => 0, "5" => 0
			);

			$q = "SELECT COUNT(1) FROM `#__cleverdine_reviews` AS `r` 
			WHERE `r`.`published`=1 AND `r`.`id_takeaway_product`=$id;";

			$dbo->setQuery($q);
			$dbo->execute();
			$obj->count = (int) $dbo->loadResult();

			$sum = 0;

			for ($i = 1; $i <= 5 && $sum < $obj->count; $i++) {
				$q = "SELECT COUNT(1) FROM `#__cleverdine_reviews` AS `r` 
				WHERE `r`.`published`=1 AND `r`.`rating`=$i AND `r`.`id_takeaway_product`=$id;";

				$dbo->setQuery($q);
				$dbo->execute();
				$obj->ratings[$i] = (int) $dbo->loadResult();

				$sum += $obj->ratings[$i];
			}

			return $obj;

		}

		return null;
	}

	/**
	 * Round the decimal digits of the rating to be 0 or 5.
	 *
	 * @param 	float	$d 	The rating to round.
	 *
	 * @return 	float 	The rating rounded.
	 */
	public function roundHalfClosest($d)
	{
		$floor = floor($d*2)/2;
		$ceil = ceil($d*2)/2;
		if (abs($d-$floor) < abs($d-$ceil)) {
			return $floor;
		}

		return $ceil;
	}

}
