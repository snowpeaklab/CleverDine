<?php
/** 
 * @package   	cleverdine
 * @subpackage 	com_cleverdine
 * @author    	Snowpeak Labs // Wood Box Media
 * @copyright 	Copyright (C) 2018 Wood Box Media. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link 		https://woodboxmedia.co.uk
 */

defined('_JEXEC') OR die('Restricted Area');

/**
 * @package 	cleverdine.Menu
 * @author 		Matteo Galletti
 * @since 		1.7
 *
 * @abstract 	Class used to represent the shape of a generic menu.
 *
 * @see 		SeparatorItemShape
 * @see 		CustomShape
 */
abstract class MenuShape {

	/**
	 * @var  array 	The list of Separators and Custom Items.
	 */
	private $menu;

	/**
	 * The construct sets all the items to contain into the menu.
	 *
	 * @param 	array  $menu 		default empty array
	 *
	 * @uses 	setMenu 			Sets the items in the menu.
	 */
	public function __construct($menu = array()) {
		$this->setMenu($menu);
	}

	/**
	 * Pushes an item into the menu.
	 *
	 * @param 	mixed 	$item 	The SeparatorItemShape or CustomShape item to push.
	 *
	 * @return 	MenuShape		Returns this object to support chaining.
	 */
	public function push($item) {
		array_push($this->menu, $item);

		return $this;
	}

	/**
	 * Returns the reference of the item at the specified index. If the index doesn't exist, returns NULL.
	 *
	 * @param 	int 	$i 		The index of the item.
	 *
	 * @return 	mixed 	The reference of the item or null.
	 */
	public function get($i) {
		if( $i >= 0 && $i < count($this->menu) ) {
			return $this->menu[$i];
		}
		return null;
	}

	/**
	 * Sets the items in the menu.
	 * @usedby __construct
	 *
	 * @param 	array 	$menu 	The array containing the items to push.
	 *
	 * @return 	MenuShape		Returns this object to support chaining.
	 */
	public function setMenu($menu) {
		if( is_array($menu) ) {
			$this->menu = $menu;
		}

		return $this;
	}

	/**
	 * Returns the list of the items in the menu.
	 *
	 * @return 	array 	The list of the items.
	 */
	public function getMenu() {
		return $this->menu;
	}

	/**
	 * Builds and returns the html structure of the menu and its children.
	 *
	 * @return 	string 	The html structure.
	 *
	 * @uses buildHtml 						Builds the html structure of the menu.
	 * @uses SeparatorItemShape::build 		Builds the html structure of the separator.
	 * @uses CustomShape::buildHtml 		Builds the html structure of the custom shape.
	 */
	public function build() {
		$html = "";
		// get the HTML structure from each child of the menu
		foreach( $this->menu as $separator ) {
			if( $separator instanceof SeparatorItemShape ) {
				// get the HTML if the child is a separator
				$html .= $separator->build();
			} else if( $separator instanceof CustomShape ) {
				// get the HTML is the child is a custom shape
				$html .= $separator->buildHtml();
			}
		}

		// build the structure of the menu, which will contain the evaluated $html
		return $this->buildHtml($html);
	}

	/**
	 * Build and returns the html structure of the menu that wraps the children.
	 * @abstract this method must be implemented to define a specific graphic of the menu.
	 * @usedby build
	 *
	 * @param 	string 	$html 	The full structure of the children of the menu.
	 *
	 * @return 	string 	The html of the menu.
	 */
	protected abstract function buildHtml($html);

}

/**
 * @package 	cleverdine.Menu
 * @author 		Matteo Galletti
 * @since 		1.7
 *
 * @abstract 	Class used to represents the shape of a separator menu.
 *
 * @see 		MenuItemShape 
 */
abstract class SeparatorItemShape {

	/**
	 * @var  string 	The title of the separator.
	 */
	private $title;

