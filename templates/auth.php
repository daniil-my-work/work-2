<!-- Страница Авторизации -->
<div class="page__reg">
    <div class="reg">
        <!-- Выбор города -->
        <div class="reg__radio hidden">
            <div class="reg__radio-wrapper">
                <h2 class="title reg__radio-title">
                    Выберите ваш город:
                </h2>

                <div class="reg__radio-content">
                    <div class="reg__form-radioButton">
                        <input id="radio-1" type="radio" name="radio" value="1" checked>
                        <label for="radio-1">
                            Ярославль
                        </label>
                    </div>

                    <div class="reg__form-radioButton">
                        <input id="radio-2" type="radio" name="radio" value="2">
                        <label for="radio-2">
                            Рыбинск
                        </label>
                    </div>
                </div>
            </div>
        </div>


        <h2 class="title">Войти</h2>

        <!-- Форма для авторизации -->
        <form class="reg__form" action="#" method="post">
            <div class="reg__form-input-wrapper">
                <label for="email">Почта:</label>
                <input class="reg__form-input" type="email" id="email" name="email" placeholder="test@yandex.ru" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="password">Пароль:</label>
                <input class="reg__form-input" type="password" id="password" name="password" placeholder="***" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой: Пароль не соответствует требованиям
                </span>
            </div>

            <button class="button--basic reg__form-button" type="submit">
                Войти
            </button>
        </form>
    </div>
</div>