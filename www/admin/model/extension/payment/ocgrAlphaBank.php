<?php /* AlphaBank 1.26 */

/* Παρακαλούμε μην πειράξετε τον κώδικα εάν δεν γνωρίζετε τι κάνετε πρώτα. */

class ModelExtensionPaymentocgrAlphaBank extends Model {
	//Εγκατάσταση
	public function install() {
		$query = $this->db->query("CREATE TABLE IF NOT EXISTS " . DB_PREFIX . "ocgrAlphaBank(id INT(11) NOT NULL AUTO_INCREMENT, timestamp DATETIME DEFAULT NULL, store_name TEXT DEFAULT NULL, 
		store_url TEXT DEFAULT NULL, merchant_id CHAR(10) DEFAULT NULL, language CHAR(2) DEFAULT NULL, 
		device_category TINYINT(1) DEFAULT NULL, order_id CHAR(50) NOT NULL UNIQUE, 
		order_description CHAR(128) DEFAULT NULL, order_amount DECIMAL(10,2) DEFAULT NULL, 
		currency CHAR(3) DEFAULT NULL, payer_name TEXT DEFAULT NULL, payer_lastname TEXT DEFAULT NULL, 
		payer_email TEXT DEFAULT NULL, payer_phone CHAR(30) DEFAULT NULL, bill_country CHAR(2) DEFAULT NULL, 
		bill_state CHAR(50) DEFAULT NULL, bill_zip CHAR(16) DEFAULT NULL, bill_city CHAR(64) DEFAULT NULL, 
		bill_address CHAR(100) DEFAULT NULL, weight FLOAT DEFAULT NULL, dimensions CHAR(25) DEFAULT NULL, 
		ship_country CHAR(2) DEFAULT NULL, ship_state CHAR(50) DEFAULT NULL, ship_zip CHAR(16) DEFAULT NULL, 
		ship_city CHAR(64) DEFAULT NULL, ship_address CHAR(100) DEFAULT NULL, fraud_score INT(11) DEFAULT NULL, 
		pay_method CHAR(20) DEFAULT NULL, transaction_type TINYINT(1) DEFAULT NULL, 
		installments_normal_offset INT(11) DEFAULT NULL, installments_normal_period INT(11) DEFAULT NULL, 
		installments_recurring_frequency INT(11) DEFAULT NULL, installments_recurring_end_date INT(11) DEFAULT NULL, 
		block_score INT(11) DEFAULT NULL, css_url CHAR(128) DEFAULT NULL, confirm_url CHAR(128) DEFAULT NULL, 
		cancel_url CHAR(128) DEFAULT NULL, var_1 CHAR(255) DEFAULT NULL, var_2 CHAR(255) DEFAULT NULL, var_3 CHAR(255) 
		DEFAULT NULL, var_4 CHAR(255) DEFAULT NULL, var_5 CHAR(255) DEFAULT NULL, digest TEXT DEFAULT NULL, 
		status ENUM('AUTHORIZED','CAPTURED','CANCELED','REFUSED','ERROR') DEFAULT NULL, 
		status_order_amount TEXT DEFAULT NULL, status_currency CHAR(3) DEFAULT NULL, 
		status_payment_total TEXT DEFAULT NULL, status_message TEXT DEFAULT NULL, status_risk_score TEXT DEFAULT NULL, 
		status_payment_method TEXT DEFAULT NULL, status_txId TEXT DEFAULT NULL, status_sequence TEXT DEFAULT NULL, 
		status_seqTxId TEXT DEFAULT NULL, status_paymentRef TEXT DEFAULT NULL, status_digest text DEFAULT NULL, 
		PRIMARY KEY(id)) DEFAULT CHARSET=utf8");
	}

	public function uninstall() {
		//Απεγκατάσταση
		$query = $this->db->query("DROP TABLE " . DB_PREFIX . "ocgrAlphaBank");
	}
}