<?php

class GetWooOrders {

	// Подключаем WordPress
	const CONSTANT = 'constant value';

	/**
	 * Возвращаю всё заказы по новой почте, которые ождают подтверждения новой почты
	 * */
	function getOrdersNP(){
		$shipping_methods = new WC_Shipping();
		$methods = $shipping_methods->get_shipping_methods();

		$args = array(
	        'status' => 'wc-processing', // Статус заказа "в обработке"
	        'limit' => -1,              // Количество заказов
	        'return' => 'ids',          // Возвращать только ID заказов
	    );

		$orders = wc_get_orders($args);
		$orders_with_nova_poshta = [];

		$order_ = [];
		foreach ($orders as $order_id) {
			$order = wc_get_order($order_id);
			$shipping_methods = $order->get_shipping_methods();

			foreach ($shipping_methods as $shipping_method) {

	            // Проверка способа доставки
				if ($shipping_method->get_method_id() == 'nova_poshta_shipping') {

					$shipping_items = $order->get_items('shipping');
					$billing_items = $order->get_items('billing');
					$product_list = array();

					if(empty($order_)){
						$order_ = $order;
							// echo "<pre>";
							// print_r($order->get_items());
							// echo "</pre>";
					}

					foreach ($shipping_items as $shipping_item) {
						$shipping_item_meta = $shipping_item->get_meta_data();

						    // Check for wcus_warehouse_ref and wcus_city_ref within the shipping item's meta data
						foreach ($shipping_item_meta as $meta_datum) {
							if ($meta_datum->key === 'wcus_warehouse_ref') {
								$wcus_warehouse_ref = $meta_datum->value;
							} elseif ($meta_datum->key === 'wcus_city_ref') {
								$wcus_city_ref = $meta_datum->value;
							}
						}
					} 

					$order_data = array(
						'order_id' => $order->get_id(),
						'first_name' => $order->get_billing_first_name(),
						'last_name' => $order->get_billing_last_name(),
						'phone' => $order->get_billing_phone(),
						'city' =>  $order->data['billing']['city'],
						'address_np' => $order->data['billing']['address_1'],
						'email' => $order->data['billing']['email'],	                
						'products' => $product_list,
						'city_ref' => $wcus_city_ref,
						'warehouse_ref' => $wcus_warehouse_ref
					);


					foreach( $order->get_items() as $item_id => $item ){
					    //Get the product ID
					    // $product_id_ = $item->get_id();
					    // $product_id = $item->get_product_id();
						$product_name = $item->get_name();
						$meta_data = $item->get_meta_data();

						$ordered_weight = $item->get_quantity();


						// Проходимся по каждой записи метаданных
						foreach ($meta_data as $meta) {

							// Проверяем, является ли ключ "Ordered Weight"
							if ($meta->key === 'Ordered Weight') {
							            // Получаем значение заказанного веса
								$ordered_weight = $meta->get_data()['value'];
							}
						}

						$order_data['products'][] = "$product_name × $ordered_weight";
					}
					$orders_with_nova_poshta[] = $order_data;
				}
			}
		}

		return $orders_with_nova_poshta;
	}
}

?>