	/**
	 * @var  string 	The url of the separator. This value can be ignored.
	 */
	private $href;

	/**
	 * @var  string 	A custom value to use during the building.
	 */
	private $custom;

	/** 
	 * @var  boolean 	If the separator is selected.
	 */
	private $selected;

	/**
	 * @var  array 		The list of the children of this separator.
	 */
	private $children;

	/**
	 * The construct sets all the required attributes of this class
	 *
	 * @param 	string 	 $title 		the title of the separator
	 * @param 	string 	 $href 			default empty
	 * @param 	boolean  $selected 		default false
	 *
	 * @uses 	setTitle 				Sets the title of the separator.
	 * @uses 	setHref 				Sets the url of the separator.
	 * @uses 	setSelected 			Sets if the separator is selected.
	 */
	public function __construct($title, $href = '', $selected = false) {
		$this->setTitle($title)
			->setHref($href)
			->setSelected($selected);

		$this->children = array();
	}

	/**
	 * Sets the title of the separator.
	 * @usedby __construct
	 *
	 * @param 	string 	$title 		The title of the separator.
	 *
	 * @return 	SeparatorItemShape	Returns this object to support chaining.
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Returns the title of the separator.
	 *
	 * @return 	string 		The title of the separator.
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the url of the separator.
	 * @usedby __construct
	 *
	 * @param 	string 	$href 		The url of the separator.
	 *
	 * @return 	SeparatorItemShape	Returns this object to support chaining.
	 */
	public function setHref($href) {
		$this->href = $href;

		return $this;
	}

	/**
	 * Returns the url of the separator.
	 *
	 * @return 	string 		The url of the separator.
	 */
	public function getHref() {
		return $this->href;
	}

	/**
	 * Sets the custom value of the separator
	 *
	 * @param 	string 	$custom 	The custom value of the separator.
	 *
	 * @return 	SeparatorItemShape	Returns this object to support chaining.
	 */
	public function setCustom($custom) {
		$this->custom = $custom;

		return $this;
	}

	/**
	 * Returns the custom value of the separator.
	 *
	 * @return 	string 		The custom value of the separator.
	 */
	public function getCustom() {
		return $this->custom;
	}

	/**
	 * Sets if the separator is selected or not.
	 * @usedby __construct
	 *
	 * @param 	boolean 	$selected 	The selection of the separator.
	 *
	 * @return 	SeparatorItemShape		Returns this object to support chaining.
	 */
	public function setSelected($selected) {
		$this->selected = $selected;

		return $this;
	}

	/**
	 * Returns true if the separator is selected, otherwise false.
	 *
	 * @return 	boolean 	If the separator is selected.
	 */
	public function isSelected() {
		return $this->selected;
	}

