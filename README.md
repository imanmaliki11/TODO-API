## PROJECT TO DO API
This Project just for me learn more with Laravel API and Sanctum, and also for my portofolio :)


## Deployment
In Your Local, you can clone or download this repository.

```bash
  cd <your-folder-to-save-this-project>
```

You need to create your database

Config your .env (You can copy from .env.example)
Don't forget to config your database in .env

```bash
  cp .env.example .env
```

After that you need to install dependency using composer, generate key, and migrate Database.

```bash
  composer install
  php artisan key:generate
  php artisan migrate
  php artisan serve
```

## API Reference
You can read my API reference (POSTMAN)

[See the API reference](https://documenter.getpostman.com/view/12621150/VV51tuLW)

You need an access token for access To Do, Parent To Do, and User API, you can get an access token after you login
## Demo

I want to try publish this API ASAP :) 


## Tech

**Laravel 8.0**

**Laravel Sanctum**

**MySQL**

**Composer**