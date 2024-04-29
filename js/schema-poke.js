const dsds = 'dsds';

// const schemaPokeNumber = {
//     1: { 'filler': 5, 'topping': 1 },
//     2: { 'filler': 3, 'topping': 2 },
// };

// class PokeManager {
//     constructor(config) {
//         this.config = config;
//         this.selectors = config.selectors;
//         this.basketPrice = document.querySelector(config.selectors.basketSum);
//         this.basketInputPrice = document.querySelector(config.selectors.basketSumInput);
//         this.storage = { protein: 0, proteinAdd: 0, fillerAdd: 0, toppingAdd: 0, sauceAdd: 0, crunchAdd: 0 };
//         this.access = {
//             protein: false,
//             filler: false,
//             topping: false,
//         };
//         localStorage.setItem(config.storageNames.sum, JSON.stringify(this.storage));
//         localStorage.setItem(config.storageNames.scheme, 1);

//         this.init();
//     }

//     init() {
//         this.updateSchemePoke();
//         this.setupSelectListeners();
//         this.setupCheckboxListeners();
//         this.setupCheckboxAddListeners();
//         this.submitForm();
//     }

//     setupSelectListeners() {
//         this.setupSelectListener('#constructor-poke__select--protein', null);
//         this.setupSelectListener('#constructor-poke__select-proteinAdd', '#constructor-poke__add-price--protein');
//         this.setupSelectListener('#constructor-poke__select-sauceAdd', '#constructor-poke__add-price--sauce');
//         this.setupSelectListener('#constructor-poke__select-crunchAdd', '#constructor-poke__add-price--crunch');
//     }

//     setupCheckboxListeners() {
//         this.checkboxListener(this.selectors.checkBoxFiller, 'filler');
//         this.checkboxListener(this.selectors.checkBoxTopping, 'topping');
//     }

//     setupCheckboxAddListeners() {
//         this.checkboxAddListener(this.selectors.checkBoxFillerAdd, 'fillerAdd', '#constructor-poke__add-price--filler');
//         this.checkboxAddListener(this.selectors.checkBoxToppingAdd, 'toppingAdd', '#constructor-poke__add-price--topping');
//     }

//     setLocalStorageValue(name, value) {
//         localStorage.setItem(name, value);
//     }

//     countCheckedItem(selector) {
//         const checkboxes = document.querySelectorAll(selector);
//         return Array.from(checkboxes).filter(cb => cb.checked).length;
//     }

//     getScheme() {
//         return localStorage.getItem(this.config.storageNames.scheme);
//     }

//     clearCheckbox(selector, fillerNumber, type) {
//         const list = document.querySelectorAll(selector);

//         list.forEach(item => {
//             if (fillerNumber > schemaPokeNumber[this.getScheme()][type]) {
//                 item.checked = false;
//             }
//         });

//         const checkedCount = Array.from(list).filter(cb => cb.checked).length;
//         this.access[type] = schemaPokeNumber[this.getScheme()][type] === checkedCount;
//     }

//     clearCheckboxList() {
//         const fillerChecked = this.countCheckedItem(this.selectors.checkBoxFiller);
//         const toppingChecked = this.countCheckedItem(this.selectors.checkBoxTopping);

//         this.clearCheckbox(this.selectors.checkBoxFiller, fillerChecked, 'filler');
//         this.clearCheckbox(this.selectors.checkBoxTopping, toppingChecked, 'topping');
//     }

//     setSchemePokeDescription(schemaValue) {
//         const fillerPokeItem = document.querySelector(this.selectors.fillerCount);
//         const toppingPokeItem = document.querySelector(this.selectors.toppingCount);

//         const fillerPokeItemText = schemaValue === '1' ? `/ Осталось 5 из 5` : `/ Осталось 3 из 3`;
//         const toppingPokeItemText = schemaValue === '1' ? `/ Осталось 1 из 1` : `/ Осталось 2 из 2`;

//         fillerPokeItem.textContent = fillerPokeItemText;
//         toppingPokeItem.textContent = toppingPokeItemText;

//         this.setLocalStorageValue(this.config.storageNames.scheme, schemaValue);

//         this.clearCheckboxList();
//     }

//     updateSchemePoke() {
//         const schemaPoke = document.querySelector(this.selectors.scheme);

//         schemaPoke.addEventListener('click', (evt) => {
//             const target = evt.target;

//             const schemaItem = target.closest('.constructor-poke-shema-item');
//             const schemaItemValue = schemaItem.dataset.shemaPoke;

//             this.setSchemePokeDescription(schemaItemValue);
//         });
//     }

