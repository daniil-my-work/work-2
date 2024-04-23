class BasketManager {
    constructor(basketUrl, updateUrl, pageSelector) {
        this.basketUrl = basketUrl;
        this.updateUrl = updateUrl;
        this.page = document.querySelector(pageSelector);

        if (pageSelector == '#page-main') {
            this.menuList = this.page.querySelector(".menu__list");
            this.menuList.addEventListener("click", this.handleBasketOperations.bind(this));

            this.updateBasketDisplay();
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

    async updateProductList(params) {
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
            await this.updateBasketDisplay();
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
        }
    }

    async handleBasketOperations(evt) {
        const element = evt.target;
        const productItem = element.closest(".menu__item");

        if (!productItem) return;

        const productCounterButton = productItem.querySelector(".product-item__counter-button");
        const productCounterWrapper = productItem.querySelector(".product-item__counter-number-wrapper");
        const productCounterInput = productItem.querySelector(".product-item__counter-input");
        const productCounterNumber = productItem.querySelector(".product-item__counter-number");
        const counterValue = Number(productCounterInput.value);

        const params = new URLSearchParams({
            productId: productItem.dataset.productId,
            tableName: 'menu',
            quantity: element.classList.contains("product-item__counter-action--plus") ? counterValue + 1 : counterValue - 1
        });

        if (element.matches(".product-item__counter-action--minus") && counterValue > 1) {
            productCounterInput.value = counterValue - 1;
            productCounterNumber.textContent = counterValue - 1;
        } else if (element.matches(".product-item__counter-action--plus")) {
            productCounterInput.value = counterValue + 1;
            productCounterNumber.textContent = counterValue + 1;
        }

        if (counterValue <= 1 && element.matches(".product-item__counter-action--minus")) {
            this.hideCounter(productCounterButton, productCounterWrapper);
            productCounterInput.value = 0;
            productCounterNumber.textContent = '0';
        }

        if (counterValue === 0 && element.matches(".product-item__counter-action--plus")) {
            this.openCounter(productCounterButton, productCounterWrapper);
        }

        if (element.matches(".product-item__counter-button")) {
            this.openCounter(productCounterButton, productCounterWrapper);
            productCounterInput.value = 1;
            productCounterNumber.textContent = '1';
        }

        await this.updateProductList(params);
        await this.updateBasketDisplay();
    }

    openCounter(button, counter) {
        button.classList.add("hidden");
        counter.classList.remove("hidden");
    }

    hideCounter(button, counter) {
        button.classList.remove("hidden");
        counter.classList.add("hidden");
    }
}


const dataFromBasket = 'get-session-data.php';
const addDataInBasket = 'api-update-order.php';

// Инициализация класса BasketManager
document.addEventListener('DOMContentLoaded', function () {
    new BasketManager(dataFromBasket, addDataInBasket, '#page-main');
});
