# Laravel internet bank website

## Features
- Change appearance between light and dark mode
- Register a new user, login and logout
- Change user's name, e-mail, password and delete
- Refresh user's security codes
- Create standard or cryptocurrency accounts
- View accounts, edit account label, close account
- View cryptocurrency price changes from coinmarketcap.com API
- Buy and sell cryptocurrencies
- Send any currency to other accounts
- View current exchange rates from bank.lv XML file
- View transaction history and filter the results

## Technologies used
- PHP 8.2
- mySQL 8.0
- Composer 2.4
- Node.js npm 8.19
- Laravel 9.45

## Instructions to run the website
1. Clone this repository and navigate to its directory by using the commands:
    ```
    https://github.com/tomskoralis/laravel-project
    cd laravel-project/
    ```
2. Install the required packages using the commands:
    ```
    composer install
    npm install
    ```
3. Make a copy of the `.env.example` and rename the copy to `.env` by using the command:
    ```
    cp .env.example .env
    ```
4. Register at https://pro.coinmarketcap.com/signup and get the API key.
5. Save the API key in the `.env` in the `COIN_MARKET_CAP_API_KEY` variable.
6. Create a new mySQL database schema
7. Enter the mySQL credentials in the `.env` file. The variables are: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
8. Run the database migrations and seed the database using the command:
    ```
    php artisan migrate:fresh --seed
    ```
9. Run the Vite development server using the command:
    ```
    npm run dev
    ```
10. Start the local development server using the command in another terminal window:
    ```
    php artisan serve
    ```
11. Test the website by opening it in the browser using the URL http://127.0.0.1:8000
