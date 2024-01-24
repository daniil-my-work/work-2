// Функция для открытия и закртия меню
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector(".header__burger");
    const nav = document.querySelector(".header__nav");

    burger.addEventListener("click", function () {
        nav.classList.toggle("show");
        burger.classList.toggle("show");
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

// Закидывает заказ в localStorage
function setBasketItem(orderId, id, number) {
    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(orderId);
    const cartData = storedData ? JSON.parse(storedData) : {};

    // Если number равно 0 или отрицательное, удаляем ключ из cartData
    if (number <= 0) {
        delete cartData[id];
    } else {
        // Обновляем данные в объекте cartData
        cartData[id] = number;
    }

    // Проверяем, является ли cartData пустым объектом
    const isCartDataEmpty = Object.keys(cartData).length === 0;
    if (isCartDataEmpty) {
        localStorage.clear();
        return;
    }

    // Сохраняем обновленные данные в локальное хранилище
    localStorage.setItem(orderId, JSON.stringify(cartData));
}

// Добавляет продукт в корзину
function addProductInOrder(evt) {
    // Номер заказа
    const orderId = "123";

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

    // Уменьшает кол-во блюд
    const decButton = element.classList.contains(
        "product-item__counter-action--minus"
    );
    if (decButton) {
        console.log("Минус");
        const newValue = counterValue - 1;

        if (newValue <= 0) {
            setBasketItem(orderId, productDataId, newValue);
            hideCounter(productCounterButton, productCounterWrapper);
            return;
        }

        setBasketItem(orderId, productDataId, newValue);
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

        console.log(newValue);

        setBasketItem(orderId, productDataId, newValue);
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

    // Устанавливает стартовое значение 1 для блюда
    const startValue = 1;
    productCounterInput.value = startValue;
    productCounterNumber.textContent = String(startValue);
    setBasketItem(orderId, productDataId, startValue);
}

const mainList = document.querySelector(".menu__list");

// Прибавляет или убавляет кол-во блюд в заказе на странице Главная
if (mainList) {
    console.log('Главная');

    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(123);
    const cartData = storedData ? JSON.parse(storedData) : {};

    // Итерируем по ключам в cartData
    for (const key in cartData) {
        // Находим элемент по data-product-id
        const productItem = document.querySelector(`[data-product-id="${key}"]`);
        console.log(productItem);

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

        // Показывает счетчик
        openCounter(productCounterButton, productCounterWrapper);
        productCounterInput.value = cartData[key];
        productCounterNumber.textContent = cartData[key];
    }

    mainList.addEventListener("click", addProductInOrder);
}


// Добавляет продукт в корзину
function addProductInOrderBasket(evt) {
    // Номер заказа
    const orderId = "123";

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

    // // Айди продукта
    const productDataId = productItem.dataset.productId;

    // Уменьшает кол-во блюд
    const decButton = element.classList.contains(
        "product-item__counter-action--minus"
    );
    if (decButton) {
        console.log("Минус");
        const newValue = counterValue - 1;

        if (newValue < 1) {
            return;
        }

        setBasketItem(orderId, productDataId, newValue);
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

        setBasketItem(orderId, productDataId, newValue);
        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;

        return;
    }

    const delButton = element.classList.contains("product-item__counter-button--basket") || element.classList.contains("product-item__counter-button-icon--basket");
    if (delButton) {
        console.log('Удаляет элемент');
        setBasketItem(orderId, productDataId, 0);
        productItem.remove();

        return;
    }
}

const basketList = document.querySelector(".basket__list");

// Прибавляет или убавляет кол-во блюд в заказе на странице Корзина
if (basketList) {
    console.log('Корзина');
    basketList.addEventListener("click", addProductInOrderBasket);

    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(123);
    const cartData = storedData ? JSON.parse(storedData) : {};

    console.log(cartData);

    for (const key in cartData) {
        const item = `<li class="basket__item" data-product-id="${key}">
            <img src="../img/assets/banner-1.jpg" alt="" class="basket__item-img">

            <div class="basket__item-info">
                <p class="sub-title basket__item-title">
                    dsdsdsdsds
                </p>
            </div>

            <div class="product-item__counter">
                <div class="product-item__counter-number-wrapper">
                    <input class="product-item__counter-input" type="hidden" name="productId" value="${cartData[key]}">

                    <span class="product-item__counter-action product-item__counter-action--minus">
                        –
                    </span>

                    <p class="product-item__counter-number">
                        ${cartData[key]}
                    </p>

                    <span class="product-item__counter-action product-item__counter-action--plus">
                        +
                    </span>
                </div>

                <button class="product-item__counter-button product-item__counter-button--basket" type="button">
                    <svg class="product-item__counter-button-icon--basket" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                        <path d="M56.521 10.182a3.522 3.522 0 0 0-3.518-3.518H42.3V3.008C42.3 1.35 41.048 0 39.509 0h-15.02C22.952 0 21.7 1.35 21.7 3.008v3.656H10.997a3.522 3.522 0 0 0-3.518 3.518c0 1.768 1.316 3.221 3.018 3.465l5.188 45.877A5.042 5.042 0 0 0 20.698 64h22.604a5.04 5.04 0 0 0 5.012-4.477l5.189-45.877c1.701-.244 3.018-1.697 3.018-3.464zM23.64 3.008c0-.578.389-1.068.85-1.068h15.02c.461 0 .852.49.852 1.068v3.656H23.64V3.008zm22.746 56.297a3.102 3.102 0 0 1-3.084 2.756H20.698a3.104 3.104 0 0 1-3.086-2.756l-5.156-45.607h39.088l-5.158 45.607zm6.617-47.547H10.997a1.578 1.578 0 0 1 0-3.154h42.006a1.58 1.58 0 0 1 1.578 1.578 1.58 1.58 0 0 1-1.578 1.576z"></path>
                        <path d="M43.841 18.469a.96.96 0 0 0-1.049.883l-3.184 36.846a.972.972 0 0 0 .967 1.053.967.967 0 0 0 .965-.885l3.184-36.846a.97.97 0 0 0-.883-1.051zm-23.684 0a.97.97 0 0 0-.881 1.051l3.184 36.846a.967.967 0 0 0 .965.885c.027 0 .055 0 .084-.002a.973.973 0 0 0 .883-1.051l-3.184-36.846a.968.968 0 0 0-1.051-.883zm11.842-.004a.97.97 0 0 0-.969.971v36.846a.97.97 0 0 0 1.94 0V19.436a.97.97 0 0 0-.971-.971z"></path>
                    </svg>  
                </button>
            </div>
        </li>`;

        basketList.innerHTML += item;
    }

    // const productList = document.querySelectorAll('.basket__item');

    // productList.forEach((element) => {
    //     element.dataset.productId = 1;

    //     // setBasketItem(orderId, productDataId, 1);
    // });
}
