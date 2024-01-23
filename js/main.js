// Функция для открытия и закртия меню
document.addEventListener("DOMContentLoaded", function () {
    const burger = document.querySelector(".header__burger");
    const nav = document.querySelector(".header__nav");

    burger.addEventListener("click", function () {
        nav.classList.toggle("show");
        burger.classList.toggle("show");
    });
});

localStorage.clear();




function openCounter(button, counter) {
    button.classList.add('hidden');
    counter.classList.remove('hidden');
}

function hideCounter(button, counter) {
    button.classList.remove('hidden');
    counter.classList.add('hidden');
}


// Прибавляет кол-во блюд
function incrementNumber(inputNode) {
    const newValue = inputNode.value + 1;
    inputNode.value = newValue;

    return
}


// Закидывает заказ в localStorage
function setBasketItem(orderId, id, number) {
    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(orderId);
    const cartData = storedData ? JSON.parse(storedData) : {};

    // Обновляем данные в объекте cartData
    cartData[id] = number;

    // Сохраняем обновленные данные в локальное хранилище
    localStorage.setItem(orderId, JSON.stringify(cartData));
}


// Добавляет продукт в корзину
function addProductInOrder(evt) {
    // Номер заказа
    const orderId = '123';

    // Элементы
    const element = evt.target;
    const productItem = element.closest(".menu__item");
    const productCounterWrapper = productItem.querySelector(".product-item__counter-number-wrapper");
    const productCounterButton = productItem.querySelector(".product-item__counter-button");
    const productCounterInput = productItem.querySelector(".product-item__counter-input");
    const productCounterNumber = productItem.querySelector(".product-item__counter-number");
    const counterValue = Number(productCounterInput.value);

    // Айди продукта
    const productDataId = productItem.dataset.productId;


    // Уменьшает кол-во блюд
    const decButton = element.classList.contains('product-item__counter-action--minus');
    if (decButton) {
        console.log('Минус');
        const newValue = counterValue - 1;

        setBasketItem(orderId, productDataId, newValue);
        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;

        return;
    }

    // Увеличивает кол-во блюд
    const plusButton = element.classList.contains('product-item__counter-action--plus');
    if (plusButton) {
        console.log('Плюс');
        const newValue = counterValue + 1;

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
    const startValue = counterValue;
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

