<!-- Страница Меню -->
<div class="page__menu">
    <!-- Вкладка -->
    <div class="account__orders-group page__menu-group">
        <?php foreach ($categoryList as $category) : ?>
            <?php $isActive = $category['category__title'] === $activeCategory ? 'account__orders-group-link--active' : ''; ?>
            <a class="button--basic account__orders-group-link <?= $isActive; ?>" href="./menu.php?category=<?= $category['category__title']; ?>">
                <?= $category['category__name']; ?>
            </a>
        <?php endforeach; ?>
    </div>


    <!-- Меню -->
    <div class="menu">
        <div class="menu__wrapper">
            <h2 class="title menu__title">
                <?= formatFirstLetter($categoryName['category__name']); ?>
            </h2>

            <ul class="menu__list">
                <?php if ($activeCategory == 'poke') : ?>
                    dsd
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <?php $productId = $product['id']; ?>
                        <li class="menu__item" data-product-id="<?= $productId ?>">
                            <?php $hiddenButton = isset($productsData[$productId]) ? 'hidden' : ''; ?>
                            <?php $hiddenCounter = !isset($productsData[$productId]) ? 'hidden' : ''; ?>

                            <img src="<?= $product['img']; ?>" alt="" class="menu__item-img">

                            <div class="menu__item-content">
                                <h3 class="sub-title menu__item-title">
                                    <?= $product['title']; ?>
                                </h3>

                                <p class="text menu__item-text">
                                    <?= $product['description']; ?>
                                </p>

                                <div class="menu__item-info">
                                    <p class="text menu__item-price">
                                        <?= $product['price']; ?>
                                    </p>

                                    <div class="product-item__counter">
                                        <button class="product-item__counter-button <?= $hiddenButton; ?>" type="button">
                                            В корзину
                                        </button>

                                        <div class="product-item__counter-number-wrapper <?= $hiddenCounter; ?>">
                                            <input class="product-item__counter-input" type="hidden" name="productId" value="<?= isset($productsData[$productId]) ? $productsData[$productId] : '0'; ?>">

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
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>