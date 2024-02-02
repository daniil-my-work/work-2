<!-- Страница Аккаунт -->
<div class="page__account">
    <div class="account">
        <!-- Общая инфа для всех кабинетов -->
        <div class="account__info">
            <img class="account__info-img" src="<?= $userInfo['user_img']; ?>" alt="">

            <h3 class="account__info-name">
                <?= $userInfo['user_name']; ?>
            </h3>

            <!-- Таблица с данными -->
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th scope="row">
                            Почта:
                        </th>
                        <td>
                            <?= $userInfo['email']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            <?= $userInfo['telephone']; ?>
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

        <!-- Инфа для клиентов: права доступа клиент -->
        <div class="account__orders">
            <h3 class="account__orders-title">
                История заказов:
            </h3>

            <input class="account__orders-input" type="date" name="" id="">

            <button class="button--basic mb-4">
                Загрузить
            </button>


            <!-- Таблица заказов пользователя -->
            <table class="table table-striped table-hover account__orders-table ">
                <thead>
                    <tr>
                        <th scope="col">
                            Номер
                        </th>
                        <th scope="col">
                            Дата
                        </th>
                        <th scope="col">
                            Состав заказа
                        </th>
                        <th scope="col">
                            Сумма заказа
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Проходится по массиву заказов -->
                    <?php foreach ($keys as $key) : ?>
                        <?php if (count($groupedItems[$key]) == 1) : ?>
                            <?php $groupedItemFirst = $groupedItems[$key][0]; ?>
                            <tr>
                                <th scope="row">
                                    <?= $groupedItemFirst['order_id']; ?>
                                </th>
                                <td>
                                    <?= $groupedItemFirst['order_date']; ?>
                                </td>
                                <td>
                                    <?= $groupedItemFirst['title']; ?>
                                    *
                                    <?= $groupedItemFirst['quantity']; ?>
                                </td>
                                <td>
                                    <?= $groupedItemFirst['total_amount']; ?>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php $groupedItemFirst = $groupedItems[$key][0]; ?>
                            <tr>
                                <th scope="row">
                                    <?= $groupedItemFirst['order_id']; ?>
                                </th>
                                <td>
                                    <?= $groupedItemFirst['order_date']; ?>
                                </td>
                                <td>
                                    <?php foreach ($groupedItems[$key] as $groupedSubItem) : ?>
                                        <?= $groupedSubItem['title']; ?>
                                        *
                                        <?= $groupedSubItem['quantity']; ?>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?= $groupedItemFirst['total_amount']; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
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
</div>