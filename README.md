Сервис для регистрации и авторизации.

### API

Сервис имеет 3 api-метода:

- **POST /register** входные параметры string email, string password. Есть проверка валидности и уникальности email. Также имеется проверка надежности пароля - если пароль легко подобрать, - выкидывает ошибку weak_password. На выходе возвращает int user_id, string password_check_status.

- **POST /authorize** входные параметры string email, string password. В ответ - jwt-токен в котором будет лежать user_id.

- **POST /logout** принимает валидный jwt токен в Authorization Bearer и удаляет его из базы.

- **GET /feed** принимает jwt токен в Authorization Bearer. Если токен валидный возвращает код 200 иначе 401 unauthorized.

### Установка и запуск
Копирование файла с переменными окружения
```bash
cp .env.example .env
```
Сборка контейнера
```bash
docker-compose up -d --build
```
Установка зависимостей
```bash
docker-compose run --rm www composer install
```
Если при создании контейнера не выполнилась команда composer install то нужно зайти в контейнер и в папке /var/www/html прописать её вручную

[Коллекция postman c API эндпоинтами.](./auth_api.postman_collection.json) Для авторизации используется Bearer token.

![bearer.jpg](./bearer.jpg)
