


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


