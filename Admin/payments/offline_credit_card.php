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
defined('_JEXEC') OR die('Restricted Area');

/**
 * The Offline Credit Card payment gateway (seamless) is not a real method of payment. 
 * This gateway collects the credit card details of your customers and then send them via e-mail to the administrator, 
 * so that it is able to make the transaction with a virtual pos.
 *
 * After the form submission the status of the order will be changed to CONFIRMED.
 * If you want to leave the status to PENDING (to change it manually) it is needed to change the default status 
 * from the parameters of your gateway.
 *
 * For PCI compliance, the system encrypts the details of the credit card and store them partially in the database.
 * The remaining details are sent to the e-mail of the administrator.
 *
 * @since 1.0
 */
class cleverdinePayment
{
	/**
	 * The esit of the transaction.
	 *
	 * @var boolean
	 */
	private $validation = 0;
	
	/**
	 * The order information needed to complete the payment process.
	 *
	 * @var array
	 */
	private $order_info;

	/**
	 * The payment configuration.
	 *
	 * @var array
	 */
	private $params;
	
	/**
	 * Return the fields that should be filled in from the details of the payment.
	 * The configuration fields are listed below:
	 * @property 	newstatus 	The status assumed after a successful transaction.
	 * @property 	usessl 		True to have the payment form under HTTPS.
	 * @property 	brands 		The accepted credit card brands.
	 *
	 * @return 	array 	The fields array.
	 */
	public static function getAdminParameters()
	{
		return array( 
			'newstatus' => array(
				'type' 		=> 'select', 
				'label' 	=> 'Set Order Status to://use PENDING in case you want to manually verify the credit card',
				'options' 	=> array('CONFIRMED', 'PENDING'),
			),
			'usessl' => array(
				'type' 		=> 'select',
				'label' 	=> 'Use SSL',
				'options' 	=> array(1 => 'ON', 0 => 'OFF'),
			),
			'brands' => array(
				'type' 		=> 'select',
				'label' 	=> 'Accepted CC Brands//leave this field empty to accept all brands',
				'multiple' 	=> 1,
				'options' 	=> array(
					'visa' 			=> 'Visa',
					'mastercard' 	=> 'Master Card',
					'amex' 			=> 'American Express',
					'diners' 		=> 'Diners Club',
					'discover' 		=> 'Discover',
					'jcb' 			=> 'JCB',
				),
			),
		);
	}
	
	/**
	 * Class constructor.
	 *
	 * @param 	array 	$order 	 The order info array.
	 * @param 	array 	$params  The payment configuration. These fields are the 
	 * 							 same of the getAdminParameters() function.
	 */
	public function __construct($order, $params = array())
	{
		$this->order_info 	= $order;
		$this->params 		= $params;

		cleverdine::loadBankingLibrary(array('creditcard'));
	}
	
	/**
	 * This method is invoked every time a user visits the page of a reservation with PENDING Status.
	 * Display the form to collect the details of a given credit card.
	 *
	 * @return 	void
	 *
	 * @uses 	hasCreditCard() 	Make sure the reservation has no CC details.
	 */
	public function showPayment()
	{
		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		
		if ($this->params['usessl']) {
			// change scheme from URLs
			$this->order_info['notify_url'] = str_replace('http:', 'https:', $this->order_info['notify_url']);
			$this->order_info['return_url'] = str_replace('http:', 'https:', $this->order_info['return_url']);
			$this->order_info['error_url'] 	= str_replace('http:', 'https:', $this->order_info['error_url']);

			$uri = JUri::getInstance();

			if (strtolower($uri->getScheme()) != 'https') {
				// Forward to HTTPS
				$uri->setScheme('https');
				$app->redirect((string) $uri, 301);
			}
		}

		if ($this->hasCreditCard()) {
			return false;
		}

		// load resources
		cleverdine::load_font_awesome();
		$vik = new VikApplication();
		$doc = JFactory::getDocument();

		$doc->addStyleSheet(JUri::root().'administrator/components/com_cleverdine/payments/off-cc/resources/off-cc.css');
		$vik->addScript(JUri::root().'administrator/components/com_cleverdine/payments/off-cc/resources/off-cc.js');

		$form = '<form action="'.$this->order_info['notify_url'].'" method="post" name="offlineccpaymform" id="offlineccpaymform">';
		$form .= '<div class="offcc-payment-wrapper">';
		$form .= '<div class="offcc-payment-box">';

		// accepted brands
		$form .= '<div class="offcc-payment-field">';

		$form .= '<div class="offcc-payment-field-wrapper">';
		foreach ((count($this->params['brands']) ? $this->params['brands'] : CreditCard::getAllBrands()) as $brand) {
			$form .= '<img src="'.JUri::root().'/administrator/components/com_cleverdine/payments/off-cc/resources/icons/'.$brand.'.png" title="'.$brand.'" alt="'.$brand.'"/> ';
		}
		$form .= '</div>';

		$form .= '</div>';

		// Cardholder Name
		$form .= '<div class="offcc-payment-field">';

		$form .= '<div class="offcc-payment-field-wrapper">';
		$form .= '<span class="offcc-payment-icon"><i class="fa fa-user"></i></span>';
		$form .= '<input type="text" name="cardholder" value="'.$this->order_info['details']['purchaser_nominative'].'" placeholder="'.JText::_('VRCCNAME').'"/>';
		$form .= '</div>';

		$form .= '</div>';

		// Credit Card
		$form .= '<div class="offcc-payment-field">';

		$form .= '<div class="offcc-payment-field-wrapper">';
		$form .= '<span class="offcc-payment-icon"><i class="fa fa-credit-card-alt"></i></span>';
		$form .= '<input type="text" name="cardnumber" value="" placeholder="'.JText::_('VRCCNUMBER').'" maxlength="16" autocomplete="off"/>';
		$form .= '<span class="offcc-payment-cctype-icon" id="credit-card-brand"></span>';
		$form .= '</div>';

		$form .= '</div>';

		// Expiry Date and CVC
		$form .= '<div class="offcc-payment-field">';

		$form .= '<div class="offcc-payment-field-wrapper inline">';
		$form .= '<span class="offcc-payment-icon"><i class="fa fa-calendar"></i></span>';
		$form .= '<input type="text" name="expdate" value="" placeholder="'.JText::_('VREXPIRINGDATEFMT').'" class="offcc-small" maxlength="7"/>';
		$form .= '</div>';

		$form .= '<div class="offcc-payment-field-wrapper inline">';
		$form .= '<span class="offcc-payment-icon"><i class="fa fa-lock"></i></span>';
		$form .= '<input type="text" name="cvc" value="" placeholder="'.JText::_('VRCVV').'" class="offcc-small" maxlength="4" autocomplete="off"/>';
		$form .= '</div>';

		$form .= '</div>';

		// Submit
		$form .= '<div class="offcc-payment-field">';

		$form .= '<div class="offcc-payment-field-wrapper inline">';
		$form .= '<button type="submit" onclick="return validateCreditCardForm();" class="cc-submit-btn">Submit</button>';
		$form .= '</div>';

		$form .= '</div>';

		$form .= '</div>';
		$form .= '</div>';
		$form .= '</form>';
		
		//output
		echo $form;
		
		return true;
	}
	
