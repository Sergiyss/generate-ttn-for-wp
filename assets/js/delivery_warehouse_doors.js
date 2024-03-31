
    
    document.addEventListener('DOMContentLoaded', function() {
            // Получаем все чекбоксы в карточках
        var checkboxes = document.querySelectorAll('.card input[type="checkbox"]');
        var modal = document.getElementById("myModal");
        var closeButton = document.getElementsByClassName("close_np")[0];
        var closeButtonForm = document.getElementsByClassName("close_np_form")[0];
            // Добавляем обработчик события для каждого чекбокса
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var cardId = this.closest('.card').id;
                console.log('ID карточки:', cardId);
                document.getElementById("CardID").value = cardId;
    
                if(this.id === "delivery_to_address_block"){
                    if (this.checked) {
                        // Если чекбокс отмечен
                        document.getElementById('searchInput').value = ""
                        document.getElementById('streetInput').value = ""
                        document.getElementById('domInput').value = ""
                        document.getElementById("searchAddressSelect").innerHTML = ""
                        document.getElementById("addressSelect").innerHTML = ""

                        setInfoCardID(cardId);
                        modal.style.display = "block";
                    } else {
                        visibleBlock(cardId, false, '.delivery_to_address');
                        modal.style.display = "none";
                    }
                }
            });
        });
        closeButtonForm.onclick = function() {
            modal.style.display = "none";
        }
        closeButton.onclick = function() {
            var rediendAddress = document.getElementById("RediendAddress").value
            var addressSelect = document.getElementById('addressSelect').value
            //Если ручной ввод адреса доставки, то он будет в приоритете 
            var searchAddressSelect = document.getElementById('searchAddressSelect').value 
            searchAddressSelect.trim() !== ""

            var cardID = document.getElementById('CardID').value;

            if(searchAddressSelect.trim() !== ""){
                document.getElementById(cardID).querySelector('.warehouse_ref').textContent = searchAddressSelect;
                seveNewAddress();
            }else if(addressSelect.trim() !== ""){
                document.getElementById(cardID).querySelector('.warehouse_ref').textContent = addressSelect;
            }

            modal.style.display = "none";
        }
        
    });
    

    function setInfoCardID(cardID){
        var senderData = document.getElementsByClassName('senderData')[0].textContent
        
        var card = document.getElementById(cardID);
    
        // Получаем данные из каждого элемента "card"
        var id = card.id;
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
    
        /**
         * Если доставка на адресс
         * */
        var deliveryToAddressCheckbox  = card.querySelector('#delivery_to_address_block').checked;

        ////
        var senderObject = JSON.parse(senderData);
    
    
        // Создаем объект с данными, включая параметр action
        var data = {
            action              : 'your_action_name', // Замените 'your_action_name' на имя вашего обработчика AJAX в WordPress
            id                  : id,
            index               : 0,
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
            //Если на адресс
            deliveryToAddressCheckbox : deliveryToAddressCheckbox,
    
    

            state : 'find',
        };
    
          // Преобразуем объект в JSON
        var jsonData = JSON.stringify(data);
    
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/wp-content/plugins/nova-poshta-renerate-ttn/backend/DeliveryWarehouseDoors.php');
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onload = function() {
    
                if (xhr.status === 200) {
                    var jsonObject = JSON.parse(xhr.responseText);
                    document.getElementById('IdentifierREF').value = jsonObject.identifierREF;
                    
                    if(jsonObject.data['success'] == true){
                            var selectElement = document.getElementById("addressSelect");
                            selectElement.innerHTML = '';
                            jsonObject.data['data'].forEach((item, index) => {

                                var option = document.createElement("option");
                                option.value = item['Ref'];
                                option.text = item['Description'];
                                selectElement.appendChild(option);
                            });
                     
                        //Даже если и есть список адресов, то может клиент хочет добавить другой...
                        document.getElementById('CityRef').value = cityRef;
                      }else{
                        document.getElementsByClassName('add_new_addpress')[0].style.display = "block";
                        document.getElementById('CityRef').value = cityRef;

                        
                        
                        setInditificatorSuccess(jsonObject.message, false);
    
                         jsonObject.data['errors'].forEach((item, index) => {
    
                            displayToast('Замовлення #'+jsonObject.message+': '+item, 'Bottom Left', types[5])
                        });

                     }
    
                }else{
                    displayToast('Помилка сервера '+xhr.status, 'Bottom Left', types[5]);
                }
    
                };
                xhr.onerror = function() {
                    console.error('Ошибка при отправке данных');
                    displayToast('Помилка сервера '+xhr.status, 'Bottom Left', types[5]);
                };
                xhr.send(jsonData);
            
}
    
