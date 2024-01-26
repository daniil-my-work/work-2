<!-- Страница Меню -->
<div class="page__menu">
    <!-- Вкладка -->
    <div class="account__orders-group page__menu-group">
        <!-- Активный пункт: account__orders-group-link--active -->
        <a class="button--basic account__orders-group-link account__orders-group-link--active" href="./menu.php?category=soup">
            Суп
        </a>

        <a class="button--basic account__orders-group-link account__orders-group-link--active" href="./menu.php?category=rolls">
            Роллы
        </a>

        <a class="button--basic account__orders-group-link" href="">
            ...
        </a>
    </div>


    <!-- Меню -->
    <div class="menu">
        <div class="menu__wrapper">
            <h2 class="title menu__title">
                Суп
            </h2>

            <ul class="menu__list">
                <!-- 1 -->
                <li class="menu__item" data-product-id="1">
                    <?php $hiddenButton = isset($productsData['1']) ? 'hidden' : ''; ?>
                    <?php $hiddenCounter = !isset($productsData['1']) ? 'hidden' : ''; ?>

                    <img src="https://102922.selcdn.ru/nomenclature_images_test/1926609/7a849eb1-7100-4d77-bde2-d6bac3002d46.jpg" alt="" class="menu__item-img">

                    <div class="menu__item-content">
                        <h3 class="sub-title menu__item-title">
                            Поке микс Лосось-Тунец
                        </h3>

                        <p class="text menu__item-text">
                            Лосось, тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат
                            айсберг,...
                        </p>

                        <div class="menu__item-info">
                            <p class="text menu__item-price">
                                410
                            </p>

                            <div class="product-item__counter">
                                <button class="product-item__counter-button <?= $hiddenButton; ?>" type="button">
                                    В корзину
                                </button>

                                <div class="product-item__counter-number-wrapper <?= $hiddenCounter; ?>">
                                    <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($productsData['1']) ? $productsData['1'] : '0'; ?>">

                                    <span class="product-item__counter-action product-item__counter-action--minus">
                                        –
                                    </span>

                                    <p class="product-item__counter-number">
                                        <?= isset($productsData['1']) ? $productsData['1'] : '0'; ?>
                                    </p>

                                    <span class="product-item__counter-action product-item__counter-action--plus">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- 2 -->
                <li class="menu__item" data-product-id="2">
                    <?php $hiddenButton = isset($productsData['2']) ? 'hidden' : ''; ?>
                    <?php $hiddenCounter = !isset($productsData['2']) ? 'hidden' : ''; ?>

                    <img src="https://102922.selcdn.ru/nomenclature_images_test/1926609/7a849eb1-7100-4d77-bde2-d6bac3002d46.jpg" alt="" class="menu__item-img">

                    <div class="menu__item-content">
                        <h3 class="sub-title menu__item-title">
                            Поке микс Лосось-Тунец
                        </h3>

                        <p class="text menu__item-text">
                            Лосось, тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат
                            айсберг,...
                        </p>

                        <div class="menu__item-info">
                            <p class="text menu__item-price">
                                410
                            </p>

                            <div class="product-item__counter">
                                <button class="product-item__counter-button <?= $hiddenButton; ?>" type="button">
                                    В корзину
                                </button>

                                <div class="product-item__counter-number-wrapper <?= $hiddenCounter; ?>">
                                    <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($productsData['2']) ? $productsData['2'] : '0'; ?>">

                                    <span class="product-item__counter-action product-item__counter-action--minus">
                                        –
                                    </span>

                                    <p class="product-item__counter-number">
                                        <?= isset($productsData['2']) ? $productsData['2'] : '0'; ?>
                                    </p>

                                    <span class="product-item__counter-action product-item__counter-action--plus">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- 3 -->
                <li class="menu__item" data-product-id="3">
                    <?php $hiddenButton = isset($productsData['3']) ? 'hidden' : ''; ?>
                    <?php $hiddenCounter = !isset($productsData['3']) ? 'hidden' : ''; ?>

                    <img src="https://102922.selcdn.ru/nomenclature_images_test/1926609/7a849eb1-7100-4d77-bde2-d6bac3002d46.jpg" alt="" class="menu__item-img">

                    <div class="menu__item-content">
                        <h3 class="sub-title menu__item-title">
                            Поке микс Лосось-Тунец
                        </h3>

                        <p class="text menu__item-text">
                            Лосось, тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат
                            айсберг,...
                        </p>

                        <div class="menu__item-info">
                            <p class="text menu__item-price">
                                410
                            </p>

                            <div class="product-item__counter">
                                <button class="product-item__counter-button <?= $hiddenButton; ?>" type="button">
                                    В корзину
                                </button>

                                <div class="product-item__counter-number-wrapper <?= $hiddenCounter; ?>">
                                    <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($productsData['3']) ? $productsData['3'] : '0'; ?>">

                                    <span class="product-item__counter-action product-item__counter-action--minus">
                                        –
                                    </span>

                                    <p class="product-item__counter-number">
                                        <?= isset($productsData['3']) ? $productsData['3'] : '0'; ?>
                                    </p>

                                    <span class="product-item__counter-action product-item__counter-action--plus">
                                        +
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>