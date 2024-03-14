# Apply

## Описание

Apply - CMS для карьерных сайтов с возможностью продвижения в соцсетях и таргете:

<a href="https://drive.google.com/uc?export=view&id=18ArhcWjCoojndqgsnyYSawzUpCaRzwgb"><img src="https://drive.google.com/uc?export=view&id=18ArhcWjCoojndqgsnyYSawzUpCaRzwgb" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

## Архитектура

Apply разработан на [Laravel](https://laravel.com/docs/5.8) и [Nova](https://nova.laravel.com/docs/installation.html). Для понимания принципов работы необходимо ознакомиться с документацией по ссылкам.

Все клиенты работают в единой системе и используют общий функционал (например, интеграцию с рекламными кабинетами и алгоритмы продвижения). При этом каждый клиент может настроить для себя внешний вид справочников (поля, фильтры, виджеты и т.д.), навигацию, а также разработать собственную бизнес-логику на встроенном скриптовом языке (js).

## Инфраструктура

Развернуты тестовый и рабочий серверы. Вертикальная оранжевая полоска слева - индикатор тестового сервера, зеленая - локальной среды разработки:

<a href="https://drive.google.com/uc?export=view&id=1fdJM3OohZZQfnmwAtDYC3VDj_t_oGj4a"><img src="https://drive.google.com/uc?export=view&id=1fdJM3OohZZQfnmwAtDYC3VDj_t_oGj4a" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

Клиент может выполнить все настройки на тестовом сервере и одной кнопкой перенести их на рабочий.

## API

Доступ к системе из внутренних скриптов, а также извне реализован посредством [EloquentJs](http://parsnick.github.io/eloquentjs/). Для понимания принципов работы необходимо ознакомиться с документацией по ссылке.

`Eloquent.Post.find(1).then(post => console.log(post))`

Внутренние скрипты выполняются от имени системного пользователя, который задается в ресурсе Tenant (аккаунт клиента). Для внешних скриптов необходима аутентификация по токену.

## Конфигуратор

Клиент может настроить систему согласно своим требованиям через json и встроенный скриптовый язык (js). Настройки json поддерживают элементы схем $ref и allOf, что позволяет разбить настройки на небольшие, логически независимые куски, а также реализовать наследование:

<a href="https://drive.google.com/uc?export=view&id=128KkwbOjNT-dKunZDlhv64kQG7Evu6F3"><img src="https://drive.google.com/uc?export=view&id=128KkwbOjNT-dKunZDlhv64kQG7Evu6F3" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />


Есть два слоя настройки - вендора и клиента. Настройки вендора доступны только для чтения и хранятся в ресурсе ResourceSetting - они нужны исключительно для удобства. Можно рассматривать их как настройки по умолчанию.

Клиентские настройки хранятся в ресурсе ResourceCustomSetting и их может редактировать сам клиент. Настройки для конкретного пользователя хранятся в ресурсе UserProfile (роль) и, как правило, собираются/наследуются от клиентских/вендорских настроек.

Верхний уровень:
- `resources` - Настройки ресурсов.
- `navigation` - Левая панель навигации.
- `home` - Домашняя страница.

Настройки конкретного ресурса (см. ниже):
- `policy`
- `fields`
- `lenses`
- `actions`
- `indexQuery`
- `cards`
- `label` - Заголовок ресурса.
- `singularLabel` - Заголовок ресурса в единственном числе.

### `policy`

Политики. Можно разрешить или запретить пользователю конкретное действие, например, delete или update.

Можно разрешить или запретить действие по условию (формат условия соответствует QueryBuilder из EloquentJs).

### `fields`

Поля. Каждое поле задается как:
- class (строка)
- constructor (массив параметров)
- calls (название метода: массив параметров)

Все классы и методы можно посмотреть в документации к Laravel Nova.

Поддерживаются вложенные поля, например, Tabs (позволяет разбить поля на вкладки) и DependencyContainer (позволяет отображать/скрывать группу полей по условию):

<a href="https://drive.google.com/uc?export=view&id=1lwHzDF_XJX3L7-ZWHw6LI6I7G949g_3z"><img src="https://drive.google.com/uc?export=view&id=1lwHzDF_XJX3L7-ZWHw6LI6I7G949g_3z" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

### `lenses`

Быстрые фильтры. Можно задать название и условие (формат условия по аналогии с политиками):

<a href="https://drive.google.com/uc?export=view&id=1Ux4GC2lgU2eCiAj17MXxUwtIk2s9vYB8"><img src="https://drive.google.com/uc?export=view&id=1Ux4GC2lgU2eCiAj17MXxUwtIk2s9vYB8" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

### `actions`

Быстрые действия:

<a href="https://drive.google.com/uc?export=view&id=1O1PasHxOV9COzD7qycmcC30YiTsNR11R"><img src="https://drive.google.com/uc?export=view&id=1O1PasHxOV9COzD7qycmcC30YiTsNR11R" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

Позволяют запросить у пользователя информацию (набор полей), валидировать ввод и выполнить произвольный скрипт:

<a href="https://drive.google.com/uc?export=view&id=1S8D9zmxiAAqG-na80w7nU_1Q_kEW_tq_"><img src="https://drive.google.com/uc?export=view&id=1S8D9zmxiAAqG-na80w7nU_1Q_kEW_tq_" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

### `indexQuery`

Общий фильтр записей в списке. Применяется всегда, в т.ч. при переходе к быстрому фильтру.

### `cards`

Виджеты ресурса. Можно выводить различные метрики и диаграммы, а также виджет для работы с бизнес-процессом (см. раздел ниже):

<a href="https://drive.google.com/uc?export=view&id=1I0py6xgEw86Fy8v6t_Au4AGx-Vq1RxQH"><img src="https://drive.google.com/uc?export=view&id=1I0py6xgEw86Fy8v6t_Au4AGx-Vq1RxQH" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

## Бизнес-процессы

Некоторые ресурсы, например, Вакансии и Отклики, поддерживают бизнес-процессы (БП или Workflow). Для такого ресурса можно задать список статусов и правила перехода из одного статуса в другой. Конечный пользователь может перевести запись из одного статуса в другой посредством виджета (см. cards в разделе Конфигурация).

БП представляет собой json, где первый уровень - это статусы, из которых возможен переход, а второй - в которые:

<a href="https://drive.google.com/uc?export=view&id=1fsoDnijFKjXjXfDVnhrv4o45NvVxeoxg"><img src="https://drive.google.com/uc?export=view&id=1fsoDnijFKjXjXfDVnhrv4o45NvVxeoxg" style="width: 650px; max-width: 100%; height: auto" title="Click to enlarge picture" />

Для каждого перехода можно задать:
- `label` - Название кнопки (может отличаться от названия статуса, например, "Отправить на доработку" и "На доработке").
- `permissions` - Список ролей, имеющих право выполнить переход.
- `operations` - Аналог быстрых действий в ресурсах (см. actions в разделе Конфигуратор). Например, можно запросить у пользователя комментарий при отправке на доработку и занести его в историю работы с записью.

## Консоль вендора

### Набор команд artisan для вендора:

- `apply:manage` - Позволяет добавлять новые и менять существующие ресурсы, поля и связи. Автоматически создает миграции, модели, политики, настройки json для конфигуратора и т.д.
- `account:manage` - Добавление новых клиентов.

## Roadmap

1. Удобный GUI для конфигуратора.
2. Разработка демо-конфигурации.
3. Разработка типовых отчетов Google Data Studio по эффективности рекламных кампаний (все данные есть в БД apply, каждому клиенту предоставляется специальный пользователь БД с доступом на чтение) и самого сайта.
