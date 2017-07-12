# Install
    - git clone https://github.com/GlennKimbleJr/timeclock.git
    - cd timeclock
    - composer install
    - npm install
    - mv .env.example .env
    - php artisan key:generate
    - edit the database variables in .env
    - php artisan migrate
    - php artisan serve
