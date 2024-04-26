const shemaPokeNumber = {
    1: {
        'filler': 5,
        'topping': 1,
    },
    2: {
        'filler': 3,
        'topping': 2,
    },
};


class pokeManager {
    constructor(storageSumName, storageSchemeName, schemeSelector, fillerSelector, toppingSelector, basketSumSelector, basketSumInputSelector, checkBoxSelectorFiller, checkBoxSelectorTopping) {
        this.storageSumName = storageSumName;
        this.storageSchemeName = storageSchemeName;
        this.schemeSelector = schemeSelector;
        this.fillerSelector = fillerSelector;
        this.toppingSelector = toppingSelector;
        this.basketSumSelector = basketSumSelector;
        this.basketSumInputSelector = basketSumInputSelector;
        this.checkBoxFiller = document.querySelectorAll(checkBoxSelectorFiller);
        this.checkBoxTopping = document.querySelectorAll(checkBoxSelectorTopping);

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

        this.init();
    }


    init() {
        this.updateSchemePoke();

        this.setupSelectListener('#constructor-poke__select--protein', null);
        this.setupSelectListener('#constructor-poke__select-proteinAdd', '#constructor-poke__add-price--protein');
        this.setupSelectListener('#constructor-poke__select-sauceAdd', '#constructor-poke__add-price--sauce');
        this.setupSelectListener('#constructor-poke__select-crunchAdd', '#constructor-poke__add-price--crunch');

        this.checkboxListener(this.checkBoxFiller, 'filler');
        this.checkboxListener(this.checkBoxTopping, 'topping');
    }

    setLocalStorageValue(name, value) {
        localStorage.setItem(name, value);
    }

    getLocalStorageValue(value) {
        return localStorage.getItem(value);
    }


    countCheckedItem(list) {
        let checked = 0;

        list.forEach(item => {
            if (item.checked) {
                checked++;
            }
        });
        return checked;
    }

    clearCheckbox(list, fillerNumber, number, type) {
        list.forEach(item => {
            if (fillerNumber > shemaPokeNumber[number][type]) {
                item.checked = false;
            }
        });
    }

    clearCheckboxList() {
        const fillerChecked = this.countCheckedItem(this.checkBoxFiller);
        const toppingChecked = this.countCheckedItem(this.checkBoxTopping);

        const number = this.getLocalStorageValue(this.storageSchemeName);

        this.clearCheckbox(this.checkBoxFiller, fillerChecked, number, 'filler');
        this.clearCheckbox(this.checkBoxTopping, toppingChecked, number, 'topping');
    }

    setSchemePokeDescription(schemaValue) {
        const fillerPokeItem = document.querySelector(this.fillerSelector);
        const toppingPokeItem = document.querySelector(this.toppingSelector);

        const fillerPokeItemText = schemaValue === '1' ? `/ Осталось 5 из 5` : `/ Осталось 3 из 3`;
        const toppingPokeItemText = schemaValue === '1' ? `/ Осталось 1 из 1` : `/ Осталось 2 из 2`;

        fillerPokeItem.textContent = fillerPokeItemText;
        toppingPokeItem.textContent = toppingPokeItemText;

        this.setLocalStorageValue(this.storageSchemeName, schemaValue);

        this.clearCheckboxList();
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

    setupSelectListener(selector, labelSelector) {
        const select = document.querySelector(selector);
        const selectType = select.getAttribute('name');

        if (select) {
            select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
        }
    }

    changeCountValue(number, diff, type) {
        const fillerPokeItem = document.querySelector(this.fillerSelector);
        const toppingPokeItem = document.querySelector(this.toppingSelector);

        // Заполняет данные об оставшемся кол-ве ингредиентов
        if (diff < 0) {
            return;
        }

        if (type === 'filler') {
            if (number === '1') {
                fillerPokeItem.textContent = `/ Осталось ${diff} из 5`;
            } else {
                fillerPokeItem.textContent = `/ Осталось ${diff} из 3`;
            }
        } else if (type === 'topping') {
            if (number === '1') {
                toppingPokeItem.textContent = `/ Осталось ${diff} из 1`;
            } else {
                toppingPokeItem.textContent = `/ Осталось ${diff} из 2`;
            }
        }
    }

    handleCheckboxChange(evt, list, type) {
        const number = this.getLocalStorageValue(this.storageSchemeName);
        let checkedCount = 0;

        list.forEach(item => {
            if (item.checked) {
                checkedCount++;
            }
        });

        const diff = shemaPokeNumber[number][type] - checkedCount;
        this.changeCountValue(number, diff, type);

        if (checkedCount > shemaPokeNumber[number][type]) {
            evt.preventDefault();
            evt.target.checked = false;
            return;
        }
    }

    checkboxListener(list, type) {
        list.forEach(item => {
            item.addEventListener('change', (evt) => this.handleCheckboxChange(evt, list, type));
        });
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
//     // '.constructor-poke-item-checkbox--topping',
// ];

// const checkboxTopingList = document.querySelectorAll('.constructor-poke-item-checkbox--toping');


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
        '.constructor-poke-item-checkbox--filler',
        '.constructor-poke-item-checkbox--toping',
    );
}

