<?php 

$messages = array(
	'success' => 'Дані успішно записані',
	'warning' => 'Что-то пошло не так',
	'empty' => 'Нет данных',
);

$status = array(
	'success' => 'success',
	'error' => 'error',
);


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
function insert_data_dase(){
	global $wpdb;


	$orders = $_POST['orders'];
	$linkGenerateTTN = $_POST['link_generate_ttn'];

	$history_data = array(
		'order_number' => $orders,
		'order_link' => $linkGenerateTTN,
	    'order_date' => date('Y-m-d') // Текущая дата
	);

	$table_name = $table_name = getNameTable($wpdb);;

	$wpdb->insert($table_name, $history_data);

	// Проверка на ошибки при вставке
	if ( ! empty( $wpdb->last_error ) ) {
		echo 'Ошибка при вставке данных: ' . $wpdb->last_error;
	} else {
		echo 'Данные успешно вставлены в базу данных.';
	}


    $response = array('status' => $status['success'], 'message' => '0', 'data' => $messages['success']);
    wp_send_json($response);

	wp_die();
}
add_action('wp_ajax_get_change_insert_data_dase', 'insert_data_dase'); // Зарегистрированные пользователи
add_action('wp_ajax_nopriv_get_change_insert_data_dase', 'insert_data_dase'); // Неавторизованные пользователи

?>