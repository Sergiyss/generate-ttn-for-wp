<?php

include trailingslashit( WP_PLUGIN_DIR . '/nova-poshta-renerate-ttn' ) . '/vendor/NovaPoshtaApi2.php';
//include trailingslashit( WP_PLUGIN_DIR . '/nova-poshta-renerate-ttn' ) . '/backend/SetSenderData.php';

function getSettingPlugin() { ?>
    <div class="wrap">
        <h1>My Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            // Выводим скрытое поле безопасности
            settings_fields('my_plugin_settings');
            // Выводим секции с настройками
            do_settings_sections('my_plugin_settings');
            // Кнопка сохранения настроек
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    
    <?php
}


// Добавляем страницу настроек
add_action('admin_menu', 'my_plugin_add_settings_page');

function my_plugin_add_settings_page() {
    add_submenu_page(
        'options-general.php', // Родительская страница
        'My Plugin Settings', // Заголовок страницы
        'My Plugin', // Название в меню
        'manage_options', // Разрешение на просмотр страницы
        'my_plugin_settings', // Уникальный идентификатор страницы
        'my_plugin_settings_page' // Функция для отображения страницы настроек
    );
}

// Регистрируем секции и поля настроек
add_action('admin_init', 'my_plugin_initialize_settings');

function my_plugin_initialize_settings() {
    // Секция "General Settings"
    add_settings_section(
        'my_plugin_general_settings_section', // ID секции
        'Настройка отправителя', // Заголовок секции
        'my_plugin_general_settings_section_callback', // Функция обратного вызова для вывода описания секции
        'my_plugin_settings' // Идентификатор страницы, на которой будет отображаться секция
    );

    // Поле "Максимальная скидка"
    add_settings_field(
        'np_settings_apiKey', // ID поля
        'ApiKey', // Заголовок поля
        'np_settings_apiKey_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_settings_pib', // ID поля
        'П.І.Б.', // Заголовок поля
        'np_settings_pib_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );


    add_settings_field(
        'np_settings_phone', // ID поля
        'Номер телефона', // Заголовок поля
        'np_settings_phone_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_settings_city_sender', // ID поля
        'Місто відправника', // Заголовок поля
        'np_settings_city_sender_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_settings_address_np', // ID поля
        'Повна адреса Нової пошти', // Заголовок поля
        'np_settings_address_np_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );


    add_settings_field(
        'np_settings_generate_data_user_np', // ID поля
        'После ввода данных, или обновление, стоит сгенерировать данные', // Заголовок поля
        'np_settings_generate_data_user_np_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_settings_section' // ID секции, к которой будет привязано поле
    );


    /**
     * Дефолтные настройки
     * */
    add_settings_section(
        'my_plugin_general_default_value_section', // ID секции
        'Значення за замовчуванням для доставки', // Заголовок секции
        'my_plugin_general_settings_section_callback', // Функция обратного вызова для вывода описания секции
        'my_plugin_settings' // Идентификатор страницы, на которой будет отображаться секция
    );

    /**
     * Основные параметры посылки
     * */
    add_settings_field(
        'np_default_weight', // ID поля
        'Вага для доставки', // Заголовок поля
        'np_default_weight_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_default_value_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_default_parcel_price', // ID поля
        'Вартість посилки', // Заголовок поля
        'np_default_parcel_price_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_default_value_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_default_notepad', // ID поля
        'Примітки до товару / Опис вантажу', // Заголовок поля
        'np_default_notepad_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_default_value_section' // ID секции, к которой будет привязано поле
    );
    /**
     * Поштомат
     * */

    add_settings_field(
        'np_default_width',
        'Ширина (см)', 
        'np_default_width_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_general_default_value_section' // ID секции, к которой будет привязано поле
    );
    add_settings_field(
        'np_default_length', 
        'Довжина (см)', 
        'np_default_length_form',
        'my_plugin_settings',
        'my_plugin_general_default_value_section' 
    );


    add_settings_field(
        'np_default_height',
        'Висота (см)', 
        'np_default_height_form', 
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );


    add_settings_field(
        'np_default_volumetric_volume',
        'об\'єм, минимум 0.0004',
        'np_default_volumetric_volume_form', 
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );

    add_settings_field(
        'np_default_payment_type',
        'Тип оплати NonCash|Cash', 
        'np_default_payment_type_form',
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );

    /**
     * Остальные настройки
     * */

    add_settings_field(
        'np_default_who_pays_for_shipping',
        'Хто оплачує за доставку Recipient|Sender', 
        'np_default_who_pays_for_shipping_form',
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );


    add_settings_field(
        'np_default_number_of_seats',
        'Кількість місць', 
        'np_default_number_of_seats_form',
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );

    add_settings_field(
        'np_default_type_delivery',
        'Тип доставки: Parcel|Documents|Cargo', 
        'np_default_type_delivery_form',
        'my_plugin_settings',
        'my_plugin_general_default_value_section'
    );


    /**
     * Другие настройки
     * */
    add_settings_section(
        'my_plugin_other_settings_section', // ID секции
        'Другие настройки плагина', // Заголовок секции
        'my_plugin_general_settings_section_callback', // Функция обратного вызова для вывода описания секции
        'my_plugin_settings' // Идентификатор страницы, на которой будет отображаться секция
    );


    add_settings_field(
        'np_settings_auto_update_order_status_np', // ID поля
        'Після генерації ттн, оновлювати статус замовлення на виконано?', // Заголовок поля
        'np_settings_auto_update_order_status_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_other_settings_section' // ID секции, к которой будет привязано поле
    );

    add_settings_field(
        'np_settings_is_active_delivery', // ID поля
        'Активація післяплати', // Заголовок поля
        'np_settings_is_active_delivery_form', // Функция обратного вызова для вывода HTML-кода поля
        'my_plugin_settings', // Идентификатор страницы, на которой будет отображаться поле
        'my_plugin_other_settings_section' // ID секции, к которой будет привязано поле
    );



// np_default_weight,
// np_default_parcel_price,
// np_default_notepad,
// np_settings_notepad,
// np_settings_width,
// np_settings_length,
// np_settings_height,
// np_default_who_pays_for_shipping,
// np_default_number_of_seats,
// np_default_type_delivery,



    // Регистрируем опции
    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_apiKey', // Название опции
    );

    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_pib' // Название опции
    );

    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_phone' // Название опции
    );
    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_city_sender_form' // Название опции
    );
    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_address_np_form' // Название опции
    );

    register_setting(
        'my_plugin_settings', // Группа опций
        'np_settings_generate_data_user_np' // Название опции
    );

    /**
     * Дефолтные настройки
     * 
     * */

    // Для np_default_weight
    register_setting('my_plugin_settings', 'np_default_weight');
    
    // Для np_default_parcel_price
    register_setting('my_plugin_settings', 'np_default_parcel_price');
    
    // Для np_default_notepad
    register_setting('my_plugin_settings', 'np_default_notepad');
    
    // Для np_settings_notepad
    register_setting('my_plugin_settings', 'np_default_notepad');
    
    // Для np_settings_width
    register_setting('my_plugin_settings', 'np_default_width');
    
    // Для np_settings_length
    register_setting('my_plugin_settings', 'np_default_length');
    
    // Для np_settings_height
    register_setting('my_plugin_settings', 'np_default_height');
    
    register_setting('my_plugin_settings', 'np_default_volumetric_volume');

    // Для np_default_who_pays_for_shipping
    register_setting('my_plugin_settings', 'np_default_who_pays_for_shipping');
    
    // Для np_default_number_of_seats
    register_setting('my_plugin_settings', 'np_default_number_of_seats');
    

    register_setting('my_plugin_settings', 'np_default_type_delivery');


    register_setting('my_plugin_settings', 'np_settings_auto_update_order_status_np');

    register_setting('my_plugin_settings', 'np_settings_is_active_delivery');

}

