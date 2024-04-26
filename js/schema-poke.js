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


class pokeManager {
    constructor(storageSumName, storageSchemeName, schemeSelector, fillerSelector, topingSelector, basketSumSelector, basketSumInputSelector) {
        this.storageSumName = storageSumName;
        this.storageSchemeName = storageSchemeName;
        this.schemeSelector = schemeSelector;
        this.fillerSelector = fillerSelector;
        this.topingSelector = topingSelector;
        this.basketSumSelector = basketSumSelector;
        this.basketSumInputSelector = basketSumInputSelector;

        this.sumOfPoke = {
            protein: 0,
            proteinAdd: 0,
            fillerAdd: 0,
            toppingAdd: 0,
            sauceAdd: 0,
            crunchAdd: 0,
        };

        this.setLocalStorageValue(this.storageSumName, JSON.stringify(this.sumOfPoke));
        this.setLocalStorageValue(this.storageSchemeName, 1);


        this.updateSchemePoke();
        this.init();
    }


    init() {
        this.setupSelectListener('#constructor-poke__select--protein', null);
        this.setupSelectListener('#constructor-poke__select-proteinAdd', '#constructor-poke__add-price--protein');
        this.setupSelectListener('#constructor-poke__select-sauceAdd', '#constructor-poke__add-price--sauce');
        this.setupSelectListener('#constructor-poke__select-crunchAdd', '#constructor-poke__add-price--crunch');
    }

    setLocalStorageValue(name, value) {
        localStorage.setItem(name, value);
    }

    getLocalStorageValue(value) {
        return localStorage.getItem(value);
    }

    updateSchemePoke() {
        const schemaPoke = document.querySelector(this.schemeSelector);

        schemaPoke.addEventListener('click', (evt) => {
            const target = evt.target;

            const schemaItem = target.closest('.constructor-poke-shema-item');
            const schemaItemValue = schemaItem.dataset.shemaPoke;

            this.setSchemePokeDescription(schemaItemValue);
        });
    }

    setSchemePokeDescription(schemaValue) {
        const fillerPokeItem = document.querySelector(this.fillerSelector);
        const topingPokeItem = document.querySelector(this.topingSelector);

        const fillerPokeItemText = schemaValue === '1' ? `/ Осталось 5 из 5` : `/ Осталось 3 из 3`;
        const topingPokeItemText = schemaValue === '1' ? `/ Осталось 1 из 1` : `/ Осталось 2 из 2`;

        fillerPokeItem.textContent = fillerPokeItemText;
        topingPokeItem.textContent = topingPokeItemText;

        this.setLocalStorageValue(this.storageSchemeName, schemaValue);
    }

    calcAmountPrice() {
        let result = 0;

        Object.values(this.sumOfPoke).forEach(value => {
            result += value;
        });

        return result;
    }

    setupSelectListener(selector, labelSelector) {
        const select = document.querySelector(selector);
        const selectType = select.getAttribute('name');

        if (select) {
            select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
        }
    }

    handleSelectChange(evt, key, priceLabel) {
        const price = evt.target.options[evt.target.selectedIndex].getAttribute('data-price');

        // Обновляем метку цены для добавок
        const labelPrice = document.querySelector(priceLabel);
        if (labelPrice) {
            labelPrice.textContent = price ? `+ ${price} руб` : '';
        }

        // Обновляем цену в объекте sumOfPoke и обновляем сумму в корзине
        this.sumOfPoke[key] = Number(price) || 0;
        this.updateBasketSum();

        console.log(this.sumOfPoke);
    }


    calcAmountPrice() {
        let result = 0;
        Object.values(this.sumOfPoke).forEach(value => {
            result += value;
        });
        return result;
    }

    updateBasketSum() {
        const basketSum = document.querySelector(this.basketSumSelector);
        const basketSumInput = document.querySelector(this.basketSumInputSelector);

        const result = this.calcAmountPrice();

        basketSum.textContent = `${result} руб`;
        basketSumInput.value = result;
    }




    // checkCheckboxList(list, type) {
    //     this.topingSelector = document.querySelectorAll('.constructor-poke-item-checkbox--filler');
    //     this.topingSelector = document.querySelectorAll('.constructor-poke-item-checkbox--topping');

    //     let checkedList = 0;

    //     list.forEach(item => {
    //         if (item.checked) {
    //             checkedList++;
    //         }
    //     });

    //     const number = this.getLocalStorageValue(this.storageSchemeName);

    //     list.forEach(item => {
    //         if (checkedList > shemaPokeNumber[number][type]) {
    //             item.checked = false;
    //         }
    //     });
    // }

}


// const option = [
//     'constructorPokeSum',
//     'shemaPoke',
//     '.constructor-poke-shema',
//     // '.basket__order-number',
//     // '#total-price',
//     // '#fillerCounter',
//     // '#topingCounter',
//     // '.constructor-poke-item-checkbox--filler',
//     // '.constructor-poke-item-checkbox--toping',
// ];

if (pagePoke) {
    // console.log('ds');

    new pokeManager(
        'constructorPokeSum',
        'shemaPoke',
        '.constructor-poke-shema',
        '#fillerCounter',
        '#topingCounter',
        '.basket__order-number',
        '#total-price',
    );
}

