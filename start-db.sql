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
    ('sauce', 'соус'),
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
        'Лосось, тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        410,
        40,
        1
    ),
    (
        'Поке морской микс',
        './img/poke-2.png',
        'Коктейльные креветки, осьминоги, кальмар, соус Тай чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        410,
        41,
        1
    ),
    (
        'Поке с кальмаром',
        './img/poke-3.png',
        'Кальмар, соус Тай Чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        360,
        40,
        1
    ),
    (
        'Поке с коктейльными креветками',
        './img/poke-3.png',
        'Коктейльные креветки, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори лайм',
        380,
        40,
        1
    ),
    (
        'Поке с крабом',
        './img/poke-3.png',
        'Краб в соусе Японский, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        350,
        40,
        1
    ),
    (
        'Поке с креветкой',
        './img/poke-3.png',
        'Тигровые креветки, панко,кляр, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        420,
        40,
        1
    ),
    (
        'Поке с курицей',
        './img/poke-3.png',
        'Куриное филе в соусе Тай чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        360,
        40,
        1
    ),
    (
        'Поке с лососем',
        './img/poke-3.png',
        'Лосось, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        410,
        40,
        1
    ),
    (
        'Поке с морским гребешком',
        './img/poke-3.png',
        'Морской гребешок, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        450,
        40,
        1
    ),
    (
        'Поке с тофу',
        './img/poke-3.png',
        'Тофу, соус Тай Чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        350,
        40,
        1
    ),
    (
        'Поке с тунцом',
        './img/poke-3.png',
        'Тунец, соус дрессинг, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        380,
        40,
        1
    ),
    (
        'Поке с угрем',
        './img/poke-3.png',
        'Угорь, соус Терияки, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        490,
        40,
        1
    ),
    (
        'Поке со свининой',
        './img/poke-3.png',
        'Свиная вырезка в соусе Тай Чили, рис, грибы, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори, лайм',
        360,
        40,
        1
    ),
    (
        'Поке с телятиной',
        './img/poke-3.png',
        'Маринованный ростбиф, рис, грибы муэр, битые огурцы, азиатская морковь, салат айсберг, томаты черри, авокадо, соус манго-маракуйя, нори',
        400,
        40,
        1
    ),
    (
        'Ролл 4 рыбы',
        './img/roll-1.png',
        '*ОСТРО* Лосось, тунец, угорь,креветка, рис, нори, сливочный сыр, соус Спайс, ким нори',
        460,
        20,
        2
    ),
    (
        'Ролл авокадо',
        './img/roll-1.png',
        'Нори, рис, авокадо',
        150,
        20,
        2
    ),
    (
        'Ролл Бонито',
        './img/roll-1.png',
        'Рис,лосось, сливочный сыр, авокадо, стружка тунца',
        350,
        20,
        2
    ),
    (
        'Ролл Калифорния',
        './img/roll-1.png',
        'Рис, нори, сурими, авокадо, огурец, японский майонез, масаго',
        330,
        20,
        2
    ),
    (
        'Ролл Канада',
        './img/roll-1.png',
        'Рис, нори, угорь, лосось, сливочный сыр, авокадо, соус Терияки, кунжут',
        490,
        20,
        2
    ),
    (
        'Ролл креветка',
        './img/roll-1.png',
        'Рис, нори, креветка',
        160,
        20,
        2
    ),
    (
        'Ролл лосось',
        './img/roll-1.png',
        'Рис, нори, лосось',
        220,
        20,
        2
    ),
    (
        'Ролл Манго Fish',
        './img/roll-1.png',
        'Рис, нори, сливочный сыр, манго,лосось, соус манго-маракуйя',
        390,
        20,
        2
    ),
    (
        'Ролл миндальный лосось',
        './img/roll-1.png',
        'Темпура, нори, рис, сливочный сыр, лосось, панко, миндальные лепестки',
        350,
        20,
        2
    ),
    (
        'Ролл миндальный тунец',
        './img/roll-1.png',
        'Темпура, нори, рис, сливочный сыр, тунец, панко, миндальные лепестки',
        320,
        20,
        2
    ),
    (
        'Ролл миндальный угорь',
        './img/roll-1.png',
        'Рис, нори, темпура, сливочный сыр, угорь, панко, миндальные лепестки',
        390,
        20,
        2
    ),
    (
        'Ролл Много рыбы',
        './img/roll-1.png',
        'Рис, нори, угорь, лосось, тигровая креветка, огурец, масаго,кунжут',
        380,
        20,
        2
    ),
    (
        'Ролл Много сыра',
        './img/roll-1.png',
        'Рис, нори, сыры: Фета, Чеддер, Пармезан, сливочный, панко, темпура',
        320,
        20,
        2
    ),
    (
        'Ролл огурец',
        './img/roll-1.png',
        'Рис, нори, огурец',
        120,
        20,
        2
    ),
    (
        'Ролл Саймон',
        './img/roll-1.png',
        'Рис, нори, лосось, угорь, авокадо, сливочный сыр, темпурный кляр, панко',
        330,
        20,
        2
    ),
    (
        'Ролл сыр',
        './img/roll-1.png',
        'Рис, нори, сливочный сыр',
        120,
        20,
        2
    ),
    (
        'Ролл тар-тар',
        './img/roll-1.png',
        'Нори, рис, лосось, авокадо, соус Васаби, сливочный сыр',
        410,
        20,
        2
    ),
    (
        'Ролл тунец',
        './img/roll-1.png',
        'Рис, нори, тунец',
        150,
        20,
        2
    ),
    (
        'Ролл угорь',
        './img/roll-1.png',
        'Рис, нори, угорь',
        230,
        20,
        2
    ),
    (
        'Ролл Фила сяке',
        './img/roll-1.png',
        'Нори, рис, лосось, огурец, сливочный сыр',
        390,
        20,
        2
    ),
    (
        'Ролл Фила туна',
        './img/roll-1.png',
        'Рис, нори, тунец, огурец, сливочный сыр',
        320,
        20,
        2
    ),
    (
        'Ролл Филадельфия',
        './img/roll-1.png',
        'Рис, нори, лосось, сливочный сыр',
        420,
        20,
        2
    ),
    (
        'Ролл Филадельфия Green',
        './img/roll-1.png',
        'Рис, нори, сливочный сыр, авокадо,лосось, соус Том Ям',
        370,
        20,
        2
    ),
    (
        'Ролл Хотате',
        './img/roll-1.png',
        'Рис, лосось, морской гребешок, икра Масаго, сыр сливочный,лук порей,темпура, панко',
        460,
        20,
        2
    ),
    (
        'Ролл чука',
        './img/roll-1.png',
        'Рис, нори, чука',
        150,
        20,
        2
    ),
    (
        'Ролл тамаго краб',
        './img/roll-1.png',
        'Снежный краб, сливочный сыр, томаты в яичном блине, соус Японский',
        250,
        20,
        2
    ),
    (
        'Ролл тамаго лосось',
        './img/roll-1.png',
        'Лосось, сливочный сыр, томаты в яичном блине, соус Японский',
        280,
        20,
        2
    ),
    (
        'Ролл тамаго тунец',
        './img/roll-1.png',
        'Тунец, сливочный сыр, томаты в яичном блине, соус Японский',
        280,
        20,
        2
    ),
    (
        'Ролл Шримп ролл',
        './img/roll-1.png',
        'Рис, нори, икра масаго, тигровая креветка, огурец, сливочный сыр, соус Терияки, кунжут',
        350,
        20,
        2
    ),
    (
        'Ролл Сливочный Краб',
        './img/roll-1.png',
        '',
        250,
        20,
        2
    ),
    (
        'Мисо суп',
        './img/soup-1.png',
        'Бульон Мисо, вакаме,тофу, шампиньоны, зелень',
        180,
        30,
        3
    ),
    (
        'Суп Много рыбы',
        './img/soup-3.png',
        'Бульон Даси, лосось, тунец, тигровые креветки, кальмар, вакаме, соевые ростки, сливки, зелень',
        330,
        30,
        3
    ),
    (
        'Суп Чеддер',
        './img/soup-4.png',
        'Сливочный сыр, сливки, грибы шампиньоны, куриное филе в крахмале, томаты черри, микс салат',
        320,
        30,
        3
    ),
    (
        'Том Ям с креветками',
        './img/soup-1.png',
        '*ОСТРО* Бульон Том Ям, шампиньоны, томаты черри, тигровые креветки, стручковая фасоль, рис, лайм',
        380,
        30,
        3
    ),
    (
        'Том Ям с курицей',
        './img/soup-2.png',
        '*ОСТРО* Бульон Том Ям, шампиньоны, томаты черри, куриное филе, стручковая фасоль, рис, лайм',
        330,
        30,
        3
    ),
    (
        'Мисо суп с лососем',
        './img/soup-2.png',
        'Бульон Мисо,лосось, вакаме,тофу,шампиньоны,зелень',
        280,
        30,
        3
    ),
    (
        'Томатный суп с морепродуктами',
        './img/soup-2.png',
        'Томатный бульон, кальмар, тигровые креветки, сельдерей, болгарский перец, кинза',
        380,
        30,
        3
    ),
    (
        'Вок с креветками и ананасом',
        './img/hot-1.png',
        '*ОСТРО* Тигровые креветки, рис, ананас,огурец, красный лук, болгарский перец, соус Тай Чили, соус Вок, лук порей, зелень',
        410,
        40,
        4
    ),
    (
        'Вок с курицей и ананасом',
        './img/hot-1.png',
        '*ОСТРО* Куриное филе, рис, ананас,огурец, красный лук, болгарский перец, соус Тай Чили, соус Вок, лук порей, зелень',
        330,
        40,
        4
    ),
    (
        'Карри Тайское с кальмаром',
        './img/hot-2.png',
        '*ОСТРО* Кальмар, рис, шампиньоны, красный лук, болгарский перец, паста Карри , соус Вок, сливки, зеленый лук, кинза',
        400,
        40,
        4
    ),
    (
        'Карри Тайское с креветками',
        './img/hot-2.png',
        '*ОСТРО* Тигровые креветки, рис, шампиньоны, красный лук, болгарский перец, паста Карри , соус Вок, сливки, зеленый лук, кинза',
        480,
        40,
        4
    ),
    (
        'Карри Тайское с курицей',
        './img/hot-2.png',
        '*ОСТРО* Куриное филе, рис, шампиньоны, красный лук, болгарский перец, паста Карри , соус Вок, сливки, зеленый лук, кинза',
        370,
        40,
        4
    ),
    (
        'Пад тай с креветками',
        './img/hot-3.png',
        'Тигровые креветки, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая фасоль, рисовая лапша , соус Вок, соевые ростки, арахис, яйцо, соус Пад тай, зелень',
        420,
        40,
        4
    ),
    (
        'Пад тай с креветками и рисом',
        './img/hot-4.png',
        'Тигровые креветки, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая фасоль, рис , соус Вок, соевые ростки, арахис, яйцо, соус Пад тай, зелень',
        420,
        40,
        4
    ),
    (
        'Пад тай с курицей',
        './img/hot-4.png',
        'Куриное филе, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая фасоль, рисовая лапша , соус Вок, соевые ростки, арахис, яйцо, соус Пад тай, зелень',
        370,
        40,
        4
    ),
    (
        'Пад тай с курицей и рисом',
        './img/hot-4.png',
        'Куриное филе, красный лук. морковь, цукини, болгарксий перец, шампиньоны, стручковая фасоль, рис , соус Вок, соевые ростки, арахис, яйцо, соус Пад тай, зелень',
        370,
        40,
        4
    ),
    (
        'Курица генерала Тсо',
        './img/hot-4.png',
        '*ОСТРО* Куриное филе в маринаде, рис, брокколи',
        370,
        40,
        4
    ),
    (
        'Лосось генерала Тсо',
        './img/hot-4.png',
        '*ОСТРО* Лосось в соусе и кунжуте, рис, брокколи',
        670,
        40,
        4
    ),
    (
        'Лапша по-сингапурски',
        './img/hot-4.png',
        '*ОСТРО* Маринованная телятина,лапша фунчоза, красный лук, болгарский перец, цуккини, шампиньоны, морковь, сельдерей, паста карри, зелень',
        420,
        40,
        4
    ),
    (
        'Телятина с брокколи стир-фрай',
        './img/hot-4.png',
        '*ОСТРО* Маринованная телятина,бланшированная брокколи, красный лук, сельдерей, соус вок, соус черный перец',
        420,
        40,
        4
    ),
    (
        'Эби удон',
        './img/hot-4.png',
        'Тигровые креветки,кальмар, лапша удон, шампиньоны, болгарский перец, цуккини, сливки,лук порей',
        450,
        40,
        4
    ),
    (
        'Вок с креветками',
        './img/wok-1.png',
        'Лапша (на выбор), тигровые креветки, лук красный, морковь, цукини, болгарский перец, шампиньоны, стручковая фасоль, соус (на выбор), кунжут',
        400,
        10,
        5
    ),
    (
        'Вок с курицей',
        './img/wok-1.png',
        'Лапша (на выбор), куриное филе, лук красный, морковь, цукини, болгарский перец, шампиньоны, стручковая фасоль, соус (на выбор), кунжут',
        350,
        10,
        5
    ),
    (
        'Вок с морепродуктами',
        './img/wok-1.png',
        'Лапша (на выбор), тигровые креветки, кальмар, креветки коктейльные, лук красный, морковь, цукини, болгарский перец, шампиньоны, стручковая фасоль, соус (на выбор), кунжут',
        400,
        10,
        5
    ),
    (
        'Вок с овощами',
        './img/wok-2.png',
        'Лапша (на выбор),лук красный,морковь,цукини, болгарский перец,шампиньоны, стручковая фасоль, соус ( на выбор), кунжут',
        270,
        11,
        5
    ),
    (
        'Вок с тофу',
        './img/wok-4.png',
        'Лапша (на выбор),тофу,лук красный,морковь,цукини, болгарский перец,шампиньоны, стручковая фасоль, соус ( на выбор), кунжут',
        300,
        13,
        5
    ),
    (
        'Вок со свининой',
        './img/wok-3.png',
        'Лапша (на выбор),свинина, лук красный,морковь,цукини, болгарский перец,шампиньоны, стручковая фасоль, соус ( на выбор), кунжут',
        350,
        12,
        5
    ),
    (
        'Вок с телятиной',
        './img/wok-3.png',
        'Лапша (на выбор), телятина, лук красный, морковь, цукини, болгарский перец, шампиньоны, стручковая фасоль, соус (на выбор), кунжут',
        400,
        12,
        5
    ),

    --  ======== СТОП ===== доделать
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
        'протеин',
        'c коктельными креветками'
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
        'протеин',
        'с морским гребешком'
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

-- Адреса Кафе
INSERT INTO
    cafe_address (city, address_name)
VALUES
    ('Ярославль', 'г. Ярославль, Некрасова 52/35'),
    ('Ярославль', 'г. Ярославль, Урицкого 39'),
    ('Ярославль', 'г. Ярославль, Фрунзе 38'),
    ('Ярославль', 'г. Ярославль, Совхозная 6'),
    ('Ярославль', 'г. Ярославль, Свободы 52/39'),
    ('Ярославль', 'г. Ярославль, Тургенева 1а');
    ('Рыбинск', 'г. Рыбинск, Крестовая д.97');