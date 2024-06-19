<?php

include 'vendor/NovaPoshtaApi2.php';


// Получаем отправленные данные
$jsonData = file_get_contents('php://input');

// Декодируем JSON данные в ассоциативный массив
$data = json_decode($jsonData, true);

// Теперь у вас есть доступ к данным из JavaScript
$id                  = $data['id'];
$index               = $data['index'];
$apiKey              = $data['apiKey'];
$senderPhone         = $data['senderPhone'];
$senderRef           = $data['senderRef'];
$contactSenderRef    = $data['contactSenderRef'];
$resultSenderCityRef = $data['resultSenderCityRef'];
$getWarehousesNPRef            = $data['getWarehousesNPRef'];
$getWarehousesNPWarehouseIndex = $data['getWarehousesNPWarehouseIndex'];
$senderData          = $data['senderData'] ?? '';
$firstLastName       = $data['firstLastName'];
$addressNp           = $data['addressNp'];
$phone               = $data['phone'];
$weight              = $data['weight'];
$price               = $data['price'];
$totalOrder          = $data['totoalOrder'];
$cityRef             = $data['cityRef'];
$warehouseRef        = $data['warehouseRef'];
//Другие настройки
$note_for_order      = $data['note_for_order'];
$deliveryCheckbox    = $data['deliveryCheckbox'];
$postomatCheckbox    = $data['postomatCheckbox'];
$volumetricVolume    = $data['volumetricVolume'];
$volumetricWidth     = $data['volumetricWidth'];
$volumetricLength    = $data['volumetricLength'];
$volumetricHeight    = $data['volumetricHeight'];



$paymentType         = $data['paymentType'];
$whoPaysForShipping  = $data['whoPaysForShipping'];
$numberOfSeats       = $data['numberOfSeats'];
$typeDelivery        = $data['typeDelivery'];


/*
Если доставка к двери, то передаю эти настройки
*/

$deliveryToAddressCheckbox = $data['deliveryToAddressCheckbox'];




$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

if ($deliveryToAddressCheckbox != true){
    $RecipientWarehouseIndex = $np->setRecipientWarehouseIndex($cityRef, $addressNp)['data'][0]['WarehouseIndex'];
}

$orderData = array(
        // Дата отправления
    'DateTime' => date('d.m.Y'),
        // Тип оплаты, дополнительно - getPaymentForms()
    'PaymentMethod' => $paymentType,
        // Кто оплачивает за доставку
    'PayerType' => $whoPaysForShipping,
        // Стоимость груза в грн
    'Cost' => $price,
        // Кол-во мест
    'SeatsAmount' => $numberOfSeats,
        // Описание груза
    'Description' => $note_for_order,
        // Тип доставки, дополнительно - getCargoTypes
    'CargoType' => $typeDelivery,
        // Вес груза
    'Weight' => $weight,
        // Объем груза в куб.м.
);

