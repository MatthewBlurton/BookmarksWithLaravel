cmd /C "npm install && composer install && php artisan key:generate && php artisan storage:link && php artisan migrate:refresh --seed && php artisan passport:install"
