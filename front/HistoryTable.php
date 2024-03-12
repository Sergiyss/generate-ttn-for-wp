<?php
	function generate_form_data($historys){
		echo '


			<table border="1">
			    <thead>
			        <tr>
			            <th>ID</th>
			            <th>Номер заказа</th>
			            <th>Ссылка на заказ</th>
			            <th>Дата заказа</th>
			        </tr>
			    </thead>
			    <tbody>'.
			        
			            // Предположим, что у вас есть массив $ordersData с данными о заказах
			            foreach ($ordersData as $order) {
			                echo "<tr>";
			                echo "<td>" $historys['ID'] "</td>";
			                echo "<td>"$historys['order_number'] "</td>";
			                echo "<td><a href=" $historys['order_link'] ">" $historys['order_link'] "</a></td>";
			                echo "<td>" $historys['order_date'] "</td>";
			                echo "</tr>";
			            }
			        .'
			    </tbody>
			</table>

		';
	}
?>

