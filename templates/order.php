<!-- Страница заказ -->
<div class="page__order">
    <span class="basket__breadcrumbs">
        В личный кабинет
    </span>

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
                        <?= $orderItems['price']; ?>
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