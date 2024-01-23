// Функция для открытия и закртия меню
document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.header__burger');
    const nav = document.querySelector('.header__nav');

    burger.addEventListener('click', function () {
        nav.classList.toggle('show');
        burger.classList.toggle('show');
    });
});


// Уменьшает кол-во блюд
function decrementNumber(itemNode, inputNode, button, counter) {
    const value = Number(itemNode.textContent);
    const newValue = Number(itemNode.textContent) - 1;

    if (value <= 1) {
        button.classList.remove('hidden');
        counter.classList.add('hidden');

        itemNode.textContent = 1;
        inputNode.value = 1;

        itemCounter.removeEventListener('click', handleCounterClick);
    } else {
        itemNode.textContent = newValue;
        inputNode.value = newValue;
    }
}

// Прибавляет кол-во блюд
function incrementNumber(itemNode, inputNode) {
    const newValue = Number(itemNode.textContent) + 1
    itemNode.textContent = newValue;
    inputNode.value = newValue;
}

// Объявляем функцию обработчика на счетчик
function handleCounterClick(evt) {
    const target = evt.target;

    const menuItem = target.closest('.menu__item');
    const itemCounter = menuItem.querySelector('.basket__item-counter');
    const countNumberItem = menuItem.querySelector('.basket__item-count');
    const counterInput = menuItem.querySelector('.basket__item_input');
    const basketButton = menuItem.querySelector('.menu__item-button');

    const actionCounter = target.classList.contains('basket__item-action');
    if (!actionCounter) {
        return;
    }

    const minusActionButton = target.classList.contains('basket__item-action--minus');
    if (minusActionButton) {
        // console.log('Минус');

        decrementNumber(countNumberItem, counterInput, basketButton, itemCounter);
    }

    const plusActionButton = target.classList.contains('basket__item-action--plus');
    if (plusActionButton) {
        // console.log('Плюс');

        incrementNumber(countNumberItem, counterInput);
    }
}

const mainList = document.querySelector('.menu__list');

// Прибавляет или убавляет кол-во блюд в заказе
mainList.addEventListener('click', (evt) => {
    const target = evt.target;

    const inBasket = target.classList.contains('menu__item-button');
    if (!inBasket) {
        return;
    }

    const basketButton = target;
    const menuItem = target.closest('.menu__item');
    const itemCounter = menuItem.querySelector('.basket__item-counter');
    const itemCounterNumber = menuItem.querySelector('.basket__item-count');
    const itemCounterInput = menuItem.querySelector('.basket__item_input');

    basketButton.classList.add('hidden');
    itemCounter.classList.remove('hidden');
    itemCounterInput.value = Number(itemCounterNumber.textContent);

    // Прибавляет / уменьшает кол-во блюд
    itemCounter.addEventListener('click', handleCounterClick);
});


