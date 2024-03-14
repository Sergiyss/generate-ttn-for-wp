const types = [
    'is-primary',
    'is-link',
    'is-info',
    'is-success',
    'is-warning',
    'is-danger',
    ]

const DocumentRefs = []; //После создания ттнки, приходит его ферка и инт документ.

const IntDocNumbers = [];

const OrderSuccess = []; //Те заказы, которые отработали на 200


// Находим элемент с id "send_generate_ttn"
var sendBtn = document.getElementsByClassName('send_generate_ttn')[0];
if (sendBtn) {
    // Добавляем обработчик события клика на этот элемент
    sendBtn.addEventListener('click', function() {
        //document.querySelector('.loader').style.display = 'block';
        create_ttn();
    });
}



function hideLoader() {
    document.querySelector('.loader').style.display = 'none';
}


function create_ttn(){

    var senderData = document.getElementsByClassName('senderData')[0].textContent

    if(senderData === ""){
        displayToast('Для початку потрібно в налаштування ввести дані відправника.', 'Bottom Left', types[4])
        return;
    }

    var cards = document.querySelectorAll('.orders .card');

    cards.forEach(function(card, index) {

        console.log('INDEX');
         console.log(index);

      // Получаем данные из каждого элемента "card"
        var id = card.id;

        if(card.querySelector('.ttn_code').textContent === ""){

            var firstLastName = card.querySelector('.first_last_name').textContent;
            var phone = card.querySelector('.phone').textContent;
            var weight = card.querySelector('#weight').value;
            var addressNp = card.querySelector('.address_np').textContent;
            var price = card.querySelector('#price').value;
            var totoal_order = card.querySelector('#total_order').value;
            var cityRef = card.querySelector('.city_ref').textContent;
            var warehouseRef = card.querySelector('.warehouse_ref').textContent;
        //**Другие настройки доставки*//
            var postomatCheckbox  = card.querySelector('#postomat').checked;
            
            var deliveryCheckbox  = card.querySelector('#delivery');
            if (deliveryCheckbox !== null) {
                deliveryCheckbox = deliveryCheckbox.checked;
            } else {
                deliveryCheckbox = false;
            }
        /***/

            var note_for_order = card.querySelector('#note_for_order').value;
        /***/
            var volumetricVolume = card.querySelector('#volumetric_volume').value;
            var volumetricWidth = card.querySelector('#volumetricWidth').value;
            var volumetricLength = card.querySelector('#volumetricLength').value;
            var volumetricHeight = card.querySelector('#volumetricHeight').value;


        //Другие настройки

            var payment_type = card.querySelector('#payment_type').value;
            var who_pays_for_shipping = card.querySelector('#who_pays_for_shipping').value;
            var number_of_seats = card.querySelector('#number_of_seats').value;
            var type_delivery = card.querySelector('#type_delivery').value;



            var senderObject = JSON.parse(senderData);


          // Создаем объект с данными, включая параметр action
            var data = {
            action              : 'your_action_name', // Замените 'your_action_name' на имя вашего обработчика AJAX в WordPress
            id                  : id,
            index               : index,
            apiKey              : senderObject.apiKey,
            senderPhone         : senderObject.senderPhone,
            senderRef           : senderObject.senderRef,
            contactSenderRef    : senderObject.ContactSenderRef,
            resultSenderCityRef : senderObject.resultSenderCityRef,
            getWarehousesNPRef            : senderObject.getWarehousesNPRef,
            getWarehousesNPWarehouseIndex : senderObject.getWarehousesNPWarehouseIndex,
            firstLastName       : firstLastName,
            totoalOrder         : totoal_order,
            addressNp           : addressNp,
            phone               : phone,
            weight              : weight,
            price               : price,
            cityRef             : cityRef,
            warehouseRef        : warehouseRef,
            note_for_order      : note_for_order,
            deliveryCheckbox    : deliveryCheckbox,
            postomatCheckbox    : postomatCheckbox,
            volumetricVolume    : volumetricVolume,
            volumetricWidth     : volumetricWidth,
            volumetricLength    : volumetricLength,
            volumetricHeight    : volumetricHeight,
            weight              : weight,
            paymentType         : payment_type,
            whoPaysForShipping  : who_pays_for_shipping,
            numberOfSeats       : number_of_seats,
            typeDelivery        : type_delivery,
        };

          // Преобразуем объект в JSON
        var jsonData = JSON.stringify(data);
        setTimeout(function() {
            // Отправляем данные на сервер
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/wp-content/plugins/nova-poshta-renerate-ttn/backend/Callback.php');
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.onload = function() {

            if (xhr.status === 200) {
                var jsonObject = JSON.parse(xhr.responseText);

                if(jsonObject.data['success'] == true){
                      setInditificatorSuccess(jsonObject.message, true);
                      OrderSuccess.push(jsonObject.message); //Передаю его ID
                      insertTTN(jsonObject.message, jsonObject.data);
                      displayToast('Замовлення #'+jsonObject.message+': Створено', 'Bottom Left', types[3])
                  }else{
                     setInditificatorSuccess(jsonObject.message, false);

                     jsonObject.data['errors'].forEach((item, index) => {

                        displayToast('Замовлення #'+jsonObject.message+': '+item, 'Bottom Left', types[5])
                    });

                 }
                 //По завершению
                console.log(cards.length+" >> Last "+index);
                // if (index === cards.length-1) {
                //     displayToast('Готово, змінюю статус замовлення', 'Bottom Left', types[3])
                //     inset_data_base_link_generate_ttn()
                // }

            }else{
                displayToast('Помилка сервера '+xhr.status, 'Bottom Left', types[5]);
            }

        };
        xhr.onerror = function() {
            console.error('Ошибка при отправке данных');
            displayToast('Помилка сервера '+xhr.status, 'Bottom Left', types[5]);
        };
        xhr.send(jsonData);
    }, index * 500);
    }else{
      displayToast('Ви вже створили накладну для #'+id, 'Bottom Left', types[4])

  }
});

document.querySelector('.loader').style.display = 'none';
}



