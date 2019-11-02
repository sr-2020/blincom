[![Build Status](https://travis-ci.org/sr-2020/blincom.svg?branch=master)](https://travis-ci.org/sr-2020/blincom)
# Eva Auth Service

Swagger OpenAPI 3 documentaton: http://blincom.evarun.ru/api/documentation
- [Установка](#setup)
- [Пользователи](#users)
	- [Регистрация](#registration)
	- [Авторизация](#authorization)
	- [Авторизационный токен](#authtoken)
	- [Профиль](#profile)
	- [Список пользователей со статусами](#usersList)

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
Регистрация осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/register

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
curl -X POST "http://blincom.evarun.ru/api/v1/register" -H "Content-Type: application/json" -d "{\"email\":\"example@example.com\",\"password\":\"hunter2\",\"name\":\"John Doe\"}"
```


#### <a name="authorization"></a> Авторизация
Авторизация осуществляется через POST запрос на http://blincom.evarun.ru/api/v1/login

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
curl -X POST "http://blincom.evarun.ru/api/v1/login" -H "Content-Type: application/json" -d "{\"email\":\"example@example.com\",\"password\":\"hunter2\"}"
```

#### <a name="authtoken"></a> Авторизационный токен
Авторизационный токен `api_key` необходим для работы с API.

В каждый момент для конкретного пользователя валиден только один токен (полученный при последнем логине или сразу после регистрации).

К каждому запросу, требующему авторизацию должен добавляться заголовок `Authorization` формата Bearer Token:

```Authorization: Bearer <api_key>```

Авторизационный токен пользователя не являющегося администратором позволяет выполнять действия связанные только с изменением его профиля.

Все действия для получения общей информации не требуют использования авторизационного токена.

#### <a name="profile"></a> Профиль
Получение информации о текущем пользователе осуществляется через GET запрос на http://blincom.evarun.ru/api/v1/profile с авторизационным токеном `api_key`.

Этот кейс может быть полезен, когда нужно получить информацию только по одному конкретному авторизованному пользователю, вместо того, чтобы грузить весь список пользователей.

Так же данные в этом роуте не кэшируются.

Тело ответа:
```
{
  "id": 1,
  "admin": true,
  "name": "Api Tim Cook",
  "status": "free",
  "created_at": "2019-03-24 21:08:00",
  "updated_at": "2019-03-24 21:08:30"
}
```

Пример:
```
curl -X GET "http://blincom.evarun.ru/api/v1/profile" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC"
```

Редактирование информации о текущем пользователе осуществляется через PUT запрос на http://blincom.evarun.ru/api/v1/profile с авторизационным токеном `api_key`

Тело запроса:
```
{
  "email": "api-test@email.com",
  "password": "secret",
  "name": "Api Tim Cook",
  "status": "free"
}
```
Тело ответа:
```
{
  "id": 1,
  "admin": true,
  "name": "Api Tim Cook",
  "status": "free",
  "created_at": "2019-03-24 21:08:00",
  "updated_at": "2019-03-24 21:08:30"
}
```

Пример:
```
curl -X PUT "http://blincom.evarun.ru/api/v1/profile" -H "Authorization: Bearer MmVDDllSdUpKa0h5MFBDdjN1QnlVbEVC" -H "Content-Type: application/json" -d "{\"email\":\"api-test@email.com\",\"password\":\"secret\",\"name\":\"Api Tim Cook\",\"status\":\"free\"}"```
```

#### <a name="usersList"></a> Список пользователей со статусами

Получение информации о статусах всех пользователей осуществляется через GET запрос на http://blincom.evarun.ru/api/v1/users

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
curl -X GET "http://blincom.evarun.ru/api/v1/users"
```