	/**
	 * Validate the transaction details sent from the bank. 
	 * This method is invoked by the system every time the Notify URL 
	 * is visited (the one used in the showPayment() method). 
	 *
	 * @return 	array 	The array result, which MUST contain the "verified" key (1 or 0).
	 *
	 * @uses 	registerCreditCard() 	Register the CC details (partially) in the database.
	 * @uses 	notifyAdmin()	 		Send the remaining CC details via e-mail to the admin.
	 */
	public function validatePayment()
	{
		$array_result = array();
		$array_result['verified'] = 0;
		$array_result['tot_paid'] = 0.0;
		$array_result['log'] = '';

		$app 	= JFactory::getApplication();
		$input 	= $app->input;
		
		// post data (only data in POST method)
		$request = array();
		$request['cardholder'] 	= $input->post->getString('cardholder');
		$request['cardnumber'] 	= $input->post->get('cardnumber');
		$request['expdate'] 	= $input->post->get('expdate');
		$request['cvc'] 		= $input->post->get('cvc');
		// end post data

		foreach ($request as $k => $v) {
			if (empty($v)) {
				// exit and no log for invalid data
				return $array_result;
			}
		}

		if (strlen($request['expdate']) != 4) {
			// expiry date must have 4 characters to represent mmYY format
			// exit and no log for invalid data
			return $array_result;
		}

		$now = getdate();

		$month 	= intval(substr($request['expdate'], 0, 2));
		$year 	= intval(substr($now['year'], 0, 2).substr($request['expdate'], 2, 2));

		$card = CreditCard::getBrand($request['cardnumber'], $request['cvc'], $month, $year, $request['cardholder']);

		if( 
			// impossible to identify credit card brand
			!($card instanceof CreditCard)
			// impossible to charge the credit card
			|| !$card->isChargable()
			// the brand of the credit card is not accepted (empty brands means "all brands are accepted")
			|| (count($this->params['brands']) && !in_array($card->getBrandAlias(), $this->params['brands'])) 
		) {
			// exit and no log for invalid data
			return $array_result;
		}

		// register credit card in order information
		if ($this->registerCreditCard($card)) {
			// notify administrator via e-mail
			$this->notifyAdmin($card);
		} else {
			$array_result['log'] = 'Impossible to register credit card details';
			return $array_result;
		}

		// credit card information received
		
		$this->validation = 1;
		if ($this->params['newstatus'] == 'CONFIRMED') {
			$array_result['verified'] = 1;	
		}
		
		return $array_result;
	}
	
	/**
	 * This function is called after the payment has been validated for redirect actions.
	 * When this method is called, the class is invoked after the validatePayment() function.
	 *
	 * @param 	boolean 	$esit 	The esit of the transaction.
	 *
	 * @return 	void
	 */
	public function afterValidation($esit = 0)
	{
		$app = JFactory::getApplication();
		
		/**
		 * override esit with the validation calculated previously
		 * @see validatePayment()
		 */
		$esit = $this->validation;

		if ($esit < 1) {
			$app->enqueueMessage(JText::_('VRPAYNOTVERIFIED'), 'error');
		} else {
			$app->enqueueMessage(JText::_('VROFFCCPAYMENTRECEIVED'));
		}
		
		$app->redirect($this->order_info['return_url']);
		exit;
	}

