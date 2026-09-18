<?php /* OCGR AlphaBank 1.26 */


/*
OpenCart Greece FTW - https://www.opencartgreece.gr/

Είσαι προγραμματιστής και πιστεύεις ότι γνωρίζεις OpenCart; Εάν ναι, τότε έλα στην ομάδα μας. Ψάχνουμε συνεργάτες αλλά προσλαμβάνουμε
τους καλύτερους.
Περισσότερα μπορείτε να δείτε στην ιστοσελίδα μας : https://www.opencartgreece.gr
*/

class ModelExtensionPaymentocgrAlphaBank extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/ocgrAlphaBank');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('ocgrAlphaBank_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if($this->config->get('ocgrAlphaBank_total') > $total) {
			$status = false;
		} else if(!$this->config->get('ocgrAlphaBank_geo_zone_id')) {
			$status = true;
		} else if($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if($status) {
			$method_data = array(
			'code'		=>	'ocgrAlphaBank',
			'title'		=>	$this->language->get('text_title'),
			'terms'		=>	'',
			'sort_order'=>	$this->config->get('ocgrAlphaBank_sort_order'));
		}

		return $method_data;
	}

	public function getInstallmentsFromText($installments_text) {
		$i = 0;
		$installments = null;

		foreach(explode(' ', $installments_text) as $installment_values_text) {
			$installment_values = explode(':', $installment_values_text);
			if(count($installment_values) == 4 && is_numeric($installment_values[0]) && is_numeric($installment_values[1]) && is_numeric($installment_values[2]) && is_numeric($installment_values[3]))
			{
					$installments[$i][0] = $installment_values[0];
					$installments[$i][1] = $installment_values[1];
					$installments[$i][2] = $installment_values[2];
					$installments[$i][3] = $installment_values[3];
					$i++;
			}
		}

		return $installments;
	}

	public function reduceString($original_string, $limit) {
		$reduced_string = '';

		if(strlen($original_string) > $limit)
		{
			$reduced_string = substr($original_string, 0, $limit);
		}else{
			$reduced_string = $original_string;
		}

		return $reduced_string;
	}

	public function getDigest($data, $passphrase) {
		return base64_encode(sha1($data . $passphrase, TRUE));
	}

	public function addTransaction($timestamp, $store_name, $store_url, $merchant_id, $language, $device_category, $order_id, $order_description, $order_amount, $currency, $payer_name, $payer_lastname, $payer_email, $payer_phone, $bill_country, $bill_state, $bill_zip, $bill_city, $bill_address, $weight, $dimensions, $ship_country, $ship_state, $ship_zip, $ship_city, $ship_address, $fraud_score, $pay_method, $transaction_type, $installments_normal_offset, $installments_normal_period, $installments_recurring_frequency, $installments_recurring_end_date, $block_score, $css_url, $confirm_url, $cancel_url, $var_1, $var_2, $var_3, $var_4, $var_5, $digest) {
		$timestamp = $this->db->escape($timestamp);
		$store_name = $this->db->escape($store_name);
		$store_url = $this->db->escape($store_url);
		$merchant_id = $this->db->escape($merchant_id);
		$language = $this->db->escape($language);
		$device_category = $this->db->escape($device_category);
		$order_id = $this->db->escape($order_id);
		$order_description = $this->db->escape($order_description);
		$order_amount = $this->db->escape($order_amount);
		$currency = $this->db->escape($currency);
		$payer_name = $this->db->escape($payer_name);
		$payer_lastname = $this->db->escape($payer_lastname);
		$payer_email = $this->db->escape($payer_email);
		$payer_phone = $this->db->escape($payer_phone);
		$bill_country = $this->db->escape($bill_country);
		$bill_state = $this->db->escape($bill_state);
		$bill_zip = $this->db->escape($bill_zip);
		$bill_city = $this->db->escape($bill_city);
		$bill_address = $this->db->escape($bill_address);
		$weight = $this->db->escape($weight);
		$dimensions = $this->db->escape($dimensions);
		$ship_country = $this->db->escape($ship_country);
		$ship_state = $this->db->escape($ship_state);
		$ship_zip = $this->db->escape($ship_zip);
		$ship_city = $this->db->escape($ship_city);
		$ship_address = $this->db->escape($ship_address);
		$fraud_score = $this->db->escape($fraud_score);
		$pay_method = $this->db->escape($pay_method);
		$transaction_type = $this->db->escape($transaction_type);
		$installments_normal_offset = $this->db->escape($installments_normal_offset);
		$installments_normal_period = $this->db->escape($installments_normal_period);
		$installments_recurring_frequency = $this->db->escape($installments_recurring_frequency);
		$installments_recurring_end_date = $this->db->escape($installments_recurring_end_date);
		$block_score = $this->db->escape($block_score);
		$css_url = $this->db->escape($css_url);
		$confirm_url = $this->db->escape($confirm_url);
		$cancel_url = $this->db->escape($cancel_url);
		$var_1 = $this->db->escape($var_1);
		$var_2 = $this->db->escape($var_2);
		$var_3 = $this->db->escape($var_3);
		$var_4 = $this->db->escape($var_4);
		$var_5 = $this->db->escape($var_5);
		$digest = $this->db->escape($digest);
		return $this->db->query("INSERT INTO " . DB_PREFIX . "ocgrAlphaBank 
		(timestamp, store_name, store_url, merchant_id, language, device_category, order_id, order_description, order_amount, 
		currency, payer_name, payer_lastname, payer_email, payer_phone, bill_country, bill_state, bill_zip, bill_city, bill_address, 
		weight, dimensions, ship_country, ship_state, ship_zip, ship_city, ship_address, fraud_score, pay_method, transaction_type, 
		installments_normal_offset, installments_normal_period, installments_recurring_frequency, installments_recurring_end_date, 
		block_score, css_url, confirm_url, cancel_url, var_1, var_2, var_3, var_4, var_5, digest) VALUES 
		(FROM_UNIXTIME(" . $timestamp . "), '" . $store_name . "', '" . $store_url . "', '" . $merchant_id . "', '" . $language . "', 
		'" . $device_category . "', '" . $order_id . "', '" . $order_description . "', '" . $order_amount . "', '" . $currency . "', 
		'" . $payer_name . "', '" . $payer_lastname . "', '" . $payer_email . "', '" . $payer_phone . "', '" . $bill_country . "', 
		'" . $bill_state . "', '" . $bill_zip . "', '" . $bill_city . "', '" . $bill_address . "', '" . $weight . "', '" . $dimensions . "', 
		'" . $ship_country . "', '" . $ship_state . "', '" . $ship_zip . "', '" . $ship_city . "', '" . $ship_address . "', '" . $fraud_score . "', 
		'" . $pay_method . "', '" . $transaction_type . "', '" . $installments_normal_offset . "', '" . $installments_normal_period . "', 
		'" . $installments_recurring_frequency . "', '" . $installments_recurring_end_date . "', '" . $block_score . "', '" . $css_url . "', 
		'" . $confirm_url . "', '" . $cancel_url . "', '" . $var_1 . "', '" . $var_2 . "', '" . $var_3 . "', '" . $var_4 . "', '" . $var_5 . "', 
		'" . $digest . "')");
		
	}

	public function updateTransactionStatus($merchant_id, $order_id, $status, $order_amount, $currency, $payment_total, $message, $risk_score, $payment_method, $txId, $sequence, $seqtxId, $paymentRef, $digest) {
		/* Ανανέωση */
		$merchant_id = $this->db->escape($merchant_id);
		$order_id = $this->db->escape($order_id);
		$status = $this->db->escape($status);
		$order_amount = $this->db->escape($order_amount);
		$currency = $this->db->escape($currency);
		$payment_total = $this->db->escape($payment_total);
		$message = $this->db->escape($message);
		$risk_score = $this->db->escape($risk_score);
		$payment_method = $this->db->escape($payment_method);
		$txId = $this->db->escape($txId);
		$sequence = $this->db->escape($sequence);
		$seqtxId = $this->db->escape($seqtxId);
		$paymentRef = $this->db->escape($paymentRef);
		$digest = $this->db->escape($digest);
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ocgrAlphaBank 
		WHERE (order_id = '" . $order_id . "' 
		AND merchant_id = '" . $merchant_id . "')")->row;

		if(!empty($query)) {
			$this->db->query("UPDATE " . DB_PREFIX . "ocgrAlphaBank 
			SET status = '" . $status . "', status_order_amount = '" . $order_amount . "', 
			status_currency = '" . $currency . "', status_payment_total = '" . $payment_total . "', 
			status_message = '" . $message . "', status_risk_score = '" . $risk_score . "', 
			status_payment_method = '" . $payment_method . "', status_txId = '" . $txId . "', 
			status_sequence = '" . $sequence . "', status_seqtxId = '" . $seqtxId . "', 
			status_paymentRef = '" . $paymentRef . "', status_digest = '" . $digest . "' 
			WHERE (order_id = '" . $order_id . "' AND merchant_id = '" . $merchant_id . "')");
			return true;
		}else{
			return false;
		}
	}

	public function getReceipt($order_id, $digest) {
		$order_id = $this->db->escape($order_id);
		$digest = $this->db->escape($digest);

		$query = $this->db->query("SELECT payer_name, payer_lastname, order_id, store_name, store_url, 
		order_description, order_amount, timestamp, currency, status_txId 
		FROM " . DB_PREFIX . "ocgrAlphaBank 
		WHERE (order_id = '" . $order_id . "' 
		AND status_digest = '" . $digest . "' 
		AND (status = 'AUTHORIZED' OR status = 'CAPTURED'))")->row;

		if(!empty($query)){
			return $query;
		} else {
			return null;
		}
	}

	public function cleanName($name) {
		return preg_replace("/&#?[a-z0-9]{2,8};/i", "", $name);
	}
}/* OpenCart Greece 2013-2017 */?>