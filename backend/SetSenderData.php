<?php

include 'vendor/NovaPoshtaApi2.php';

class SetSenderData {

	// Подключаем WordPress
	const CONSTANT = 'constant value';

	function updateSenderData($id, $apiKey){
		$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

		$result = $np->getCounterparties('Sender');


		//var_dump( $result );

		$response = array('status' => 'success', 'message' => $id, 'data' => $result);
    	return json_encode($respons);
	}
}

?>