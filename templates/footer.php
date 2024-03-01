<footer class="footer">
    <div class="footer__wrapper">
        <header class="footer__top">
            <h3 class="sub-title footer__top-title">
                Открой свой poke-room «Много рыбы»
            </h3>

            <button type="button" class="footer__top-button">
                Подробнее
            </button>
        </header>

        <!-- Контент с колонкаи меню и адрессов -->
        <div class="footer__content">
            <img src="../../img/svg/logo-footer.svg" alt="" class="footer__logo">

            <!-- Меню -->
            <div class="footer__column">
                <h3 class="sub-title footer__column-title">
                    Меню poke-room
                </h3>

                <?php if ($categoryList) : ?>
                    <ul class="footer__list">
                        <?php foreach ($categoryList as $category) : ?>
                            <li class="footer__item">
                                <a href="./menu.php?category=<?= $category['category_title']; ?>" class="footer__item-link">
                                    <?= $category['category_name']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- Меню -->
            <div class="footer__column">
                <h3 class="sub-title footer__column-title">
                    О нас
                </h3>

                <p class="text footer__column-text">
                    ....
                </p>
            </div>

            <!-- Адреса -->
            <div class="footer__column">
                <h3 class="sub-title footer__column-title">
                    Адреса
                </h3>

                <ul class="footer__list">
                    <li class="footer__item">
                        г. Ярославль, Свободы 52/39
                    </li>
                </ul>
            </div>
        </div>

        <footer class="footer__bottom">
            <p class="text footer__bottom-text">
                © poke-room «Много рыбы»
            </p>

            <a class="text footer__bottom-text" href="#">
                Политика обработки персональных данных
            </a>
        </footer>
    </div>
</footer>