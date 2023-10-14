# CDN Code Challenge

Implementing a system with Wallet and Voucher services.

Postman collection with all the methods and example responses can be found in [HERE](https://github.com/sasani72/cdn-challenge/blob/main/docs/cdn-collection.postman_collection.json)

## Running the application

1. Clone this repository
    ```bash
    git clone https://github.com/sasani72/cdn-challenge.git
    ```
2. Install packages with composer (Make sure composer is installed already)

    ```bash
    composer install
    ```

3. Make a copy of .env.example as .env

    ```bash
    cp .env.example .env
    ```

4. Run migration and seed the database

    ```bash
    php artisan migrate
    php artisan db:seed
    ```

## Run Tests

Run application tests

```bash
 php artisan test
```

## License

Licensed under the [MIT license](https://opensource.org/licenses/MIT).
