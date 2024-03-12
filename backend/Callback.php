<?php

include 'vendor/NovaPoshtaApi2.php';


// Получаем отправленные данные
$jsonData = file_get_contents('php://input');

// Декодируем JSON данные в ассоциативный массив
$data = json_decode($jsonData, true);

// Теперь у вас есть доступ к данным из JavaScript
$id                  = $data['id'];
$apiKey              = $data['apiKey'];
$senderPhone         = $data['senderPhone'];
$senderRef           = $data['senderRef'];
$contactSenderRef    = $data['contactSenderRef'];
$resultSenderCityRef = $data['resultSenderCityRef'];
$getWarehousesNPRef            = $data['getWarehousesNPRef'];
$getWarehousesNPWarehouseIndex = $data['getWarehousesNPWarehouseIndex'];
$senderData          = $data['senderData'];
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

// Делайте что-то с полученными данными
// Например, можно сохранить их в базу данных или выполнить другие операции



// $data = array(
//     'id'                  => $data['id'],
//     'firstLastName'       => $data['firstLastName'],
//     'addressNp'           => $data['addressNp'],
//     'phone'               => $data['phone'],
//     'weight'              => $data['weight'],
//     'price'               => $data['price'],
//     'cityRef'             => $data['cityRef'],
//     'warehouseRef'        => $data['warehouseRef'],
//     'note_for_order'      => $data['note_for_order'],
//     'product_description' => $data['product_description'],
//     'deliveryCheckbox'    => $data['deliveryCheckbox'],
//     'postomatCheckbox'    => $data['postomatCheckbox'],
//     'volumetricVolume'    => $data['volumetricVolume'],
//     'volumetricWidth'     => $data['volumetricWidth'],
//     'volumetricLength'    => $data['volumetricLength'],
//     'volumetricHeight'    => $data['volumetricHeight'],
//     'p_weight'            => $data['p_weight'],
// );







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

if ($postomatCheckbox == true) {
    // Добавляем OptionsSeat в массив данных заказа
    $orderData['OptionsSeat'] = array(
        array(
            'volumetricVolume'    => $volumetricVolume,   
            'volumetricWidth'     => $volumetricWidth,  
            'volumetricLength'    => $volumetricLength, 
            'volumetricHeight'    => $volumetricHeight,
            'weight'              => $weight,
        ),
    );
    $orderData['ServiceType']   = 'WarehousePostomat';
} else {
    $orderData['VolumeGeneral'] = $volumetricVolume;
    $orderData['ServiceType']   = 'WarehouseWarehouse';
}

if($deliveryCheckbox == true){
    $orderData['BackwardDeliveryData'] = array(
        array(
            'PayerType'           => 'Recipient',   
            'CargoType'           => 'Money',  
            'RedeliveryString'    => $totalOrder, 
        ),
    );
}






$s_data = json_decode($senderData, true);




$senderData =  array(

    "SenderWarehouseIndex"  => $getWarehousesNPWarehouseIndex,
    "RecipientWarehouseIndex"  => $RecipientWarehouseIndex,
    'SendersPhone' => $senderPhone,
        'Sender' => $senderRef, //'3e32c3e7-5f4b-11ed-a60f-48df37b921db',
        'CitySender' => $resultSenderCityRef, // 'db5c88f0-391c-11dd-90d9-001a92567626',
        'SenderAddress' => $getWarehousesNPRef, //'1692284b-e1c2-11e3-8c4a-0050568002cf',
        'ContactSender' => $contactSenderRef, //https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a3575a67-8512-11ec-8ced-005056b2dbe1
    );


    // $response = array('status' => 'success', 'message' => $id, 'data' => $senderData);
    // echo json_encode($response);



$np = new \LisDev\Delivery\NovaPoshtaApi2($apiKey);

$RecipientWarehouseIndex = $np->setRecipientWarehouseIndex($cityRef, $addressNp)['data'][0]['WarehouseIndex'];




$fl_name = explode(" ", $firstLastName);
    // Генерирование новой накладной
$result = 

$np->newInternetDocument(
    $senderData,


        // Данные получателя
    array(
            //3e4c0d0d-5f4b-11ed-a60f-48df37b921db
        'FirstName' => $fl_name[1],
        'MiddleName' => '',
        'LastName' => $fl_name[0],
        'RecipientsPhone' => '38'.cleanPhoneNumber($phone),
        'AddressNP' => $addressNp,
            'CityRecipient' => $cityRef, // ref города // Буду получать здесь wcus_city_ref
            'RecipientAddress' => $warehouseRef,// https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a2322f38-8512-11ec-8ced-005056b2dbe1 // Буду получать здесь  wcus_warehouse_ref
            //'ContactRecipient' => '7654ba8c-daf4-11ee-a60f-48df37b921db', //Ідентифікатор контактної особи
            //'Recipient' => '3e4c0d0d-5f4b-11ed-a60f-48df37b921db', //тута взял https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a3575a67-8512-11ec-8ced-005056b2dbe1
        ),
    $orderData,


);

    // Отправляем ответ обратно в JavaScript (опционально)
$response = array('status' => 'success', 'message' => $id, 'data' => $result);
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