// Функция обратного вызова для секции "General Settings"
function my_plugin_general_settings_section_callback() {
    echo '';
}
//0a25b086dbd3498c8f2ff41988cfdf87
// Функция обратного вызова для поля "Максимальная скидка"
function np_settings_apiKey_form() {
    $form = get_option('np_settings_apiKey');
    ?>
    <input type="string" name="np_settings_apiKey" value="<?php echo esc_attr($form); ?>" />
    <?php
}

function np_settings_pib_form() {
    $form = get_option('np_settings_pib');
    ?>
    <input type="string" name="np_settings_pib" value="<?php echo esc_attr($form); ?>" />
    <?php
}

function np_settings_phone_form() {
    $form = get_option('np_settings_phone');
    ?>
    <input type="string" name="np_settings_phone" value="<?php echo esc_attr($form); ?>" />
    <?php
}

function np_settings_city_sender_form() {
    $form = get_option('np_settings_city_sender_form');
    ?>
    <input type="string" name="np_settings_city_sender_form" value="<?php echo esc_attr($form); ?>" />
    <?php
}

function np_settings_address_np_form() {
    $form = get_option('np_settings_address_np_form');
    ?>
    <input type="string" name="np_settings_address_np_form" value="<?php echo esc_attr($form); ?>" />
    <?php
}


