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
function setBasketItem(orderId, productId, number) {
    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(orderId);
    const cartData = storedData ? JSON.parse(storedData) : {};

    // Обновляем данные в объекте cartData
    cartData[productId] = number;

    // Сохраняем обновленные данные в локальное хранилище
    localStorage.setItem(orderId, JSON.stringify(cartData));
}


// Устанавливает кол-во блюд
function setProductNumber(evt) {
    const element = evt.target;

    const decButton = element.classList.contains('product-item__counter-action--minus');
    if (decButton) {
        console.log('Минус');

    }

    const plusButton = element.classList.contains('product-item__counter-action--plus');
    if (plusButton) {
        console.log('Плюс');

    }
}


// Добавляет продукт в корзину
function addProductInOrder(evt) {
    const element = evt.target;

    const inBasket = element.classList.contains("product-item__counter-button");
    if (!inBasket) {
        return;
    }

    const productItem = element.closest(".menu__item");
    const productCounterWrapper = productItem.querySelector(".product-item__counter-number-wrapper");
    const productCounterButton = productItem.querySelector(".product-item__counter-button");
    const productCounterInput = productItem.querySelector(".product-item__counter-input");
    const productCounterNumber = productItem.querySelector(".product-item__counter-number");

    // Показывает счетчик
    openCounter(productCounterButton, productCounterWrapper);

    // Устанавливает стартовое значение 1 для блюда
    productCounterInput.value = 1;
    productCounterNumber.textContent = productCounterInput.value;

    const orderId = '123';
    const productDataId = productItem.dataset.productId;
    setBasketItem(orderId, productDataId, productCounterInput.value);

    // Устанавливает кол-во блюд
    productCounterWrapper.addEventListener('click', setProductNumber);


    // const basketButton = target;
    // const menuItem = target.closest(".menu__item");
    // const itemCounter = menuItem.querySelector(".basket__item-counter");
    // const itemCounterInput = menuItem.querySelector(".basket__item_input");
    // const orderId = '123';
    // const productDataId = menuItem.dataset.productId;

    // basketButton.classList.add("hidden");
    // itemCounter.classList.remove("hidden");
    // itemCounterInput.value = Number(itemCounterNumber.textContent);

    // // Прибавляет / уменьшает кол-во блюд
    // itemCounter.addEventListener("click", handleCounterClick);

    // // Закидывает данные в Локал-Сторедж 
    // setBasketItem(orderId, productDataId);
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

