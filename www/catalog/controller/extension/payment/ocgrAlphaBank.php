<?php /* AlphaBank 1.30 */

/*
OpenCart Greece FTW - https://www.opencartgreece.gr/

Είσαι προγραμματιστής και πιστεύεις ότι γνωρίζεις OpenCart; Εάν ναι, τότε έλα στην ομάδα μας.
Ψάχνουμε συνεργάτες και προσλαμβάνουμε τους καλύτερους.
Περισσότερα μπορείτε να δείτε στην ιστοσελίδα μας : https://www.opencartgreece.gr
*/

class ControllerExtensionPaymentocgrAlphaBank extends Controller {
	public function index() {
		$this->language->load('extension/payment/ocgrAlphaBank');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/ocgrAlphaBank');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$data['text_tax_card_number'] = $this->language->get('text_tax_card_number');
		$data['text_installments_number'] = $this->language->get('text_installments_number');
		$data['text_installment_amount'] = $this->language->get('text_installment_amount');
		$data['text_installment_total'] = $this->language->get('text_installment_total');
		$data['total'] = $order_info['total'] * $order_info['currency_value'];

		$installments = null;
		$installments_type = $this->config->get('payment_ocgrAlphaBank_installments_type');

		if($installments_type == 1) {
			$installments = $this->model_extension_payment_ocgrAlphaBank->getInstallmentsFromText($this->config->get('payment_ocgrAlphaBank_installments_normal_period'));
			foreach($installments as $key => $installment) {
				if((float)$order_info['total'] < (float)$installment[2] ||
                    ((float)$order_info['total'] > (float)$installment[3] && (float)$installment[3] != 0))
				{
					unset($installments[$key]);
				}
			}
		}

		$data['installments'] = $installments;

		//$merchant_hosted_payment = $this->config->get('payment_ocgrAlphaBank_merchant_hosted_payment');
		$merchant_hosted_payment = false;
		$data['merchant_hosted_payment'] = $merchant_hosted_payment;

        $data['masterpass'] = $this->config->get('payment_ocgrAlphaBank_masterpass');
        $data['text_pay_with_masterpass']   = $this->language->get('text_pay_with_masterpass');
        $data['text_with_card']             = $this->language->get('text_with_card');
        $data['text_with_masterpass']       = $this->language->get('text_with_masterpass');

		if(isset($merchant_hosted_payment) && $merchant_hosted_payment == true) {
			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_visa.png')) {
				$visa_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_visa.png';
			} else {
				$visa_image = 'catalog/view/theme/default/image/ocgrAlphaBank_visa.png';
			}

			$data['visa_image'] = $visa_image;

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_master.png')) {
				$master_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_master.png';
			} else {
				$master_image = 'catalog/view/theme/default/image/ocgrAlphaBank_master.png';
			}

