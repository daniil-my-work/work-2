-- Таблица Категория
INSERT INTO
    category_menu (category_title, category_name)
VALUES
    ('poke', 'поке'),
    ('rolls', 'роллы'),
    ('soups', 'супы'),
    ('hot', 'горячее'),
    ('wok', 'вок'),
    ('appetizers', 'закуски'),
    ('sandwiches', 'сэндвичи'),
    ('desserts', 'десерты'),
    ('beverages', 'напитки'),
    ('sauce', 'соус');

('constructor-poke', 'авторский поке');

-- Таблица меню
INSERT INTO
    menu (
        title,
        img,
        description,
        price,
        cooking_time,
        category_id
    )
VALUES
    (
        'Поке микс Лосось-Тунец',
        './img/poke-1.png',
        'Лосось, тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг,...',
        410,
        40,
        1
    ),
    (
        'Поке морской микс',
        './img/poke-2.png',
        'Коктейльные креветки,осьминоги, кальмар, соус Тай чили, рис, грибы, битые огурцы, азиатска...',
        410,
        41,
        1
    ),
    (
        'Поке с кальмаром',
        './img/poke-3.png',
        'Кальмар, соус Тай Чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты...',
        360,
        40,
        1
    ),
    (
        'Поке с крабом',
        './img/poke-3.png',
        'Краб в соусе Японский, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты ...',
        350,
        40,
        1
    ),
    (
        'Ролл Калифорния',
        './img/roll-1.png',
        'Рис, нори, сурими, авокадо, огурец, японский майонез, масаго...',
        330,
        20,
        2
    ),
    (
        'Ролл Канада',
        './img/roll-2.png',
        'Рис, нори, угорь, лосось, сливочный сыр, авокадо, соус Терияки, кунжут...',
        490,
        22,
        2
    ),
    (
        'Ролл Минд. лосось',
        './img/roll-3.png',
        'Темпура, нори, рис, сливочный сыр, лосось, панко, миндальные лепестки...',
        350,
        24,
        2
    ),
    (
        'Ролл Много рыбы',
        './img/roll-4.png',
        'Рис, нори, угорь, лосось, тигровая креветка, огурец, масаго,кунжут...',
        380,
        26,
        2
    ),
    (
        'Том-ям с креветкой',
        './img/soup-1.png',
        '*ОСТРО* Бульон Том Ям, шампиньоны, томаты черри, тигровые креветки, стручковая фасоль, рис...',
        380,
        30,
        3
    ),
    (
        'Том-ям с курицей',
        './img/soup-2.png',
        '*ОСТРО* Бульон Том Ям, шампиньоны, томаты черри, куриное филе, стручковая фасоль, рис, лай...',
        330,
        31,
        3
    ),
    (
        'Суп Много рыбы',
        './img/soup-3.png',
        'Бульон Даси, лосось,тунец,тигровые креветки,кальмар, вакаме,соевые ростки, сливки,зелень...',
        330,
        32,
        3
    ),
    (
        'Суп Чеддер',
        './img/soup-4.png',
        'Сливочный сыр, сливки, грибы шампиньоны, куриное филе в крахмале, томаты черри, микс салат...',
        320,
        33,
        3
    ),
    (
        'Вок с креветками и ананасом',
        './img/hot-1.png',
        'Сливочный сыр, сливки, грибы шампиньоны, куриное филе в крахмале, томаты черри, микс салат...',
        410,
        40,
        4
    ),
    (
        'Карри Тайское с кальмаром',
        './img/hot-2.png',
        '*ОСТРО* Кальмар, рис, шампиньоны, красный лук, болгарский перец, паста Карри , соус Вок, с...',
        400,
        41,
        4
    ),
    (
        'Пад тай с креветками',
        './img/hot-3.png',
        'Тигровые креветки, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая ...',
        420,
        42,
        4
    ),
    (
        'Пад тай с креветками и рисом',
        './img/hot-4.png',
        'Тигровые креветки, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая ...',
        420,
        43,
        4
    ),
    (
        'Вок с морепродуктами',
        './img/wok-1.png',
        'Лапша ( на выбор), тигровые креветки,кальмар, креветки коктейльные,лук красный,морковь,цук...',
        400,
        10,
        5
    ),
    (
        'Вок с овощами',
        './img/wok-2.png',
        'Лапша (на выбор),лук красный,морковь,цукини, болгарский перец,шампиньоны, стручковая фасо...',
        270,
        11,
        5
    ),
    (
        'Вок со свининой',
        './img/wok-3.png',
        'Лапша ( на выбор),свинина, лук красный,морковь,цукини, болгарский перец,шампиньоны, стручк...',
        350,
        12,
        5
    ),
    (
        'Вок с тофу',
        './img/wok-4.png',
        'Лапша ( на выбор),тофу,лук красный,морковь,цукини, болгарский перец,шампиньоны, стручковая...',
        420,
        13,
        5
    ),
    (
        'Креветки в темпуре',
        './img/appetizers-1.png',
        'Тигровые креветки, темпура, панко, соус Том Ям',
        390,
        3,
        6
    ),
    (
        'Стрипсы куриные',
        './img/appetizers-2.png',
        '*ОСТРО* Куриное филе, острая панировка, соус Тай Чили...',
        270,
        4,
        6
    ),
    (
        'Спринг ролл с креветками',
        './img/appetizers-3.png',
        'Коктейльные креветки,морковь,лук,имбирь,чеснок, спринг тесто, соус Тай Чили...',
        470,
        7,
        6
    ),
    (
        'Спринг ролл со свининой',
        './img/appetizers-4.png',
        'Свиная вырезка,морковь,лук, пекинская капуса, имбирь, чеснок, спринг тесто, соус Тай Чили...',
        360,
        11,
        6
    ),
    (
        'Сэндвич Калифорния',
        './img/sandwiches-1.png',
        'Рис, краб, авокадо, свежий огурец, сыр чеддер, икра Масаго, соус японский, темпура, панко...',
        330,
        20,
        7
    ),
    (
        'Сэндвич Филадельфия',
        './img/sandwiches-2.png',
        'Рис, лосось, свежий огурец, сливоный сыр, сыр чеддер, темпура, панко...',
        330,
        21,
        7
    ),
    (
        'Сэндвич Цезарь',
        './img/sandwiches-3.png',
        'Рис, нори, куриное филе, томаты, салат Айсберг, соус Цезарь, панировка...',
        340,
        22,
        7
    ),
    (
        'Сэндвич Спайси Туна',
        './img/sandwiches-4.png',
        '*ОСТРО* Рис, тунец, авокадо, сыр чеддер,сливочный сыр, шрирача, темпура. панко...',
        350,
        24,
        7
    ),
    (
        'Чизкейк клубника',
        './img/desserts-1.png',
        'Чизкейк клубника 1',
        220,
        24,
        8
    ),
    (
        'Чизкейк манго',
        './img/desserts-2.png',
        'Чизкейк манго 2',
        220,
        24,
        8
    ),
    (
        'Чизкейк классика',
        './img/desserts-3.png',
        'Чизкейк классика 3',
        220,
        24,
        8
    ),
    (
        'Морс облепиха-апельсин 0,5',
        './img/beverages-1.png',
        'Морс облепиха-апельсин 0,5',
        100,
        1,
        8
    ),
    (
        'Морс черная смородина 0,5',
        './img/beverages-2.png',
        'Морс черная смородина 0,5',
        100,
        2,
        8
    ),
    (
        'Васаби 5гр',
        './img/sauce-1.png',
        '5гр',
        10,
        2,
        9
    ),
    (
        'Соус Кимчи',
        './img/sauce-2.png',
        '*ОСТРО* 50гр ',
        50,
        1,
        9
    ),
    (
        'Соус Манго-маракуйя',
        './img/sauce-3.png',
        '50гр',
        50,
        1,
        9
    ),
    (
        'Соус Цезарь',
        './img/sauce-4.png',
        '50гр',
        50,
        1,
        9
    );

