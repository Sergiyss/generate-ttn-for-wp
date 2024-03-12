<?php
function generate_form_data($historys, $apiKey){
    echo '<div class="container">
    	<div class="history">

    		<h2>Історія генерації накладних</h2>

			<a class="back_btn" href="/wp-admin/admin.php?page=woocommerce-shipping-methods">Повернутись</a>
    	</div>
        <table class="form_table">
            <thead class="form_thead">
                <tr class="form_tr">
                    <th class="form_th" style="width: 10%;">ID</th>
                    <th class="form_th" style="width: 30%;">Номер замовлення</th>
                    <th class="form_th" style="width: 30%;" >TTН REF:</th>
                    <th class="form_th"> Ссылка для печати: </th>
                    <th class="form_th" style="width: 10%;">Дата замовлення</th>
                </tr>
            </thead>
            <tbody>';
    
    foreach ($historys as $history) {

    	$link_on_product = explode(",", $history['order_number']);
    	$links = "";
    	foreach($link_on_product as $link){
			$links .=  '<a href="/wp-admin/admin.php?page=wc-orders&action=edit&id='.$link.'">'.$link.'</a>,';
    	}

        echo '
            <tr class="form_tr">
                <td class="form_td">' . $history['ID'] . '</td>
                <td class="form_td">' . $links . '</td>
                <td class="form_td">' . $history['order_link'] . '</td>
                <td class="form_td"> <a href="https://my.novaposhta.ua/orders/printMarking100x100/orders/' . $history['order_link'] . '/type/pdf/apiKey/'. $apiKey. '/zebra" target="_blank">Ссылка</a> </td>
                <td class="form_td">' . $history['order_date'] . '</td>
            </tr>';
    }

    echo '
            </tbody>
        </table>
        </div>';
}
?>