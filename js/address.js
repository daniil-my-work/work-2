class AddressAutocomplete {
    constructor(apiToken) {
        this.apiToken = apiToken;
        this.apiUrl = 'http://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';
    }

    async getFullAddress(value) {
        const targetValue = value;

        const options = {
            method: "POST",
            mode: "cors",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "Authorization": "Token " + this.apiToken
            },
            body: JSON.stringify({
                query: targetValue,
                count: 5,
                language: 'ru',
            })
        }

        try {
            const response = await fetch(this.apiUrl, options);
            const data = await response.json();
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return data;
        } catch (error) {
            console.error("Error fetching address data: ", error);
            return null;
        }
    }
}

class AddressInputHandler {
    constructor(addressInputSelector, addressAutocomplete) {
        this.userAddress = document.querySelector(addressInputSelector);
        this.autocomplete = addressAutocomplete;
        this.basketDeliveryList = document.querySelector('.basket__delivery-list');

        // Устанавливает город по умолчанию
        // this.userAddress.value = city;
        
        // Ставит слушатель на ввод символов в инпут
        this.inputEventListener();
    }

    inputEventListener() {
        if (this.userAddress) {
            this.userAddress.addEventListener('input', async (evt) => {
                const value = evt.target.value;
                console.log(value);
                const result = await this.autocomplete.getFullAddress(value);

                if (result && result.suggestions) {
                    this.displayAddressSuggestions(result.suggestions);

                    this.clickEventListener();
                } else {
                    this.basketDeliveryList.innerHTML = ''; // Очищаем список, если нет результатов или произошла ошибка
                }
            });
        }
    }

    displayAddressSuggestions(addressSuggestions) {
        this.basketDeliveryList.innerHTML = '';

        addressSuggestions.forEach((el) => {
            const item = `<li class="basket__delivery-item">${el.value}</li>`;
            this.basketDeliveryList.innerHTML += item;
        });
    }

    clickEventListener() {
        const clickHandler = (evt) => {
            const target = evt.target.textContent;
            this.userAddress.value = target;

            // Присвоить значение value атрибуту value элемента #user_address
            this.userAddress.setAttribute('value', target);

            // Удалить список адресов после выбора одного из них
            this.basketDeliveryList.innerHTML = '';

            // Удаляем слушатель после выполнения действия
            this.basketDeliveryList.removeEventListener('click', clickHandler);
        };

        // Добавляем слушатель события клик, который будет удален после первого клика
        this.basketDeliveryList.addEventListener('click', clickHandler);
    }
}


const addressAutocomplete = new AddressAutocomplete("dedcff8224572325aafcec43188c29827f93a657");

if (pageBasket) {
    new AddressInputHandler('#user_address', addressAutocomplete);
}
