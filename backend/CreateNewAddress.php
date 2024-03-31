<?php 

	include 'vendor/NovaPoshtaApi2.php';


	// Получаем отправленные данные
	$jsonData = file_get_contents('php://input');
	// Декодируем JSON данные в ассоциативный массив
	$data = json_decode($jsonData, true);

	    // Получение данных из $data
	    $apiKey              = $data['apiKey'];
	    $sityRef             = $data['sityRef'];
	    $query               = $data['query'];
	    $state = $data['state'];


	    if($state === 'search'){

			$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

			$getCounterpartyAddresses = $np->model('Address')->getStreet($sityRef, $query);
	       

	        // Отправляем ответ обратно в JavaScript (опционально)
		    $response = array('status' => 'success', 'index' => 0, 'message' => 0, 'data' =>  $getCounterpartyAddresses);
		    echo json_encode($response);
		}


?>
