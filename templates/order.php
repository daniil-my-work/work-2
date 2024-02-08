<!-- Страница заказ -->
<div class="page__order">
    <a href="./account.php" class="basket__breadcrumbs">
        <svg class="basket__breadcrumbs-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path fill="currentColor" d="M4 15a1 1 0 0 0 1 1h19.586l-4.292 4.292a1 1 0 0 0 1.414 1.414l6-6a.99.99 0 0 0 .292-.702V15c0-.13-.026-.26-.078-.382a.99.99 0 0 0-.216-.324l-6-6a1 1 0 0 0-1.414 1.414L24.586 14H5a1 1 0 0 0-1 1z" />
        </svg>

        <span>
            личный кабинет
        </span>
    </a>

    <img src="./img/svg/bag.svg" alt="" class="basket__icon">

    <p class="sub-title order__id">
        #<?= $orderInfo['order_id']; ?>
    </p>

    <ul class="basket__list">
        <?php if (!$isArrayOrderItems) : ?>
            <?php $productId = $orderItems['product_id']; ?>
            <li class="basket__item" data-product-id="<?= $productId ?>">
                <img src="<?= $orderItems['img']; ?>" alt="" class="basket__item-img">

                <div class="basket__item-info">
                    <p class="sub-title basket__item-title">
                        <?= $orderItems['title']; ?>
                    </p>

                    <p class="text basket__item-price">
                        <?= $orderItems['description']; ?>
                    </p>
                </div>

                <div class="product-item__counter">
                    <div class="product-item__counter-number-wrapper">
                        <p class="product-item__counter-number">
                            <?= $orderItems['quantity']; ?>
                        </p>
                    </div>
                </div>
            </li>
        <?php else : ?>
            <?php foreach ($orderItems as $orderItem) : ?>
                <?php $productId = $orderItem['product_id']; ?>
                <li class="basket__item" data-product-id="<?= $productId ?>">
                    <img src="<?= $orderItem['img']; ?>" alt="" class="basket__item-img">

                    <div class="basket__item-info">
                        <p class="sub-title basket__item-title">
                            <?= $orderItem['title']; ?>
                        </p>

                        <p class="text basket__item-price">
                            <?= $orderItem['description']; ?>
                        </p>
                    </div>

                    <div class="product-item__counter">
                        <div class="product-item__counter-number-wrapper">
                            <p class="product-item__counter-number">
                                <?= $orderItem['quantity']; ?>
                            </p>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>


    <div class="basket__order">
        <p class="basket__order-text">
            Сумма заказа:

            <span class="basket__order-number">
                <?= $orderInfo['total_amount']; ?>
            </span>
        </p>
    </div>
</div>