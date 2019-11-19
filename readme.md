[![Build Status](https://travis-ci.org/sr-2020/blincom.svg?branch=master)](https://travis-ci.org/sr-2020/blincom)
# Blincom Service

- [Установка](#setup)
- [Пользователи](#users)
	- [Регистрация](#registration)
	- [Авторизация](#authorization)
	- [Авторизационный токен](#authtoken)
	- [Профиль](#profile)
	- [Список пользователей со статусами](#usersList)
	- [Управление видимостью местоположения](#followers)
- [Магазин иплантов](#items)
	- [Список имплантов](#itemsList)
	- [Купить имплант](#buyItem)
	- [Выбросить имплант](#dropItem)
- [Позиционирование](#position)
	- [Добавить локацию](#addLocation)
	- [Добавить маячок](#addBeacon)
	- [Check In местоположения](#checkPosition)


## <a name="setup"></a> Установка
Для локальной установки и тестирования нужно выполнить:
```
make install
make up
make test
```
Команда test может пройти не сразу, так как приложение запускается в асинхронном режиме. Нужно будет подождать 1-2 минуты и запустить команду `make test` еще раз.

## <a name="users"></a> Пользователи
#### <a name="registration"></a> Регистрация
Регистрация осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/auth/register

Тело запроса:
```
{
  "email": "example@example.com",
  "password": "hunter2",
  "name": "John Doe"
}
```
Тело ответа:
```
{
  "id": 1,
  "api_key": "MmVDellSdUpKa0h5MFBDdjN1QnlVbEVC"
}
```

Пример:
```
curl -X POST "http://blincom.evarun.ru/api/v1/auth/register" -H "Content-Type: application/json" -d "{\"email\":\"example@example.com\",\"password\":\"hunter2\",\"name\":\"John Doe\"}"
```

#### <a name="authorization"></a> Авторизация
Авторизация осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/auth/login

Тело запроса:
```
{
  "email": "example@example.com",
  "password": "hunter2"
}
```
Тело ответа:
```
{
  "id": 1,
  "api_key": "MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
}
```

Пример:
```
curl -X POST "http://blincom.evarun.ru/api/v1/auth/login" -H "Content-Type: application/json" -d "{\"email\":\"example@example.com\",\"password\":\"hunter2\"}"
```

#### <a name="authtoken"></a> Авторизационный токен
Авторизационный токен `api_key` необходим для работы с API.

В каждый момент для конкретного пользователя валиден только один токен (полученный при последнем логине или сразу после регистрации).

К каждому запросу, требующему авторизацию должен добавляться заголовок `Authorization` формата Bearer Token:

```Authorization: Bearer <api_key>```

Авторизационный токен пользователя не являющегося администратором позволяет выполнять действия связанные только с изменением его профиля.

Все действия для получения общей информации не требуют использования авторизационного токена.

#### <a name="profile"></a> Профиль
Получение информации о текущем пользователе осуществляется через GET запрос на http://blincom.evarun.ru/api/v1/auth/profile с авторизационным токеном `api_key`.

Этот кейс может быть полезен, когда нужно получить информацию только по одному конкретному авторизованному пользователю, вместо того, чтобы грузить весь список пользователей.

Так же данные в этом роуте не кэшируются.
Поля
- `items` - список купленных иплантов
- `followers` - список пользователей, которые могут видеть местоположение владельца профиля
- `following` - список пользователей, которые разрешили владельцу профиля видеть свое местоположение
- `role` - роль пользователя
- `status` - статус пользователя
- `amount` - баланс пользователя

Тело ответа:
```
{
    "id": 1,
    "admin": true,
    "amount": 900,
    "followers": [
        2
    ],
    "following": [
        2,
        3
    ],
    "items": [
        {
            "activate": false,
            "id": 3,
            "name": "Budda",
            "options": {
                "a": true,
                "b": 1,
                "c": "on"
            },
            "price": "100"
        }
    ],
    "name": "Мистер X",
    "options": {
        "a": true,
        "b": 1,
        "c": "on"
    },
    "role": "runner",
    "status": "maroon",
    "created_at": "2019-11-04 13:06:23",
    "updated_at": "2019-11-04 18:41:46"
}
```

Пример:
```
curl -X GET "http://blincom.evarun.ru/api/v1/auth/profile" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

Редактирование информации о текущем пользователе осуществляется через PUT запрос на http://blincom.evarun.ru/api/v1/auth/profile с авторизационным токеном `api_key`

Доступные роли: `runner`, `patrol`, `budda` (только при наличии купленного импланта `budda`)

Тело запроса:
```
{
  "email": "api-test@email.com",
  "password": "secret",
  "name": "Api Tim Cook",
  "status": "free",
  "role": "patrol"
}
```
Тело ответа:
```
{
  "id": 1,
  "admin": true,
  "amount": 900,
  "name": "Api Tim Cook",
  "status": "free",
  "role": "patrol",
  "created_at": "2019-03-24 21:08:00",
  "updated_at": "2019-03-24 21:08:30"
}
```

Пример:
```
curl -X PUT "http://blincom.evarun.ru/api/v1/auth/profile" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC" -H "Content-Type: application/json" -d "{\"email\":\"api-test@email.com\",\"password\":\"secret\",\"name\":\"Api Tim Cook\",\"status\":\"free\",\"role\":\"patrol\"}"```
```

#### <a name="usersList"></a> Список пользователей со статусами

Получение информации о статусах всех пользователей осуществляется через GET запрос на http://blincom.evarun.ru/api/v1/auth/users

Данные в этом списке кэшируются на 1 секунду методом автоматического прогревания кэша крон-скриптом.

Полезные поля которые можно отобразить:
 - `name` (имя указанное при регистрации)
 - `status` (статус выбранный пользователем)
 - `created_at` (время когда пользователь зарегистрировался в системе)
 - `updated_at` (время когда пользователь в последний раз обновлял свой профиль)

Тело ответа:
```
[
    {
      "id": 1,
      "admin": true,
      "name": "Api Tim Cook",
      "status": "free",
      "created_at": "2019-03-24 21:08:00",
      "updated_at": "2019-03-24 21:08:30"
    },
    ...
]
```

Пример:
```
curl -X GET "http://blincom.evarun.ru/api/v1/auth/users"
```

#### <a name="followers"></a> Управление видимостью местоположения
Чтобы разрешить пользователю видеть местоположение владельца профиля нужно выполнить POST запрос http://blincom.evarun.ru/api/v1/auth/profile/followers/{userId}, где `{userId}` - идентификатор пользователя.
После этого в поле `followers` в профиле появится идентификатор пользователя `{userId}`, у которого в свою очередь в поле `following` появится идентификатор владельца профиля, выполнившего этот запрос.

Пример
```
curl -X POST "http://blincom.evarun.ru/api/v1/auth/profile/followers/1" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

Чтобы отозвать это разрешение нужно выполнить DELETE запрос http://blincom.evarun.ru/api/v1/auth/profile/followers/{userId}.

Пример
```
curl -X DELETE "http://blincom.evarun.ru/api/v1/auth/profile/followers/1" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

## <a name="items"></a> Магазин имплантов
### <a name="itemsList"></a> Список имплантов
Просмотреть список всех имплантов, доступные для покупки отображаются с флагом `"allow":true`.
```
[
     ...
    {
        "activate": false,
        "allow": true,
        "id": 3,
        "name": "Budda",
        "options": {
            "a": true,
            "b": 1,
            "c": "on"
        },
        "price": 100
    },
    ...
```

Пример:
```
curl -X GET "http://blincom.evarun.ru/api/v1/auth/items" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

### <a name="buyItem"></a> Купить имплант
Для покупки импланта нужно выполнить пустой POST запрос на следующий роут: http://blincom.evarun.ru/api/v1/auth/profile/items/{itemId},
где `{itemId}` - идентификатор импланта.
Для успешной покупки необходима соответствующая сумма в поле профиля `amount`.

Пример
```
curl -X POST "http://blincom.evarun.ru/api/v1/auth/profile/items/3" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

### <a name="dropItem"></a> Выбросить имплант
Для того, чтобы выбросить имплант нужно выполнить пустой DELETE запрос на следующий роут: http://blincom.evarun.ru/api/v1/auth/profile/items/{itemId},
где `{itemId}` - идентификатор импланта.

Пример
```
curl -X DELETE "http://blincom.evarun.ru/api/v1/auth/profile/items/3" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

## <a name="position"></a> Позиционирование
### <a name="addLocation"></a> Добавить локацию
Добавление локации осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/position/locations
Использовать админский токен: `TkRVem4yTERSQTNQRHFxcmo4SUozNWZp`

Тело запроса:
```
{
  "label": "room1",
}
```
Тело ответа:
```
{
  "id": 3003,
  "label": "room1",
}
```

Пример:
```
curl -X POST "http://blincom.evarun.ru/api/v1/position/locations" -H "Authorization: Bearer TkRVem4yTERSQTNQRHFxcmo4SUozNWZp" -H "Content-Type: application/json" -d "{\"label\":\"room1\"}"
```


### <a name="addBeacon"></a> Добавить маячок
Добавление маячка осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/position/beacons
Использовать админский токен: `TkRVem4yTERSQTNQRHFxcmo4SUozNWZp`

Тело запроса:
```
{
  "label": "beacon1",
  "ssid": "beacon1",
  "bssid": "c0:0a:95:9d:cd:0c",
  "location_id": 3003
}
```
Тело ответа:
```
{
  "id":3009,
  "bssid":"C0:0A:95:9D:CD:0C",
  "label":"beacon1",
  "location_id":3003,
  "ssid":"beacon1"
}
```

Пример:
```
curl -X POST "http://blincom.evarun.ru/api/v1/position/beacons" -H "Authorization: Bearer TkRVem4yTERSQTNQRHFxcmo4SUozNWZp" -H "Content-Type: application/json" -d {\"label\":\"room1\",\"ssid\":\"beacon1\",\"bssid\":\"c0:0a:95:9d:00:0c\"}"
```

### <a name="checkPosition"></a> Check In местоположения
Check In местоположения осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/position/positions
Использовать пользовательский токен.

Нужно передавать список всех видимых BLE-маячков с соответствующими полями. Пользователь зачекинится у маячка, `level` уровень слышимости которого будет максимальным, после чего пользователь привяжется к локации маячка.

Тело запроса:
```
{
  "beacons": [
    {
      "ssid": "beacon1",
      "bssid": "b0:0a:95:9d:00:0a",
      "level": -70
    },
    {
      "ssid": "beacon3003",
      "bssid": "c0:0a:95:9d:cd:0c",
      "level": -50
    }
  ]
}
```
Тело ответа:
```
{
 "id": 180619,
 "user_id": 1,
 "location_id": 3003,
 "created_at": "2019-11-19 05:36:27"
}
```

Пример:
```
curl -X POST "http://blincom.evarun.ru/api/v1/position/positions" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC" -H "Content-Type: application/json" -d "{\"beacons\":[{\"ssid\":\"beacon1\",\"bssid\":\"b0:0a:95:9d:00:0a\",\"level\":-70},{\"ssid\":\"beacon1\",\"bssid\":\"c0:0a:95:9d:cd:0c\",\"level\":-50}]}"
```