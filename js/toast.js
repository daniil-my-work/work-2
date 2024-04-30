
// // Отправляет данные на сервер о городе пользователя
// async function apiUpdateModalCity(params) {
//     try {
//         const response = await fetch("api-update-modal-city.php", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/x-www-form-urlencoded",
//             },
//             body: params.toString(),
//         });

//         if (!response.ok) {
//             throw new Error("Network response was not ok");
//         }

//         // console.log("Данные успешно обновлены в сессии");
//     } catch (error) {
//         console.error("There has been a problem with your fetch operation:", error);
//     }
// }

// // Отправляет данные на сервер о городе пользователя
// async function apiCloseToast(params) {
//     try {
//         const response = await fetch("api-close-toast.php", {
//             method: "POST",
//             headers: {
//                 "Content-Type": "application/x-www-form-urlencoded",
//             },
//             body: params.toString(),
//         });

//         if (!response.ok) {
//             throw new Error("Network response was not ok");
//         }

//         // console.log("Данные успешно обновлены в сессии");
//     } catch (error) {
//         console.error("There has been a problem with your fetch operation:", error);
//     }
// }


// // Функция для установки значения города в сессии
// async function getModalInfo(evt) {
//     const target = evt.target;
//     const toast = target.closest('.toast'); // Тост

//     if (!toast) {
//         return;
//     }

//     // Формирование строки параметров
//     const params = new URLSearchParams();

//     // Категория тоста
//     const categoryToast = toast.getAttribute('data-set-category');

//     // Айди тоста
//     const toastId = toast.getAttribute('data-set-popup-id');

//     // Скрывает и удаляет тост, при клике на крестик
//     if (target && target.classList.contains('btn-close')) {
//         toast.classList.remove('show');
//         toast.remove();

//         params.append("toastId", toastId);

//         // Удаляет тоаст из сессии по id
//         await apiCloseToast(params);
//     }

//     // Логика для тоста с городом
//     if (categoryToast === 'city') {
//         if (target.classList.contains('btn')) {
//             const buttonValue = target.textContent.trim();
//             params.append("cityValue", buttonValue);

//             try {
//                 // Обновляет данные о городе пользователя
//                 await apiUpdateModalCity(params);

//                 // Удаляет тоаст из сессии по id
//                 await apiCloseToast(params);

//                 // Скрывает и удаляет тост
//                 toast.classList.remove('show');
//                 toast.remove();

//                 // console.log('Поменял город');
//             } catch (err) {
//                 console.error('Ошибка при обновлении статуса заказа:', err);
//             }
//         }
//     }
// }

// // Обертка над Модальным окном
// const modalWrapper = document.querySelector('#alert-modal');

// // Добавляем обработчик события клика
// if (modalWrapper) {
//     modalWrapper.addEventListener('click', getModalInfo);
// }



class ModalManager {
    constructor(modalSelector) {
        this.modalWrapper = document.querySelector(modalSelector);
        
        if (this.modalWrapper) {
            this.modalWrapper.addEventListener('click', this.handleModalClick.bind(this));
        }
    }

    async apiUpdateModalCity(params) {
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
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
        }
    }

    async apiCloseToast(params) {
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
        } catch (error) {
            console.error("There has been a problem with your fetch operation:", error);
        }
    }

    async handleModalClick(evt) {
        const target = evt.target;
        const toast = target.closest('.toast');

        if (!toast) {
            return;
        }

        const params = new URLSearchParams();
        const categoryToast = toast.getAttribute('data-set-category');
        const toastId = toast.getAttribute('data-set-popup-id');

        if (target && target.classList.contains('btn-close')) {
            this.removeToast(toast);
            params.append("toastId", toastId);
            await this.apiCloseToast(params);
        }

        if (categoryToast === 'city' && target.classList.contains('btn')) {
            const buttonValue = target.textContent.trim();
            params.append("cityValue", buttonValue);

            try {
                await this.apiUpdateModalCity(params);
                await this.apiCloseToast(params);
                this.removeToast(toast);
            } catch (err) {
                console.error('Ошибка при обновлении города:', err);
            }
        }
    }

    removeToast(toastElement) {
        toastElement.classList.remove('show');
        toastElement.remove();
    }
}

// Использование класса для управления модальными окнами
const modalManager = new ModalManager('#alert-modal');
