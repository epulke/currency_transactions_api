## About Currency Transactions Api

Currency Transactions API is an api that allows to make currency transactions on different currencies from one account to the other.

## Getting Started

To start using the API download all the package contents and open the project in your local repository.

Create your own .env file based on the [.env.example](https://github.com/epulke/currency_app_1/blob/master/.env.example) file. 
Currently, you need to change information about the database:

<code>DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=currency_transactions
DB_USERNAME=root
DB_PASSWORD=</code>

And also you need to add [Exchangerate.host]( https://exchangerate.host/) api key, that you can get here for free. 
Include this information in .env under:

<code>EXCHANGERATE.HOST_API_KEY=</code>

Install necessary libraries by running:

<code>composer install</code>

Then run the following command in the root of your local repository:

<code>php artisan migrate --seed</code>

This will create the necessary tables and seed your database with some data so that it would be possible to see all the possibilities of the api.

## Functionality
To see the functionality of the app simply run the command

<code>php artisan serve</code>

Next open some aplication that is able to send HTTP requests, for example, Postman. And this is how you will be able to send the requests to your local host. 

1. You can get the list of all the available accounts by client ID.
Just send GET request to:

<code>/api/accounts/{clientid}</code>

You will ge the list with all the available client accounts.

<code>[
    {
        "accountid": 1,
        "account_number": "1234567891",
        "currency_name": "EUR",
        "balance": "1000.0000000000000000"
    }
]</code>

2. To make a transaction from one account to the other, just send POST request to the following endpoint:

<code>/api/transactions</code>

In the body of the request pass the parameters as shown in the example below:

<code>{
   "accountid_from": 16,
   "accountid_to": 24,
   "amount": 10,
   "currency": "EUR"
}</code>

Be aware that the currency should match the currency of the account to which the amount is transferred.
Excahnge rates will be taken live from [Exchangerate.host]( https://exchangerate.host/) api.
If the transaction was successful, you will receive the following message: 'Funds transferred successfully.'

3. To see the list of transaction for some specific account, send the GET request to the following endpoint:

<code>api/account_transactions/{accountid}</code>

Additionally you can pass 'offset' and/or 'limit' parameters, in case you do not want to see all the transactions, but only some specific.
At the moment, for the performance purposes, the maximum number of transactions to be shown are 100.

The list of the transactions will appear on the screen, sorted from newest to oldest transaction.
The following information will be shown:

<code> {
    "transactions": [
        {
            "transactionsid": 1,
            "accountid_from": 1,
            "accountid_to": 2,
            "amount_from": "10.0000000000000000",
            "amount_to": "10.9000000000000000",
            "exchange_rate": "1.0900000000000000",
            "created_at": "2023-11-30T21:05:50.000000Z",
            "updated_at": "2023-11-30T21:05:50.000000Z"
        }
    ]
}</code>

## Testing

There are tests available for the api, but because Exchangerate.host api data is changing all the time, there is a separate database for testing puproses as well.
To set up the environment:
1. Copy your .env file and save it as .env.testing. Change the database name for a different one, other parts can stay the same.

2. Open [phpunit.xml](https://github.com/epulke/currency_transactions_api/blob/master/phpunit.xml) file input your database name in:
<code>name="DB_DATABASE" value="currency_transactions_test"</code>

3. Run migrations:
<code>php artisan migrate --env=testing</code>

4. Run seeds:
<code>php artisan --env=testing db:seed --class=TestDatabaseSeeder</code>

5. Run the tests:
<code>php artisan test</code>