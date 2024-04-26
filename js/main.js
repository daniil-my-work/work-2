// localStorage.clear();

// Обработчик отправки формы
const constructorPokeForm = document.querySelector('.constructor-poke__form');

if (constructorPokeForm) {
    constructorPokeForm.addEventListener('submit', function (event) {
        const number = localStorage.getItem('shemaPoke');
        let checkedFillerCheckbox = 0;
        let checkedToppingCheckbox = 0;

        checkboxFillerList.forEach(fillerItem => {
            if (fillerItem.checked) {
                checkedFillerCheckbox++;
            }
        });

        checkboxTopingList.forEach(toppingItem => {
            if (toppingItem.checked) {
                checkedToppingCheckbox++;
            }
        })

        if (checkedFillerCheckbox !== shemaPokeNumber[number]['filler']) {
            event.preventDefault(); // Предотвращаем отправку формы
            alert(`Выберите в категории наполнитель ${shemaPokeNumber[number]['filler']} чекбокса(ов)`);
        }

        if (checkedToppingCheckbox !== shemaPokeNumber[number]['toping']) {
            event.preventDefault(); // Предотвращаем отправку формы
            alert(`Выберите в категории топпинг ${shemaPokeNumber[number]['toping']} чекбокса(ов)`);
        }
    });
}



// Отправляет данные на сервер для сохранения в сессии
async function apiUpdateOrderStatus(params) {
    try {
        const response = await fetch("api-update-order-status.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


async function changeOrderStatus(evt) {
    const target = evt.target;
    if (!target.classList.contains('account__orders-checkbox')) {
        return;
    }

    // Доступ к родительскому tr элементу
    const parentTr = target.closest('tr');

    // Найти input элемент внутри tr
    const input = parentTr.querySelector('input[name="order-id"]');

    // Получить значение атрибута value
    const orderId = input.value;

    // Формирование строки параметров
    const params = new URLSearchParams();
    params.append("orderId", orderId);

    const parentTable = target.closest('table');
    console.log(parentTable);
    if (parentTable.id === 'account__orders-table--active') {
        params.append("status", true);
    } else {
        params.append("status", false);
    }

    try {
        // Обновляет данные о статусе заказа
        await apiUpdateOrderStatus(params);

        location.reload();
        // console.log('Поменял статус заказа');
    } catch (err) {
        console.error('Ошибка при обновлении статуса заказа:', err);
    }
}


const activeTable = document.querySelector('#account__orders-table--active');
const completeTable = document.querySelector('#account__orders-table--complete');

if (activeTable) {
    activeTable.addEventListener('click', changeOrderStatus);
}

if (completeTable) {
    completeTable.addEventListener('click', changeOrderStatus);
}




// Отправляет данные на сервер о городе пользователя
async function apiUpdateModalCity(params) {
    try {
        const response = await fetch("api-update-modal-city.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}

// Отправляет данные на сервер о городе пользователя
async function apiCloseToast(params) {
    try {
        const response = await fetch("api-close-toast.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: params.toString(),
        });

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


// Функция для установки значения города в сессии
async function getModalInfo(evt) {
    const target = evt.target;
    const toast = target.closest('.toast'); // Тост

    if (!toast) {
        return;
    }

    // Формирование строки параметров
    const params = new URLSearchParams();

    // Категория тоста
    const categoryToast = toast.getAttribute('data-set-category');

    // Айди тоста
    const toastId = toast.getAttribute('data-set-popup-id');

    // Скрывает и удаляет тост, при клике на крестик
    if (target && target.classList.contains('btn-close')) {
        toast.classList.remove('show');
        toast.remove();

        params.append("toastId", toastId);

        // Удаляет тоаст из сессии по id
        await apiCloseToast(params);
    }

    // Логика для тоста с городом
    if (categoryToast === 'city') {
        if (target.classList.contains('btn')) {
            const buttonValue = target.textContent.trim();
            params.append("cityValue", buttonValue);

            try {
                // Обновляет данные о городе пользователя
                await apiUpdateModalCity(params);

                // Удаляет тоаст из сессии по id
                await apiCloseToast(params);

                // Скрывает и удаляет тост
                toast.classList.remove('show');
                toast.remove();

                // console.log('Поменял город');
            } catch (err) {
                console.error('Ошибка при обновлении статуса заказа:', err);
            }
        }
    }
}

// Обертка над Модальным окном
const modalWrapper = document.querySelector('#alert-modal');

// Добавляем обработчик события клика
if (modalWrapper) {
    modalWrapper.addEventListener('click', getModalInfo);
}


// const tabGroup = document.querySelector('.load__update-tab');
// tabGroup.addEventListener('click', )




// Функция для скачивания таблицы csv
async function apiGetInfoFromMenu(tabGroup) {
    try {
        const response = await fetch(`fetch-data-from-db.php?tabGroup=${tabGroup}`);

        if (!response.ok) {
            throw new Error("Network response was not ok");
        }

        // Конвертируем ответ сервера в текст
        const csvData = await response.text();

        // Создаем объект Blob для данных CSV
        const blob = new Blob([csvData], { type: 'text/csv' });

        // Создаем ссылку для скачивания файла
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'data.csv';

        // Эмулируем щелчок по ссылке для скачивания
        link.click();

        // console.log("Данные успешно обновлены в сессии");
    } catch (error) {
        console.error("There has been a problem with your fetch operation:", error);
    }
}


// Функция для обработки клика на кнопку
async function getInfoFromMenu() {
    const tabGroup = document.querySelector('#tab-group').textContent;

    try {
        // Вызываем функцию для скачивания файла
        await apiGetInfoFromMenu(tabGroup);

        console.log('Скачал файл');
    } catch (err) {
        console.error('Ошибка при обновлении статуса заказа:', err);
    }
}

const loadDataButton = document.querySelector('.load__current-button');
if (loadDataButton) {
    loadDataButton.addEventListener('click', getInfoFromMenu);
}