document.addEventListener('DOMContentLoaded', function() {

    var sityRef =  document.getElementById('CityRef').value 
    
    searchInput.addEventListener('input', function() {
        var inputValue = this.value.trim();
        if (inputValue.length >= 4) {
            sendSearchRequest(inputValue);
        }
    });

    
    function sendSearchRequest(query) {
        var senderData = document.getElementsByClassName('senderData')[0].textContent;
        var identifierREF = document.getElementById('IdentifierREF').value;
        var sityRef =  document.getElementById('CityRef').value 

        var searchInput = document.getElementById("searchInput");
        var addressSelect = document.getElementById("searchAddressSelect");

        var senderObject = JSON.parse(senderData);
        
        var data = {
            action              : 'your_action_name', // Замените 'your_action_name' на имя вашего обработчика AJAX в WordPress
            id                  : 257,
            index               : 0,
            apiKey              : senderObject.apiKey,
            sityRef             : sityRef,
            query               : query,
            state               : 'search',
        };

    


        var jsonData = JSON.stringify(data);
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-content/plugins/nova-poshta-renerate-ttn/backend/CreateNewAddress.php');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                addressSelect.innerHTML = "";

                var jsonObject = JSON.parse(xhr.responseText);
                 jsonObject.data['data'].forEach((item, index) => {
                    var option = document.createElement("option");
                    option.value = item['Ref'];
                    option.text = item['Description'];
                    addressSelect.appendChild(option);
                 });
            }
        };
        xhr.onerror = function() {
            console.error('Ошибка при отправке запроса');
        };
        xhr.send(jsonData);
    }
});



//Сохраняю новый адрес

    function seveNewAddress(query) {
        var modal = document.getElementById("myModal"); //Если всё ок, то закрываю, диалоговое окно
        var senderData = document.getElementsByClassName('senderData')[0].textContent;        
        var senderObject = JSON.parse(senderData);

        var counterpartyRef =  document.getElementById('IdentifierREF').value
        var streetRef =  document.getElementById('searchAddressSelect').value
        var buildingNumber = document.getElementById('domInput').value
        var flat =  document.getElementById('streetInput').value
          //Результат его адреса доставки
        var recipientAddress = document.getElementById("RediendAddress");

   
        var data = {
            apiKey             : senderObject.apiKey,
            counterpartyRef    : counterpartyRef,
            streetRef          : streetRef,
            buildingNumber     : buildingNumber,
            flat               : flat,
       
        };
  
    
        var jsonData = JSON.stringify(data);
    
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/wp-content/plugins/nova-poshta-renerate-ttn/backend/SaveNewAddress.php');
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var jsonObject = JSON.parse(xhr.responseText);
                recipientAddress.value = jsonObject.data['data'][0]['Ref'];
                
                var cardID = document.getElementById('CardID').value;
                
                document.getElementById(cardID).querySelector('.warehouse_ref').textContent = jsonObject.data['data'][0]['Ref'];

                modal.style.display = "none";
            }
        };
        xhr.onerror = function() {
            console.error('Ошибка при отправке запроса');
        };
        xhr.send(jsonData);
    }

