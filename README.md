
# REST API (CRUD) сервис

RESTful web service представляет данные в удобном для клиента формате с использованием протокола HTTP и принципов REST по модели client <-> server.
<p>В приложени реализовано:

- Создание элементов.
- Обновление элементов.
- Удаление элементов.
- Получение информации о элементе.
- Хранение истории изменений сущности.
- Валидация полей сущности.

<p>Доступ в сервису предоставляется по токену.
Работоспособность функционала приложения и БД контролируется тестами, охватывающими 90.91% используемого кода.

С отчетом о покрытии кода тестами можно ознакомиться: `tests/coverage/index.html`.
История изменений сущности сохраняется json-поле сущности.


## Начальная настройка

После установки необходимо доустановить требуемые пакеты командой `composer install`, а так же в файле .env необходимо настроить доступ к БД. Файл .env.testing может быть использован для проведения тестирования.
Для создания рабочих таблиц выполнить команду `php artisan migrate`.
В файле `config/apitokens.php` содержится массив с действительными токенами. Токены могут быть заменены своими.
Для работы может потребоваться изменение прав доступа к файлам (зависит от операционной системы).

## Сущность

<p>Сущность: Item
<p>Поля сущности:

- id - int автоинкремент
- name - char(255)
- phone - char(15)
- key - char(25) not null
- created_at - datetime - дата создания элемента
- pdated_at - datetime - дата обновления элемента
- history - json - хранение истории изменения сущности

## Добавление элемента

- Авторизация по Bearer Token в заголовке запроса.
- Метод запроса: POST
- URL: /api/item/

<p>Параметры:

- name - char(255)
- phone - char(15)
- key - char(25) not null

## Список всех элементов

- Авторизация по Bearer Token в заголовке запроса.
- Метод запроса: GET
- URL: /api/item/list

<p>Параметры: нет

## Получение информации о элементе

- Авторизация по Bearer Token в заголовке запроса.
- Метод запроса: GET
- URL: /api/item/{id элемента}

<p>Параметры: нет

## Изменение элемента

- Авторизация по Bearer Token в заголовке запроса.
- Метод запроса: PUT
- URL: /api/item/{id элемента}

<p>Параметры:

- name - char(255)
- phone - char(15)
- key - char(25) not null

Указываются изменяемые поля.

## Удаление элемента

- Авторизация по Bearer Token в заголовке запроса.
- Метод запроса: DELETE
- URL: /api/item/{id элемента}

<p>Параметры: нет

## Получаемые данные

<p>JSON-формат

```
{
    "id": номер записи
    "name": "Имя",
    "phone": "телефон",
    "key": "ключ",
    "history": (при наличии)
        {
            "name": "Имя",
            "phone": "телефон",
            "key": "ключ",
            "updated_at": "дата и время изменения",
        },
        {
            "name": "Имя",
            "phone": "телефон",
            "key": "ключ",
            "updated_at": "дата и время изменения",
        }
    "created_at": "дата и время создания",
    "updated_at": "дата и время изменения",
}
```
