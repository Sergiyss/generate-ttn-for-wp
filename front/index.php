
<?php
include 'card.php';

function generateList($list){
    // Цикл для обхода каждого объекта в массиве
    foreach ($list as $order) {
        card($order, get_option('np_settings_is_active_delivery'));
    }
}

?>


<!-- Подключаем Axios через CDN -->
<!-- <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css"
/>
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
/>



<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.8.6/showdown.min.js"></script>
<script src="https://unpkg.com/bulma-toast"></script>

<div class="header" id="header">
    <div class="header_r r_row">
       
        <a class="tab_settings" href="?page=woocommerce-shipping-methods&tab=settings">⚙️ Налаштунки</a>
        <a class="tab_history" href="?page=woocommerce-shipping-methods&history">✍️ Історія</a>
        <?php if(!get_option('np_settings_auto_update_order_status_np')) {?>
            <button class="udpdate_order_statys" onclick="changeOrderStatus()"><p>🆙 Оновити статус</p></button>
        <?php }; ?>
        <button class="print_btn" onclick="setPdfTTNs('<?php echo get_option('np_settings_auto_update_order_status_np'); ?>')"><p>🖨 Роздрукувати ТТН</p></button>
        <div class="send_generate_ttn">
            <p>👨‍🎨 Створити накладні</p>
        </div>
    </div>
</div>


<div class="box" style="display: none;">
     <div class="loader"></div>
</div>

<div class="container_form"></div>


<div class="modal_np" id="myModal" style="display: none;">
  <div class="modal-content_np">
    <div class="contact_form">
        <h3 class="title_h3_form">Знайдені адреси доставки для клієнта:</h3>
        <select id="addressSelect">
        <option value="">Виберіть адресу</option>
        </select>

        <input id="IdentifierREF" type="string" style="display: none;">
      
        <input type="string" id="CityRef" style="display: none;">
      
        <input type="string" id="CardID" style="display: none;">
      
        <input type="string" id="RediendAddress" style="display: none;">
        <br>
        
            <h3 class="title_h3_form">Якщо немає потрібної адреси, то потрібно ввести нові вручну</h3>
        

        <input type="text" id="searchInput" placeholder="Введіть вулицю ">
        <select id="searchAddressSelect"></select>
        <div class="row">
            
        <input type="text" id="domInput" placeholder="Номер будинку">
        <br>
        <input type="text" id="streetInput" placeholder="Номер квартири">
        </div>
        

    </div>

    <span class="close_np done">Застосувати</span>
    <span class="close_np_form">X</span>
  </div>
</div>


<script src="<?php echo plugin_dir_url('') . 'nova-poshta-renerate-ttn/assets/js/frontend.js'; ?>"></script>
<script src="<?php echo plugin_dir_url('') . 'nova-poshta-renerate-ttn/assets/js/delivery_warehouse_doors.js'; ?>"></script>