function insertTTN(id, dataObject) {
  var card = document.getElementById(id);
  if (card) {
    if ('IntDocNumber' in dataObject.data[0] && 'Ref' in dataObject.data[0]) {
      DocumentRefs.push(dataObject.data[0]['Ref']);
      IntDocNumbers.push(dataObject.data[0]['IntDocNumber']);

      var selector = card.querySelector('.ttn_code');
      selector.innerText = dataObject.data[0]['IntDocNumber'];


      console.log(dataObject.data[0]['IntDocNumber']);
      document.querySelector('.ttn_block').style.display = 'block';
  }
}
}


function setInditificatorSuccess(id, is_success){
  // Получаем элемент с id "5352"
  var card5352 = document.getElementById(id);

  if (card5352) {
      // Получаем элемент с классом "order_numbe" внутри элемента "card5352"
      var orderNumber5352 = card5352.querySelector('.status');
      
      // Проверяем, что элемент найден
      if (orderNumber5352) {

          if(is_success == true){
            orderNumber5352.style.height = '16px';
            orderNumber5352.style.width = '16px';
            orderNumber5352.style.background = '#15d715';
            orderNumber5352.style.borderRadius = '50px';
            orderNumber5352.style.marginLeft = '10px';
        }else{
          orderNumber5352.style.height = '16px';
          orderNumber5352.style.width = '16px';
          orderNumber5352.style.background = '#ff0000';
          orderNumber5352.style.borderRadius = '50px';
          orderNumber5352.style.marginLeft = '10px';
      }
  }
}
}


//Пересчет метров кубических в заказе

function volumetricVolume(id, value){
    var card = document.getElementById(id);
    if (card) {
        var findCard = card.querySelector('#volumetric_volume');
        
        if (findCard) {
            findCard.value = value;
        }
    }
}


function visibleBlock(id, is_show, className){
  // Получаем элемент с id "5352"
  var card5352 = document.getElementById(id);

  if (card5352) {
      // Получаем элемент с классом "order_numbe" внутри элемента "card5352"
      var findCard = card5352.querySelector(className);
      
      // Проверяем, что элемент найден
      if (findCard) {
          if (is_show) {
                findCard.classList.remove('hidden'); // Удаление класса hidden, чтобы показать элемент
            } else {
                findCard.classList.add('hidden'); // Добавление класса hidden, чтобы скрыть элемент
            }
        }
    }
}



/*Печать ттнак**/

function setPdfTTN(id){

  var card = document.getElementById(id);
  if (card) {
    var getTTN = card.querySelector('.ttn_code').innerText;
    console.log(id + " "+getTTN);
}



}

