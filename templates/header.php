<header class="header">
    <div class="header__wrapper container">
        <a class="header__logo-link" href="./index.php">
            <img src="./img/assets/logo.svg" alt="" class="header__logo">
        </a>

        <div class="header__burger">
            <span class="header__burger-line"></span>
            <span class="header__burger-line"></span>
            <span class="header__burger-line"></span>
            <span class="header__burger-line"></span>
        </div>

        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a class="header__nav-item-link" href="./index.php">
                        Главная
                    </a>
                </li>

                <li class="header__nav-item">
                    <?php if (!$isAuth) : ?>
                        <a class="header__nav-item-link" href="./reg.php">
                            Регистрация
                        </a>

                        <a class="header__nav-item-link" href="./auth.php">
                            Войти
                        </a>
                    <?php else : ?>
                        <a class="header__nav-item-link" href="./logout.php">
                            Выйти
                        </a>
                    <?php endif; ?>
                </li>

                <li class="header__nav-item">
                    <?php $accountLink = './account.php'; ?>

                    <?php if (isset($_SESSION['user_role'])) : ?>
                        <?php if ($_SESSION['user_role'] == $userRole['owner']) : ?>
                            <?php $accountLink = './owner.php'; ?>
                        <?php elseif ($_SESSION['user_role'] == $userRole['admin']) : ?>
                            <?php $accountLink = './admin.php'; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <a class="header__nav-item-link" href="<?= $accountLink; ?>">
                        Личный кабинет
                    </a>
                </li>

                <?php if (isset($_SESSION['user_role'])) : ?>
                    <?php if ($_SESSION['user_role'] == $userRole['client']) : ?>
                        <li class="header__nav-item">
                            <a class="header__nav-item-link" href="./basket.php">
                                Корзина
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <li class="header__nav-item">
                    <a class="header__nav-item-link" href="./menu.php">
                        Меню
                    </a>
                </li>

                <?php if (isset($_SESSION['user_role'])) : ?>
                    <?php if ($_SESSION['user_role'] == $userRole['owner'] || $_SESSION['user_role'] == $userRole['admin']) : ?>
                        <li class="header__nav-item">
                            <a class="header__nav-item-link" href="./load-menu.php">
                                Редактировать меню
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>