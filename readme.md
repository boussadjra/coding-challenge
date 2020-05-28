# Installation and usage :

##### clone the project :

    git clone https://github.com/boussadjra/coding-challenge.git
    
    cd coding-challenge

##### setup the server side :

    composer update

    php artisan serve

navigate to :

    http://127.0.0.1:8000 

Run seeder 

    php artisan db:seed --class=GraphsTableSeeder

##### setup the client side :

  change directory to the `front-end` folder at the project root :

    cd front-end
 
    npm install
  
    npm run serve

navigate to :

    http://127.0.0.1:8080 

