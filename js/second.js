localStorage.clear();


// Закидывает заказ в localStorage
function setBasketItem(orderId, productId, number = 1) {
    // Получаем текущие данные из локального хранилища
    const storedData = localStorage.getItem(orderId);
    const cartData = storedData ? JSON.parse(storedData) : {};

    // Обновляем данные в объекте cartData
    cartData[productId] = number;

    // Сохраняем обновленные данные в локальное хранилище
    localStorage.setItem(orderId, JSON.stringify(cartData));
}


// Закидывает данные в Локал-Сторедж 
// setBasketItem(orderId, productDataId, 0);


// Уменьшает кол-во блюд
function decrementNumber(productCounter, productInput) {
    const value = Number(productCounter.textContent);
    const newValue = value - 1;

    if (value <= 1) {
        button.classList.remove("hidden");
        counter.classList.add("hidden");

        itemNode.textContent = 1;
        inputNode.value = 1;

        itemCounter.removeEventListener("click", handleCounterClick);
    } else {
        itemNode.textContent = newValue;
        inputNode.value = newValue;

        // Закидывает данные в Локал-Сторедж 
        setBasketItem(orderId, productDataId, newValue);
    }
}

// Прибавляет кол-во блюд
function incrementNumber(itemNode, inputNode, orderId, productDataId) {
    const newValue = Number(itemNode.textContent) + 1;
    itemNode.textContent = newValue;
    inputNode.value = newValue;

    // Закидывает данные в Локал-Сторедж 
    setBasketItem(orderId, productDataId, newValue);
}

// Объявляем функцию обработчика на счетчик
function handleCounterClick(evt) {
    const target = evt.target;

    const menuItem = target.closest(".menu__item");
    const itemCounter = menuItem.querySelector(".basket__item-counter");
    const countNumberItem = menuItem.querySelector(".basket__item-count");
    const counterInput = menuItem.querySelector(".basket__item_input");
    const basketButton = menuItem.querySelector(".menu__item-button");
    const orderId = '123';
    const productDataId = menuItem.dataset.productId;


    const actionCounter = target.classList.contains("basket__item-action");
    if (!actionCounter) {
        return;
    }

    const minusActionButton = target.classList.contains(
        "basket__item-action--minus"
    );
    if (minusActionButton) {
        // console.log('Минус');

        decrementNumber(countNumberItem, counterInput, basketButton, itemCounter, orderId, productDataId);
    }

    const plusActionButton = target.classList.contains(
        "basket__item-action--plus"
    );
    if (plusActionButton) {
        // console.log('Плюс');

        incrementNumber(countNumberItem, counterInput, orderId, productDataId);
    }

}