	/**
	 * Returns true if the separator is collapsed, otherwise false.
	 * A separator is collapsed when it contains at least a selected child.
	 *
	 * @return 	boolean 	If the separator is collapsed.
	 *
	 * @uses 	MenuItemShape::isSelected 	Checks ff the item is selected.
	 */
	public function isCollapsed() {
		foreach( $this->children as $c ) {
			if( $c instanceof SeparatorItemShape && $c->isCollapsed() ) {
				return true;
			} else if( $c instanceof MenuItemShape && $c->isSelected() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Adds a child into the separator.
	 *
	 * @param 	MenuItemShape 	$child 	The child to add.
	 *
	 * @return 	SeparatorItemShape		Returns this object to support chaining.
	 */
	public function addChild($child) {
		array_push($this->children, $child);

		return $this;
	}

	/**
	 * Sets a child at the specified position. Returns true on success, otherwise false.
	 *
	 * @param 	int 			$i 		The position of the child to replace or add.
	 * @param 	MenuItemShape 	$child 	The child to add.
	 *
	 * @return 	boolean			True on success.
	 */
	public function setChild($i, $child) {
		if( $i >= 0 && $i < count($this->children) ) {
			$this->children[$i] = $child;
			return true;
		}
		return false;
	}

	/**
	 * Removes the child at the specified position. Returns true on success, otherwise false.
	 *
	 * @param 	int 	$i 	The position of the child to remove.
	 *
	 * @return 	boolean		True on success.
	 */
	public function unsetChild($i) {
		$n = count($this->children);
		if( $i >= 0 && $i < $n ) {
			// shifts all the items left from the position to remove
			// only the item to remove will be overwritten
			for( $j = $i; $j < $n-1; $j++ ) {
				$this->children[$j] = $this->children[$j+1];
			}
			// remove the last item in the list
			unset($a[$n-1]);
			return true;
		}
		return false;
	}

	/**
	 * Empties the children list.
	 *
	 * @return 	SeparatorItemShape		Returns this object to support chaining.
	 */
	public function clearChildren() {
		$this->children = array();

		return $this;
	}

	/**
	 * Returns the list of the children of the separator.
	 *
	 * @return array 	The MenuItemShape array list of the children.
	 */
	public function children() {
		return $this->children;
	}

	/**
	 * Builds and returns the html structure of the separator and its children.
	 * @usedby MenuShape::build
	 *
	 * @return 	string 		The html structure.
	 *
	 * @uses 	buildHtml 	Builds the html structure of the separator.
	 */
	public function build() {
		$html = "";
		// get the HTML structure from each child of the separator
		foreach( $this->children as $c ) {
			if( $c instanceof SeparatorItemShape ) {
				// get the HTML if the child is a separator
				$html .= $c->build();
			} else if( $c instanceof MenuItemShape ) {
				// get the HTML if the child is a menu item
				$html .= $c->buildHtml();
			}
		}

		// build the structure of the separator, which will contain the evaluated $html
		return $this->buildHtml($html);
	}

	/**
	 * Build and returns the html structure of the separator that wraps the children.
	 * @abstract this method must be implemented to define a specific graphic of the separator.
	 * @usedby build
	 *
	 * @param 	string 	$html 	The full structure of the children of the separator.
	 *
	 * @return 	string 	The html of the separator.
	 */
	protected abstract function buildHtml($html);

}

/**
 * @package 	cleverdine.Menu
 * @author 		Matteo Galletti
 * @since 		1.7
 *
 * @abstract 	Class used to represents the shape of a menu item.
 */
abstract class MenuItemShape {

	/**
	 * @var  string 	The title of the separator.
	 */
	private $title;

	/** 
	 * @var  string 	The url of the separator. This value can be ignored.
	 */
	private $href;

	/**
	 * @var  string 	A custom value to use during the building.
	 */
	private $custom;

	/**
	 * @var  boolean 	If the separator is selected.
	 */
	private $selected;

	/**
	 * The construct sets all the required attributes of this class
	 *
	 * @param  	string 	$title 			the title of the menu item
	 * @param  	string 	$href 			default empty
	 * @param  	boolean $selected 		default false
	 *
	 * @uses 	setTitle 				Sets the title of the menu item.
	 * @uses 	setHref 				Sets the url of the menu item.
	 * @uses 	setSelected 			Sets if the menu item is selected.
	 */
	public function __construct($title, $href, $selected = false) {
		$this->setTitle($title)
			->setHref($href)
			->setSelected($selected);
	}

	/**
	 * Sets the title of the menu item.
	 * @usedby __construct
	 *
	 * @param 	string 	$title 	The title of the menu item.
	 *
	 * @return 	MenuItemShape	Returns this object to support chaining.
	 */
	public function setTitle($title) {
		$this->title = $title;

		return $this;
	}

	/**
	 * Returns the title of the menu item.
	 *
	 * @return 	string 		The title of the menu item.
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * Sets the url of the menu item. 
	 * @usedby __construct
	 *
	 * @param 	string 	$href 	The url of the menu item.
	 *
	 * @return 	MenuItemShape	Returns this object to support chaining.
	 */
	public function setHref($href) {
		$this->href = $href;

		return $this;
	}

	/**
	 * Returns the url of the menu item.
	 *
	 * @return 	string 		The url of the menu item.
	 */
	public function getHref() {
		return $this->href;
	}

	/**
	 * Sets the custom value of the menu item
	 *
	 * @param 	string 	$custom 	The custom value of the menu item.
	 *
	 * @return 	MenuItemShape		Returns this object to support chaining.
	 */
	public function setCustom($custom) {
		$this->custom = $custom;

		return $this;
	}

	/**
	 * Returns the custom value of the menu item.
	 *
	 * @return 	string 		The custom value of the menu item.
	 */
	public function getCustom() {
		return $this->custom;
	}

	/**
	 * Sets if the menu item is selected or not.
	 * @usedby __construct
	 *
	 * @param 	boolean 	$selected 	The selection of the menu item.
	 *
	 * @return 	MenuItemShape			Returns this object to support chaining.
	 */
	public function setSelected($selected) {
		$this->selected = $selected;

		return $this;
	}

	/**
	 * Returns true if the menu item is selected, otherwise false.
	 * @usedby SeparatorMenuShape::isCollapsed
	 *
	 * @return 	boolean 	True if selected.
	 */
	public function isSelected() {
		return $this->selected;
	}

	/**
	 * Builds and returns the html structure of the menu item.
	 * @abstract this method must be implemented to define a specific graphic of the menu item.
	 * @usedby MenuShape::build
	 * @usedby SeparatorMenuShape::build
	 *
	 * @return  string 		The html of the menu item.
	 */
	public abstract function buildHtml();
}

/**
 * @package 	cleverdine.Menu
 * @author 		Matteo Galletti
 * @since 		1.7
 *
 * @abstract 	Class used to represents the shape of a custom item.
 */
abstract class CustomShape {

	/**
	 * @var  array 		The array containing all the attributes of this custom item.
	 * 					The attributes of this custom item cannot be directly accessed.
	 *
	 * @see  get 		To access an attribute of this mustom item it is required to use the get function.
	 */
	private $params;

	/**
	 * The construct sets all the required attributes of this class
	 *
	 * @param 	array 	$params 	default empty array
	 *
	 * @uses 	setParams 			Sets the attributes of this custom item.
	 */
	public function __construct($params = array()) {
		$this->setParams($params);
	}

	/**
	 * Sets all the attributes of this custom item.
	 * @usedby __construct
	 *
	 * @param 	array 	$params 	The attributes to use.
	 *
	 * @return 	CustomShape			Returns this object to support chaining.
	 */
	public function setParams($params) {
		if( is_array($params) ) {
			$this->params = $params;
		}

		return $this;
	}

	/**
	 * Returns the value of the attribute specified. Returns NULL is the specified attribute doesn't exist.
	 * The attributes of this custom item are not accessible from external classes.
	 *
	 * @param 	string 	$key 	The name of the attribute.
	 *
	 * @return 	string 	The value of the attribute.
	 */
	public function get($key) {
		if( array_key_exists($key, $this->params) ) {
			return $this->params[$key];
		}

		return null;
	}

	/**
	 * Adds a new parameter with the specified attribute.
	 *
	 * @param 	string 	$key 		The attribute of the parameter.
	 * @param 	string 	$param 		The value of the parameter.
	 *
	 * @return 	CustomShape			Returns this object to support chaining.
	 */
	public function add($key, $param) {
		$this->params[$key] = $param;

		return $this;
	}

	/**
	 * Build and returns the html structure of the custom menu item.
	 * @abstract this method must be implemented to define a specific graphic of the custom item.
	 * @usedby MenuShape::build
	 *
	 * @return 	string 		The html of the custom item.
	 */
	public abstract function buildHtml();

}

?>