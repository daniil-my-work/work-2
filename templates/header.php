<header class="header">
    <div class="header__wrapper">
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
                    
                    <?php if ($_SESSION && isset($_SESSION['user_role'])) : ?>
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

                <li class="header__nav-item">
                    <a class="header__nav-item-link" href="./basket.php">
                        Корзина
                    </a>
                </li>

                <li class="header__nav-item">
                    <a class="header__nav-item-link" href="./menu.php">
                        Меню
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>