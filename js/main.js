// Кнопка корзина
const actionBasket = document.querySelector('.action__basket');


// Получает значение из Корзины
function getProductInBasket() {
    const basketUrl = 'get-session-data.php';

    fetch(basketUrl, {
        method: "GET",
        credentials: 'same-origin'
    })
        .then(response => response.json())
        .then(data => {
            const dataLength = Object.keys(data).length === 0;

            if (!dataLength) {
                actionBasket.classList.remove('hidden');
            } else {
                actionBasket.classList.add('hidden');
            }
        })
        .catch(error => {
            console.log("Ошибка при получении данных из сессии:" + error);
        });
}


// Закрывает меню при клике вне области Меню
function closeMenu(evt) {
    const burger = document.querySelector(".header__burger");
    const nav = document.querySelector(".header__nav");
    const target = evt.target

    if (target.closest('.header__burger')) {
        return;
    }

    if (!target.closest('.header__nav')) {
        nav.classList.remove("show");
        burger.classList.remove("show");
    }
}

// Функция для открытия и закртия меню
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector(".header__burger");
    const nav = document.querySelector(".header__nav");
    const body = document.querySelector(".page__body");

    burger.addEventListener("click", function () {
        nav.classList.toggle("show");
        burger.classList.toggle("show");

        if (nav.classList.contains('show')) {
            body.style.overflowY = 'hidden';
            body.addEventListener('click', closeMenu);
        } else {
            body.style.overflowY = 'scroll';
            body.removeEventListener('click', closeMenu);
        }
    });
});



// Показывет кнопку: В корзину
function openCounter(button, counter) {
    button.classList.add("hidden");
    counter.classList.remove("hidden");
}

// Скрывает кнопку: В корзину
function hideCounter(button, counter) {
    button.classList.remove("hidden");
    counter.classList.add("hidden");
}

// Отправляет данные на сервак для сохранения в Сессию
function apiUpdateProductList(params) {
    // Отправка Fetch-запроса на сервер
    fetch("api-update-order.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: params.toString(),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then((data) => {
            // Обработка ответа (если необходимо)
            // location.reload();
        })
        .catch((error) => {
            console.error(
                "There has been a problem with your fetch operation:",
                error
            );
        });
}

// Добавляет продукт в корзину
function addProductInBasket(evt) {
    // Элементы
    const element = evt.target;
    const productItem = element.closest(".menu__item");
    const productCounterWrapper = productItem.querySelector(
        ".product-item__counter-number-wrapper"
    );
    const productCounterButton = productItem.querySelector(
        ".product-item__counter-button"
    );
    const productCounterInput = productItem.querySelector(
        ".product-item__counter-input"
    );
    const productCounterNumber = productItem.querySelector(
        ".product-item__counter-number"
    );
    const counterValue = Number(productCounterInput.value);

    // Айди продукта
    const productDataId = productItem.dataset.productId;

    // Формирование строки параметров
    const params = new URLSearchParams();
    params.append("productId", productDataId);

    // Уменьшает кол-во блюд
    const decButton = element.classList.contains(
        "product-item__counter-action--minus"
    );
    if (decButton) {
        console.log("Минус");
        const newValue = counterValue - 1;

        if (counterValue <= 1) {
            console.log("Устанавливает значение 0");

            // Обновляет данные в сессии
            params.append("quantity", 0);
            apiUpdateProductList(params);
            // console.log(params.toString());

            // Получает значение из Корзины
            getProductInBasket();

            hideCounter(productCounterButton, productCounterWrapper);
            return;
        }

        // Обновляет данные в сессии
        params.append("quantity", newValue);
        apiUpdateProductList(params);
        // console.log(params.toString());

        // Получает значение из Корзины
        getProductInBasket();

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

        // Получает значение из Корзины
        getProductInBasket();

        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;

        return;
    }

    const inBasket = element.classList.contains("product-item__counter-button");
    if (!inBasket) {
        return;
    }

    // Показывает счетчик
    openCounter(productCounterButton, productCounterWrapper);

    // Устанавливает стартовое значение 1 при добавлении в корзину
    const startValue = 1;
    productCounterInput.value = startValue;
    productCounterNumber.textContent = String(startValue);

    // Обновляет данные в сессии
    params.append("quantity", startValue);
    apiUpdateProductList(params);

    // Получает значение из Корзины
    getProductInBasket();
}

// Обработчик для страницы Главная и Меню
const mainList = document.querySelector(".menu__list");
if (mainList) {
    console.log("Главная / Меню");

    mainList.addEventListener("click", addProductInBasket);
}


// Добавляет продукт в корзину
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

    // Формирование строки параметров
    const params = new URLSearchParams();
    params.append("productId", productDataId);

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

        // Обновить страницу
        location.reload();

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

        // Обновить страницу
        location.reload();

        return;
    }

    const delButton =
        element.classList.contains("product-item__counter-button--basket") ||
        element.classList.contains("product-item__counter-button-icon--basket");
    if (delButton) {
        console.log("Удаляет элемент из Корзины");

        params.append("quantity", 0);
        apiUpdateProductList(params);
        productItem.remove();

        // Обновить страницу
        location.reload();

        return;
    }
}

// Прибавляет или убавляет кол-во блюд в заказе на странице Корзина
const basketList = document.querySelector(".basket__list");
if (basketList) {
    console.log("Корзина");

    basketList.addEventListener("click", addProductInBasketSecond);
}


localStorage.clear();

const basketSum = document.querySelector('.basket__order-number');
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

shemaPoke.addEventListener('click', (evt) => {
    const target = evt.target;

    const shemaItem = target.closest('.constructor-poke-shema-item');
    const shemaItemValue = shemaItem.dataset.shemaPoke;

    const fillerPokeItem = document.querySelector('#fillerCounter');
    const topingPokeItem = document.querySelector('#topingCounter');

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


// Селект Протеин
const selectProtein = document.querySelector('#constructor-poke__select--protein');

// Назначаем обработчик изменения состояния каждому чекбоксу
selectProtein.addEventListener('change', (evt) => {
    const selectedIndex = evt.target.selectedIndex;
    const selectedOption = evt.target.options[selectedIndex];
    const price = selectedOption.getAttribute('data-price');

    // Обновляет сумму в Хранилище
    sumOfPoke.protein = Number(price);
    updatePokeSum(sumOfPoke);
    updatePokeBasketSum();
});



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


// Селект с соусом: Добавка к Поке
const selectSauceAdd = document.querySelector('#constructor-poke__select-sauceAdd');
const labelSauceAdd = document.querySelector('#constructor-poke__add-price--sauce');

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

// Селект с соусом: Добавка к Поке
const selectСrunchAdd = document.querySelector('#constructor-poke__select-crunchAdd');
const labelСrunchAdd = document.querySelector('#constructor-poke__add-price--crunch');

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
document.querySelector('.constructor-poke__form').addEventListener('submit', function (event) {
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