function setPdfTTNs(on_status_order){


    var senderData = document.getElementsByClassName('senderData')[0].textContent
    if(on_status_order === "on"){
       displayToast('Автоматична зміна статусу замовлення після друку, увімкнена!', 'Bottom Left', types[3])
   }
   if(senderData === ""){
    displayToast('Для початку потрібно в налаштування ввести дані відправника.', 'Bottom Left', types[4])
    return;
}

var senderObject = JSON.parse(senderData);

if (DocumentRefs.length !== 0) {


     inset_data_base_link_generate_ttn()

  var data = {
          action: 'generate_ttn', // Замените 'your_action_name' на имя вашего обработчика AJAX в WordPress
          id:1,
          apiKey : senderObject.apiKey,
          ttns: DocumentRefs,
      };
      
      // Преобразуем объект в JSON
      var jsonData = JSON.stringify(data);

        // Отправляем данные на сервер
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'https://www.nhnl.com.ua/wp-content/plugins/nova-poshta-renerate-ttn/backend/CallbackPrintTtn.php');
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.onload = function() {
        if (xhr.status === 200) {
            var jsonObject = JSON.parse(xhr.responseText);

            if(jsonObject.data['success'] == true){
                displayToast('Зачекайте, триває підготовка даних для друку', 'Bottom Left', types[3])
                
                if(on_status_order === "on"){
                    displayToast('Готово, змінюю статус замовлення', 'Bottom Left', types[3])
                    changeOrderStatus();
                }

                var link = jsonObject.data['data'][0];
                window.open(link, "_blank");
            }else{
                displayToast('Сталася помилка: '+jsonObject.data['errors'], 'Bottom Left', types[5])
            }
            console.log(jsonObject);

        } else {
            console.error('Ошибка при отправке данных. Статус: ' + xhr.status);
            if (index === cards.length) { hideLoader();}
        }
    };
    xhr.onerror = function() {
        console.error('Ошибка при отправке данных');
        if (index === cards.length) { hideLoader();}
    };
    xhr.send(jsonData);

}else{
    displayToast('Немає даних для друку. Для початку потрібно створити ттнки', 'Bottom Left', types[4])
}

}

function displayToast(message, position, type) {
    bulmaToast.toast({
      message: message,
      type: type,
      position: position.toLowerCase().replace(' ', '-'),
      dismissible: true,
      duration: 4000,
      pauseOnHover: true,
      animate: { in: 'fadeIn', out: 'fadeOut' },
  })
}




/**
 * Настройки пользователя
 * */
function setUserData(){



  let form_data = new FormData();
  form_data.append('action', 'get_orders_np2');

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/wp-admin/admin-ajax.php');
  xhr.onload = function() {
    if (xhr.status === 200) {
      const dataArray = JSON.parse(xhr.responseText);


      location.reload();

  } else {
      console.error('Request failed. Status: ' + xhr.status);
  }
};
xhr.onerror = function() {
    console.error('Request failed');
};
xhr.send(form_data);
}

/*
Смена статуса заказа
*/
function changeOrderStatus(){

    if (OrderSuccess.length !== 0) {

        var orders = "";
        if(OrderSuccess.length == 1){
            orders = OrderSuccess[0] + ",";
        }else{
         // Преобразование массива в строку с разделителем ","
            orders = OrderSuccess.join(",");
        }
        
        let form_data = new FormData();
        form_data.append('action', 'change_order_status');
        form_data.append('order_ids', orders); // Пример параметра order_id

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-admin/admin-ajax.php');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const dataArray = JSON.parse(xhr.responseText);

                displayToast('Зроблено. Дочекайтеся оновлення сторінки', 'Bottom Left', types[3])
                
                location.reload();

            } else {
                console.error('Request failed. Status: ' + xhr.status);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send(form_data);
    }else{
        displayToast('Немає даних. Для початку потрібно створити ттнки', 'Bottom Left', types[4])
    }
}






document.addEventListener('DOMContentLoaded', function() {
        // Получаем все чекбоксы в карточках
    var checkboxes = document.querySelectorAll('.card input[type="checkbox"]');

        // Добавляем обработчик события для каждого чекбокса
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            var cardId = this.closest('.card').id;
            console.log('ID карточки:', cardId);

            if(this.id === "postomat"){
                if (this.checked) {
                        // Если чекбокс отмечен
                    console.log('Чекбокс ' + this.id + ' отмечен');
                    visibleBlock(cardId, true, '.descript');
                        // Добавьте здесь ваш код, который должен выполниться при отмеченном чекбоксе
                } else {
                    visibleBlock(cardId, false, '.descript');
                        // Если чекбокс снят
                    console.log('Чекбокс ' + this.id + ' снят');
                        // Добавьте здесь ваш код, который должен выполниться при снятом чекбоксе
                }
            }


            if(this.id === "other_settings"){
                if (this.checked) {
                        // Если чекбокс отмечен
                    console.log('Чекбокс ' + this.id + ' отмечен');
                    visibleBlock(cardId, true, '.other_settings_block');
                        // Добавьте здесь ваш код, который должен выполниться при отмеченном чекбоксе
                } else {
                    visibleBlock(cardId, false, '.other_settings_block');
                        // Если чекбокс снят
                    console.log('Чекбокс ' + this.id + ' снят');
                        // Добавьте здесь ваш код, который должен выполниться при снятом чекбоксе
                }
            }
        });
    });
});




