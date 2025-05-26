Prerequisites:
-composer  
-laravel 10
-xamp 
-bash shell
installation steps:
- clone project
- install composer
- create db 
- php artisan key:generate
- php artisan migrate
- in bash shell put this code and run it to run schedule task automatically in local server
 while true; do
    php artisan schedule:run
    sleep 60
done
- php artisan serve 

