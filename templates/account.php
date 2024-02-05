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


            <form class="account__orders-calendar" action="./account.php" method="POST">
                <!-- Календарь -->
                <div class="input__date-wrapper">
                    <input class="" type="hidden" id="date-first" name="date-first" placeholder="Дата начала">
                    <input class="" type="hidden" id="date-second" name="date-second" placeholder="Дата окончания">

                    <input class="input__date account__orders-input" type="text" id="datepicker" placeholder="Выберите дату">

                    <svg class="input__date-icon" xmlns="http://www.w3.org/2000/svg" width="1792" height="1792" viewBox="0 0 1792 1792">
                        <path d="M192 1664h1408V640H192v1024zM576 448V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm768 0V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm384-64v1280q0 52-38 90t-90 38H192q-52 0-90-38t-38-90V384q0-52 38-90t90-38h128v-96q0-66 47-113T480 0h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z" />
                    </svg>
                </div>

                <button class="button--basic">
                    Загрузить
                </button>
            </form>


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