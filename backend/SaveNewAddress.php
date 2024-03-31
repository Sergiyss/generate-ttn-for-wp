<?php 

	include 'vendor/NovaPoshtaApi2.php';


	// Получаем отправленные данные
	$jsonData = file_get_contents('php://input');
	// Декодируем JSON данные в ассоциативный массив
	$data = json_decode($jsonData, true);

	    // Получение данных из $data
	    $apiKey              = $data['apiKey'];
	    $counterpartyRef     = $data['counterpartyRef'];
	    $streetRef             = $data['streetRef'];
	    $buildingNumber      = $data['buildingNumber'];
	    $flat                = $data['flat'];


		$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

		$saveAddress = $np->model('Address')->save(
			array(
				'CounterpartyRef' => $counterpartyRef,
				'StreetRef' => $streetRef,
				'BuildingNumber' => $buildingNumber,
				'Flat' => $flat,
			)
		);
	   

	    // Отправляем ответ обратно в JavaScript (опционально)
	    $response = array('status' => 'success', 'index' => 0, 'message' => 0, 'data' =>  $saveAddress);
	    echo json_encode($response);
		
?>
