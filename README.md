# Board McBoardson

A simple message board build using Laravel, Vue.js and TypeScript.

## Requirements
- PHP 7.2
- MySQL 5.7
- Node.js 14.15
- NPM 6.14

## Dependencies
- Laravel 7.29
- Laravel Mix 5.0
- TypeScript 4.0
- Vue 2.6
- Vue Material 1.0
- Vue Class Components 7.2
- Vue Property Decorators 9.0

## Instalation

After checking out this git repository, install the PHP dependencies:

```
composer install
```

Copy the `.env.example` into a new file named `.env`, and fill your DB credentials:

```
DB_CONNECTION=<your database server "flavour" (MySQL is recommended: 'mysql')>
DB_HOST=<your database server address (IP or name)>
DB_PORT=<your database server port>
DB_DATABASE=<your database name>
DB_USERNAME=<your database username>
DB_PASSWORD=<your database password>
```

After that, you'll need to set the application key:

```
php artisan key:generate
```

Now let's set up the DB:

```
php artisan migrate
```

And it could be interesting to run the seeders too, specially the User Seeder. Fill it with your credentials and run the seeds:

```
php artisan db:seed
```

We're almost there, so now let's install our frontend:

```
npm install
```

And to get it running:

```
npm run production
```