// Форма ввода
function np_default_weight_form() {
    $value = get_option('np_default_weight');
    ?>
    <input type="text" name="np_default_weight" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_parcel_price_form() {
    $value = get_option('np_default_parcel_price');
    ?>
    <input type="text" name="np_default_parcel_price" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_notepad_form() {
    $value = get_option('np_default_notepad');
    ?>
    <input type="text" name="np_default_notepad" value="<?php echo esc_attr($value); ?>" />
    <?php
}



function np_default_width_form() {
    $value = get_option('np_default_width');
    ?>
    <input type="text" name="np_default_width" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_length_form() {
    $value = get_option('np_default_length');
    ?>
    <input type="text" name="np_default_length" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_height_form() {
    $value = get_option('np_default_height');
    ?>
    <input type="text" name="np_default_height" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_volumetric_volume_form() {
    $value = get_option('np_default_volumetric_volume');
    ?>
    <input type="text" name="np_default_volumetric_volume" value="<?php echo esc_attr($value); ?>" />
    <?php
}



function np_default_who_pays_for_shipping_form() {
    $value = get_option('np_default_who_pays_for_shipping');
    ?>
    <input type="text" name="np_default_who_pays_for_shipping" value="<?php echo esc_attr($value); ?>" />
    <?php
}



function np_default_payment_type_form() {
    $value = get_option('np_default_payment_type');
    ?>
    <input type="text" name="np_default_payment_type" value="<?php echo esc_attr($value); ?>" />
    <?php
}


function np_default_number_of_seats_form() {
    $value = get_option('np_default_number_of_seats');
    ?>
    <input type="text" name="np_default_number_of_seats" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_default_type_delivery_form() {
    $value = get_option('np_default_type_delivery');
    ?>
    <input type="text" name="np_default_type_delivery" value="<?php echo esc_attr($value); ?>" />
    <?php
}

function np_settings_auto_update_order_status_form() {
    $value = get_option('np_settings_auto_update_order_status_np');
    ?>
    <input type="checkbox" name="np_settings_auto_update_order_status_np" <?php checked( $value, 'on' ); ?> />
    <?php
}

function np_settings_is_active_delivery_form() {
    $value = get_option('np_settings_is_active_delivery');
    ?>
    <input type="checkbox" name="np_settings_is_active_delivery" <?php checked( $value, 'on' ); ?> />
    <?php
}


function np_settings_generate_data_user_np_form(){ 
 $plugin_data = get_option('my_plugin_data');
 ?>

 <div class="row">
    <h4>SenderWarehouseIndex</h4>
    <p class="SenderWarehouseIndex"><?php echo $plugin_data['getWarehousesNPWarehouseIndex'] ? $plugin_data['getWarehousesNPWarehouseIndex'] : 'нет данных'; ?></p>
</div>

<div class="row">
    <h4>Sender</h4>
    <p class="Sender"><?php echo $plugin_data['senderRef'] ? $plugin_data['senderRef'] : 'нет данных'; ?></p>
</div>

<div class="row">
    <h4>CitySender</h4>
    <p class="CitySender"><?php echo $plugin_data['resultSenderCityRef'] ? $plugin_data['resultSenderCityRef'] :'нет данных'; ?></p>
</div>

<div class="row">
    <h4>SenderAddressNP</h4>
    <p class="SenderAddressNP"><?php echo $plugin_data['getWarehousesNPRef'] ? $plugin_data['getWarehousesNPRef'] : 'нет данных'; ?></p>
</div>

<div class="row">
    <h4>ContactSender</h4>
    <p class="ContactSender"><?php echo $plugin_data['ContactSenderRef'] ? $plugin_data['ContactSenderRef'] : 'нет данных'; ?></p>
</div>

<a href="?page=woocommerce-shipping-methods&tab=settings&update">Update</a>


<?php
}



if (isset($_GET['update'])) {
    $update_value = $_GET['update'];
    get_orders_np2();
} 


function get_orders_np2() {

	


	$np = new \LisDev\Delivery\NovaPoshtaApi2(get_option('np_settings_apiKey'));

	$result = $np->getCounterparties('Sender');
	$resultSenderCity = $np->getCities(0, null, get_option('np_settings_city_sender_form'));


	
	if($result['success'] == true && $resultSenderCity['success'] == true){
		$senderRef = $result['data'][0]['Ref'];

		$ContactSender = $np->getCounterpartyContactPersons($senderRef);


		$resultSenderCityRef = $resultSenderCity['data'][0]['Ref'];
		
		$getWarehousesNP = $np->getWarehouses($resultSenderCityRef, null, null, get_option('np_settings_address_np_form'));

		if($getWarehousesNP['success'] == true && $ContactSender['success'] == true){
			
			$getWarehousesNPRef =  $getWarehousesNP['data'][0]['CityRef'];
			$getWarehousesNPWarehouseIndex =  $getWarehousesNP['data'][0]['WarehouseIndex'];

			$ContactSenderRef = $ContactSender['data'][0]['Ref'];


			// Собираем все данные в массив
            $plugin_data = array(
                'apiKey' => get_option('np_settings_apiKey'),
                'senderPhone' => get_option('np_settings_phone'),
                'senderRef' => $senderRef,
                'ContactSenderRef' => $ContactSenderRef,
                'resultSenderCityRef' => $resultSenderCityRef,
                'getWarehousesNPRef' => $getWarehousesNPRef,
                'getWarehousesNPWarehouseIndex' => $getWarehousesNPWarehouseIndex
            );

            // Сохраняем данные в базе данных WordPress
            update_option('my_plugin_data', $plugin_data);

        }
    }

   	wp_die(); // Обязательно для завершения выполнения скрипта
   }

add_action('wp_ajax_get_orders_np2', 'get_orders_np2'); // Зарегистрированные пользователи
add_action('wp_ajax_nopriv_get_orders_np2', 'get_orders_np2'); // Неавторизованные пользователи
?>