			$data['master_image'] = $master_image;

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_visa_electron.png')) {
				$visa_electron_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_visa_electron.png';
			} else {
				$visa_electron_image = 'catalog/view/theme/default/image/ocgrAlphaBank_visa_electron.png';
			}

			$data['visa_electron_image'] = $visa_electron_image;

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_maestro.png')) {
				$maestro_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_maestro.png';
			} else {
				$maestro_image = 'catalog/view/theme/default/image/ocgrAlphaBank_maestro.png';
			}

			$data['maestro_image'] = $maestro_image;

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_ae.png')) {
				$ae_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_ae.png';
			} else {
				$ae_image = 'catalog/view/theme/default/image/ocgrAlphaBank_ae.png';
			}

			$data['ae_image'] = $ae_image;

			$data['text_credit_card_number'] = $this->language->get('text_credit_card_number');
			$data['text_credit_card_expiration_date'] = $this->language->get('text_credit_card_expiration_date');
			$data['text_credit_card_holder_name'] = $this->language->get('text_credit_card_holder_name');
			$data['text_credit_card_verification_code'] = $this->language->get('text_credit_card_verification_code');
		}

		$data['action'] = 'index.php?route=extension/payment/ocgrAlphaBank/send';
		$data['continue'] = $this->url->link('extension/payment/ocgrAlphaBank/send');
		$data['button_confirm'] = $this->language->get('button_confirm');

		return $this->load->view('extension/payment/ocgrAlphaBank', $data);
	}

	public function send() {
		$this->language->load('extension/payment/ocgrAlphaBank');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/ocgrAlphaBank');

		$timestamp = null;
		$store_name = null;
		$store_url = null;
		$merchant_id = $this->config->get('payment_ocgrAlphaBank_merchant_id');
		$language = null;
		$device_category = 0;
		$order_id = null;
		$order_description = null;
		$order_amount = null;
		$currency = null;
		$payer_name = null;
		$payer_lastname = null;
		$payer_email = null;
		$payer_phone = null;
		$bill_country = null;
		$bill_state = null;
		$bill_zip = null;
		$bill_city = null;
		$bill_address = null;
		$weight = null;
		$dimensions = null;
		$ship_country = null;
		$ship_state = null;
		$ship_zip = null;
		$ship_city = null;
		$ship_address = null;
		$fraud_score = null;
		$pay_method = null;
		$transaction_type = $this->config->get('payment_ocgrAlphaBank_transaction_type') + 1;
		$installments_normal_offset = null;
		$installments_normal_period = null;
		$installments_recurring_frequency = null;
		$installments_recurring_end_date = null;
		$block_score = null;
		$css_url = null;
		$confirm_url = null;
		$cancel_url = null;
		$var_1 = null;
		$var_2 = null;
		$var_3 = null;
		$var_4 = null;
		$var_5 = null;
		$digest = null;

		//$merchant_hosted_payment = $this->config->get('payment_ocgrAlphaBank_merchant_hosted_payment');
		$merchant_hosted_payment = false;

		$mode = $this->config->get('payment_ocgrAlphaBank_mode');

        $masterpass = $this->config->get('payment_ocgrAlphaBank_masterpass');

		if($merchant_hosted_payment) {
			if($mode) {
				$bank_url = ' https://www.alphaecommerce.gr/vpos/xmlpayvpos';
			} else {
				$bank_url = ' https://alpha.test.modirum.com/vpos/xmlpayvpos';
			}
		} else {
			if($mode) {
				$bank_url = 'https://www.alphaecommerce.gr/vpos/shophandlermpi';
			} else {
				$bank_url = 'https://alpha.test.modirum.com/vpos/shophandlermpi';
			}
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if($order_info['store_name'] != null && $order_info['store_name'] != '') {
			$store_name = $this->model_extension_payment_ocgrAlphaBank->cleanName($order_info['store_name']);
		}

		if($order_info['store_url'] != null && $order_info['store_url'] != '') {
			$store_url = $order_info['store_url'];
		}

		if($order_info['language_code'] != null && $order_info['language_code'] != '') {
			$language = $order_info['language_code'];
		}

		if($order_info['order_id'] != null && $order_info['order_id'] != '') {
			$time = explode(" ", microtime());
			$timestamp = $time[1];
			$timestamp_microseconds = str_replace('0.', '', $time[0]);

			$order_id = $timestamp . 'M' . $timestamp_microseconds . 'ID' . $order_info['order_id'];
		}

		$products = $this->cart->getProducts();
		if($products != null) {
			foreach($products as $product) {
				$order_description = $order_description . $product['quantity'] . ' x ' .
                    $this->model_extension_payment_ocgrAlphaBank->cleanName($product['name']) . " ";
			}
			$order_description = str_replace('"', '', trim($order_description));
			if(strlen($order_description) > 128) {
				$order_description = $order_id;
			}
		}

		if($order_info['total'] != null && $order_info['total'] != '') {
			$order_amount = number_format($order_info['total'], 2, '.', '');
		}

		if($order_info['currency_code'] != null && $order_info['currency_code'] != '') {
			$currency = $order_info['currency_code'];
		}

		if($order_info['payment_firstname'] != null && $order_info['payment_firstname'] != '') {
			$payer_name = $order_info['payment_firstname'];
		}

		if($order_info['payment_lastname'] != null && $order_info['payment_lastname'] != '') {
			$payer_lastname = $order_info['payment_lastname'];
		}

		if($order_info['email'] != null && $order_info['email'] != '') {
			$payer_email = $order_info['email'];
		}

		if($order_info['telephone'] != null && $order_info['telephone'] != '') {
			$payer_phone = $order_info['telephone'];
		}

		if($order_info['payment_iso_code_2'] != null && $order_info['payment_iso_code_2'] != '') {
			$bill_country = $order_info['payment_iso_code_2'];
		}

		if($order_info['payment_zone'] != null && $order_info['payment_zone'] != '') {
			$bill_state = $order_info['payment_zone'];
		}

		if($order_info['payment_postcode'] != null && $order_info['payment_postcode'] != '') {
			$bill_zip = $order_info['payment_postcode'];
		}

		if($order_info['payment_city'] != null && $order_info['payment_city'] != '') {
			$bill_city = $order_info['payment_city'];
		}

		if($order_info['payment_address_1'] != null && $order_info['payment_address_1'] != '') {
			$bill_address = $order_info['payment_address_1'];
		}

		if($this->cart->getWeight() != 0) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), '1');
		}

		if($order_info['shipping_iso_code_2'] != null && $order_info['shipping_iso_code_2'] != '') {
			$ship_country = $order_info['shipping_iso_code_2'];
		}

		if($order_info['shipping_zone'] != null && $order_info['shipping_zone'] != '') {
			$ship_state = $order_info['shipping_zone'];
		}

		if($order_info['shipping_postcode'] != null && $order_info['shipping_postcode'] != '') {
			$ship_zip = $order_info['shipping_postcode'];
		}

		if($order_info['shipping_city'] != null && $order_info['shipping_city'] != '') {
			$ship_city = $order_info['shipping_city'];
		}

		if($order_info['shipping_address_1'] != null && $order_info['shipping_address_1'] != '') {
			$ship_address = $order_info['shipping_address_1'];
		}

		$installments_type = $this->config->get('payment_ocgrAlphaBank_installments_type');

		if($installments_type == 1) {
			$order_info['installment'] = null;
			if(isset($this->request->post['installment'])) {
				$installments = $this->model_extension_payment_ocgrAlphaBank->getInstallmentsFromText($this->config->get('payment_ocgrAlphaBank_installments_normal_period'));
				foreach($installments as $key => $installment) {
					if((float)$installment[2] > (float)$order_info['total'] || ((float)$installment[3] < (float)$order_info['total'] && (float)$installment[3] != 0))
					{
						unset($installments[$key]);
					}
				}
				$installments_normal_period = $installments[$this->request->post['installment']][0];

				$order_amount = number_format($order_amount * (1 + ($installments[$this->request->post['installment']][1] / 100)), 2, '.', '');
				if($installments_normal_period > 1)
				{
					$order_info['installment'] = $installments_normal_period;

					$installments_normal_offset = $this->config->get('payment_ocgrAlphaBank_installments_normal_offset');
					if($installments_normal_offset == null || $installments_normal_offset == '' || !is_numeric($installments_normal_offset))
					{
						$installments_normal_offset = 0;
					}
				} else {
					$installments_normal_period = null;
				}
			}
		} else if($installments_type == 2) {
			$installments_recurring_frequency = $this->config->get('payment_ocgrAlphaBank_installments_recurring_frequency');
			if($installments_recurring_frequency == null || $installments_recurring_frequency == '' || !is_numeric($installments_recurring_frequency)) {
				$installments_recurring_frequency = null;
			}

			if($installments_recurring_frequency != null) {
				$installments_recurring_end_date = $this->config->get('payment_ocgrAlphaBank_installments_recurring_end_date');
				if($installments_recurring_end_date == null || $installments_recurring_end_date == '' || $installments_recurring_end_date == '00-00-0000')
				{
					$installments_recurring_end_date = null;
				}
			}
		}

		$css_url = $this->config->get('payment_ocgrAlphaBank_css_url');
		if($css_url == '') {
			$css_url = null;
		}

		if(isset($this->request->post['tax_card_number']) && $this->request->post['tax_card_number'] != '') {
			$var_1 = $this->request->post['tax_card_number'];
		}

        if(isset($this->request->post['using_masterpass']) && $this->request->post['using_masterpass'] == '1') {
            $pay_method = 'auto:MasterPass';
        } else {
            $pay_method = null;
        }

		if(isset($merchant_hosted_payment) && $merchant_hosted_payment == true) {
		    // NOT IN THIS VERSION. ENTERPRISE VERSION AVAILABLE ON REQUEST. PLEASE CONTACT OPENCART GREECE
		} else {

			if($store_url != null) {
				$confirm_url = $this->url->link('extension/payment/ocgrAlphaBank/callback', '', 'SSL');
				$cancel_url = $confirm_url;
			}

			$digest = $this->model_extension_payment_ocgrAlphaBank->getDigest($merchant_id . $language .
                $device_category . $order_id . $order_description . $order_amount . $currency . $payer_email .
                $payer_phone . $bill_country . $bill_state . $bill_zip . $bill_city . $bill_address . $weight .
                $dimensions . $ship_country . $ship_state . $ship_zip . $ship_city . $ship_address . $fraud_score .
                $pay_method . $transaction_type . $installments_normal_offset . $installments_normal_period .
                $installments_recurring_frequency . $installments_recurring_end_date . $block_score . $css_url .
                $confirm_url . $cancel_url . $var_1 . $var_2 . $var_3 . $var_4 . $var_5,
                $this->config->get('payment_ocgrAlphaBank_shared_secret'));

			$this->model_extension_payment_ocgrAlphaBank->addTransaction($timestamp, $store_name, $store_url,
                $merchant_id, $language, $device_category, $order_id, $order_description, $order_amount,
                $currency, $payer_name, $payer_lastname, $payer_email, $payer_phone, $bill_country, $bill_state,
                $bill_zip, $bill_city, $bill_address, $weight, $dimensions, $ship_country, $ship_state,
                $ship_zip, $ship_city, $ship_address, $fraud_score, $pay_method, $transaction_type,
                $installments_normal_offset, $installments_normal_period, $installments_recurring_frequency,
                $installments_recurring_end_date, $block_score, $css_url, $confirm_url, $cancel_url, $var_1,
                $var_2, $var_3, $var_4, $var_5, $digest);

			$data['config_url'] = $this->config->get('config_url');
			$data['bank_url'] = $bank_url;
			$data['merchant_id'] = $merchant_id;
			$data['language'] = $language;
			$data['device_category'] = $device_category;
			$data['order_id'] = $order_id;
			$data['order_description'] = $order_description;
			$data['order_amount'] = $order_amount;
			$data['currency'] = $currency;
			$data['payer_name'] = $payer_name;
			$data['payer_lastname'] = $payer_lastname;
			$data['payer_email'] = $payer_email;
			$data['payer_phone'] = $payer_phone;
			$data['bill_country'] = $bill_country;
			$data['bill_state'] = $bill_state;
			$data['bill_zip'] = $bill_zip;
			$data['bill_city'] = $bill_city;
			$data['bill_address'] = $bill_address;
			$data['weight'] = $weight;
			$data['dimensions'] = $dimensions;
			$data['ship_country'] = $ship_country;
			$data['ship_state'] = $ship_state;
			$data['ship_zip'] = $ship_zip;
			$data['ship_city'] = $ship_city;
			$data['ship_address'] = $ship_address;
			$data['fraud_score'] = $fraud_score;
			$data['pay_method'] = $pay_method;
			$data['transaction_type'] = $transaction_type;
			$data['installments_normal_offset'] = $installments_normal_offset;
			$data['installments_normal_period'] = $installments_normal_period;
			$data['installments_recurring_frequency'] = $installments_recurring_frequency;
			$data['installments_recurring_end_date'] = $installments_recurring_end_date;
			$data['block_score'] = $block_score;
			$data['css_url'] = $css_url;
			$data['confirm_url'] = $confirm_url;
			$data['cancel_url'] = $cancel_url;
			$data['var_1'] = $var_1;
			$data['var_2'] = $var_2;
			$data['var_3'] = $var_3;
			$data['var_4'] = $var_4;
			$data['var_5'] = $var_5;
			$data['digest'] = $digest;

			$data['message'] = $this->language->get('text_redirect_to') . ' Alpha Bank ... ';
			$this->document->setTitle($data['message']);
			$data['heading_title'] = $data['message'];
			$data['text_if_redirect_fail'] = $this->language->get('text_if_redirect_fail');
			$data['text_redirect_to'] = $this->language->get('text_redirect_to');
			$data['text_or'] = $this->language->get('text_or');
			$data['text_now'] = $this->language->get('text_now');
			$data['text_here'] = $this->language->get('text_here');

			$data['header'] = $this->load->controller('common/header');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank_data', $data));
		}
	}

	public function callback() {
		$this->language->load('extension/payment/ocgrAlphaBank');

		if(true) {
			$merchant_id = null;
			$order_id = null;
			$status = null;
			$order_amount = null;
			$currency = null;
			$payment_total = null;
			$risk_score = null;
			$payment_method = null;
			$message = null;
			$txId = null;
			$sequence = null;
			$seqtxId = null;
			$paymentRef = null;
			$digest = null;

			$this->load->model('extension/payment/ocgrAlphaBank');

			if(isset($this->request->post['mid'])) {
				$merchant_id = $this->request->post['mid'];
			}

			if(isset($this->request->post['orderid'])) {
				$order_id = $this->request->post['orderid'];
			}

			if(isset($this->request->post['status'])) {
				$status = $this->request->post['status'];
			}

			if(isset($this->request->post['orderAmount'])) {
				$order_amount = $this->request->post['orderAmount'];
			}

			if(isset($this->request->post['currency'])) {
				$currency = $this->request->post['currency'];
			}

			if(isset($this->request->post['paymentTotal'])) {
				$payment_total = $this->request->post['paymentTotal'];
			}

			if(isset($this->request->post['message'])) {
				$message = $this->request->post['message'];
			}

			if(isset($this->request->post['riskScore'])) {
				$risk_score = $this->request->post['riskScore'];
			}

			if(isset($this->request->post['payMethod'])) {
				$payment_method = $this->request->post['payMethod'];
			}

			if(isset($this->request->post['txId'])) {
				$txId = $this->request->post['txId'];
			}

			if(isset($this->request->post['sequence'])) {
				$sequence = $this->request->post['sequence'];
			}

			if(isset($this->request->post['seqtxId'])) {
				$seqtxId = $this->request->post['seqtxId'];
			}

			if(isset($this->request->post['paymentRef'])) {
				$paymentRef = $this->request->post['paymentRef'];
			}

			if(isset($this->request->post['digest'])) {
				$digest = $this->request->post['digest'];
			}

			if($digest == $this->model_extension_payment_ocgrAlphaBank->getDigest($merchant_id . $order_id .
                    $status . $order_amount . $currency . $payment_total . $message . $risk_score . $payment_method .
                    $txId . $sequence . $seqtxId . $paymentRef,
                    $this->config->get('payment_ocgrAlphaBank_shared_secret'))) {
				if($this->model_extension_payment_ocgrAlphaBank->updateTransactionStatus($merchant_id, $order_id,
                    $status, $order_amount, $currency, $payment_total, $message, $risk_score, $payment_method, $txId,
                    $sequence, $seqtxId, $paymentRef, $digest))
				{
					if($status == 'AUTHORIZED' || $status == 'CAPTURED') {
						$this->load->model('checkout/order');
						$order_info = 'Order ID:' . $order_id . ' Χρέωση:' . $payment_total . ' Νόμισμα:' . $currency;
						$this->model_checkout_order->addOrderHistory(substr($order_id,
                            strpos($order_id, "D") + 1),
                            $this->config->get('payment_ocgrAlphaBank_order_status_id'), $order_info, true);
						$this->clean();
						$data['message'] = $this->language->get('text_payment_success');
						$data['message_info'] = $this->language->get('text_payment_success_info');
						$image = 'ocgrAlphaBank_ok.png';
						$data['count'] = 10;
						$data['redirect_url'] = 'index.php?route=extension/payment/ocgrAlphaBank/receipt&order_id=' .
                            $order_id . '&digest=' . urlencode($digest);
					} else if($status == 'CANCELED' || $status == 'REFUSED' || $status == 'ERROR') {
						$data['message'] = $this->language->get('text_payment_error');
						if($status == 'CANCELED') {
							$data['message_info'] = $this->language->get('text_payment_error_info_cancelled');
						} else if($status == 'REFUSED') {
							$data['message_info'] = $this->language->get('text_payment_error_info_refused');
						} else {
							$data['message_info'] = $this->language->get('text_payment_error_info_error');
						}

						if($paymentRef != null && trim($paymentRef) != '') {
							$data['message_info'] = $data['message_info'] . ' ' .
                                $this->language->get('text_payment_error_reference') . ' ' . $paymentRef;
						} else if($data['message_info'] != null && $data['message_info'] != '') {
							$data['message_info'] = $data['message_info'] . ' ' .
                                $this->language->get('text_payment_error_message') . ' ' . $message;
						}

						$image = 'ocgrAlphaBank_error.png';
						$data['count'] = 10;
						$data['redirect_url'] = 'index.php?route=checkout/checkout';
					} else {
						$data['message'] = $this->language->get('text_payment_cancelled');
						$data['message_info'] = $this->language->get('text_payment_cancelled_info');
						$image = 'ocgrAlphaBank_error.png';
						$data['count'] = 10;
						$data['redirect_url'] = 'index.php?route=checkout/checkout';
					}
				} else {
					$data['message'] = $this->language->get('text_payment_unidentified');
					$data['message_info'] = $this->language->get('text_payment_unidentified_info');
					$image = 'ocgrAlphaBank_error.png';
					$data['count'] = 10;
					$data['redirect_url'] = 'index.php';
				}
			} else {
				$data['message'] = $this->language->get('text_digest_fail');
				$data['message_info'] = $this->language->get('text_digest_fail_info');
				$image = 'ocgrAlphaBank_error.png';
				$data['count'] = 10;
				$data['redirect_url'] = 'index.php';
			}
		} else {
			$data['message'] = $this->language->get('text_restricted_page');
			$data['message_info'] = $this->language->get('text_restricted_page_info');
			$image = 'ocgrAlphaBank_error.png';
			$data['count'] = 10;
			$data['redirect_url'] = 'index.php';
		}

		$this->document->setTitle($data['message']);

	  	$data['heading_title'] = $data['message'];

		if (file_exists(DIR_TEMPLATE . 'catalog/view/theme/' .
            $this->config->get('config_template') . '/image/' . $image)) {
			$message_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/' . $image;
		} else {
			$message_image = 'catalog/view/theme/default/image/' . $image;
		}
		$data['message_image'] = $message_image;

		if (file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') .
            '/image/ocgrAlphaBank_load.gif')) {
			$redirect_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_load.gif';
		} else {
			$redirect_image = 'catalog/view/theme/default/image/ocgrAlphaBank_load.gif';
		}
		$data['redirect_image'] = $redirect_image;

		$data['text_redirect_in'] = $this->language->get('text_redirect_in');
		$data['text_or'] = $this->language->get('text_or');
		$data['text_now'] = $this->language->get('text_now');

		$data['header'] = $this->load->controller('common/header');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank_message', $data));
	}

	public function receipt() {
		$this->language->load('extension/payment/ocgrAlphaBank');
		$this->load->model('extension/payment/ocgrAlphaBank');

		$order_id = null;
		$digest = null;

		if(isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		}

		if(isset($this->request->get['digest'])) {
			$digest = urldecode($this->request->get['digest']);
		}

		$receipt = $this->model_extension_payment_ocgrAlphaBank->getReceipt($order_id, $digest);

		if($receipt != null) {
			$data['text_receipt'] = $this->language->get('text_receipt');
			$data['text_name_lastname'] = $this->language->get('text_name') . ' ' . $this->language->get('text_lastname');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_business_name'] = $this->language->get('text_business_name');
			$data['text_business_web_address'] = $this->language->get('text_business_web_address');
			$data['text_order_info'] = $this->language->get('text_order_info');
			$data['text_transaction_amount'] = $this->language->get('text_transaction_amount');
			$data['text_transaction_currency'] = $this->language->get('text_transaction_currency');
			$data['text_transaction_date'] = $this->language->get('text_transaction_date');

			$data['name_lastname'] = $receipt['payer_name'] . ' ' . $receipt['payer_lastname'];
			$data['order_id'] = $receipt['order_id'];
			$data['business_name'] = $receipt['store_name'];
			$data['business_web_address'] = $receipt['store_url'];
			$data['order_info'] = $receipt['order_description'];
			$data['transaction_amount'] = $receipt['order_amount'];
			$data['transaction_currency'] = $receipt['currency'];
			$data['transaction_date'] = $receipt['timestamp'];

			$this->document->setTitle($data['text_receipt']);
			$data['heading_title'] = $data['text_receipt'];

			$data['header'] = $this->load->controller('common/header');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

			$data['continue'] = $this->url->link('account/account');
			$data['button_continue'] = $this->language->get('button_continue');

			$this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank_receipt', $data));
		} else {
			$data['message'] = $this->language->get('text_payment_receipt_error');
			$data['message_info'] = $this->language->get('text_payment_receipt_error_info');
			$image = 'ocgrAlphaBank_error.png';
			$data['count'] = 10;
			$data['redirect_url'] = 'index.php';

			$this->document->setTitle($data['message']);
			$data['heading_title'] = $data['message'];

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/' . $image)) {
				$message_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/' . $image;
			} else {
				$message_image = 'catalog/view/theme/default/image/' . $image;
			}
			$data['message_image'] = $message_image;

			if(file_exists(DIR_TEMPLATE . 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_load.gif')) {
				$redirect_image = 'catalog/view/theme/' . $this->config->get('config_template') . '/image/ocgrAlphaBank_load.gif';
			} else {
				$redirect_image = 'catalog/view/theme/default/image/ocgrAlphaBank_load.gif';
			}
			$data['redirect_image'] = $redirect_image;

			$data['text_redirect_in'] = $this->language->get('text_redirect_in');
			$data['text_or'] = $this->language->get('text_or');
			$data['text_now'] = $this->language->get('text_now');

			$data['header'] = $this->load->controller('common/header');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank_message', $data));
		}
	}

	private function clean() {
		/* Διαγραφή των δεδομένων από το session. */
		$this->cart->clear();
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);
		unset($this->session->data['totals']);
	}
}
?>