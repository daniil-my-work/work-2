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
    constructor(storageSumName, storageSchemeName, basketSumSelector, basketSumInputSelector, schemeSelector) {
        this.storageSumName = storageSumName;
        this.storageSchemeName = storageSchemeName;
        this.basketSumSelector = basketSumSelector;
        this.basketSumInputSelector = basketSumInputSelector;
        this.schemeSelector = schemeSelector;

        this.sumOfPoke = {
            'protein': 0,
            'proteinAdd': 0,
            'filler': 0,
            'topping': 0,
            'sauce': 0,
            'crunch': 0,
        };

        setLocalStorageValue(this.storageSumName, JSON.stringify(this.sumOfPoke));
        setLocalStorageValue(this.storageSchemeName, 1);
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
        return JSON.parse(localStorage.getItem(value));
    }

    updateBasketSum() {
        const basketSum = document.querySelector(this.basketSumSelector);
        const basketSumInput = document.querySelector(this.basketSumInputSelector);

        const poke = this.getLocalStorageValue(this.storageSumName);
        const result = this.calcAmountPrice(poke);

        basketSum.textContent = `${result} руб`;
        basketSumInput.value = result;
    }

    updateSchemePoke() {
        const schemaPoke = document.querySelector(this.schemeSelector);

        schemaPoke.adde
    }

    //     localStorage.setItem(this.storageName, JSON.stringify(this.sumOfPoke));
    // }

    // setLocalStoragePoke() {
    //     localStorage.setItem(this.storageName, JSON.stringify(this.sumOfPoke));
    // }


}


if (pagePoke) {
    new pokeManager('constructorPokeSum', 'shemaPoke', '.basket__order-number', '#total-price', '.constructor-poke-shema');
}

