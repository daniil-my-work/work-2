<!-- Страница Корзина -->
<div class="page__basket">
    <span class="basket__breadcrumbs">
        Назад в меню
    </span>

    <img src="./img/svg/bag.svg" alt="" class="basket__icon">

    <!-- Форма заказа: Корзина -->
    <form class="page__basket-form" method="post" action="basket.php">
        <ul class="basket__list">
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
        </ul>


        <div class="basket__order">
            <p class="basket__order-text">
                Ваш заказ:

                <span class="basket__order-number">
                    4.000 руб
                </span>
            </p>

            <button type="submit" class="basket__order-button">
                Заказать
            </button>
        </div>
    </form>
</div>