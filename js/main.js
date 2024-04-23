
// Изменяет кол-во продуктов на странице Корзина
function addProductInBasketSecond(evt) {
    // Элементы
    const element = evt.target;
    const productItem = element.closest(".basket__item");
    const productCounterInput = productItem.querySelector(
        ".product-item__counter-input"
    );
    const productCounterNumber = productItem.querySelector(
        ".product-item__counter-number"
    );
    const counterValue = Number(productCounterInput.value);

    // Айди продукта
    const productDataId = productItem.dataset.productId;
    const tableName = productItem.dataset.tableName;

    // Формирование строки параметров
    const params = new URLSearchParams();
    params.append("productId", productDataId);
    params.append("tableName", tableName);

    // Уменьшает кол-во блюд
    const decButton = element.classList.contains(
        "product-item__counter-action--minus"
    );
    if (decButton) {
        console.log("Минус");

        const newValue = counterValue - 1;

        if (counterValue <= 1) {
            console.log("Минимальное значение 1");
            return;
        }

        // Обновляет данные в сессии
        params.append("quantity", newValue);
        apiUpdateProductList(params);
        // console.log(params.toString());

        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;

        return;
    }

    // Увеличивает кол-во блюд
    const plusButton = element.classList.contains(
        "product-item__counter-action--plus"
    );
    if (plusButton) {
        console.log("Плюс");
        const newValue = counterValue + 1;

        // Обновляет данные в сессии
        params.append("quantity", newValue);
        apiUpdateProductList(params);
        // console.log(params.toString());

        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;

        return;
    }

    const delButton =
        element.classList.contains("product-item__counter-button--basket") ||
        element.classList.contains("product-item__counter-button-icon--basket");
    if (delButton) {
        console.log("Удаляет элемент из Корзины");

        params.append("quantity", 0);
        apiUpdateProductList(params);
        // console.log(params.toString());

        productItem.remove();

        return;
    }
}


// Селект: способ доставки корзина
const deliveryType = document.querySelector('#delivery-type');
const basketCafe = document.querySelector('#basket-cafe-list');
const addressList = document.querySelector('#basket-delivery-list');
const basketDeliveryList = document.querySelector('.basket__delivery-list');


if (basketDeliveryList) {
    // Переместить обработчик события click за пределы функции getFullAddress
    basketDeliveryList.addEventListener('click', (evt) => {
        const target = evt.target.textContent;
        userAddress.value = target;

        // Присвоить значение value атрибуту value элемента #user_address
        userAddress.setAttribute('value', target);

        // Удалить список адресов после выбора одного из них
        basketDeliveryList.innerHTML = '';
    });
}

// Измененный вариант функции getFullAddress
function getFullAddress(value) {
    const url = "http://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
    const token = "dedcff8224572325aafcec43188c29827f93a657"; // Апи ключ
    const targetValue = "г Ярославль " + value;

    const options = {
        method: "POST",
        mode: "cors",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "Authorization": "Token " + token
        },
        body: JSON.stringify({
            query: targetValue,
            count: 5,
            language: 'ru',
        })
    }

    fetch(url, options)
        .then(response => response.json())
        .then(
            result => {
                basketDeliveryList.innerHTML = '';
                const addressResult = result.suggestions;

                addressResult.forEach((el) => {
                    const item = `<li class="basket__delivery-item">${el.value}</li>`;
                    basketDeliveryList.innerHTML += item;
                });
            }
        )
        .catch(error => console.log("error", error));
}

// Обработчик события input для поля userAddress
const userAddress = document.querySelector('#user_address');

if (userAddress) {
    userAddress.addEventListener('input', (evt) => {
        const value = evt.target.value;

        // Запрос для получения полного адреса
        getFullAddress(value);
    });
}


function setDeliveryType(evt) {
    const target = evt.target;

    if (target.value === 'default') {
        return;
    }

    // Сохраняем выбранный способ доставки в localStorage
    localStorage.setItem('deliveryMethod', target.value);

    if (target.value === 'delivery') {
        addressList.classList.remove('hidden');
        basketCafe.classList.add('hidden');
    } else {
        basketCafe.classList.remove('hidden');
        addressList.classList.add('hidden');
    }
}


