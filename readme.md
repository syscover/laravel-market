# Market for Laravel

[![Total Downloads](https://poser.pugx.org/syscover/market/downloads)](https://packagist.org/packages/syscover/market)

## Installation

**1 - After install Laravel framework, execute on console:**
```
composer require syscover/pulsar-market
```

**2 - Execute publish command**
```
php artisan vendor:publish --provider="Syscover\Market\MarketServiceProvider"
```

**3 - Execute optimize command load new classes**
```
php artisan optimize
```

**4 - And execute migrations and seed database**
```
php artisan migrate
php artisan db:seed --class="MarketTableSeeder"
```

**5 - Execute command to load all updates**
```
php artisan migrate --path=vendor/syscover/pulsar-market/src/database/migrations/updates
```

**6 - Register middleware pulsar.taxRule on file app/Http/Kernel.php add to routeMiddleware array**
```
'pulsar.tax.rule' => \Syscover\Market\Middleware\TaxRule::class,
```


## General configuration environment values
We indicate configuration variables available, to change them what you should do from the file environment variables .env

### Order id prefix [default value empty]
You can set a prefix for all your orders, for example, if you can set on all you orders the prefix ORDER, set this value on you .env file
```
MARKET_ORDER_ID_PREFIX=ORDER
```

### Tax default country [default value ES]
To set default country to calculate tax, you can use this parameter, for example to change to US, set this value on you .env file

```
MARKET_DEFAULT_COUNTRY_TAX=US
```

### Default customer class tax [default value 1]
Set default ID customer class value for calculate tax amount of products

```
MARKET_DEFAULT_CLASS_CUSTOMER_TAX=1
```

### Set product price tax [default value 1]
Defines the types of prices that are introduced in products, this option is consulted when you create or update a product
You have this values:
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_PRODUCT_TAX_PRICES=1
```

### Set shipping price tax [default value 1]
Defines the types of prices that are introduced in shipping prices, this option is consulted when you create or update a shipping price
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_TAX_SHIPPING_PRICES=1
```

### Set product display price tax [default value 1]
Defines how you want display product prices
You have this values:
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_PRODUCT_TAX_DISPLAY_PRICES=1
```

### Set shipping display price tax [default value 1]
Defines how you want display shipping prices
* Value: 1 *Excluding tax*
* Value: 2 *Including tax*

```
MARKET_TAX_SHIPPING_DISPLAY_PRICES=1
```

## PayPal environment values

### Set PayPal mode
* Value: sandbox *for testing or development environments* 
* Value: live *for production environments* 
```
MARKET_PAYPAL_MODE=sandbox
```

### PayPal sandbox values
```
MARKET_PAYPAL_SANDBOX_WEB_PROFILE=XX-XXXX-XXXX-XXXX-XXXX
MARKET_PAYPAL_SANDBOX_CLIENT_ID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MARKET_PAYPAL_SANDBOX_SECRET=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### PayPal live values
```
MARKET_PAYPAL_LIVE_WEB_PROFILE=XX-XXXX-XXXX-XXXX-XXXX
MARKET_PAYPAL_LIVE_CLIENT_ID=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MARKET_PAYPAL_LIVE_SECRET_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### PayPal translations
If you want set shipping description in PayPal you mus to create the next key in common.php language file
```
paypal_shipping_description
```

If you want set item list description in PayPal you mus to create the next key in common.php language file
```
paypal_item_list_description
```

## Redsys environment values

### Set Redsys mode
* Value: test *for testing or development environments* 
* Value: live *for production environments* 
```
MARKET_REDSYS_MODE=test
```

### Redsys test values
```
MARKET_REDSYS_TEST_MERCHANT_NAME="MERCHANT NAME (TEST)"
MARKET_REDSYS_TEST_MERCHANT_CODE=xxxxxxxxx
MARKET_REDSYS_TEST_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

### Redsys live values
```
MARKET_REDSYS_LIVE_MERCHANT_NAME="MERCHANT NAME"
MARKET_REDSYS_LIVE_MERCHANT_CODE=xxxxxxxxx
MARKET_REDSYS_LIVE_KEY=xXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx
```

## Other concepts 

### Set tax rules values for each customer
When a customer is login on your web application, you need know your country and customer group to calculate tax rules for all products.
You have a Middleware who is responsible to do this actions.

```
Route::group(['middleware' => ['pulsar.tax.rule']], function() {

    // write here your routes

});

```

This middleware set market.taxCountry and market.taxCustomerClass if customer has country and customer group id defined