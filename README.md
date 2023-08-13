Добавляем файл конфигураций
cp .env.example .env

Создаем папки
mkdir storage/framework/cache
mkdir storage/framework/sessions
mkdir storage/framework/testing
mkdir storage/framework/views

Устанавливаем зависимости
php composer.phar install

Генерируем секретный ключ
php artisan key:generate

Создаем ссылку на хранилище
php artisan storage:link


