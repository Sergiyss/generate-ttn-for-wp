<?php 

function getNameTable($wpdb){
	return $wpdb->prefix . 'history_generate_ttn';
}

/**
 * Создания базы данных для историю генерации накладных
 * 
 * vscode.dev ))
 * */
function createTable(){

	global $wpdb;

	$table_name = getNameTable($wpdb);
	//Проверка на существование базы данных
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		// Определение структуры таблицы
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			order_number TEXT NOT NULL,
			order_link TEXT NOT NULL,
			order_date date NOT NULL,
			PRIMARY KEY  (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

		// Создание или обновление таблицы
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}

function dropTable() {
    global $wpdb;

    $table_name = getNameTable($wpdb);

    // Удаление таблицы
    $wpdb->query("DROP TABLE $table_name");
}


function delete_all_records() {
    global $wpdb;
    
    $table_name = getNameTable($wpdb);

    // Удаление всех записей из таблицы
    $wpdb->query( "DELETE FROM $table_name" );
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
	    'order_date' => date('Y-m-d') // Текущая дата
	);

	$table_name = $table_name = getNameTable($wpdb);

	$wpdb->insert($table_name, $history_data);

	// Проверка на ошибки при вставке
	if ( ! empty( $wpdb->last_error ) ) {
		// $response = array('status' => 'error', 'message' => '0', 'data' => 'Ошибка при вставке данных');
    	// wp_send_json($response);

    	$response = array('status' => 'error', 'message' => 0, 'data' => 'Під час запису в базу даних сталася помилка'.$wpdb->last_error.' '.$linkGenerateTTN);
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

	//dropTable();
	//createTable();
	//delete_all_records();
    // Название таблицы с учетом префикса WordPress
    $table_name = $table_name = getNameTable($wpdb);

    // Выбор всех данных из таблицы
    $results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 50", ARRAY_A);


    // Проверка на наличие результатов
    if ( $results ) {
        $history_data = array(); 
        
        foreach ( $results as $row ) {
            $formatted_date = date('d.m.y', strtotime($row['order_date'])); // Преобразуем дату в нужный формат
            $history_data[] = array(
                'ID' => $row['id'],
                'order_number' => $row['order_number'], // Преобразуем строку JSON в массив
                'order_link'   => $row['order_link'],   // Преобразуем строку JSON в массив
                'order_date'   => $formatted_date,      // Используем преобразованную дату
            );
        }
    }
    return $history_data;
}

?>