document.addEventListener('DOMContentLoaded', function() {
    // Получаем все поля ввода текста
    var inputs = document.querySelectorAll('.form_style');

    // Добавляем обработчик события для каждого поля ввода
    inputs.forEach(function(input) {

        input.addEventListener('input', function() {
            var cardId = this.closest('.card').id;
            
            var width = parseFloat(document.getElementById('volumetricWidth').value);
            var length = parseFloat(document.getElementById('volumetricLength').value);
            var height = parseFloat(document.getElementById('volumetricHeight').value);
            
            var volume = width * length * height;
            console.log('Объем посылки:', volume);


            var width = parseFloat(document.getElementById('volumetricWidth').value);
            var length = parseFloat(document.getElementById('volumetricLength').value);
            var height = parseFloat(document.getElementById('volumetricHeight').value);
            
            var volume = (width * length * height) / 1000000;
            
            // Проверяем, чтобы значение объема не было меньше 0.01 м³
            if (volume < 0.0004) {
                volume = 0.0004; // Если меньше 0.01, устанавливаем значение объема равным 0.01 м³
            }
            

            volumetricVolume(cardId, volume.toFixed(4));

            console.log('Объем посылки:', volume.toFixed(4) + ' м³ '+cardId); // Выводим значение объема с двумя знаками после запятой
        });
    });
});



/**
 * Всё для работы с историяй
 * */

function inset_data_base_link_generate_ttn(){
     if (OrderSuccess.length !== 0 && DocumentRefs.length !==0) {
        
        var orders_ids = OrderSuccess;
        var document_refs = DocumentRefs;

        let form_data = new FormData();
        form_data.append('action', 'insert_data_base');
        form_data.append('order_ids', orders_ids); // Пример параметра order_id
        form_data.append('order_ttn_refs', document_refs); // Пример параметра order_id
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-admin/admin-ajax.php');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const dataArray = JSON.parse(xhr.responseText);

                // if(dataArray.status)



                if(dataArray.status === "success"){

                }else{

                    displayToast(dataArray.data, 'Bottom Left', types[5])
                }

            } else {

                // displayToast(jsonObject.data['success'], 'Bottom Left', types[3])
                // console.error('Request failed. Status: ' + xhr.status);
            }
        };
        xhr.onerror = function() {
            displayToast('Request failed', 'Bottom Left', types[5])
        };
        xhr.send(form_data);
     }
}


function get_all_history_ttn(){
     //if (OrderSuccess.length !== 0 && DocumentRefs.length !==0) {
        
        var orders_ids = JSON.stringify(OrderSuccess);
        var document_refs = JSON.stringify(DocumentRefs);

        let form_data = new FormData();
        form_data.append('action', 'get_all_histoty_generate_ttn');
        // form_data.append('order_ids', orders_ids); // Пример параметра order_id
        // form_data.append('order_ttn_refs', document_refs); // Пример параметра order_id
        
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-admin/admin-ajax.php');
        xhr.onload = function() {
            if (xhr.status === 200) {
                const dataArray = JSON.parse(xhr.responseText);

                // if(dataArray.status)



                if(dataArray.status === "success"){
                    generate_table(dataArray)
                }else{

                    //displayToast(dataArray.data, 'Bottom Left', types[5])
                }

            } else {

                // displayToast(jsonObject.data['success'], 'Bottom Left', types[3])
                // console.error('Request failed. Status: ' + xhr.status);
            }
        };
        xhr.onerror = function() {
            displayToast('Request failed', 'Bottom Left', types[5])
        };
        xhr.send(form_data);
     //}
}







function generate_table(json_data){
    // Извлекаем данные из JSON
    const dataArray = json_data;

    // Получаем div контейнер
    const container = document.getElementsByClassName('container_form')[0];

    // Создаем таблицу
    const table = document.createElement('table');

    // Создаем заголовок таблицы
    const headerRow = document.createElement('tr');
    for (const key in dataArray.data[0]) {
        const th = document.createElement('th');
        th.textContent = key;
        headerRow.appendChild(th);
    }
    table.appendChild(headerRow);

    // Добавляем строки с данными
    dataArray.data.forEach(item => {

        console.log(item.order_link[0].length);

        const row = document.createElement('tr');
        for (const key in item) {
            const cell = document.createElement('td');
            cell.textContent = Array.isArray(item[key]) ? item[key].join(', ') : item[key];
            row.appendChild(cell);
        }
        table.appendChild(row);
    });

    // Вставляем таблицу в контейнер
    container.appendChild(table);

}