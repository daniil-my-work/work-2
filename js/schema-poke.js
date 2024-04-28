const schemaPokeNumber = {
    1: { 'filler': 5, 'topping': 1 },
    2: { 'filler': 3, 'topping': 2 },
};


class PokeManager {
    constructor(config) {
        this.config = config;
        this.selectors = config.selectors;
        this.basketPrice = document.querySelector(config.selectors.basketSum);
        this.basketInputPrice = document.querySelector(config.selectors.basketSumInput);
        this.storage = { protein: 0, proteinAdd: 0, fillerAdd: 0, toppingAdd: 0, sauceAdd: 0, crunchAdd: 0 };
        localStorage.setItem(config.storageNames.sum, JSON.stringify(this.storage));
        localStorage.setItem(config.storageNames.scheme, 1);

        this.init();
    }

    init() {
        this.updateSchemePoke();
        this.setupSelectListeners();
        this.setupCheckboxListeners();
        this.setupCheckboxAddListeners();
    }

    setupSelectListeners() {
        this.setupSelectListener('#constructor-poke__select--protein', null);
        this.setupSelectListener('#constructor-poke__select-proteinAdd', '#constructor-poke__add-price--protein');
        this.setupSelectListener('#constructor-poke__select-sauceAdd', '#constructor-poke__add-price--sauce');
        this.setupSelectListener('#constructor-poke__select-crunchAdd', '#constructor-poke__add-price--crunch');
    }

    setupCheckboxListeners() {
        this.checkboxListener(this.selectors.checkBoxFiller, 'filler');
        this.checkboxListener(this.selectors.checkBoxTopping, 'topping');
    }

    setupCheckboxAddListeners() {
        this.checkboxAddListener(this.selectors.checkBoxFillerAdd, 'fillerAdd', '#constructor-poke__add-price--filler');
        this.checkboxAddListener(this.selectors.checkBoxToppingAdd, 'toppingAdd', '#constructor-poke__add-price--topping');
    }

    setLocalStorageValue(name, value) {
        localStorage.setItem(name, value);
    }

    getLocalStorageValue(value) {
        return localStorage.getItem(value);
    }

    countCheckedItem(selector) {
        const checkboxes = document.querySelectorAll(selector);
        return Array.from(checkboxes).filter(cb => cb.checked).length;
    }

    getScheme() {
        return localStorage.getItem(this.config.storageNames.scheme);
    }

    clearCheckbox(selector, fillerNumber, type) {
        const list = document.querySelectorAll(selector);

        list.forEach(item => {
            if (fillerNumber > schemaPokeNumber[this.getScheme()][type]) {
                item.checked = false;
            }
        });
    }

    clearCheckboxList() {
        const fillerChecked = this.countCheckedItem(this.selectors.checkBoxFiller);
        const toppingChecked = this.countCheckedItem(this.selectors.checkBoxTopping);

        this.clearCheckbox(this.selectors.checkBoxFiller, fillerChecked, 'filler');
        this.clearCheckbox(this.selectors.checkBoxTopping, toppingChecked, 'topping');
    }

    setSchemePokeDescription(schemaValue) {
        const fillerPokeItem = document.querySelector(this.selectors.fillerCount);
        const toppingPokeItem = document.querySelector(this.selectors.toppingCount);

        const fillerPokeItemText = schemaValue === '1' ? `/ Осталось 5 из 5` : `/ Осталось 3 из 3`;
        const toppingPokeItemText = schemaValue === '1' ? `/ Осталось 1 из 1` : `/ Осталось 2 из 2`;

        fillerPokeItem.textContent = fillerPokeItemText;
        toppingPokeItem.textContent = toppingPokeItemText;

        this.setLocalStorageValue(this.config.storageNames.scheme, schemaValue);

        this.clearCheckboxList();
    }

    updateSchemePoke() {
        const schemaPoke = document.querySelector(this.selectors.scheme);

        schemaPoke.addEventListener('click', (evt) => {
            const target = evt.target;

            const schemaItem = target.closest('.constructor-poke-shema-item');
            const schemaItemValue = schemaItem.dataset.shemaPoke;

            this.setSchemePokeDescription(schemaItemValue);
        });
    }

