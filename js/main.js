// Функция для открытия и закртия меню
document.addEventListener('DOMContentLoaded', function () {
    const burger = document.querySelector('.header__burger');
    const nav = document.querySelector('.header__nav');

    burger.addEventListener('click', function () {
        nav.classList.toggle('show');
        burger.classList.toggle('show');
    });
});



function decrementNumber(itemNode, inputNode) {
    const value = Number(itemNode.textContent);
    const newValue = Number(itemNode.textContent) - 1;

    if (value < 1) {
        itemNode.textContent = 0;
        inputNode.value = 0;
    }

    itemNode.textContent = newValue;
    inputNode.value = newValue;
}

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
        console.log('Минус');

        decrementNumber(countNumberItem, counterInput);

        if (Number(countNumberItem.textContent) < 1) {
            console.log('dsds');

            basketButton.classList.remove('hidden');
            itemCounter.classList.add('hidden');

            itemCounter.removeEventListener('click', handleCounterClick);
        }
    }

    const plusActionButton = target.classList.contains('basket__item-action--plus');
    if (plusActionButton) {
        console.log('Плюс');

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
    const countNumberItem = menuItem.querySelector('.basket__item-count');
    const counterInput = menuItem.querySelector('.basket__item_input');

    basketButton.classList.add('hidden');
    itemCounter.classList.remove('hidden');
    counterInput.value = Number(countNumberItem.textContent);

    // Прибавляет / уменьшает кол-во блюд
    itemCounter.addEventListener('click', handleCounterClick);
});


