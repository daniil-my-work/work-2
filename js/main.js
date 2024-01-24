// Функция для открытия и закртия меню
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector(".header__burger");
    const nav = document.querySelector(".header__nav");

    burger.addEventListener("click", function () {
        nav.classList.toggle("show");
        burger.classList.toggle("show");
    });
});

// localStorage.clear();

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
    mainList.addEventListener("click", addProductInOrder);
}

// const basketList = document.querySelector(".basket__list");

// // Прибавляет или убавляет кол-во блюд в заказе на странице Корзина
// if (basketList) {
//     basketList.addEventListener("click", addProductInOrder);
// }
