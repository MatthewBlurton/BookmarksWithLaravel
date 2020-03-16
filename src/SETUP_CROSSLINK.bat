cmd /C "npm install && composer install && php artisan key:generate && php artisan storage:link && php artisan migrate:fresh --seed && php artisan passport:install"