	///////////
	// UTILS //
	///////////

	/**
	 * Check if the reservation already owns some credit card details.
	 *
	 * @return 	boolean 	True if any, otherwise false.
	 */
	private function hasCreditCard()
	{
		$dbo = JFactory::getDbo();

		$table = '#__cleverdine_'.($this->order_info['tid'] == 0 ? '' : 'takeaway_').'reservation';

		$q = $dbo->getQuery(true);

		$q->select($dbo->qn('cc_details'))
			->from($dbo->qn($table))
			->where($dbo->qn('id') . ' = ' . (int) $this->order_info['oid']);


		$dbo->setQuery($q, 0, 1);
		$dbo->execute();

		return ($dbo->getNumRows() && strlen($dbo->loadResult()));
	}

	/**
	 * Encrypt the partial details of the credit card and register them
	 * into the database.
	 *
	 * @param 	CreditCard 	$card 	The credit card details.
	 *
	 * @return 	boolean 	True on success, otherwise false.
	 */
	private function registerCreditCard(CreditCard $card)
	{
		if ($card === null) {
			return false;
		}

		cleverdine::loadCryptLibrary();

		$dbo = JFactory::getDbo();

		// build object
		$obj = new stdClass;

		$obj->brand = new stdClass;
		$obj->brand->label = JText::_('VRCCBRAND');
		$obj->brand->value = $card->getBrandName();
		$obj->brand->alias = $card->getBrandAlias();

		$obj->cardHolder = new stdClass;
		$obj->cardHolder->label = JText::_('VRCCNAME');
		$obj->cardHolder->value = $card->getCardholderName();

		$obj->cardNumber = new stdClass;
		$obj->cardNumber->label = JText::_('VRCCNUMBER');
		$obj->cardNumber->value = $card->getMaskedCardNumber();
		// get only short masked card number
		$obj->cardNumber->value = $obj->cardNumber->value[0];

		$obj->expiryDate = new stdClass;
		$obj->expiryDate->label = JText::_('VREXPIRINGDATE');
		$obj->expiryDate->value = $card->getExpiryDate();

		$obj->cvc = new stdClass;
		$obj->cvc->label = JText::_('VRCVV');
		$obj->cvc->value = $card->getCvc();

		// JSON encode
		$json = json_encode($obj);

		// mask secure key
		$cipher = SecureCipher::getInstance();

		$data = $cipher->safeEncodingEncryption($json);

		// register credit card details in database
		$table = '#__cleverdine_'.($this->order_info['tid'] == 0 ? '' : 'takeaway_').'reservation';

		$q = $dbo->getQuery(true);

		$q->update($dbo->qn($table))
			->set($dbo->qn('cc_details') . ' = ' . $dbo->q($data))
			->where($dbo->qn('id') . ' = ' . (int) $this->order_info['oid']);

		$dbo->setQuery($q);
		$dbo->execute();

		return ($dbo->getAffectedRows() ? true : false);
	}

	/**
	 * Notify the administratot via e-mail with the remaining details
	 * of the credit card.
	 *
	 * @param 	CreditCard 	The credit card details.
	 *
	 * @return 	void
	 */
	private function notifyAdmin(CreditCard $card)
	{
		$tag 		= JFactory::getLanguage()->getTag();
		$def_tag 	= cleverdine::getDefaultLanguage();

		// load default language
		if ($def_tag != $tag) {
			cleverdine::loadLanguage($def_tag);
		}
	
		// get mailing settings
		$admin_mail_list 	= cleverdine::getAdminMailList();
		$sendermail 		= cleverdine::getSenderMail();
		if (empty($sendermail)) {
			$sendermail = $admin_mail_list[0];
		}
		$fromname = cleverdine::getRestaurantName();

		// get information to send
		$masked_card_number = $card->getMaskedCardNumber();
		$admin_link = JUri::root().'administrator/index.php?option=com_cleverdine&task='.($this->order_info['tid'] == 0 ? '' : 'tk').'reservations&tools=1&ordnum='.$this->order_info['oid'];
		$admin_link = '<a href="'.$admin_link.'">'.$admin_link.'</a>';

		// build subject
		$subject = JText::_($this->order_info['tid'] == 0 ? 'VROFFCCMAILSUBJECTRS' : 'VROFFCCMAILSUBJECTTK');
	
		// build message
		$mess = JText::sprintf('VROFFCCMAILCONTENT', $this->order_info['oid'], $masked_card_number[1], $admin_link);
		
		$vik = new VikApplication();
		foreach ($admin_mail_list as $_m) {
			$vik->sendMail($sendermail, $fromname, $_m, $_m, $subject, $mess);
		}

		// reload customer language
		if ($def_tag != $tag) {
			cleverdine::loadLanguage($tag);
		}
	}
	
}
