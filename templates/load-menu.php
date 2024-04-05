<!-- Cтраница загрузки Меню -->
<div class="page__load">

    <!-- Загрузка меню -->
    <div class="load">
        <h2 class="title load__title">
            Редактировать меню
        </h2>

        <!-- Текущее значение меню -->
        <div class="load__current">
            <p class="text about__text mb-3">
                Вы имеете возможность редактировать меню. Для получения текущих значений меню в виде таблицы, нажмите кнопку "Скачать".
            </p>

            <div class="load__current-content">
                <p class="text load__current-text">
                    Таблица меню
                </p>

                <button class="button--basic button--second load__current-button">
                    Скачать
                </button>
            </div>
        </div>



        <!-- Обновленное значение меню -->
        <div class="load__update">
            <div class="account__orders-group load__update-tab">
                <?php $activeLinkMenu = isset($tabGroup) && $tabGroup === 'menu' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $activeLinkMenu; ?>" href="./load-menu.php?tab=menu">
                    Меню
                </a>

                <?php $activeLinkPoke = isset($tabGroup) && $tabGroup === 'poke' ? 'account__orders-group-link--active' : ''; ?>
                <a class="button--basic account__orders-group-link <?= $activeLinkPoke; ?>" href="./load-menu.php?tab=poke">
                    Поке
                </a>
            </div>


            <div class="load__update-description">
                <?php if (isset($tabGroup) && $tabGroup === 'menu') : ?>
                    <p class="text about__text mb-2" id="load-description-menu">
                        Чтобы обновить меню, прикрепите файл в формате csv, полученный в виде шаблона.
                    </p>
                <?php else : ?>
                    <p class="text about__text mb-2" id="load-description-poke">
                        Чтобы обновить конструктор поке, прикрепите файл в формате csv, полученный в виде шаблона.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Инпут -->
            <form action="./load-menu.php" class="load__form" enctype="multipart/form-data" method="post">
                <div class="load__update-content">
                    <input class="input load__update-input" type="file" name="file" id="file" required>

                    <p class="text load__update-text">
                        вставить файл
                        <br>
                        +
                    </p>
                </div>

                <?php $classHidden = isset($errors['file']) ? '' : 'hidden'; ?>
                <span class="reg__form-input-wrapper-error <?= $classHidden; ?>">
                    <?= $errors['file']; ?>
                </span>

                <!-- hidden -->
                <button class="button--basic button--second load__update-button" type="submit">
                    Загрузить
                </button>
            </form>
        </div>
    </div>
</div>