# testeMobly
Teste admissional para desenvolvedor PHP na Mobly

# Install & run (Windows)
 
 - cd testeMobly

 - composer install

 - php artisan make:db_create

 - editar arquivo .env.example (criar novo de preferencia) com db_mobly para DB e usuário e senha do MYSQL

 - php artisan migrate:install 
 
 - php artisan migrate

 - php artisan key:generate

 - php artisan serve