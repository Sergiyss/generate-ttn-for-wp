
<?php
include 'card.php';

function generateList($list){
    // Цикл для обхода каждого объекта в массиве
    foreach ($list as $order) {
        card($order);
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

<link
  rel="stylesheet"
  href="<?php echo plugin_dir_url('') . 'nova-poshta-renerate-ttn/assets/css/style.css'; ?>"/>


<script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.8.6/showdown.min.js"></script>
<script src="https://unpkg.com/bulma-toast"></script>

<div class="header" id="header">
    <div class="header_r r_row">
        <a class="tab_settings" href="?page=woocommerce-shipping-methods&tab=settings">⚙️ Налаштунки</a>
        <button class="udpdate_order_statys" onclick="changeOrderStatus()"><p>🆙 Оновити статус</p></button>
        <button class="print_btn" onclick="setPdfTTNs()"><p>🖨 Роздрукувати ТТН</p></button>
        <div class="send_generate_ttn">
            <p>👨‍🎨 Створити накладні</p>
        </div>
    </div>
</div>


<div class="box" style="display: none;">
     <div class="loader"></div>
</div>




<script src="<?php echo plugin_dir_url('') . 'nova-poshta-renerate-ttn/assets/js/frontend.js'; ?>"></script>