// Прибавляет или убавляет кол-во блюд в заказе на странице Корзина
const basketList = document.querySelector(".basket__list");

const pageBasket = document.querySelector("#page-basket");
if (pageBasket) {
    console.log("Корзина");

    basketList.addEventListener("click", addProductInBasketSecond);

    deliveryType.addEventListener('change', setDeliveryType);

    // ====== TODO: Сделать сохранение в localStorage ======
    const deliveryMethod = localStorage.getItem('deliveryMethod');

    // Проверяем, сохранено ли значение deliveryMethod
    if (deliveryMethod !== null) {
        // Применяем сохраненное значение к элементу <select>
        deliveryType.value = deliveryMethod;

        // В зависимости от значения deliveryMethod скрываем или показываем элементы
        if (deliveryMethod === 'delivery') {
            addressList.classList.remove('hidden');
            basketCafe.classList.add('hidden');
        } else {
            basketCafe.classList.remove('hidden');
            addressList.classList.add('hidden');
        }
    }
}


// localStorage.clear();

const basketSum = document.querySelector('.basket__order-number');
const basketSumInput = document.querySelector('#total-price');

const sumOfPoke = {
    'protein': 0,
    'proteinAdd': 0,
    'filler': 0,
    'topping': 0,
    'sauce': 0,
    'crunch': 0,
};


// Преобразуем объект в строку JSON и сохраняем в localStorage
localStorage.setItem('constructorPokeSum', JSON.stringify(sumOfPoke));


function updatePokeSum(pokeObj) {
    localStorage.setItem('constructorPokeSum', JSON.stringify(pokeObj));
}

function updatePokeBasketSum() {
    const storedSumOfPoke = JSON.parse(localStorage.getItem('constructorPokeSum'));
    console.log(storedSumOfPoke); // Выведет сохраненный объект

    let result = 0;
    for (const key in storedSumOfPoke) {
        result += storedSumOfPoke[key];
    }

    basketSum.textContent = `${result} руб`;
    basketSumInput.value = result;
}


const shemaPokeNumber = {
    1: {
        'filler': 5,
        'toping': 1,
    },
    2: {
        'filler': 3,
        'toping': 2,
    },
};

localStorage.setItem('shemaPoke', 1);
const shemaPoke = document.querySelector('.constructor-poke-shema');
const fillerPokeItem = document.querySelector('#fillerCounter');
const topingPokeItem = document.querySelector('#topingCounter');


if (shemaPoke) {
    shemaPoke.addEventListener('click', (evt) => {
        const target = evt.target;

        const shemaItem = target.closest('.constructor-poke-shema-item');
        const shemaItemValue = shemaItem.dataset.shemaPoke;

        if (shemaItemValue === '1') {
            fillerPokeItem.textContent = `/ Осталось 5 из 5`;
            topingPokeItem.textContent = `/ Осталось 1 из 1`;

            localStorage.setItem('shemaPoke', 1);
        } else {
            fillerPokeItem.textContent = `/ Осталось 3 из 3`;
            topingPokeItem.textContent = `/ Осталось 2 из 2`;

            localStorage.setItem('shemaPoke', 2);
        }

        // Схеама из стореджа
        const number = localStorage.getItem('shemaPoke');

        // Очищает чекбоксы Наполнителя 
        let fillerItemNumber = 0;
        checkboxFillerList.forEach(fillerItem => {
            if (fillerItem.checked) {
                fillerItemNumber++;
            }
        });

        checkboxFillerList.forEach(fillerItem => {
            if (fillerItemNumber > shemaPokeNumber[number]['filler']) {
                fillerItem.checked = false;
            }
        });

        // Очищает чекбоксы Топинга  
        let topppingItemNumber = 0;
        checkboxTopingList.forEach(topppingItem => {
            if (topppingItem.checked) {
                topppingItemNumber++;
            }
        });

        checkboxTopingList.forEach(topppingItem => {
            if (topppingItemNumber > shemaPokeNumber[number]['toping']) {
                topppingItem.checked = false;
            }
        });
    });
}



// Селект Протеин
const selectProtein = document.querySelector('#constructor-poke__select--protein');

