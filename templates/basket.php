<!-- Страница Корзина -->
<div class="page__basket">
    <span class="basket__breadcrumbs">
        Назад в меню
    </span>

    <img src="./img/svg/bag.svg" alt="" class="basket__icon">

    <!-- Форма заказа: Корзина -->
    <form class="page__basket-form" method="post" action="basket.php">
        <ul class="basket__list">
            <?php if ($productLength == 1) : ?>
                <?php $productId = $products['id']; ?>
                <li class="basket__item" data-product-id="<?= $productId ?>">
                    <img src="<?= $products['img']; ?>" alt="" class="basket__item-img">

                    <div class="basket__item-info">
                        <p class="sub-title basket__item-title">
                            <?= $products['title']; ?>
                        </p>

                        <p class="text basket__item-price">
                            <?= $products['price']; ?>
                        </p>
                    </div>

                    <div class="product-item__counter">
                        <div class="product-item__counter-number-wrapper">
                            <input class="product-item__counter-input" type="hidden" name="productId" value="<?= $productsData[$productId]; ?>">
                            <span class="product-item__counter-action product-item__counter-action--minus">
                                –
                            </span>

                            <p class="product-item__counter-number">
                                <?= isset($productsData[$productId]) ? $productsData[$productId] : '0'; ?>
                            </p>

                            <span class="product-item__counter-action product-item__counter-action--plus">
                                +
                            </span>
                        </div>

                        <button class="product-item__counter-button product-item__counter-button--basket" type="button">
                            <svg class="product-item__counter-button-icon--basket" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                <path d="M56.521 10.182a3.522 3.522 0 0 0-3.518-3.518H42.3V3.008C42.3 1.35 41.048 0 39.509 0h-15.02C22.952 0 21.7 1.35 21.7 3.008v3.656H10.997a3.522 3.522 0 0 0-3.518 3.518c0 1.768 1.316 3.221 3.018 3.465l5.188 45.877A5.042 5.042 0 0 0 20.698 64h22.604a5.04 5.04 0 0 0 5.012-4.477l5.189-45.877c1.701-.244 3.018-1.697 3.018-3.464zM23.64 3.008c0-.578.389-1.068.85-1.068h15.02c.461 0 .852.49.852 1.068v3.656H23.64V3.008zm22.746 56.297a3.102 3.102 0 0 1-3.084 2.756H20.698a3.104 3.104 0 0 1-3.086-2.756l-5.156-45.607h39.088l-5.158 45.607zm6.617-47.547H10.997a1.578 1.578 0 0 1 0-3.154h42.006a1.58 1.58 0 0 1 1.578 1.578 1.58 1.58 0 0 1-1.578 1.576z" />
                                <path d="M43.841 18.469a.96.96 0 0 0-1.049.883l-3.184 36.846a.972.972 0 0 0 .967 1.053.967.967 0 0 0 .965-.885l3.184-36.846a.97.97 0 0 0-.883-1.051zm-23.684 0a.97.97 0 0 0-.881 1.051l3.184 36.846a.967.967 0 0 0 .965.885c.027 0 .055 0 .084-.002a.973.973 0 0 0 .883-1.051l-3.184-36.846a.968.968 0 0 0-1.051-.883zm11.842-.004a.97.97 0 0 0-.969.971v36.846a.97.97 0 0 0 1.94 0V19.436a.97.97 0 0 0-.971-.971z" />
                            </svg>
                        </button>
                    </div>
                </li>
            <?php else : ?>
                <?php foreach ($products as $product) : ?>
                    <?php $productId = $product['id']; ?>
                    <li class="basket__item" data-product-id="<?= $productId ?>">
                        <img src="<?= $product['img']; ?>" alt="" class="basket__item-img">

                        <div class="basket__item-info">
                            <p class="sub-title basket__item-title">
                                <?= $product['title']; ?>
                            </p>

                            <p class="text basket__item-price">
                                <?= $product['price']; ?>
                            </p>
                        </div>

                        <div class="product-item__counter">
                            <div class="product-item__counter-number-wrapper">
                                <input class="product-item__counter-input" type="hidden" name="productId" value="<?= $productsData[$productId]; ?>">
                                <span class="product-item__counter-action product-item__counter-action--minus">
                                    –
                                </span>

                                <p class="product-item__counter-number">
                                    <?= isset($productsData[$productId]) ? $productsData[$productId] : '0'; ?>
                                </p>

                                <span class="product-item__counter-action product-item__counter-action--plus">
                                    +
                                </span>
                            </div>

                            <button class="product-item__counter-button product-item__counter-button--basket" type="button">
                                <svg class="product-item__counter-button-icon--basket" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                    <path d="M56.521 10.182a3.522 3.522 0 0 0-3.518-3.518H42.3V3.008C42.3 1.35 41.048 0 39.509 0h-15.02C22.952 0 21.7 1.35 21.7 3.008v3.656H10.997a3.522 3.522 0 0 0-3.518 3.518c0 1.768 1.316 3.221 3.018 3.465l5.188 45.877A5.042 5.042 0 0 0 20.698 64h22.604a5.04 5.04 0 0 0 5.012-4.477l5.189-45.877c1.701-.244 3.018-1.697 3.018-3.464zM23.64 3.008c0-.578.389-1.068.85-1.068h15.02c.461 0 .852.49.852 1.068v3.656H23.64V3.008zm22.746 56.297a3.102 3.102 0 0 1-3.084 2.756H20.698a3.104 3.104 0 0 1-3.086-2.756l-5.156-45.607h39.088l-5.158 45.607zm6.617-47.547H10.997a1.578 1.578 0 0 1 0-3.154h42.006a1.58 1.58 0 0 1 1.578 1.578 1.58 1.58 0 0 1-1.578 1.576z" />
                                    <path d="M43.841 18.469a.96.96 0 0 0-1.049.883l-3.184 36.846a.972.972 0 0 0 .967 1.053.967.967 0 0 0 .965-.885l3.184-36.846a.97.97 0 0 0-.883-1.051zm-23.684 0a.97.97 0 0 0-.881 1.051l3.184 36.846a.967.967 0 0 0 .965.885c.027 0 .055 0 .084-.002a.973.973 0 0 0 .883-1.051l-3.184-36.846a.968.968 0 0 0-1.051-.883zm11.842-.004a.97.97 0 0 0-.969.971v36.846a.97.97 0 0 0 1.94 0V19.436a.97.97 0 0 0-.971-.971z" />
                                </svg>
                            </button>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>


        <div class="basket__order">
            <p class="basket__order-text">
                <?php if ($productLength == 0) : ?>
                    Ваша корзина пуста
                <?php else : ?>
                    Ваш заказ:

                    <span class="basket__order-number">
                        <?= $fullPrice; ?> руб
                    </span>
                <?php endif; ?>
            </p>

            <?php $isHidden = $productLength == 0 ? 'hidden' : ''; ?>
            <button type="submit" class="basket__order-button <?= $isHidden; ?>">
                Заказать
            </button>
        </div>
    </form>
</div>