//     updateBasketSum() {
//         const sum = Object.values(this.storage).reduce((a, b) => a + b, 0);

//         this.basketPrice.textContent = `${sum} руб`;
//         this.basketInputPrice.value = sum;
//     }

//     handleSelectChange(evt, key, priceLabel) {
//         const price = evt.target.options[evt.target.selectedIndex].getAttribute('data-price');

//         // Обновляем метку цены для добавок
//         const labelPrice = document.querySelector(priceLabel);
//         if (labelPrice) {
//             labelPrice.textContent = price ? `+ ${price} руб` : '';
//         }

//         // Обновляем цену в объекте sumOfPoke и обновляем сумму в корзине
//         this.storage[key] = Number(price) || 0;
//         this.updateBasketSum();

//         this.accessSendForm(null, null, key, price);
//     }

//     setupSelectListener(selector, labelSelector) {
//         const select = document.querySelector(selector);
//         const selectType = select.getAttribute('name');

//         if (select) {
//             select.addEventListener('change', (evt) => this.handleSelectChange(evt, selectType, labelSelector));
//         }
//     }

//     changeCountValue(number, diff, type) {
//         const fillerPokeItem = document.querySelector(this.selectors.fillerCount);
//         const toppingPokeItem = document.querySelector(this.selectors.toppingCount);

//         // Заполняет данные об оставшемся кол-ве ингредиентов
//         if (diff < 0) {
//             return;
//         }

//         if (type === 'filler') {
//             if (number === '1') {
//                 fillerPokeItem.textContent = `/ Осталось ${diff} из 5`;
//             } else {
//                 fillerPokeItem.textContent = `/ Осталось ${diff} из 3`;
//             }
//         } else if (type === 'topping') {
//             if (number === '1') {
//                 toppingPokeItem.textContent = `/ Осталось ${diff} из 1`;
//             } else {
//                 toppingPokeItem.textContent = `/ Осталось ${diff} из 2`;
//             }
//         }
//     }

//     handleCheckboxChange(evt, checkboxes, type) {
//         let checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
//         const allowedCount = schemaPokeNumber[this.getScheme()][type];

//         if (checkedCount > allowedCount) {
//             evt.preventDefault();
//             evt.target.checked = false;
//             checkedCount = allowedCount;
//         }

//         const diff = allowedCount - checkedCount;
//         this.changeCountValue(this.getScheme(), diff, type);

//         this.accessSendForm(checkedCount, allowedCount, type, null);
//     }

//     checkboxListener(selector, type) {
//         const checkboxList = document.querySelectorAll(selector);

//         checkboxList.forEach(item => {
//             item.addEventListener('change', (evt) => this.handleCheckboxChange(evt, checkboxList, type));
//         });
//     }

//     calculateAdditions(checkboxes) {
//         return Array.from(checkboxes).reduce((sum, cb) => cb.checked ? sum + Number(cb.dataset.price) : sum, 0);
//     }

//     handleCheckboxAddChange(list, type, labelCheckBox) {
//         const sum = this.calculateAdditions(list);

//         // Обновляем цену в объекте sumOfPoke и обновляем сумму в корзине
//         this.storage[type] = Number(sum) || 0;
//         this.updateBasketSum();

//         // Обновляем метку цены для добавок
//         const labelPrice = document.querySelector(labelCheckBox);
//         if (labelPrice) {
//             labelPrice.textContent = sum ? `+ ${sum} руб` : '';
//         }
//     }

//     checkboxAddListener(selector, type, labelCheckBox) {
//         const checkboxList = document.querySelectorAll(selector);

//         checkboxList.forEach(item => {
//             item.addEventListener('change', () => this.handleCheckboxAddChange(checkboxList, type, labelCheckBox));
//         });
//     }

//     accessSendForm(checkboxes = null, number = null, type, price) {
//         if (type === 'filler' || type === 'topping') {
//             this.access[type] = checkboxes === number;
//         } else if (type === 'protein' || type === 'base' || type === 'sauce' || type === 'crunch') {
//             this.access[type] = price !== null ? true : false;
//         }
//     }

//     submitForm() {
//         const form = document.querySelector(this.selectors.form);

//         form.addEventListener('submit', (event) => {
//             event.preventDefault();

//             let isSend = true;
//             let alertMessage = "";

//             for (const key in this.access) {
//                 if (!this.access[key]) {
//                     isSend = false;
//                     const requiredCount = schemaPokeNumber[this.getScheme()][key];
//                     alertMessage += `Выберите в категории ${key} ${requiredCount} чекбокса(ов).\n`;
//                 }
//             }

