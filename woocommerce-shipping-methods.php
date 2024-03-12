<?php
/*
Plugin Name: WooCommerce Shipping Methods
Description: Плагін управління замовленнями Нової пошти. Створення експрес накладних, друк накладних, зміна стану замовлення.
Version: 1.0
Author: Krainik Serhii
*/
include 'function.php';
include 'backend/GetWooOrders.php';
include 'tabs/Settings.php';



/**
 * Активация плагина
 * **/
function display_shipping_methods_activate() {
    createNewRole();  
    grant_woocommerce_settings_access();
    createTable();
}

register_activation_hook(__FILE__, 'display_shipping_methods_activate');


// Добавляем пункт меню в раздел WooCommerce
add_action('admin_menu', 'woocommerce_shipping_methods_menu');

function woocommerce_shipping_methods_menu() {
    // add_submenu_page(
    //     'woocommerce',
    //     'Shipping Methods',
    //     'Створити експрес-накладну',
    //     'manage_woocommerce',
    //     'woocommerce-shipping-methods',
    //     'display_shipping_methods'
    // );


    add_menu_page(
        "Створити експрес-накладну", // Заголовок страницы в меню
        "Створити експрес-накладну", // Текст ссылки на страницу
        "manage_my_plugin", // Роль пользователя, который может просматривать эту страницу
        "woocommerce-shipping-methods", // Уникальный идентификатор страницы
        "display_shipping_methods", // Функция, которая будет вызвана при выводе страницы
        "dashicons-clipboard", // Иконка, которая будет использоваться в меню
        30 // Позиция пункта меню в списке
    );
}


// Выводим список методов доставки
function display_shipping_methods() {
    if (isset($_GET['tab'])) {
        getSettingPlugin();
    } else if(isset($_GET['update'])){
     echo "::";
 } else {
    include 'front/index.php';
    if(get_option('my_plugin_data') == false){
        echo '<div class="senderData" style="display: none;"></div>';
    }else{

        echo '<div class="senderData" style="display: none;">'.json_encode(get_option('my_plugin_data')).'</div>';
    }
    echo '<div class="orders">';
    generateList(get_orders_np());
    echo '</div>';
} 
 
}

function get_orders_np() {
    $get_woo_orders = new GetWooOrders();
    return $get_woo_orders->getOrdersNP();
}

function change_order_status(){
    $orders_ids = $_POST['order_ids'];
    $orders = json_decode($orders_ids); // Преобразует JSON строку в массив

    // Используем цикл foreach для итерации по массиву
    foreach ($orders as $id) {

        $order = wc_get_order($id);

        // Проверяем, существует ли заказ с указанным номером
        if ($order) {
            // Устанавливаем статус заказа на "Выполнено"
            $order->update_status('completed');
        }
    }

    wp_die();
}

add_action('wp_ajax_get_change_order_status', 'change_order_status'); // Зарегистрированные пользователи
add_action('wp_ajax_nopriv_get_change_order_status', 'change_order_status'); // Неавторизованные пользователи


function createNewRole(){
    remove_role( 'seller' );
    add_role('seller', __('Продавець'), array(
        'read' => true,
        'manage_options' => false,
        'edit_dashboard' => true,
        'remove_users' => true,
        'promote_users' => true,
        'delete_users' => true,
        'edit_users' => true,
        'unfiltered_html' => true,
        'edit_posts' => true,
        
    )
);
}



function grant_woocommerce_settings_access() {
    //     $user = wp_get_current_user();
    //     $user->remove_cap('manage_woocommerce');
    //     $user->remove_cap('woocommerce-shipping-methods');

    $roles = wp_roles();
    $roles->add_cap( 'administrator', 'manage_my_plugin' );
    $roles->add_cap( 'seller', 'manage_my_plugin' );
}
?>
