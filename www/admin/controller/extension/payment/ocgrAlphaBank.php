<?php /* AlphaBank 1.30 OC 3.x */
class ControllerExtensionPaymentocgrAlphaBank extends Controller {
	private $error = array();

	public function index(){
		$this->load->model('setting/setting');

		if(($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_ocgrAlphaBank', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

            if(version_compare(VERSION, '3.0') >= 0) { //3.0.x
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
            }elseif(version_compare(VERSION, '2.2') > 0) { //2.3.x
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
			}else{
				$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data = array();
        if(version_compare(VERSION, '3.0') >= 0) { //3.0.x
            $data += $this->language->load('extension/payment/ocgrAlphaBank');
            $this->document->setTitle($this->language->get('heading_title'));

            $data['breadcrumbs'] = array();

            $data['breadcrumbs'][] = array	(
                'text'	=>	$this->language->get('text_home'),
                'href'	=>	$this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'));

            $data['breadcrumbs'][] = array	(
                'text'	=>	$this->language->get('text_payment'),
                'href'	=>	$this->url->link('marketplace/extension', 'user_token=' .
                    $this->session->data['user_token'] . '&type=payment', 'SSL'));

            $data['action'] = $this->url->link('extension/payment/ocgrAlphaBank', 'user_token=' .
                $this->session->data['user_token'], true);
            $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' .
                $this->session->data['user_token'] . '&type=payment', true);

            $data['breadcrumbs'][] = array	(
                'text'	=>	$this->language->get('heading_title'),
                'href'	=>	$this->url->link('extension/payment/ocgrAlphaBank', 'user_token=' .
                    $this->session->data['user_token'], 'SSL'));

        }elseif(version_compare(VERSION, '2.2') > 0) { //2.3.x
			$data += $this->language->load('extension/payment/ocgrAlphaBank');
			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array	(
			'text'	=>	$this->language->get('text_home'),
			'href'	=>	$this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			
			$data['breadcrumbs'][] = array	(
			'text'	=>	$this->language->get('text_payment'),
			'href'	=>	$this->url->link('extension/extension', 'user_token=' .
                $this->session->data['user_token'], 'SSL'));
			
			$data['action'] = $this->url->link('extension/payment/ocgrAlphaBank', 'user_token=' .
                $this->session->data['user_token'], true);
			$data['cancel'] = $this->url->link('extension/extension', 'user_token=' .
                $this->session->data['user_token'] . '&type=payment', true);
			
			$data['breadcrumbs'][] = array	(
		'text'	=>	$this->language->get('heading_title'),
		'href'	=>	$this->url->link('extension/payment/ocgrAlphaBank', 'user_token=' .
            $this->session->data['user_token'], 'SSL'));
		
		}else{
			$data += $this->language->load('payment/ocgrAlphaBank');
			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array	(
			'text'	=>	$this->language->get('text_home'),
			'href'	=>	$this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL'));
			$data['breadcrumbs'][] = array	(
		'text'	=>	$this->language->get('text_payment'),
		'href'	=>	$this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=payment', true));
			$data['action'] = $this->url->link('payment/ocgrAlphaBank', 'user_token=' . $this->session->data['user_token'], 'SSL');
			$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']. '&type=payment', true);
			
			$data['breadcrumbs'][] = array	(
		'text'	=>	$this->language->get('heading_title'),
		'href'	=>	$this->url->link('payment/ocgrAlphaBank', 'user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		/* OpenCart Greece FYI */
		

		if(isset($this->request->post['payment_ocgrAlphaBank_merchant_id'])) {
			$data['ocgrAlphaBank_merchant_id'] = $this->request->post['payment_ocgrAlphaBank_merchant_id'];
		} else {
			$data['ocgrAlphaBank_merchant_id'] = $this->config->get('payment_ocgrAlphaBank_merchant_id');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_shared_secret'])) {
			$data['ocgrAlphaBank_shared_secret'] = $this->request->post['payment_ocgrAlphaBank_shared_secret'];
		} else {
			$data['ocgrAlphaBank_shared_secret'] = $this->config->get('payment_ocgrAlphaBank_shared_secret');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_mode'])) {
			$data['ocgrAlphaBank_mode'] = $this->request->post['payment_ocgrAlphaBank_mode'];
		} else {
			$data['ocgrAlphaBank_mode'] = $this->config->get('payment_ocgrAlphaBank_mode');
		}

        if(isset($this->request->post['payment_ocgrAlphaBank_masterpass'])) {
            $data['ocgrAlphaBank_masterpass'] = $this->request->post['payment_ocgrAlphaBank_masterpass'];
        } else {
            $data['ocgrAlphaBank_masterpass'] = $this->config->get('payment_ocgrAlphaBank_masterpass');
        }

		if(isset($this->request->post['payment_ocgrAlphaBank_merchant_hosted_payment'])) {
			$data['ocgrAlphaBank_merchant_hosted_payment'] = $this->request->post['payment_ocgrAlphaBank_merchant_hosted_payment'];
		} else {
			$data['ocgrAlphaBank_merchant_hosted_payment'] = $this->config->get('payment_ocgrAlphaBank_merchant_hosted_payment');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_transaction_type'])) {
			$data['ocgrAlphaBank_transaction_type'] = $this->request->post['payment_ocgrAlphaBank_transaction_type'];
		} else {
			$data['ocgrAlphaBank_transaction_type'] = $this->config->get('payment_ocgrAlphaBank_transaction_type');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_installments_type'])) {
			$data['ocgrAlphaBank_installments_type'] = $this->request->post['payment_ocgrAlphaBank_installments_type'];
		} else {
			$data['ocgrAlphaBank_installments_type'] = $this->config->get('payment_ocgrAlphaBank_installments_type');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_installments_normal_period'])) {
			$data['ocgrAlphaBank_installments_normal_period'] = $this->request->post['payment_ocgrAlphaBank_installments_normal_period'];
		} else {
			$data['ocgrAlphaBank_installments_normal_period'] = $this->config->get('payment_ocgrAlphaBank_installments_normal_period');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_installments_normal_offset'])) {
			$data['ocgrAlphaBank_installments_normal_offset'] = $this->request->post['payment_ocgrAlphaBank_installments_normal_offset'];
		} else {
			$data['ocgrAlphaBank_installments_normal_offset'] = $this->config->get('payment_ocgrAlphaBank_installments_normal_offset');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency'])) {
			$data['ocgrAlphaBank_installments_recurring_frequency'] = $this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency'];
		} else {
			$data['ocgrAlphaBank_installments_recurring_frequency'] = $this->config->get('payment_ocgrAlphaBank_installments_recurring_frequency');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_installments_recurring_end_date'])) {
			$data['ocgrAlphaBank_installments_recurring_end_date'] = $this->request->post['payment_ocgrAlphaBank_installments_recurring_end_date'];
		} else {
			$data['ocgrAlphaBank_installments_recurring_end_date'] = $this->config->get('payment_ocgrAlphaBank_installments_recurring_end_date');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_css_url'])) {
			$data['ocgrAlphaBank_css_url'] = $this->request->post['payment_ocgrAlphaBank_css_url'];
		} else {
			$data['ocgrAlphaBank_css_url'] = $this->config->get('payment_ocgrAlphaBank_css_url');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_total'])) {
			$data['ocgrAlphaBank_total'] = $this->request->post['payment_ocgrAlphaBank_total'];
		} else {
			$data['ocgrAlphaBank_total'] = $this->config->get('payment_ocgrAlphaBank_total');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_order_status_id'])) {
			$data['ocgrAlphaBank_order_status_id'] = $this->request->post['payment_ocgrAlphaBank_order_status_id'];
		} else {
			$data['ocgrAlphaBank_order_status_id'] = $this->config->get('payment_ocgrAlphaBank_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if(isset($this->request->post['payment_ocgrAlphaBank_geo_zone_id'])) {
			$data['ocgrAlphaBank_geo_zone_id'] = $this->request->post['payment_ocgrAlphaBank_geo_zone_id'];
		} else {
			$data['ocgrAlphaBank_geo_zone_id'] = $this->config->get('payment_ocgrAlphaBank_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$geo_zones = Array (0 => Array("geo_zone_id" => 0, "name" => $data['text_all_zones'], "description" => $data['text_all_zones'], "date_modified" => '0000-00-00 00:00:00', "date_added" => '0000-00-00 00:00:00'));
		$geo_zones = array_merge($geo_zones, $this->model_localisation_geo_zone->getGeoZones());
		$data['geo_zones'] = $geo_zones;


		if(isset($this->request->post['payment_ocgrAlphaBank_status'])) {
			$data['ocgrAlphaBank_status'] = $this->request->post['payment_ocgrAlphaBank_status'];
		} else {
			$data['ocgrAlphaBank_status'] = $this->config->get('payment_ocgrAlphaBank_status');
		}

		if(isset($this->request->post['payment_ocgrAlphaBank_sort_order'])) {
			$data['ocgrAlphaBank_sort_order'] = $this->request->post['payment_ocgrAlphaBank_sort_order'];
		} else {
			$data['ocgrAlphaBank_sort_order'] = $this->config->get('payment_ocgrAlphaBank_sort_order');
		}

		if(isset($this->error['ocgrAlphaBank_warning'])) {
			$data['ocgrAlphaBank_warning_error'] = $this->error['ocgrAlphaBank_warning'];
		} else {
			$data['ocgrAlphaBank_warning_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_merchant_id'])) {
			$data['ocgrAlphaBank_merchant_id_error'] = $this->error['ocgrAlphaBank_merchant_id'];
		} else {
			$data['ocgrAlphaBank_merchant_id_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_shared_secret'])) {
			$data['ocgrAlphaBank_shared_secret_error'] = $this->error['ocgrAlphaBank_shared_secret'];
		} else {
			$data['ocgrAlphaBank_shared_secret_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_installments_normal_offset'])) {
			$data['ocgrAlphaBank_installments_normal_offset_error'] = $this->error['ocgrAlphaBank_installments_normal_offset'];
		} else {
			$data['ocgrAlphaBank_installments_normal_offset_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_installments_recurring_frequency'])) {
			$data['ocgrAlphaBank_installments_recurring_frequency_error'] = $this->error['ocgrAlphaBank_installments_recurring_frequency'];
		} else {
			$data['ocgrAlphaBank_installments_recurring_frequency_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_installments_recurring_end_date'])) {
			$data['ocgrAlphaBank_installments_recurring_end_date_error'] = $this->error['ocgrAlphaBank_installments_recurring_end_date'];
		} else {
			$data['ocgrAlphaBank_installments_recurring_end_date_error'] = '';
		}

		if(isset($this->error['ocgrAlphaBank_sort_order'])) {
			$data['ocgrAlphaBank_sort_order_error'] = $this->error['ocgrAlphaBank_sort_order'];
		} else {
			$data['ocgrAlphaBank_sort_order_error'] = '';
		}
        $product_id=19;
        $domain=$_SERVER['SERVER_NAME']; if($domain == "" || $domain == "domain"){ die("Wrong Domain"); }
        $licenseServer = base64_decode("aHR0cDovL2JpbGxpbmcub3BlbmNhcnRncmVlY2UuZ3IvbGljZW5zZXMvYXBpLw=="); $postvalue="domain=$domain&product=".$product_id;
       	if  (in_array  ('curl', get_loaded_extensions())) {
            $ch = curl_init(); curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_URL, $licenseServer);
            curl_setopt($ch, CURLOPT_POST, true); curl_setopt($ch, CURLOPT_POSTFIELDS, $postvalue);
            $result = json_decode(curl_exec($ch), true);
            $data['result']=$result;
            $wrong_http_codes = '^0|4.*|5.*$';
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        }else{
            $result_2 = file_get_contents($licenseServer."?".$postvalue); $result = json_decode($result_2,true);
            $headers = get_headers($licenseServer."?".$postvalue);
            $http_code = substr($headers[0], 9, 3);
        }
        if (preg_match("/$wrong_http_codes/", $http_code) === 1) {

        }else{
            if($result['status'] != 200 && $result['status'] != 500 && $result['status'] != 501 && $result['status'] != 502) {
                if($result['valid']=="0"){

                     }
            }
        }

        $result=array('valid'=>'3','license'=>'active');
        $data['result']=$result;

        if(isset($result['valid']) && $result['valid'] == "3") {
            $data['domain']=$domain;
        }

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
        if(version_compare(VERSION, '3.0') >= 0) { //3.x
            $this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank', $data));
        }elseif(version_compare(VERSION, '2.2') > 0) { //2.3.x
			$this->response->setOutput($this->load->view('extension/payment/ocgrAlphaBank.tpl', $data));
		}else{
			$this->response->setOutput($this->load->view('payment/ocgrAlphaBank.tpl', $data));
		}
	}

	private function validate() {
		

		if(version_compare(VERSION, '2.2') > 0) { //2.3.x
		$this->language->load('extension/payment/ocgrAlphaBank');
			if(!$this->user->hasPermission('modify', 'extension/payment/ocgrAlphaBank')) {
				$this->error['ocgrAlphaBank_warning'] = $this->language->get('error_permission');
			}
		}else{ //2.2.x
			$this->language->load('payment/ocgrAlphaBank');
			if(!$this->user->hasPermission('modify', 'payment/ocgrAlphaBank')) {
				$this->error['ocgrAlphaBank_warning'] = $this->language->get('error_permission');
			}
		}

		if(!$this->request->post['payment_ocgrAlphaBank_merchant_id']) {
			$this->error['ocgrAlphaBank_merchant_id'] = $this->language->get('error_merchant_id');
		}

		if(!$this->request->post['payment_ocgrAlphaBank_shared_secret']) {
			$this->error['ocgrAlphaBank_shared_secret'] = $this->language->get('error_shared_secret');
		}

		if($this->request->post['payment_ocgrAlphaBank_installments_type'] == 1 ) {
			if($this->request->post['payment_ocgrAlphaBank_installments_normal_offset'] != '' && (!is_numeric($this->request->post['payment_ocgrAlphaBank_installments_normal_offset']) || $this->request->post['payment_ocgrAlphaBank_installments_normal_offset'] < 1 || !is_int($this->request->post['payment_ocgrAlphaBank_installments_normal_offset'])))
			{
				$this->error['ocgrAlphaBank_installments_normal_offset'] = $this->language->get('error_installments_normal_offset');
			}
		} elseif ($this->request->post['payment_ocgrAlphaBank_installments_type'] == 2) {
			if($this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency'] != '' || !is_numeric($this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency']) || !is_int($this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency']) || $this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency'] < 1 || $this->request->post['payment_ocgrAlphaBank_installments_recurring_frequency'] > 28) {
				$this->error['ocgrAlphaBank_installments_recurring_frequency'] = $this->language->get('error_installments_recurring_frequency');
			}

			if(!$this->request->post['payment_ocgrAlphaBank_installments_recurring_end_date']){
				$date_parts = explode("-", $this->request->post['payment_ocgrAlphaBank_installments_recurring_end_date']);
				if(count($date_parts) != 3 || !is_numeric($date_parts[0]) || !is_numeric($date_parts[1]) || !is_numeric($date_parts[2]))
				{
					$this->error['ocgrAlphaBank_installments_recurring_end_date'] = $this->language->get('error_installments_recurring_end_date');
				}
			}
		}

		if($this->request->post['payment_ocgrAlphaBank_sort_order'] != '' && !is_numeric($this->request->post['payment_ocgrAlphaBank_sort_order'])) {
			$this->error['ocgrAlphaBank_sort_order'] = $this->language->get('error_sort_order');
		}

		return !$this->error;
	}

	public function install() {
		if(version_compare(VERSION, '2.2') > 0) { //2.3.x
			$this->load->model('extension/payment/ocgrAlphaBank');
			$this->model_extension_payment_ocgrAlphaBank->install();
		}else{
			$this->load->model('payment/ocgrAlphaBank');
			$this->model_payment_ocgrAlphaBank->install();
		}
	}

	public function uninstall() {
		if(version_compare(VERSION, '2.2') > 0) { //2.3.x
			$this->load->model('extension/payment/ocgrAlphaBank');
			$this->model_extension_payment_ocgrAlphaBank->uninstall();
		}else{
			$this->load->model('payment/ocgrAlphaBank');
			$this->model_payment_ocgrAlphaBank->uninstall();
		}
	}
}
//OpenCart Greece - 2013-2018
?>