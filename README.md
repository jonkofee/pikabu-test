# Начало работы

``` bash
# Создание файл переменных окружения
cp .env.dist .env

# Запуск docker
docker-compose up -d

# Запуск миграций БД
php cli.php migrate

#В hosts файл добавить
127.0.0.1 pikabu-test.jonkofe
```