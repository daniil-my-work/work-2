<!-- Страница Аккаунт -->
<div class="page__account">
    <div class="account">
        <!-- Общая инфа для всех кабинетов -->
        <div class="account__info">
            <img class="account__info-img" src="<?= isset($userInfo['user_img']) ? $userInfo['user_img'] : ''; ?>" alt="">

            <h3 class="account__info-name">
                <?= isset($userInfo['user_name']) ? $userInfo['user_name'] : ''; ?>
            </h3>

            <!-- Таблица с данными -->
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th scope="row">
                            Почта:
                        </th>
                        <td>
                            <?= isset($userInfo['user_email']) ? $userInfo['user_email'] : ''; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            Номер телефона:
                        </th>
                        <td>
                            <?= isset($userInfo['user_telephone']) ? $userInfo['user_telephone'] : ''; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <!-- Инфа для админа: права доступа админ -->
        <div class="account__orders-wrapper">
            <!-- Вкладка -->
            <div class="account__orders-group">
                <?php $isActiveGroupOrders = $statisticGroup == 'orders' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupOrders; ?>" href="./admin.php?group=orders">
                    Заказы
                </a>

                <?php $isActiveGroupClients = $statisticGroup == 'clients' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $isActiveGroupClients; ?>" href="./admin.php?group=clients">
                    Клиенты
                </a>
            </div>

            <!-- Заказы -->
            <?php $isHiddenOrders = $statisticGroup != 'orders' ? 'hidden' : ''; ?>
            <div class="account__orders-order <?= $isHiddenOrders; ?>">
                <form class="account__orders-calendar account__orders-calendar--second" action="./admin.php" method="POST">
                    <h3 class="sub-title">
                        Статистика:
                    </h3>

                    <div class="account__orders-time">
                        <!-- Календарь -->
                        <div class="input__date-wrapper">
                            <input class="" type="hidden" id="date-first" name="date-first" placeholder="Дата начала" value="<?= is_null($dateFirst) ? '' : $dateFirst; ?>">
                            <input class="" type="hidden" id="date-second" name="date-second" placeholder="Дата окончания" value="<?= is_null($dateSecond) ? '' : $dateSecond; ?>">

                            <input class="input__date account__orders-input" type="text" id="datepicker" placeholder="Выберите дату">

                            <svg class="input__date-icon" xmlns="http://www.w3.org/2000/svg" width="1792" height="1792" viewBox="0 0 1792 1792">
                                <path d="M192 1664h1408V640H192v1024zM576 448V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm768 0V160q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v288q0 14 9 23t23 9h64q14 0 23-9t9-23zm384-64v1280q0 52-38 90t-90 38H192q-52 0-90-38t-38-90V384q0-52 38-90t90-38h128v-96q0-66 47-113T480 0h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z" />
                            </svg>
                        </div>

                        <button class="button--basic">
                            Загрузить
                        </button>
                    </div>
                </form>


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
                                    Id заказа
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
                                    Выполнен
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Проходится по массиву заказов -->
                            <?php if (count($orderListActive) != 0) : ?>
                                <?php foreach ($keysActive as $key) : ?>
                                    <?php if (count($orderListActive[$key]) == 1) : ?>
                                        <?php $groupedItemFirst = $orderListActive[$key][0]; ?>
                                        <tr>
                                            <th scope="row">
                                                <?= $groupedItemFirst['order_date']; ?>
                                            </th>
                                            <td>
                                                <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                    <?= $groupedItemFirst['order_id']; ?>
                                                </a>
                                            </td>
                                            <td class="account__orders-col--big">
                                                <?= $groupedItemFirst['title']; ?>
                                                *
                                                <?= $groupedItemFirst['quantity']; ?>
                                            </td>
                                            <td>
                                                <?= $groupedItemFirst['total_amount']; ?>
                                            </td>
                                            <td class="account__orders-col--middle">
                                                <?= $groupedItemFirst['order_address']; ?>
                                            </td>
                                            <td>
                                                <?= $groupedItemFirst['date_end']; ?>
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php $groupedItemFirst = $orderListActive[$key][0]; ?>
                                        <tr>
                                            <th scope="row">
                                                <?= $groupedItemFirst['order_date']; ?>
                                            </th>
                                            <td>
                                                <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                    <?= $groupedItemFirst['order_id']; ?>
                                                </a>
                                            </td>
                                            <td class="account__orders-col--big">
                                                <?php foreach ($orderListActive[$key] as $groupedSubItem) : ?>
                                                    <?= $groupedSubItem['title']; ?>
                                                    *
                                                    <?= $groupedSubItem['quantity']; ?>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?= $groupedItemFirst['total_amount']; ?>
                                            </td>
                                            <td class="account__orders-col--middle">
                                                <?= $groupedItemFirst['order_address']; ?>
                                            </td>
                                            <td class="account__orders-check">
                                                <input class="form-check-input account__orders-checkbox" type="checkbox" value="" id="flexCheckDefault">
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Активные заказы -->
                    <?php $prevPageNumberActive = $currentPageActive - 1; ?>
                    <?php $nextPageNumberActive = $currentPageActive + 1; ?>


                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationActive && count($paginationActive) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageActive > 1) : ?>
                                <a href="./admin.php?pageActive=<?= $prevPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageActive == count($paginationActive) && $prevPageNumberActive != 1) : ?>
                                    <a href="./admin.php?pageActive=<?= $prevPageNumberActive - 1; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberActive - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberActive != 0) : ?>
                                    <a href="./admin.php?pageActive=<?= $prevPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberActive; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageActive; ?>
                                </p>

                                <?php if ($currentPageActive != count($paginationActive)) : ?>
                                    <a href="./admin.php?pageActive=<?= $nextPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberActive; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberActive == 0 && $nextPageNumberActive != count($paginationActive)) : ?>
                                    <a href="./admin.php?pageActive=<?= $nextPageNumberActive + 1; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberActive + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageActive != count($paginationActive)) : ?>
                                <a href="./admin.php?pageActive=<?= $nextPageNumberActive; ?>&pageСomplete=<?= $currentPageСomplete; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>

                <!-- Таблица с данными -->
                <div class="account__orders account__orders--complete">
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
                                    Id заказа
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
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Проходится по массиву заказов -->
                            <?php if (count($orderListСomplete) != 0) : ?>
                                <?php foreach ($keysActive as $key) : ?>
                                    <?php if (count($orderListСomplete[$key]) == 1) : ?>
                                        <?php $groupedItemFirst = $orderListСomplete[$key][0]; ?>
                                        <tr>
                                            <th scope="row">
                                                <?= $groupedItemFirst['order_date']; ?>
                                            </th>
                                            <td>
                                                <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                    <?= $groupedItemFirst['order_id']; ?>
                                                </a>
                                            </td>
                                            <td class="account__orders-col--big">
                                                <?= $groupedItemFirst['title']; ?>
                                                *
                                                <?= $groupedItemFirst['quantity']; ?>
                                            </td>
                                            <td>
                                                <?= $groupedItemFirst['total_amount']; ?>
                                            </td>
                                            <td class="account__orders-col--middle">
                                                <?= $groupedItemFirst['order_address']; ?>
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php $groupedItemFirst = $orderListСomplete[$key][0]; ?>
                                        <tr>
                                            <th scope="row">
                                                <?= $groupedItemFirst['order_date']; ?>
                                            </th>
                                            <td>
                                                <a href="./order.php?orderId=<?= $groupedItemFirst['order_id']; ?>&prevLink=account">
                                                    <?= $groupedItemFirst['order_id']; ?>
                                                </a>
                                            </td>
                                            <td class="account__orders-col--big">
                                                <?php foreach ($orderListСomplete[$key] as $groupedSubItem) : ?>
                                                    <?= $groupedSubItem['title']; ?>
                                                    *
                                                    <?= $groupedSubItem['quantity']; ?>
                                                <?php endforeach; ?>
                                            </td>
                                            <td>
                                                <?= $groupedItemFirst['total_amount']; ?>
                                            </td>
                                            <td class="account__orders-col--middle">
                                                <?= $groupedItemFirst['order_address']; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>


                    <!-- Заверешенные заказы -->
                    <?php $prevPageNumberСomplete = $currentPageСomplete + 1; ?>
                    <?php $nextPageNumberСomplete = $currentPageСomplete + 1; ?>

                    <!-- Навигация для таблицы заказов -->
                    <?php if ($paginationСomplete && count($paginationСomplete) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">

                            <?php if ($currentPageСomplete > 1) : ?>
                                <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPageСomplete == count($paginationСomplete) && $prevPageNumberСomplete != 1) : ?>
                                    <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete - 1; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberСomplete - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberСomplete != 0) : ?>
                                    <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $prevPageNumberСomplete; ?>" class="menu__nav-item">
                                        <?= $prevPageNumberСomplete; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPageСomplete; ?>
                                </p>

                                <?php if ($currentPageСomplete != count($paginationСomplete)) : ?>
                                    <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberСomplete; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumberСomplete == 0 && $nextPageNumberСomplete != count($paginationСomplete)) : ?>
                                    <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete + 1; ?>" class="menu__nav-item">
                                        <?= $nextPageNumberСomplete + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPageСomplete != count($paginationСomplete)) : ?>
                                <a href="./admin.php?pageActive=<?= $currentPageActive; ?>&pageСomplete=<?= $nextPageNumberСomplete; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Клиенты -->
            <?php $isHiddenClients = $statisticGroup != 'clients' ? 'hidden' : ''; ?>
            <div class="account__orders-client <?= $isHiddenClients; ?>">
                <!-- Календарь -->
                <div class="account__orders-time">
                    <h3 class="sub-title">
                        Поиск:
                    </h3>

                    <input class="account__orders-input" type="text" name="" id="">

                    <button class="button--basic">
                        Загрузить
                    </button>
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