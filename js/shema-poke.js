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
    constructor(storageSumName, storageSchemeName, basketSumSelector, basketSumInputSelector, schemeSelector, fillerSelector, topingSelector) {
        this.storageSumName = storageSumName;
        this.storageSchemeName = storageSchemeName;
        this.basketSumSelector = basketSumSelector;
        this.basketSumInputSelector = basketSumInputSelector;
        this.schemeSelector = schemeSelector;
        this.fillerSelector = fillerSelector;
        this.topingSelector = topingSelector;

        this.sumOfPoke = {
            'protein': 0,
            'proteinAdd': 0,
            'filler': 0,
            'topping': 0,
            'sauce': 0,
            'crunch': 0,
        };

        this.setLocalStorageValue(this.storageSumName, JSON.stringify(this.sumOfPoke));
        this.setLocalStorageValue(this.storageSchemeName, 1);
    }

    calcAmountPrice(value) {
        let result = 0;

        for (const key in value) {
            result += value[key];
        }

        return result;
    }

    setLocalStorageValue(name, value) {
        localStorage.setItem(name, value);
    }

    getLocalStorageValue(value) {
        return localStorage.getItem(value);
    }

    updateBasketSum() {
        const basketSum = document.querySelector(this.basketSumSelector);
        const basketSumInput = document.querySelector(this.basketSumInputSelector);

        const poke = JSON.parse(this.getLocalStorageValue(this.storageSumName));
        const result = this.calcAmountPrice(poke);

        basketSum.textContent = `${result} руб`;
        basketSumInput.value = result;
    }

    updateSchemePoke() {
        const schemaPoke = document.querySelector(this.schemeSelector);

        schemaPoke.addEventListener('click', (evt) => {
            const target = evt.target;

            const schemaItem = target.closest('.constructor-poke-shema-item');
            const schemaItemValue = schemaItem.dataset.shemaPoke;

            setSchemePokeDescription(schemaItemValue);
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


    checkCheckboxList(list, type) {
        this.topingSelector = document.querySelectorAll('.constructor-poke-item-checkbox--filler');
        this.topingSelector = document.querySelectorAll('.constructor-poke-item-checkbox--topping');

        let checkedList = 0;

        list.forEach(item => {
            if (item.checked) {
                checkedList++;
            }
        });

        const number = this.getLocalStorageValue(this.storageSchemeName);

        list.forEach(item => {
            if (checkedList > shemaPokeNumber[number][type]) {
                item.checked = false;
            }
        });
    }

}


const option = [
    'constructorPokeSum',
    'shemaPoke',
    '.basket__order-number',
    '#total-price',
    '.constructor-poke-shema',
    '#fillerCounter',
    '#topingCounter',
    '.constructor-poke-item-checkbox--filler',
    '.constructor-poke-item-checkbox--toping',
];

if (pagePoke) {
    new pokeManager(option);
}

