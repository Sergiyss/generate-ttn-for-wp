<?php 

	include 'vendor/NovaPoshtaApi2.php';


	// Получаем отправленные данные
	$jsonData = file_get_contents('php://input');
	// Декодируем JSON данные в ассоциативный массив
	$data = json_decode($jsonData, true);

	    // Получение данных из $data
	    $id                  = $data['id'];
	    $index               = $data['index'];
	    $apiKey              = $data['apiKey'];
	    $firstLastName       = $data['firstLastName'];
	    $phone               = $data['phone'];
	    $cityRef             = $data['cityRef'];
	    $state = $data['state'];


	    if($state === 'find'){

			$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

			$flName = explode(" ", $firstLastName);
			//Получаю или созданю данные получателя 
			$counterpartyData = $np->model('Counterparty')->save(
	            array(
	                'FirstName' => $flName[1],
	                'MiddleName' => '',
	                'LastName' => $flName[0],
	                'Phone' =>  '38'.cleanPhoneNumber($phone),
	                'Email' => '',
	                'CounterpartyType' => 'PrivatePerson',
	                'CounterpartyProperty' =>  'Recipient',
	            ),
	        );

			if (!empty($counterpartyData['data'])) {

	        	$recipient = $counterpartyData['data'][0]['Ref']; //Получаю его id
		        //Получаю список адрес Контрагентів
		        //https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a30dbb7c-8512-11ec-8ced-005056b2dbe1


		        $getCounterpartyAddresses = $np->model('Counterparty')->getCounterpartyAddresses($recipient);


		        if(empty($getCounterpartyAddresses['data'])){

		        }
			}

	       

	        // Отправляем ответ обратно в JavaScript (опционально)
		    $response = array('status' => 'success', 'index' => $index, 'message' => $id, 'data' => $getCounterpartyAddresses, 'identifierREF' => $recipient);
		    echo json_encode($response);
		}
	

	function cleanPhoneNumber($phone_number) {
        // Удаляем все символы, кроме цифр
	    $cleaned_phone_number = preg_replace('/\D/', '', $phone_number);

	        // Если номер имеет длину 12 символов и начинается с "380", удаляем первые два символа
	    if (strlen($cleaned_phone_number) === 12 && substr($cleaned_phone_number, 0, 3) === '380') {
	        $cleaned_phone_number = substr($cleaned_phone_number, 2);
	    }

	    return $cleaned_phone_number;
	}

?>