//             if (!isSend) {
//                 event.preventDefault();
//                 alert(alertMessage); // Показываем все сообщения сразу
//             }
//             console.log("Форма отправлена:", isSend);
//         });
//     }
// }


// const pokePageConfig = {
//     storageNames: {
//         sum: 'constructorPokeSum',
//         scheme: 'shemaPoke'
//     },
//     selectors: {
//         form: '.constructor-poke__form',
//         scheme: '.constructor-poke-shema',
//         fillerCount: '#fillerCounter',
//         toppingCount: '#topingCounter',
//         basketSum: '.basket__order-number',
//         basketSumInput: '#total-price',
//         checkBoxFiller: '.constructor-poke-item-checkbox--filler',
//         checkBoxTopping: '.constructor-poke-item-checkbox--toping',
//         checkBoxFillerAdd: '.constructor-poke-item-checkbox--fillerAdd',
//         checkBoxToppingAdd: '.constructor-poke-item-checkbox--toppingAdd',
//     }
// };

// if (pagePoke) {
//     new PokeManager(pokePageConfig);
// }

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


// ============ Новое ============

class SchemeManager {
    constructor(config) {
        this.config = config;
        this.scheme = localStorage.getItem(config.storageNames.scheme) || 1;
        this.fillerPokeItem = document.querySelector(config.selectors.fillerCount);
        this.toppingPokeItem = document.querySelector(config.selectors.toppingCount);
        this.updateSchemeDescription(); // Обновляем UI при инициализации
    }

    updateSchemeDescription() {
        const schemeData = schemaPokeNumber[this.scheme];
        this.fillerPokeItem.textContent = `/ Осталось ${schemeData.filler} из ${schemeData.filler}`;
        this.toppingPokeItem.textContent = `/ Осталось ${schemeData.topping} из ${schemeData.topping}`;
    }

    setScheme(newScheme) {
        this.scheme = newScheme;
        localStorage.setItem(this.config.storageNames.scheme, newScheme);
        this.updateSchemeDescription();
    }

    getSchemeData() {
        return schemaPokeNumber[this.scheme];
    }
}


class BasketSumManager {
    constructor(selectors) {
        this.basketPrice = document.querySelector(selectors.basketSum);
        this.basketInputPrice = document.querySelector(selectors.basketSumInput);
        this.storage = { protein: 0, proteinAdd: 0, fillerAdd: 0, toppingAdd: 0, sauceAdd: 0, crunchAdd: 0 };
    }

    updateBasketSum() {
        const sum = Object.values(this.storage).reduce((total, value) => total + value, 0);
        this.basketPrice.textContent = `${sum} руб`;
        this.basketInputPrice.value = sum;
    }

    updateStorageItem(key, value) {
        this.storage[key] = Number(value);
        this.updateBasketSum();
    }

    getStorageData() {
        return this.storage;
    }
}

class CheckboxManager {
    constructor(selectors, schemeManager) {
        this.selectors = selectors;
        this.schemeManager = schemeManager;
    }

    setupListeners() {
        document.querySelectorAll(this.selectors.checkBoxFiller).forEach(checkbox => {
            checkbox.addEventListener('change', this.handleCheckboxChange.bind(this, 'filler'));
        });
        document.querySelectorAll(this.selectors.checkBoxTopping).forEach(checkbox => {
            checkbox.addEventListener('change', this.handleCheckboxChange.bind(this, 'topping'));
        });
    }

    handleCheckboxChange(type, event) {
        const target = event.target;
        const checkboxes = document.querySelectorAll(this.selectors[`checkBox${type}`]);
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const allowedCount = schemaPokeNumber[this.schemeManager.getScheme()][type];

        if (checkedCount > allowedCount) {
            target.checked = false;
            event.preventDefault();
            // Update user interface or alert the user
        }
    }
}

class PokeManager {
    constructor(config) {
        this.config = config;
        this.schemeManager = new SchemeManager(config);
        this.basketManager = new BasketSumManager(config.selectors);
        this.checkboxManager = new CheckboxManager(config.selectors, this.schemeManager);

        this.init();
    }

    init() {
        this.schemeManager.updateSchemeDescription();
        this.checkboxManager.setupListeners();
        // Добавить другие инициализации и слушатели событий
    }
}


const pokePageConfig = {
    storageNames: {
        sum: 'constructorPokeSum',
        scheme: 'shemaPoke'
    },
    selectors: {
        form: '.constructor-poke__form',
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

const schemaPokeNumber = {
    1: { 'filler': 5, 'topping': 1 },
    2: { 'filler': 3, 'topping': 2 },
};

if (pagePoke) {
    new PokeManager(pokePageConfig);
}
