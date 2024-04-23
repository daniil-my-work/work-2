class BasketManager {
    constructor(basketUrl, updateUrl, pageSelector) {
        this.basketUrl = basketUrl;
        this.updateUrl = updateUrl;
        this.selector = pageSelector;
        this.page = document.querySelector(pageSelector);

        if (pageSelector == '#page-main') {
            this.menuList = this.page.querySelector(".menu__list");
            this.menuList.addEventListener("click", this.handleBasketOperationsOnMain.bind(this));

            this.updateBasketDisplay();
        } else if (pageSelector == '#page-basket') {
            this.basketList = this.page.querySelector(".basket__list");
            this.basketList.addEventListener("click", this.handleBasketOperationsOnBasket.bind(this));
        }
    }

    async updateBasketDisplay() {
        try {
            const response = await fetch(this.basketUrl, {
                method: "GET",
                credentials: 'same-origin'
            });

            if (!response.ok) throw new Error("Network response was not ok");
            const data = await response.json();
            const basketIndicator = document.querySelector('.action__basket');
            if (data.totalLength > 0) {
                basketIndicator.classList.remove('hidden');
            } else {
                basketIndicator.classList.add('hidden');
            }
        } catch (error) {
            console.error("Ошибка при получении данных из сессии:", error);
        }
    }

    async updateProductList(params, pageSelector) {
        try {
            const response = await fetch(this.updateUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: params.toString(),
            });

            if (!response.ok) throw new Error("Network response was not ok");
            console.log("Данные успешно обновлены в сессии");

            if (pageSelector == '#page-main') {
                await this.updateBasketDisplay();
            }
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
        }
    }

    async handleBasketOperationsOnMain(evt) {
        const element = evt.target;
        const productItem = element.closest(".menu__item");

        if (!productItem) return;

        const { productCounterButton, productCounterWrapper, productCounterInput, productCounterNumber } = this.#getDOMElements(productItem, this.selector);
        const counterValue = Number(productCounterInput.value);

        this.#adjustCounterOnMain(element, counterValue, productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper);

        const params = this.#createParams(productItem, element, counterValue);

        await this.updateProductList(params, this.selector);
        await this.updateBasketDisplay();
    }


    // Изменяет кол-во продуктов на странице Корзина
    // function addProductInBasketSecond(evt) {
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

    //     // Айди продукта
    //     const productDataId = productItem.dataset.productId;
    //     const tableName = productItem.dataset.tableName;

    //     // Формирование строки параметров
    //     const params = new URLSearchParams();
    //     params.append("productId", productDataId);
    //     params.append("tableName", tableName);

    //     // Уменьшает кол-во блюд
    //     const decButton = element.classList.contains(
    //         "product-item__counter-action--minus"
    //     );
    //     if (decButton) {
    //         console.log("Минус");

    //         const newValue = counterValue - 1;

    //         if (counterValue <= 1) {
    //             console.log("Минимальное значение 1");
    //             return;
    //         }

    //         // Обновляет данные в сессии
    //         params.append("quantity", newValue);
    //         apiUpdateProductList(params);
    //         // console.log(params.toString());

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

    //         // Обновляет данные в сессии
    //         params.append("quantity", newValue);
    //         apiUpdateProductList(params);
    //         // console.log(params.toString());

    //         productCounterInput.value = newValue;
    //         productCounterNumber.textContent = newValue;

    //         return;
    //     }

    //     const delButton =
    //         element.classList.contains("product-item__counter-button--basket") ||
    //         element.classList.contains("product-item__counter-button-icon--basket");
    //     if (delButton) {
    //         console.log("Удаляет элемент из Корзины");

    //         params.append("quantity", 0);
    //         apiUpdateProductList(params);
    //         // console.log(params.toString());

    //         productItem.remove();

    //         return;
    //     }
    // }

    async handleBasketOperationsOnBasket(evt) {
        const element = evt.target;
        const productItem = element.closest(".basket__item");

        if (!productItem) return;

        const { productCounterButton, productCounterWrapper, productCounterInput, productCounterNumber } = this.#getDOMElements(productItem, this.selector);
        const counterValue = Number(productCounterInput.value);

        this.#adjustCounterOnBasket(element, productItem, counterValue, productCounterInput, productCounterNumber);

        const params = this.#createParams(productItem, element, counterValue);

        await this.updateProductList(params, this.selector);
    }

    #createParams(productItem, element, counterValue) {
        return new URLSearchParams({
            productId: productItem.dataset.productId,
            tableName: 'menu',
            quantity: element.classList.contains("product-item__counter-action--plus") ? counterValue + 1 : counterValue - 1
        });
    }

    #getDOMElements(productItem, pageSelector) {
        if (pageSelector == '#page-main') {
            return {
                productCounterButton: productItem.querySelector(".product-item__counter-button"),
                productCounterWrapper: productItem.querySelector(".product-item__counter-number-wrapper"),
                productCounterInput: productItem.querySelector(".product-item__counter-input"),
                productCounterNumber: productItem.querySelector(".product-item__counter-number")
            };
        } else if (pageSelector == '#page-basket') {
            return {
                productCounterButton: productItem.querySelector(".product-item__counter-button"),
                productCounterWrapper: productItem.querySelector(".product-item__counter-number-wrapper"),
                productCounterInput: productItem.querySelector(".product-item__counter-input"),
                productCounterNumber: productItem.querySelector(".product-item__counter-number")
            };
        }
    }

    #adjustCounterOnMain(element, counterValue, productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper) {
        if (element.matches(".product-item__counter-action--plus")) {
            this.#incrementCounter(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-action--minus")) {
            this.#decrementCounter(productCounterInput, productCounterNumber, counterValue, productCounterButton, productCounterWrapper);
        } else if (element.matches(".product-item__counter-button")) {
            this.#clickToButtonBasket(productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper);
        }
    }

    #adjustCounterOnBasket(element, productItem, counterValue, productCounterInput, productCounterNumber) {
        if (element.matches(".product-item__counter-action--plus")) {
            this.#incrementCounter(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-action--minus")) {
            this.#decrementCounterOnBasket(productCounterInput, productCounterNumber, counterValue);
        } else if (element.matches(".product-item__counter-button--basket") || element.matches("product-item__counter-button-icon--basket")) {
            this.#clickToButtonDeleteOnBasket(productItem, productCounterInput, productCounterNumber);
        }
    }

    #incrementCounter(productCounterInput, productCounterNumber, counterValue) {
        const newValue = counterValue + 1;

        productCounterInput.value = newValue;
        productCounterNumber.textContent = newValue;
    }

    #decrementCounter(productCounterInput, productCounterNumber, counterValue, productCounterButton, productCounterWrapper) {
        const newValue = counterValue - 1;

        if (newValue <= 0) {
            this.#hideCounter(productCounterButton, productCounterWrapper);
            productCounterInput.value = 0;
            productCounterNumber.textContent = '0';
        } else {
            productCounterInput.value = newValue;
            productCounterNumber.textContent = newValue;
        }
    }

    #decrementCounterOnBasket(productCounterInput, productCounterNumber, counterValue) {
        const newValue = counterValue - 1;

        if (newValue <= 0) {
            return;
        } else {
            productCounterInput.value = newValue;
            productCounterNumber.textContent = newValue;
        }
    }

    #clickToButtonBasket(productCounterInput, productCounterNumber, productCounterButton, productCounterWrapper) {
        this.#openCounter(productCounterButton, productCounterWrapper);

        productCounterInput.value = 1;
        productCounterNumber.textContent = '1';
    }

    #clickToButtonDeleteOnBasket(productItem, productCounterInput, productCounterNumber) {
        productCounterInput.value = 0;
        productCounterNumber.textContent = '0';

        productItem.remove();
    }

    #openCounter(button, counter) {
        button.classList.add("hidden");
        counter.classList.remove("hidden");
    }

    #hideCounter(button, counter) {
        button.classList.remove("hidden");
        counter.classList.add("hidden");
    }
}


const URL_BASKET_DATA = 'get-session-data.php';
const URL_ADD_TO_BASKET = 'api-update-order.php';



// Инициализация класса BasketManager
document.addEventListener('DOMContentLoaded', function () {
    const pageMain = document.querySelector('#page-main');

    if (pageMain) {
        new BasketManager(URL_BASKET_DATA, URL_ADD_TO_BASKET, '#page-main');
    }

    const pageBasket = document.querySelector('#page-basket');

    if (pageBasket) {
        new BasketManager(URL_BASKET_DATA, URL_ADD_TO_BASKET, '#page-basket');
    }
});