// Назначаем обработчик изменения состояния каждому чекбоксу
if (selectProtein) {
    selectProtein.addEventListener('change', (evt) => {
        const selectedIndex = evt.target.selectedIndex;
        const selectedOption = evt.target.options[selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        // Обновляет сумму в Хранилище
        sumOfPoke.protein = Number(price);
        updatePokeSum(sumOfPoke);
        updatePokeBasketSum();
    });
}


function changeFillerCountValue(number, diff) {
    // Заполняет данные об оставшемся кол-ве ингредиентов
    if (diff < 0) {
        return;
    }

    if (number === '1') {
        fillerPokeItem.textContent = `/ Осталось ${diff} из 5`;
    } else {
        fillerPokeItem.textContent = `/ Осталось ${diff} из 3`;
    }
}


function changeTopingCountValue(number, diff) {
    // Заполняет данные об оставшемся кол-ве ингредиентов
    if (diff < 0) {
        return;
    }

    if (number === '1') {
        topingPokeItem.textContent = `/ Осталось ${diff} из 1`;
    } else {
        topingPokeItem.textContent = `/ Осталось ${diff} из 2`;
    }
}


// Список инпутов Наполнитель
const checkboxFillerList = document.querySelectorAll('.constructor-poke-item-checkbox--filler');

// Обработчик изменения состояния чекбоксов
function handleFillerCheckboxChange(event) {
    const number = localStorage.getItem('shemaPoke');
    let checkedCount = 0;

    checkboxFillerList.forEach(fillerItem => {
        if (fillerItem.checked) {
            checkedCount++;
        }
    });

    const diff = shemaPokeNumber[number]['filler'] - checkedCount;
    changeFillerCountValue(number, diff);

    if (checkedCount > shemaPokeNumber[number]['filler']) {
        event.preventDefault(); // Предотвращаем изменение состояния
        event.target.checked = false;
        return;
    }
}

// Назначаем обработчик изменения состояния каждому чекбоксу
checkboxFillerList.forEach(fillerItem => {
    fillerItem.addEventListener('change', handleFillerCheckboxChange);
});


// Список инпутов Топпинг
const checkboxTopingList = document.querySelectorAll('.constructor-poke-item-checkbox--toping');

// Обработчик изменения состояния чекбоксов
function handleTopingCheckboxChange(event) {
    const number = localStorage.getItem('shemaPoke');
    let checkedCount = 0;

    checkboxTopingList.forEach(topppingItem => {
        if (topppingItem.checked) {
            checkedCount++;
        }
    });

    const diff = shemaPokeNumber[number]['toping'] - checkedCount;
    changeTopingCountValue(number, diff);

    if (checkedCount > shemaPokeNumber[number]['toping']) {
        event.preventDefault(); // Предотвращаем изменение состояния
        event.target.checked = false;
        return;
    }
}

// Назначаем обработчик изменения состояния каждому чекбоксу
checkboxTopingList.forEach(topppingItem => {
    topppingItem.addEventListener('change', handleTopingCheckboxChange);
});




// Селект с протеином: Добавка к Поке
const selectProteinAdd = document.querySelector('#constructor-poke__select-proteinAdd');
const labelProteinAdd = document.querySelector('#constructor-poke__add-price--protein');

if (selectProteinAdd) {
    selectProteinAdd.addEventListener('change', (evt) => {
        const selectedIndex = evt.target.selectedIndex;
        const selectedOption = evt.target.options[selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        labelProteinAdd.textContent = `+ ${price} руб`;

        // Обновляет сумму в Хранилище
        sumOfPoke.proteinAdd = Number(price);
        updatePokeSum(sumOfPoke);
        updatePokeBasketSum();
    });
}


// Селект с соусом: Добавка к Поке
const selectSauceAdd = document.querySelector('#constructor-poke__select-sauceAdd');
const labelSauceAdd = document.querySelector('#constructor-poke__add-price--sauce');

if (selectSauceAdd) {
    selectSauceAdd.addEventListener('change', (evt) => {
        const selectedIndex = evt.target.selectedIndex;
        const selectedOption = evt.target.options[selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        labelSauceAdd.textContent = `+ ${price} руб`;

        // Обновляет сумму в Хранилище
        sumOfPoke.sauce = Number(price);
        updatePokeSum(sumOfPoke);
        updatePokeBasketSum();
    });
}


// Селект с соусом: Добавка к Поке
const selectСrunchAdd = document.querySelector('#constructor-poke__select-crunchAdd');
const labelСrunchAdd = document.querySelector('#constructor-poke__add-price--crunch');

if (selectСrunchAdd) {
    selectСrunchAdd.addEventListener('change', (evt) => {
        const selectedIndex = evt.target.selectedIndex;
        const selectedOption = evt.target.options[selectedIndex];
        const price = selectedOption.getAttribute('data-price');

        labelСrunchAdd.textContent = `+ ${price} руб`;

        // Обновляет сумму в Хранилище
        sumOfPoke.crunch = Number(price);
        updatePokeSum(sumOfPoke);
        updatePokeBasketSum();
    });
}

// Список инпутов Наполнитель дополнительно
const labelFillerAdd = document.querySelector('#constructor-poke__add-price--filler');
const checkboxFillerAddList = document.querySelectorAll('.constructor-poke-item-checkbox--fillerAdd');

// Обработчик изменения состояния чекбоксов
function handleFillerAddCheckboxChange() {
    let fillerAddSum = 0;

    checkboxFillerAddList.forEach(fillerItem => {
        if (fillerItem.checked) {
            fillerAddSum += Number(fillerItem.dataset.price);
        }
    });

    // Обновляет сумму в Хранилище
    sumOfPoke.filler = fillerAddSum;
    updatePokeSum(sumOfPoke);
    updatePokeBasketSum();

    labelFillerAdd.textContent = `+ ${fillerAddSum} руб`;
}

// Назначаем обработчик изменения состояния каждому чекбоксу
checkboxFillerAddList.forEach(fillerItem => {
    fillerItem.addEventListener('change', handleFillerAddCheckboxChange);
});



// Список инпутов Топпинг дополнительно
const labelToppingAdd = document.querySelector('#constructor-poke__add-price--topping');
const checkboxToppingAddList = document.querySelectorAll('.constructor-poke-item-checkbox--toppingAdd');


// Обработчик изменения состояния чекбоксов
function handleToppingAddCheckboxChange() {
    let toppingAddSum = 0;

    checkboxToppingAddList.forEach(toppingItem => {
        if (toppingItem.checked) {
            toppingAddSum += Number(toppingItem.dataset.price);
        }
    });

    // Обновляет сумму в Хранилище
    sumOfPoke.topping = toppingAddSum;
    updatePokeSum(sumOfPoke);
    updatePokeBasketSum();

    labelToppingAdd.textContent = `+ ${toppingAddSum} руб`;
}

// Назначаем обработчик изменения состояния каждому чекбоксу
checkboxToppingAddList.forEach(toppingItem => {
    toppingItem.addEventListener('change', handleToppingAddCheckboxChange);
});



// Обработчик отправки формы
const constructorPokeForm = document.querySelector('.constructor-poke__form');

if (constructorPokeForm) {
    constructorPokeForm.addEventListener('submit', function (event) {
        const number = localStorage.getItem('shemaPoke');
        let checkedFillerCheckbox = 0;
        let checkedToppingCheckbox = 0;

        checkboxFillerList.forEach(fillerItem => {
            if (fillerItem.checked) {
                checkedFillerCheckbox++;
            }
        });

        checkboxTopingList.forEach(toppingItem => {
            if (toppingItem.checked) {
                checkedToppingCheckbox++;
            }
        })

        if (checkedFillerCheckbox !== shemaPokeNumber[number]['filler']) {
            event.preventDefault(); // Предотвращаем отправку формы
            alert(`Выберите в категории наполнитель ${shemaPokeNumber[number]['filler']} чекбокса(ов)`);
        }

        if (checkedToppingCheckbox !== shemaPokeNumber[number]['toping']) {
            event.preventDefault(); // Предотвращаем отправку формы
            alert(`Выберите в категории топпинг ${shemaPokeNumber[number]['toping']} чекбокса(ов)`);
        }
    });
}



// Отправляет данные на сервер для сохранения в сессии
async function apiUpdateOrderStatus(params) {
    try {
        const response = await fetch("api-update-order-status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


async function changeOrderStatus(evt) {
    const target = evt.target;
    if (!target.classList.contains('account__orders-checkbox')) {
        return;
    }

    // Доступ к родительскому tr элементу
    const parentTr = target.closest('tr');

    // Найти input элемент внутри tr
    const input = parentTr.querySelector('input[name="order-id"]');

    // Получить значение атрибута value
    const orderId = input.value;

    // Формирование строки параметров
    const params = new URLSearchParams();
    params.append("orderId", orderId);

    const parentTable = target.closest('table');
    console.log(parentTable);
    if (parentTable.id === 'account__orders-table--active') {
        params.append("status", true);
    } else {
        params.append("status", false);
    }

    try {
        // Обновляет данные о статусе заказа
        await apiUpdateOrderStatus(params);

        location.reload();
        // console.log('Поменял статус заказа');
    } catch (err) {
        console.error('Ошибка при обновлении статуса заказа:', err);
    }
}


const activeTable = document.querySelector('#account__orders-table--active');
const completeTable = document.querySelector('#account__orders-table--complete');

if (activeTable) {
    activeTable.addEventListener('click', changeOrderStatus);
}

if (completeTable) {
    completeTable.addEventListener('click', changeOrderStatus);
}




// Отправляет данные на сервер о городе пользователя
async function apiUpdateModalCity(params) {
    try {
        const response = await fetch("api-update-modal-city.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}

// Отправляет данные на сервер о городе пользователя
async function apiCloseToast(params) {
    try {
        const response = await fetch("api-close-toast.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


// Функция для установки значения города в сессии
async function getModalInfo(evt) {
    const target = evt.target;
    const toast = target.closest('.toast'); // Тост

    if (!toast) {
        return;
    }

    // Формирование строки параметров
    const params = new URLSearchParams();

    // Категория тоста
    const categoryToast = toast.getAttribute('data-set-category');

    // Айди тоста
    const toastId = toast.getAttribute('data-set-popup-id');

    // Скрывает и удаляет тост, при клике на крестик
    if (target && target.classList.contains('btn-close')) {
        toast.classList.remove('show');
        toast.remove();

        params.append("toastId", toastId);

        // Удаляет тоаст из сессии по id
        await apiCloseToast(params);
    }

    // Логика для тоста с городом
    if (categoryToast === 'city') {
        if (target.classList.contains('btn')) {
            const buttonValue = target.textContent.trim();
            params.append("cityValue", buttonValue);

            try {
                // Обновляет данные о городе пользователя
                await apiUpdateModalCity(params);

                // Удаляет тоаст из сессии по id
                await apiCloseToast(params);

                // Скрывает и удаляет тост
                toast.classList.remove('show');
                toast.remove();

                // console.log('Поменял город');
            } catch (err) {
                console.error('Ошибка при обновлении статуса заказа:', err);
            }
        }
    }
}

// Обертка над Модальным окном
const modalWrapper = document.querySelector('#alert-modal');

// Добавляем обработчик события клика
if (modalWrapper) {
    modalWrapper.addEventListener('click', getModalInfo);
}


// const tabGroup = document.querySelector('.load__update-tab');
// tabGroup.addEventListener('click', )




// Функция для скачивания таблицы csv
async function apiGetInfoFromMenu(tabGroup) {
    try {
        const response = await fetch(`fetch-data-from-db.php?tabGroup=${tabGroup}`);

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // Конвертируем ответ сервера в текст
        const csvData = await response.text();

        // Создаем объект Blob для данных CSV
        const blob = new Blob([csvData], { type: 'text/csv' });

        // Создаем ссылку для скачивания файла
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'data.csv';

        // Эмулируем щелчок по ссылке для скачивания
        link.click();

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


// Функция для обработки клика на кнопку
async function getInfoFromMenu() {
    const tabGroup = document.querySelector('#tab-group').textContent;

    try {
        // Вызываем функцию для скачивания файла
        await apiGetInfoFromMenu(tabGroup);

        console.log('Скачал файл');
    } catch (err) {
        console.error('Ошибка при обновлении статуса заказа:', err);
    }
}

const loadDataButton = document.querySelector('.load__current-button');
if (loadDataButton) {
    loadDataButton.addEventListener('click', getInfoFromMenu);
}


