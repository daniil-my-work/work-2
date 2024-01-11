<!-- Страница регистрации -->
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


        <h2 class="title">Регистрация</h2>

        <!-- Форма регистрации -->
        <form class="reg__form" action="#" method="post">
            <div class="reg__form-input-wrapper">
                <label for="name">Имя:</label>
                <input class="reg__form-input" type="text" id="name" name="name" placeholder="Даниил" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="email">Почта:</label>
                <input class="reg__form-input" type="email" id="email" name="email" placeholder="test@yandex.ru" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="phone">Номер телефона:</label>
                <input class="reg__form-input reg__form-input--tel" type="tel" id="phone" name="phone" pattern="[0-9]{10}" placeholder="+7(980) 705 70 02" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой: Введите номер телефона без пробелов и символов
                </span>
            </div>

            <div class="reg__form-input-wrapper">
                <label for="password">Пароль:</label>
                <input class="reg__form-input" type="password" id="password" name="password" placeholder="***" required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой: Пароль не соответствует требованиям
                </span>
            </div>

            <div class="reg__form-address-wrapper">
                <label for="address">Адрес:</label>

                <input class="reg__form-input reg__form-input--adress" type="text" id="address" name="address" pattern="[0-9]{10}" placeholder="ул. Антонова ... " required>

                <span class="reg__form-input-wrapper-error hidden">
                    Сообщение с ошибкой: Такого адреса не существует
                </span>

                <ul class="reg__form-address-list hidden">
                    <li class="reg__form-address-item">
                        1
                    </li>
                </ul>
            </div>


            <button class="button--basic reg__form-button" type="submit">
                Зарегистрироваться
            </button>
        </form>
    </div>
</div>