    updateBasketSum() {
        const sum = Object.values(this.storage).reduce((a, b) => a + b, 0);

        this.basketPrice.textContent = `${sum} руб`;
        this.basketInputPrice.value = sum;
    }

    handleSelectChange(evt, key, priceLabel) {
        const price = evt.target.options[evt.target.selectedIndex].getAttribute('data-price');

        // Обновляем метку цены для добавок
        const labelPrice = document.querySelector(priceLabel);
        if (labelPrice) {
            labelPrice.textContent = price ? `+ ${price} руб` : '';
        }

        // Обновляем цену в объекте sumOfPoke и обновляем сумму в корзине
        this.storage[key] = Number(price) || 0;
        this.updateBasketSum();

        console.log(this.storage);
    }

    setupSelectListener(selector, labelSelector) {
        const select = document.querySelector(selector);
        const selectType = select.getAttribute('name');

        if (select) {
            select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
        }
    }

    changeCountValue(number, diff, type) {
        const fillerPokeItem = document.querySelector(this.selectors.fillerCount);
        const toppingPokeItem = document.querySelector(this.selectors.toppingCount);

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

    handleCheckboxChange(evt, checkboxes, type) {
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const allowedCount = schemaPokeNumber[this.getScheme()][type];

        if (checkedCount > allowedCount) {
            evt.preventDefault();
            evt.target.checked = false;
        }

        const diff = allowedCount - checkedCount;
        this.changeCountValue(this.getScheme(), diff, type);
    }

    checkboxListener(selector, type) {
        const checkboxList = document.querySelectorAll(selector);

        checkboxList.forEach(item => {
            item.addEventListener('change', (evt) => this.handleCheckboxChange(evt, checkboxList, type));
        });
    }

    calculateAdditions(checkboxes) {
        return Array.from(checkboxes).reduce((sum, cb) => cb.checked ? sum + Number(cb.dataset.price) : sum, 0);
    }

    handleCheckboxAddChange(list, type, labelCheckBox) {
        const sum = this.calculateAdditions(list);

        // Обновляем цену в объекте sumOfPoke и обновляем сумму в корзине
        this.storage[type] = Number(sum) || 0;
        this.updateBasketSum();

        // Обновляем метку цены для добавок
        const labelPrice = document.querySelector(labelCheckBox);
        if (labelPrice) {
            labelPrice.textContent = sum ? `+ ${sum} руб` : '';
        }
    }

    checkboxAddListener(selector, type, labelCheckBox) {
        const checkboxList = document.querySelectorAll(selector);

        checkboxList.forEach(item => {
            item.addEventListener('change', () => this.handleCheckboxAddChange(checkboxList, type, labelCheckBox));
        });
    }
}


const pokePageConfig = {
    storageNames: {
        sum: 'constructorPokeSum',
        scheme: 'shemaPoke'
    },
    selectors: {
        scheme: '.constructor-poke-shema',
        fillerCount: '#fillerCounter',
        toppingCount: '#topingCounter',
        basketSum: '.basket__order-number',
        basketSumInput: '#total-price',
        checkBoxFiller: '.constructor-poke-item-checkbox--filler',
        checkBoxTopping: '.constructor-poke-item-checkbox--toping',
        checkBoxFillerAdd: '.constructor-poke-item-checkbox--fillerAdd',
        checkBoxToppingAdd: '.constructor-poke-item-checkbox--toppingAdd',
    }
};

if (pagePoke) {
    new PokeManager(pokePageConfig);
}

// if (pagePoke) {
//     new pokeManager(
//         'constructorPokeSum',
//         'shemaPoke',
//         '.constructor-poke-shema',
//         '#fillerCounter',
//         '#topingCounter',
//         '.basket__order-number',
//         '#total-price',
//         '.constructor-poke-item-checkbox--filler',
//         '.constructor-poke-item-checkbox--toping',
//         '.constructor-poke-item-checkbox--fillerAdd',
//         '.constructor-poke-item-checkbox--toppingAdd',
//     );
// }

