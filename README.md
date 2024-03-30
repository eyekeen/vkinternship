Сервис для регистрации и авторизации.

Docker v26.0.0
docker compose v2.25.0

## API

### API endpoints
[Коллекция postman c API эндпоинтами](./auth_api.postman_collection.json)

Сервис имеет 4 api-метода:

**POST /register**
- Описание: регистрация нового пользователя.
- Входные параметры: email (string) и password (string)
- Проверки: валидность и уникальность email. Надежность пароля (если пароль легко подобрать, возвращается ошибка weak_password)
- Выходные данные: user_id (int) и password_check_status (string)

**POST /authorize** 
- Описание: авторизация пользователя.
- Входные параметры: email (string) и password (string)
- JWT-токен, содержащий user_id и expire_date

**POST /logout**
- Описание: выход пользователя из системы.
- Входные параметры: JWT-токен в заголовке Authorization в формате Bearer <token>
- Действие: Удаление валидного JWT-токена из базы данных.

**GET /feed** 
- Описание: проверка валидности JWT-токена.
- Входные параметры: JWT-токен в заголовке Authorization в формате Bearer <token>
- Выходные данные: код 200, если токен валиден и код 401 unauthorized, если токен невалиден

### Установка и запуск
1.Копируем файл с переменными окружения
```bash
cp .env.example .env
```
2.Сборка контейнера
```bash
docker compose up -d --build
```
3.Установка зависимостей
```bash
docker compose run --rm www composer install
```
4.Импортируем в postman [коллекция c API эндпоинтами](./auth_api.postman_collection.json)