// Если доставка на поштомат
if ($postomatCheckbox == true) {
    // Добавляем OptionsSeat в массив данных заказа

    $senderData =  array(
        "SenderWarehouseIndex"  => $getWarehousesNPWarehouseIndex,
        "RecipientWarehouseIndex"  => $RecipientWarehouseIndex,
        'SendersPhone' => $senderPhone,
        'Sender' => $senderRef, 
        'CitySender' => $resultSenderCityRef, 
        'SenderAddress' => $getWarehousesNPRef, 
        'ContactSender' => $contactSenderRef,
        'ServiceType' => 'WarehousePostomat',
    );

    $orderData['OptionsSeat'] = array(
        array(
            'volumetricVolume'    => $volumetricVolume,   
            'volumetricWidth'     => $volumetricWidth,  
            'volumetricLength'    => $volumetricLength, 
            'volumetricHeight'    => $volumetricHeight,
            'weight'              => $weight,
        ),
    );

} elseif ($deliveryToAddressCheckbox == true){
    // //Если доставка к адресу
    // $senderAddress = $np->getStreet($cityRef, $RecipientAddressName);

    // $senderAddress['data'][0]['Ref']; //Получил Ref адрес улицы
    
    $senderData =  array(
        'SenderWarehouseIndex'  => $getWarehousesNPWarehouseIndex,
        'RecipientWarehouseIndex'  => null, // в novaPoshtaApi2 будет как ref адреса доставки
        'SendersPhone' => $senderPhone,
        'Sender' => $senderRef, 
        'CitySender' => $resultSenderCityRef, 
        'SenderAddress' => $getWarehousesNPRef, 
        'ContactSender' => $contactSenderRef,
        'SenderAddressRef' => $senderAddress,
        'ServiceType' => 'WarehouseDoors',
    );


} else {
    //Если доставка к отделению
    $senderData =  array(
        "SenderWarehouseIndex"  => $getWarehousesNPWarehouseIndex,
        "RecipientWarehouseIndex"  => $RecipientWarehouseIndex,
        'SendersPhone' => $senderPhone,
        'Sender' => $senderRef, 
        'CitySender' => $resultSenderCityRef, 
        'SenderAddress' => $getWarehousesNPRef, 
        'ContactSender' => $contactSenderRef,
        'ServiceType' => 'WarehouseWarehouse',
    );
}
//Если это Накладений платіж
if($deliveryCheckbox == true){

    $orderData['BackwardDeliveryData'] = array(
        array(
            'PayerType'           => 'Recipient',   
            'CargoType'           => 'Money',  
            'RedeliveryString'    => $totalOrder, 
        ),
    );
}

$fl_name = explode(" ", $firstLastName);
$recipients = array(
    'FirstName' => $fl_name[1],
    'MiddleName' => '',
    'LastName' => $fl_name[0],
    'RecipientsPhone' => '38'.cleanPhoneNumber($phone),
    'AddressNP' => $addressNp,
    'CityRecipient' => $cityRef,
    'RecipientAddress' => $warehouseRef,
);




        // $data_arr = array();

        // $data_arr["senderData"] = $senderData;
        // $data_arr["recipients"] = $recipients;
        // $data_arr["orderData"] = $orderData;
        // $data_arr["warehouseRef"] = $warehouseRef;

        // // Сохраняем данные в локальном хранилище 
        // $json_data = json_encode( $data_arr, JSON_UNESCAPED_UNICODE );
        // //wp_mail( "serhii.kr93@gmail.com", "Форма заявки 2", "json_data" );

        // $current_directory = __DIR__;

        // // Путь к файлу, в который вы хотите записать данные
        // $file_path = $current_directory . '/vendor/data2.json';

        // file_put_contents($file_path, $json_data);


    //Нужно камоментить, если будет рефакторинг
    //$RecipientWarehouseIndex = $np->setRecipientWarehouseIndex($cityRef, $addressNp)['data'][0]['WarehouseIndex'];

    //$listNp =  $np->setRecipientWarehouseIndex($cityRef, $addressNp)['data'];

    /**
     * Рефакторинг, если $np->setRecipientWarehouseIndex($cityRef, $addressNp)['data'] возвращает больше чем один адресс, то, нужно проверить на совпадения.
     * 
     * Еще не тестировал... 15.03.24
     * */

    // foreach($listNp as $data){
    //     if($data['CityRef'] === $getWarehousesNPRef){
    //         $RecipientWarehouseIndex = $data['WarehouseIndex']
    //     }
    // }




    // Генерирование новой накладной
    $result = $np->newInternetDocument(
    
        $senderData,
        // Данные получателя
        $recipients,

        $orderData,
    );

    // Отправляем ответ обратно в JavaScript (опционально)
    $response = array('status' => 'success', 'index' => $index, 'message' => $id, 'data' => $result, 'address_np'=> $RecipientWarehouseIndex);
    echo json_encode($response);




    //Форматирование номера

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