-- Таблица Компоненты поке
INSERT INTO
    components (
        title,
        img,
        price,
        component_type,
        component_name,
        component_poke_type
    )
VALUES
    (
        'Кальмар',
        '1',
        360,
        'protein',
        'протеин',
        'c кальмаром'
    ),
    (
        'Коктейльные креветки',
        '1',
        380,
        'protein',
        'протеин'
    ),
    (
        'Краб',
        '1',
        350,
        'protein',
        'протеин',
        'с крабом'
    ),
    (
        'Курица',
        '1',
        360,
        'protein',
        'протеин',
        'с курицей'
    ),
    (
        'Лосось',
        '1',
        410,
        'protein',
        'протеин',
        'с лососем'
    ),
    ('Тофу', '1', 350, 'protein', 'протеин', 'с тофу'),
    (
        'Тунец',
        '1',
        380,
        'protein',
        'протеин',
        'с тунцом'
    ),
    (
        'Угорь',
        '1',
        490,
        'protein',
        'протеин',
        'с угрем'
    ),
    (
        'Свинина',
        '1',
        360,
        'protein',
        'протеин',
        'со свининой'
    ),
    (
        'Морской гребешок',
        '1',
        450,
        'protein',
        'протеин' 'с морским гребешком'
    ),
    (
        'Лосось-тунец',
        '1',
        410,
        'protein',
        'протеин',
        'с лососем и тунцом'
    ),
    (
        'Креветка',
        '1',
        420,
        'protein',
        'протеин',
        'с креветкой'
    ),
    (
        'Морской микс',
        '1',
        410,
        'protein',
        'протеин',
        'с морским миксом'
    ),
    (
        'Телятина',
        '1',
        400,
        'protein',
        'протеин',
        'с телятиной'
    ),
    -- для добавки
    (
        'Кальмар',
        '1',
        180,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Коктейльные креветки',
        '1',
        190,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Краб',
        '1',
        175,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Креветка',
        '1',
        210,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Курица',
        '1',
        180,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Лосось',
        '1',
        205,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Свинина',
        '1',
        180,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Тофу',
        '1',
        175,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Тунец',
        '1',
        190,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Угорь',
        '1',
        245,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Морской гребешок',
        '1',
        225,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Лосось-тунец',
        '1',
        205,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Морской микс',
        '1',
        205,
        'protein-add',
        'протеин-добавка',
        null
    ),
    (
        'Телятина',
        '1',
        200,
        'protein-add',
        'протеин-добавка',
        null
    ),
    ('Удон', '2', null, 'base', 'основа', null),
    ('Айсберг', '2', null, 'base', 'основа', null),
    ('Киноа', '2', null, 'base', 'основа', null),
    ('Рис', '2', null, 'base', 'основа', null),
    ('Соба', '2', null, 'base', 'основа', null),
    ('Киноа + рис', '2', null, 'base', 'основа', null),
    (
        'Микс салат',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    ('Такуан', '3', 40, 'filler', 'наполнитель', null),
    ('Тамаго', '3', 40, 'filler', 'наполнитель', null),
    (
        'Томаты черри',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Картофельные дольки',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Брокколи',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Спаржа фучжу',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Битые огурцы',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Болгарский перец',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Соевые ростки',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    ('Чука', '3', 40, 'filler', 'наполнитель', null),
    (
        'Баклажан',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Свежий огурчик',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Кукуруза',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    ('Кимчи', '3', 40, 'filler', 'наполнитель', null),
    (
        'Грибы Муэр',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Корнишоны',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Азиатская морковь',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Салат Айсберг',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    (
        'Шиитаке',
        '3',
        40,
        'filler',
        'наполнитель',
        null
    ),
    ('Манго', '4', 50, 'topping', 'топпинг', null),
    ('Маслины', '4', 50, 'topping', 'топпинг', null),
    ('Авокадо', '4', 50, 'topping', 'топпинг', null),
    ('Масаго', '4', 50, 'topping', 'топпинг', null),
    ('Сыр Фета', '4', 50, 'topping', 'топпинг', null),
    (
        'Сыр Пармезан',
        '4',
        50,
        'topping',
        'топпинг',
        null
    ),
    ('Ананас', '4', 50, 'topping', 'топпинг', null),
    (
        'Сливочный сыр',
        '4',
        50,
        'topping',
        'топпинг',
        null
    ),
    ('Манго-маракуйя', '5', 50, 'sauce', 'соус', null),
    ('Японский', '5', 50, 'sauce', 'соус', null),
    ('Карри', '5', 50, 'sauce', 'соус', null),
    ('Том-ям', '5', 50, 'sauce', 'соус', null),
    (
        'Медово-горчичный',
        '5',
        50,
        'sauce',
        'соус',
        null
    ),
    ('Сладкий чили', '5', 50, 'sauce', 'соус', null),
    ('Цезарь', '5', 50, 'sauce', 'соус', null),
    ('Спайс', '5', 50, 'sauce', 'соус', null),
    ('Терияки', '5', 50, 'sauce', 'соус', null),
    (
        'Тыквенные семечки',
        '6',
        25,
        'crunch',
        'хруст',
        null
    ),
    ('Начос', '6', 25, 'crunch', 'хруст', null),
    (
        'Бобы нут жареные',
        '6',
        25,
        'crunch',
        'хруст',
        null
    ),
    (
        'Кукуруза жареная',
        '6',
        25,
        'crunch',
        'хруст',
        null
    ),
    ('Арахис', '6', 25, 'crunch', 'хруст', null),
    ('Нори', '6', 25, 'crunch', 'хруст', null),
    ('Кунжут', '6', 25, 'crunch', 'хруст', null),
    ('Кешью', '6', 25, 'crunch', 'хруст', null),
    (
        'Миндальный лепестки',
        '6',
        25,
        'crunch',
        'хруст',
        null
    ),
    ('Жареный лук', '6', 25, 'crunch', 'хруст', null);

INSERT INTO
    cafe_address (city, address_name)
VALUES
    ('Ярославль', 'г. Ярославль, Некрасова 52/35'),
    ('Ярославль', 'г. Ярославль, Урицкого 39'),
    ('Ярославль', 'г. Ярославль, Фрунзе 38'),
    ('Ярославль', 'г. Ярославль, Совхозная 6'),
    ('Ярославль', 'г. Ярославль, Свободы 52/39'),
    ('Ярославль', 'г. Ярославль, Тургенева 1а');