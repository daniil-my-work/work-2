<!-- Главная страница -->
<div class="page__main">
    <!-- Предложение -->
    <div class="offer">
        <div class="offer__wrapper">
            <button class="offer__button" type="button">
                Меню
            </button>

            <button class="offer__button" type="button">
                Создать поке
            </button>
        </div>
    </div>


    <!-- Баннер -->
    <div class="banner">
        <div class="banner__wrapper">
            <img src="./img/assets/banner-1.jpg" alt="" class="banner__img">
        </div>
    </div>


    <!-- Меню -->
    <div class="menu">
        <div class="menu__wrapper">
            <h2 class="title menu__title">
                Популярные блюда
            </h2>


            <!-- Меню -->
            <ul class="menu__list">
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
            </ul>


            <nav class="menu__nav">
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


    <!-- О компании -->
    <div class="about">
        <div class="about__wrapper">
            <img src="../../img/assets/about-1.png" alt="" class="about__img">

            <h2 class="title about__title">
                О компании
            </h2>

            <p class="text about__text">
                Здоровый и вкусный завтрак, быстрый обед или ужин со свежими и экзотическими ароматами. В "Много
                Рыбы" у нас есть все! Ознакомьтесь с нашим меню, разработанным искусными шеф-поварами с
                использованием настоящих сезонных ингредиентов, и полюбите вкуснейший Poke Bowl. Либо выберите
                одно из наших фирменных блюд, либо создайте свой идеальный Poke!
            </p>
        </div>
    </div>


    <!-- Иконка корзины -->
    <a href="" class="action__basket hidden">
        <span class="action__basket-icon"></span>
    </a>
</div>