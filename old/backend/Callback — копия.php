<?php

include 'vendor/NovaPoshtaApi2.php';


// Получаем отправленные данные
$jsonData = file_get_contents('php://input');

// Декодируем JSON данные в ассоциативный массив
$data = json_decode($jsonData, true);

// Теперь у вас есть доступ к данным из JavaScript
$id = $data['id'];
$ttns = $data['ttns'];


// Делайте что-то с полученными данными
// Например, можно сохранить их в базу данных или выполнить другие операции





    $np = new \LisDev\Delivery\NovaPoshtaApi2('0a25b086dbd3498c8f2ff41988cfdf87');

    $response  = $np->printGetLink('printMarking85x85', $ttns, 'pdf8');




    
    // Отправляем ответ обратно в JavaScript (опционально)
    $response = array('status' => 'success', 'message' => $id, 'data' => $response,);
    echo json_encode($response);


?>
