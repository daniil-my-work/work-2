<div class="page__poke">
    <a href="./menu.php" class="basket__breadcrumbs">
        <svg class="basket__breadcrumbs-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <path fill="currentColor" d="M4 15a1 1 0 0 0 1 1h19.586l-4.292 4.292a1 1 0 0 0 1.414 1.414l6-6a.99.99 0 0 0 .292-.702V15c0-.13-.026-.26-.078-.382a.99.99 0 0 0-.216-.324l-6-6a1 1 0 0 0-1.414 1.414L24.586 14H5a1 1 0 0 0-1 1z">
            </path>
        </svg>

        <span>
            меню
        </span>
    </a>


    <!-- Меню -->
    <div class="constructor-poke">
        <div class="constructor-poke__wrapper">
            <h2 class="title menu__title">
                Конструктор Поке
            </h2>

            <div class="constructor-poke__list">
                <!-- Протеин -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            1
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step1.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Выберите протеин
                    </h3>

                    <select class="form-select constructor-poke__select" aria-label="" name="protein">
                        <?php foreach ($proteinList as $proteinItem) : ?>
                            <option value="<?= $proteinItem['title']; ?>">
                                <?= $proteinItem['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Основа -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            2
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step2.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Выберите основу
                    </h3>

                    <select class="form-select constructor-poke__select" aria-label="" name="base">
                        <?php foreach ($baseList as $baseItem) : ?>
                            <option value="<?= $baseItem['title']; ?>">
                                <?= $baseItem['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Наполнитель -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            3
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step3.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Наполнитель
                    </h3>


                    <fieldset class="constructor-poke-shema">
                        <div class="constructor-poke-shema-item" data-shema-poke="1">
                            <input type="radio" class="constructor-poke-item-radio" id="shema1" name="shema" value="1" checked>
                            <label for="shema1">
                                5 наполнителей + 1 топинг
                            </label>
                        </div>

                        <div class="constructor-poke-shema-item" data-shema-poke="2">
                            <input type="radio" class="constructor-poke-item-radio" id="shema2" name="shema" value="2">
                            <label for="shema2">
                                3 наполнителя + 2 топинга
                            </label>
                        </div>
                    </fieldset>


                    <div class="constructor-poke-info">
                        <span class="constructor-poke-info-text">
                            Наполнители
                        </span>

                        <span class="constructor-poke-info-number" id="fillerCounter">
                            / Осталось 5 из 5
                        </span>
                    </div>

                    <ul class="constructor-poke-list">
                        <?php foreach ($fillerList as $fillerItem) : ?>
                            <li class="constructor-poke-item">
                                <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--filler" id="<?= $fillerItem['title']; ?>" name="filler" value="<?= $fillerItem['title']; ?>">

                                <label for="<?= $fillerItem['title']; ?>">
                                    <?= $fillerItem['title']; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Топпинг -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            4
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step4.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Топпинг
                    </h3>

                    <div class="constructor-poke-info">
                        <span class="constructor-poke-info-text">
                            Топпинг
                        </span>

                        <span class="constructor-poke-info-number" id="topingCounter">
                            / Осталось 1 из 1
                        </span>
                    </div>

                    <ul class="constructor-poke-list constructor-poke-list--toping">
                        <?php foreach ($toppingList as $toppingItem) : ?>
                            <li class="constructor-poke-item">
                                <input type="checkbox" class="constructor-poke-item-checkbox constructor-poke-item-checkbox--toping" id="<?= $toppingItem['title']; ?>" name="topping" value="<?= $toppingItem['title']; ?>">

                                <label for="<?= $toppingItem['title']; ?>">
                                    <?= $toppingItem['title']; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Соус -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            5
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step5.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Соус
                    </h3>

                    <select class="form-select constructor-poke__select" aria-label="" name="sauce">
                        <?php foreach ($sauceList as $sauceItem) : ?>
                            <option value="<?= $sauceItem['title']; ?>">
                                <?= $sauceItem['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Хруст -->
                <div class="constructor-poke__component">
                    <div class="constructor-poke__step">
                        <span class="constructor-poke__step-number">
                            6
                        </span>

                        <img class="constructor-poke__step-icon" src="../img/c-poke/c-poke-step6.png" alt="">
                    </div>

                    <h3 class="constructor-poke__title">
                        Хруст
                    </h3>

                    <select class="form-select constructor-poke__select" aria-label="" name="crunch">
                        <?php foreach ($crunchList as $crunchItem) : ?>
                            <option value="<?= $crunchItem['title']; ?>">
                                <?= $crunchItem['title']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="constructor-poke__add">
                <h3 class="sub-title">
                    Дополнительно
                </h3>

                <p class="text">
                    Если хотите вы можете добавить дополнительные ингредиенты
                </p>

                <label>
                    Протеин

                    <span class="constructor-poke__add-price">
                        + 436 руб
                    </span>
                </label>
                <select class="form-select constructor-poke__select" aria-label="" name="protein">
                    <?php foreach ($proteinAddList as $proteinAddItem) : ?>
                        <option value="<?= $proteinAddItem['title']; ?>" data-price="<?= $proteinAddItem['price']; ?>">
                            <?= $proteinAddItem['title']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>


                <label>
                    Наполнитель

                    <span class="constructor-poke__add-price">
                        + 436 руб
                    </span>
                </label>
                <ul class="constructor-poke-list constructor-poke-list--second">
                    <?php foreach ($fillerList as $fillerItem) : ?>
                        <li class="constructor-poke-item" data-price="<?= $fillerItem['price']; ?>">
                            <input type="checkbox" class="constructor-poke-item-checkbox" id="<?= $fillerItem['title']; ?>-add" name="filler" value="<?= $fillerItem['price']; ?>">

                            <label for="<?= $fillerItem['title']; ?>-add">
                                <?= $fillerItem['title']; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>


                <label>
                    Топпинг

                    <span class="constructor-poke__add-price">
                        + 436 руб
                    </span>
                </label>
                <ul class="constructor-poke-list constructor-poke-list--second">
                    <?php foreach ($toppingList as $toppingItem) : ?>
                        <li class="constructor-poke-item" data-price="<?= $toppingItem['price']; ?>">
                            <input type="checkbox" class="constructor-poke-item-checkbox" id="<?= $toppingItem['title']; ?>-add" name="topping" value="<?= $toppingItem['price']; ?>">

                            <label for="<?= $toppingItem['title']; ?>-add">
                                <?= $toppingItem['title']; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>


                <label>
                    Соус

                    <span class="constructor-poke__add-price">
                        + 436 руб
                    </span>
                </label>
                <select class="form-select constructor-poke__select" aria-label="" name="sauce">
                    <?php foreach ($sauceList as $sauceItem) : ?>
                        <option value="<?= $sauceItem['title']; ?>" data-price="<?= $crunchItem['price']; ?>">
                            <?= $sauceItem['title']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>
                    Хруст

                    <span class="constructor-poke__add-price">
                        + 436 руб
                    </span>
                </label>
                <select class="form-select constructor-poke__select" aria-label="" name="crunch">
                    <?php foreach ($crunchList as $crunchItem) : ?>
                        <option value="<?= $crunchItem['title']; ?>" data-price="<?= $crunchItem['price']; ?>">
                            <?= $crunchItem['title']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="basket__order">
                <p class="basket__order-text">
                    Ваш заказ:

                    <span class="basket__order-number">
                        660 руб
                    </span>
                </p>

                <button type="submit" class="button--second constructor-poke__button">
                    Заказать
                </button>
            </div>

        </div>
    </div>
</div>