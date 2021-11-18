# Decission Support System's Method Calculator

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/fanboykun/decissionsupportsystem.svg?style=flat-square)](https://packagist.org/packages/fanboykun/decissionsupportsystem)
[![Total Downloads](https://img.shields.io/packagist/dt/fanboykun/decissionsupportsystem.svg?style=flat-square)](https://packagist.org/packages/fanboykun/decissionsupportsystem)
![GitHub Actions](https://github.com/fanboykun/decissionsupportsystem/actions/workflows/main.yml/badge.svg) -->

This is a laravel package to do certain method of Decision Support System. make sure to always use latest version of Laravel, and minimum PHP version is 7.4.



## Installation

You can install the package via composer:

```bash
composer require fanboykun/decissionsupportsystem
```

you don't have to configure anything, it's ready to use as it functionality is only to make calculation based on your method's choise (explained below)

## Usage
import the class on your controller
```php
use Fanboykun\DecissionSuppportSystem\DecissionSuppportSystem;
```
and then use it as a parameter on your function, example :

```php
public function calculate (DecissionSupportSystem $decissionSupportSystem)
{
    // for example, let's use moora as the method, you can choose what method you want to use, method list and the function are available below
    $result = $decissionSupportSystem->mooraOperator($your_data_to_calculate);

    return $result;
}
```
or you can make a new class instance, example :
```php
public function calculate ()
{
    // for example, let's use moora as the method, you can choose what method you want to use, method list and the function are available below
    $decissionSupportSystem = new DecissionSupportSystem();
    $result = $decissionSupportSystem->mooraOperator($your_data_to_calculate);

    return $result;
}
```
NOTE: the returned data type is array and the data that returned is like this example :
```php
$returned_data = [
    ['alternative_id' => 1, 'optimized_value' => 0.9, 'rank' => 1,],
    ['alternative_id' => 2, 'optimized_value' => 0.8, 'rank' => 2,],
    ['alternative_id' => 3, 'optimized_value' => 0.6, 'rank' => 3,],
    ['alternative_id' => 4, 'optimized_value' => 0.5, 'rank' => 4,],
    ['alternative_id' => 5, 'optimized_value' => 0.4, 'rank' => 5,],
]
```
 the key of an index is ```alternative_id ```, and sorted by value of ```optimized_value```, and add an index named ```rank```.

 ## Required Data
 here is the example of the data that required and accepted, make sure to follow this step because in order to read and calculate the data, it depends on the array index name. you may have different index name so you have to map it's name and value.

 example from getting the data from database and mapping it :
 ```php
 public function getData(DecissionSupportSystem $decissionSupportSystem) : array
{
    $data_to_calculate = Criteria::with('alternatives')->get()
    ->map(function ($item, $key){
        return [
            'criteria_id' => $item->id,
            'name' => $item->name,
            'type' => $item->is_cost,
            'weight' => $item->weight,
            'max_value' => $item->max_value,
            'alternatives' => $item->alternatives->map(function ($item, $key){
                return [
                    'alternative_id' => $item->id,
                    'name' => $item->name,
                    'value' => $item->pivot->value,
                ];
            })->toArray()
        ];
    })->toArray();

    // example, we use moora method
    $result = $decissionSupportSystem->mooraOperator($data_to_calculate);

    return $result;
}
 ```
in above example, we are getting data from the database with eloquent, we get the data from ```Criteria``` model that have ``` ManyToMany ``` relationship with ```Alternative``` model.
If you are still confuse about the many to many relationship, make sure to read the Laravel documentation.

## Acceptable Data Types
From above explaination, we know the required data to pass. Here i going to show you the data type(s)

| Column                 | Data Types | Description |
|------------------------|------------|-------------|
| criteria_id            | integer    | it should be unique (id)
| name                   | string     | it is not used to begin any operation, but it's required
| type                   | boolean    | true is cost and false is benefit
| weight                 | float      | better to pass it as float, but it's accept any numeric value
| max_value              | float      | it's not required, when it's null. this package will search the max value
| alternatives           | array      | it is an array wrapper for the alternative
| alternative_id         | integer    | it should be unique (id)
| name                   | string     | it is not used to begin any operation, but it's required
| value                  | float      | better to pass it as float, but it's accept any numeric value


## Available Method and It's Function

- waspas :
```php
waspasOperator()
```

- moora :
```php
mooraOperator()
```

remember that you have to use the ``` DecissionSupportSystem ``` class, either from your function as a parameter or make a new class instance inside your function.

for now, the method that available is only those, imma add new method soon.
<!-- ### Testing

```bash
composer test
``` -->

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email irfanramadhan1812@gmail.com instead of using the issue tracker.

## Credits

-   [fanboykun](https://github.com/fanboykun)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
