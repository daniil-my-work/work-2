<!-- Страница Аккаунт -->
<div class="page__account">
    <div class="account">
        <!-- Общая инфа для всех кабинетов -->
        <div class="account__info">
            <img class="account__info-img" src="" alt="">

            <h3 class="account__info-name">
                <?= $user_name; ?>
            </h3>

            <!-- Таблица с данными -->
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th scope="row">
                            Почта:
                        </th>
                        <td>
                            test@yandex.ru
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            +7(980) 705 70 02
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Пароль:
                        </th>
                        <td>
                            ****
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Адрес:
                        </th>
                        <td>
                            Таким образом постоянный количественный рост и сфера нашей активности требуют
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Инфа для админа: права доступа админ -->
        <div class="account__orders-wrapper">
            <!-- Вкладка -->
            <div class="account__orders-group">
                <a class="button--basic account__orders-group-link" href="">
                    Заказы
                </a>

                <a class="button--basic account__orders-group-link" href="">
                    Клиенты
                </a>
            </div>

            <!-- Заказы -->
            <div class="account__orders-order hidden">
                <!-- Календарь -->
                <div class="account__orders-time">
                    <h3 class="sub-title">
                        Статистика:
                    </h3>

                    <input class="account__orders-input" type="date" name="" id="">
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders account__orders--active">
                    <h3 class="account__orders-title">
                        Активные заказы:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Время заказа
                                </th>
                                <th scope="col">
                                    Состав заказа
                                </th>
                                <th scope="col">
                                    Сумма заказа
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Статус
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>

                            <li class="menu__nav-item">
                                ...
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders">
                    <h3 class="account__orders-title">
                        Завершенные заказы:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    Время заказа
                                </th>
                                <th scope="col">
                                    Состав заказа
                                </th>
                                <th scope="col">
                                    Сумма заказа
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Статус
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Клиенты -->
            <div class="account__orders-client">
                <!-- Календарь -->
                <div class="account__orders-time">
                    <h3 class="sub-title">
                        Поиск:
                    </h3>

                    <input class="account__orders-input" type="text" name="" id="">
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders">
                    <h3 class="account__orders-title">
                        Список клиентов:
                    </h3>

                    <table class="table table-striped table-hover account__orders-table ">
                        <thead>
                            <tr>
                                <th scope="col">
                                    id пользователя
                                </th>
                                <th scope="col">
                                    Имя
                                </th>
                                <th scope="col">
                                    Контакты
                                </th>
                                <th scope="col">
                                    Адрес
                                </th>
                                <th scope="col">
                                    Рейтинг клиента
                                </th>
                                <th scope="col">
                                    Ср. чек
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Навигация для таблицы заказов -->
                    <nav class="menu__nav account__orders-nav">
                        <button class="menu__nav-button menu__nav-button--prev">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>

                        <ul class="menu__nav-list">
                            <li class="menu__nav-item">
                                1
                            </li>

                            <li class="menu__nav-item">
                                2
                            </li>

                            <li class="menu__nav-item">
                                3
                            </li>

                            <li class="menu__nav-item">
                                ...
                            </li>
                        </ul>

                        <button class="menu__nav-button menu__nav-button--next">
                            <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>