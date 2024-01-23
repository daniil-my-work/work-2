<!-- Страница Корзина -->
<div class="page__basket">
    <span class="basket__breadcrumbs">
        Назад в меню
    </span>

    <img src="./img/svg/bag.svg" alt="" class="basket__icon">

    <!-- Форма заказа: Корзина -->
    <form class="page__basket-form" action="./basket.php">
        <ul class="basket__list">
            <li class="basket__item" data-product-id="1">
                <img src="" alt="" class="basket__item-img">

                <div class="basket__item-info">
                    <p class="sub-title basket__item-title">
                        dsdsdsdsds
                    </p>

                    <p class="text basket__item-text">
                        dsjdsdjs djnsjdsd nsjdnsjdss
                    </p>
                </div>

                <div class="basket__item-counter">
                    <span class="basket__item-action basket__item-action--minus">
                        –
                    </span>

                    <p class="basket__item-count">
                        10
                    </p>

                    <span class="basket__item-action basket__item-action--plus">
                        +
                    </span>
                </div>
            </li>

            <li class="basket__item" data-product-id="2">
                <img src="" alt="" class="basket__item-img">

                <div class="basket__item-info">
                    <p class="sub-title basket__item-title">
                        dsdsdsdsds
                    </p>

                    <p class="text basket__item-text">
                        dsjdsdjs djnsjdsd nsjdnsjdss
                    </p>
                </div>

                <div class="basket__item-counter">
                    <span class="basket__item-action basket__item-action--minus">
                        –
                    </span>

                    <p class="basket__item-count">
                        10
                    </p>

                    <span class="basket__item-action basket__item-action--plus">
                        +
                    </span>
                </div>
            </li>

            <li class="basket__item" data-product-id="3">
                <img src="" alt="" class="basket__item-img">

                <div class="basket__item-info">
                    <p class="sub-title basket__item-title">
                        dsdsdsdsds
                    </p>

                    <p class="text basket__item-text">
                        dsjdsdjs djnsjdsd nsjdnsjdss
                    </p>
                </div>

                <div class="basket__item-counter">
                    <span class="basket__item-action basket__item-action--minus">
                        –
                    </span>

                    <p class="basket__item-count">
                        10
                    </p>

                    <span class="basket__item-action basket__item-action--plus">
                        +
                    </span>
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