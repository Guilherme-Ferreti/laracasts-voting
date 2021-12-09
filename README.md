# Laracasts Voting App
Share your opinion with the world! Create ideas, vote and comment on them, sort and filter the results, and even administer the site.

This project was created along with the course [Build a Voting App](https://laraecasts.com/series/build-a-voting-app) by Laracasts instructor Andre Madarang.

You can see it live [here](https://laracasts-voting.herokuapp.com/).

### Features added by myself
• Portuguease translation (Coming soon).

### Requirements

* PHP 8.0.6 or newer.

### Installation

Start by cloning this repository. 

``` git clone https://github.com/Guilherme-Ferreti/laracasts-voting.git```

Make sure you have installed Composer. If not, please check its official [guide](http://getcomposer.org/doc/00-intro.md#installation).

When ready, install the dependencies by running the following command in your application's root folder.

```composer install```

Create a new .env file by copying your .env.local file.

```cp .env.example .env```

Configure your database connection and use the following command to create tables and some test data.

```php artisan migrate:fresh --seed```

Then, serve the application using PHP's development server.

Finally, generate the encryption key:

```php artisan key:generate```

```php artisan serve```

Access [localhost:8000](http://localhost:8000) and enjoy!