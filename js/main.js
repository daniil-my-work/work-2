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


// Отправляет данные на сервак для сохранения в Сессию
function apiUpdateProductList(params) {
    // Отправка Fetch-запроса на сервер
    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: params.toString(),
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            // Обработка ответа (если необходимо)
            // location.reload();
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
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
    params.append('productId', productDataId);

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
            params.append('quantity', 0);
            apiUpdateProductList(params);
            // console.log(params.toString());

            hideCounter(productCounterButton, productCounterWrapper);
            return;
        }

        // Обновляет данные в сессии
        params.append('quantity', newValue);
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
        params.append('quantity', newValue);
        apiUpdateProductList(params);
        // console.log(params.toString());

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
    params.append('quantity', startValue);
    apiUpdateProductList(params);
    // console.log(params.toString());
}

// Обработчик для страницы Главная и Меню
const mainList = document.querySelector(".menu__list");
mainList.addEventListener("click", addProductInBasket);


// Добавляет продукт в корзину
// function addProductInBasket(evt) {
//     // Номер заказа
//     // const orderId = "123";

//     // Элементы
//     const element = evt.target;
//     const productItem = element.closest(".basket__item");
//     const productCounterInput = productItem.querySelector(
//         ".product-item__counter-input"
//     );
//     const productCounterNumber = productItem.querySelector(
//         ".product-item__counter-number"
//     );
//     const counterValue = Number(productCounterInput.value);

//     // // Айди продукта
//     const productDataId = productItem.dataset.productId;

//     // Уменьшает кол-во блюд
//     const decButton = element.classList.contains(
//         "product-item__counter-action--minus"
//     );
//     if (decButton) {
//         console.log("Минус");
//         const newValue = counterValue - 1;

//         if (newValue < 1) {
//             return;
//         }

//         setBasketItem(orderId, productDataId, newValue);
//         productCounterInput.value = newValue;
//         productCounterNumber.textContent = newValue;

//         return;
//     }

//     // Увеличивает кол-во блюд
//     const plusButton = element.classList.contains(
//         "product-item__counter-action--plus"
//     );
//     if (plusButton) {
//         console.log("Плюс");
//         const newValue = counterValue + 1;

//         setBasketItem(orderId, productDataId, newValue);
//         productCounterInput.value = newValue;
//         productCounterNumber.textContent = newValue;

//         return;
//     }

//     const delButton = element.classList.contains("product-item__counter-button--basket") || element.classList.contains("product-item__counter-button-icon--basket");
//     if (delButton) {
//         console.log('Удаляет элемент');
//         setBasketItem(orderId, productDataId, 0);
//         productItem.remove();

//         return;
//     }
// }

const basketList = document.querySelector(".basket__list");

// Прибавляет или убавляет кол-во блюд в заказе на странице Корзина
if (basketList) {
    console.log('Корзина');

    // basketList.addEventListener("click", addProductInOrderBasket);
}
