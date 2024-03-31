<?php 

function card ($list, $is_active_delivery) {
    $products_html = '';
    foreach ($list['products'] as $product) {
        $products_html .= '<li>'.$product.'</li>';
    }

    $street = explode(" ", $list['address_np']);
    $recipientHouse = "";
    if (isset($street[1])) {
        $recipientHouse = $street[1];
    }

    echo '
        <div id="'.$list['order_id'].'" class="card" style="position: relative;">
            <div class="colums_np">
				<div class="row image_svg">
					<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
						 width="184.000000pt" height="184.000000pt" viewBox="0 0 184.000000 184.000000"
						 preserveAspectRatio="xMidYMid meet">

						<g transform="translate(0.000000,184.000000) scale(0.100000,-0.100000)"
						fill="#ff0000" stroke="none">
						<path d="M706 1641 c-116 -116 -196 -203 -192 -210 5 -7 44 -11 105 -11 62 0
						101 -4 109 -12 8 -8 12 -60 12 -169 0 -200 -11 -189 187 -189 192 0 183 -9
						183 193 0 106 4 157 12 165 8 8 46 12 104 12 57 0 95 4 100 11 8 14 -384 409
						-406 409 -8 0 -105 -89 -214 -199z"/>
						<path d="M197 1132 c-108 -108 -197 -204 -197 -212 0 -15 388 -410 403 -410 4
						0 7 184 7 410 0 226 -3 410 -8 410 -4 0 -96 -89 -205 -198z"/>
						<path d="M1440 920 c0 -220 3 -400 7 -400 15 0 393 385 393 400 0 15 -378 400
						-393 400 -4 0 -7 -180 -7 -400z"/>
						<path d="M750 771 c-6 -11 -10 -88 -10 -178 0 -110 -4 -163 -12 -171 -8 -8
						-47 -12 -110 -12 -72 0 -98 -3 -98 -13 0 -17 382 -397 400 -397 18 0 400 380
						400 397 0 10 -25 13 -93 13 -59 0 -97 4 -105 12 -8 8 -12 61 -12 171 0 90 -4
						167 -10 178 -10 18 -23 19 -175 19 -152 0 -165 -1 -175 -19z"/>
						</g>
					</svg>

					<div class="order_numbe">
						<div class="row">
							<h2>Замовлення # '.$list['order_id'].' </h2>
							<input type="checkbox" id="is_generate_ttn" name="генерувати накладну" checked />
							<div class="status"></div>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="colums_np">
                    	<div class="recipient"><h3>Одержувач</h3></div>
                        <div class="contact_info">
                            <div class="row">
								<div class="placeholder">👨І.П.П:</div>
								<div class="first_last_name">'.$list['last_name'].' '.$list['first_name'].'</div>
							</div>
							<div class="row">
								<div class="placeholder">📱Телефон:</div>
								<div class="phone">'.$list['phone'].'</div>
							</div>
							<div class="row">
								<div class="placeholder pl_address">🏠Місто:</div>
								<div class="city">'.$list['city'].'</div>
							</div>
							<div class="row">
								<div class="placeholder pl_address">🏠Адреса НП:</div>
								<div class="address_np">'.$list['address_np'].'</div>
							</div>
                        </div>
                        <div class="product_list">
                            <h3>Список замовлення</h3>
                            <ul>
                                '.$products_html.'
                            </ul>
                            <div class="total_order_text" style="margin-top:10px; margin-bottom: 10px; background: #ff00000f; padding:5px; border-radius: 6px;">💸Вартість замовлення 
                            <input id="total_order" type="string" value="'.$list['total'].'" style="font-size: 18px; font-size: 18px; max-width: 75px !important;border: 0px;font-family: sans-serif; font-weight: 600;"> грн</div>
                        </div>
                    </div>


                <div class="row">
                    <input type="checkbox" id="delivery_to_address_block" name="delivery_to_address_block" />
                        <div class="info">
                            <p><strong>🚖Замовлення на адресу</strong></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                <input type="checkbox" id="postomat" name="postomat" />
					<div class="info">
						<p><strong>📮Замовлення через поштомат</strong></p>
					</div>
                </div>';
                if($is_active_delivery == true) {
                echo '<div class="row">
	                <input type="checkbox" id="delivery" name="delivery" />
	                <div class="info">
						<p>💸Накладений платіж?</p>
					</div>
                 </div>';
                 } ;
                echo '<div class="row">
	                <input type="checkbox" id="other_settings" name="other_settings" />
	                <div class="info">
						<p>🚚Інші налаштування доставки</p>
					</div>
                 </div>


                <div style="display: flex;align-items: flex-end;justify-content: flex-start;align-content: center;flex-wrap: wrap;">
                    <div class="weight colums_np">
                        <div class="t_weight">Вага (кг)</div>
                        <input id="weight" type="string" value="'. get_option('np_default_weight') .'" style="width: 100px; padding: 10px;">
                    </div>

                    <div class="price colums_np" style="margin-right: 1px;">
                        <div class="t_weight">об\'єм</div>
                        <input id="volumetric_volume" type="string" value="'.get_option('np_default_volumetric_volume').'" style="width: 100px; padding: 10px; margin-right: 10px;">
                    </div>

                    <div class="price colums_np">
                        <div class="t_price">Вартість (грн)</div>
                        <input id="price" type="string" value="'. get_option('np_default_parcel_price') .'" style="width: 100px; padding: 10px;">
                    </div>

                </div>

                <div class="weight colums_np">
                    <div class="t_note">✍️ Примітки до товару / Опис вантажу</div>
                    <input id="note_for_order" type="string" value="'.get_option('np_default_notepad').'" style="width: 100%; padding: 10px;">
                </div>

            </div>
            	<div class="delivery_to_address_block">
					<div class="delivery_to_address hidden">
						
					</div>
				</div>


				<div class="OptionsSeat">
					<div class="descript hidden">
						<h3 class="desc_text">Для доставки на поштомат потрібно ввести такі дані</h3>
						<p>Обмеження:</p>
						<ul>
							<li>Поштомат може бути лише відділенням одержувача;</li>
							<li>Відправляти на поштамат можна тільки типи вантажу Посилка (Parcel) та Документи (Documents);</li>
							<li>Максимальне значення оцінної вартості для відправки на поштомат (параметр Cost) – 10000 грн.;</li>
							<li>Максимально допустимі габарити вантажу: Ширина 40 см; Довжина 60 см; Висота 30 см;</li>
							<li>Максимально допустима вага вантажу 20 кг;</li>
							<li>При створенні відправлення на поштомат можна вказувати лише одне місце на одне відправлення.</li>
						</ul>

						<div class="row">
				           
				            <div class="weight colums_np" style="max-width: 120px;">
				                <div class="decs_input_text">ширина одного місця в см</div>
				                <input class="form_style" id="volumetricWidth" type="string" value="'.get_option('np_default_width').'" style="width: 100px; padding: 10px;">
				            </div>
		
				            <div class="weight colums_np" style="max-width: 120px;">
				                <div class="decs_input_text">довжина одного місця в см</div>
				                <input class="form_style" id="volumetricLength" type="string" value="'.get_option('np_default_length').'" style="width: 100px; padding: 10px;">
				            </div>
				           <div class="weight colums_np" style="max-width: 120px;">
				                <div class="decs_input_text">висота одного місця в см</div>
				                <input class="form_style" id="volumetricHeight" type="string" value="'.get_option('np_default_height').'" style="width: 100px; padding: 10px;">
				            </div>
				        
				        </div>
					</div>
				</div>
				<div class="other_settings">
					<div class="other_settings_block hidden">
						<h3 class="desc_text" >Інші додаткові налаштування</h3>

						<div class="row">
				            <div class=" colums_np">
				                <div class="decs_input_text">Тип оплати: NonCash|Cash</div>
				                <input class="form_style" id="payment_type" type="string" value="'.get_option('np_default_payment_type').'" style="width: 100px; padding: 10px;">
				            </div>
				            <div class=" colums_np">
				                <div class="decs_input_text">Хто оплачує за доставку: Recipient|Sender</div>
				                <input class="form_style" id="who_pays_for_shipping" type="string" value="'.get_option('np_default_who_pays_for_shipping').'" style="width: 100px; padding: 10px;">
				            </div>
				        </div>
				        <div class="row">
				            <div class=" colums_np">
				                <div class="decs_input_text">Кількість місць</div>
				                <input class="form_style" id="number_of_seats" type="string" value="'.get_option('np_default_number_of_seats').'" style="width: 100px; padding: 10px;">
				            </div>
				           <div class=" colums_np">
				                <div class="decs_input_text">Тип доставки: Parcel|Documents|Cargo</div>
				                <input class="form_style" id="type_delivery" type="string" value="'.get_option('np_default_type_delivery').'" style="width: 100px; padding: 10px;">
				            </div>
				        </div>
					</div>
				</div>


	           	<div class="city_ref" style="display: none;">'.$list['city_ref'].'</div>
				<div class="warehouse_ref" style="display: none;">'.$list['warehouse_ref']. '</div>



				<div class="ttn_block" style="padding-top: 30px;">
					<div class="row">
						<div class="ttn_code"></div>
					</div>
				</div>

        </div>
    ';
}
?>

