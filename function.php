<?php 

function getNameTable($wpdb){
	return $wpdb->prefix . 'history_generate_ttn';
}

/**
 * Создания базы данных для историю генерации накладных
 * */
function createTable(){

	global $wpdb;

	$table_name = getNameTable($wpdb);
	//Проверка на существование базы данных
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		// Определение структуры таблицы
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			order_number varchar(50) NOT NULL,
			order_link varchar(255) NOT NULL,
			order_date date NOT NULL,
			PRIMARY KEY  (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		// Создание или обновление таблицы
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
/**
 * Вставка новых данных в базу данных
 * 
 * @param orders => список ид заказов
 * @param linkGenerateTTN => ссылка на генерацию накладных
 * */
function insert_data_base(){
	global $wpdb;


	$orders_json_ids = $_POST['order_ids'];
	$linkGenerateTTN = $_POST['order_ttn_refs'];
	
	
		
	$history_data = array(
		'order_number' => $orders_json_ids,
		'order_link' => $linkGenerateTTN,
	    'order_date' => date('d.m.Y') // Текущая дата
	);

	$table_name = $table_name = getNameTable($wpdb);

	$wpdb->insert($table_name, $history_data);

	// Проверка на ошибки при вставке
	if ( ! empty( $wpdb->last_error ) ) {
		// $response = array('status' => 'error', 'message' => '0', 'data' => 'Ошибка при вставке данных');
    	// wp_send_json($response);

    	$response = array('status' => 'error', 'message' => 0, 'data' => 'Ошибка при вставке данных');
    	echo json_encode($response);
	} else {
		$response = array('status' => 'success', 'message' => '0', 'data' => 'success');
    	wp_send_json($response);
	}

	wp_die();
}
add_action('wp_ajax_insert_data_base', 'insert_data_base'); // Зарегистрированные пользователи
add_action('wp_ajax_nopriv_insert_data_base', 'insert_data_base'); // Неавторизованные пользователи



function get_all_histoty_generate_ttn(){
	global $wpdb;

	// Название таблицы с учетом префикса WordPress
	$table_name = $table_name = getNameTable($wpdb);

	// Выбор всех данных из таблицы
	$results = $wpdb->get_results( "SELECT * FROM $table_name LIMIT 50", ARRAY_A);

	// Проверка на наличие результатов
	if ( $results ) {
		$history_data = array(); 
	    
	    foreach ( $results as $row ) {
	       
	        $history_data[] = array(
                'ID' => $row['id'],
                'order_number' => $row['order_number'], // Преобразуем строку JSON в массив
                'order_link'   => $row['order_link'],   // Преобразуем строку JSON в массив
                'order_date'   => $row['order_date'],
            );
	    }
	}
	return $history_data;
}


?>