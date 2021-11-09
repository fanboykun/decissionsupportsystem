# Decission Support System's Method Calculator

<!-- [![Latest Version on Packagist](https://img.shields.io/packagist/v/fanboykun/decissionsupportsystem.svg?style=flat-square)](https://packagist.org/packages/fanboykun/decissionsupportsystem)
[![Total Downloads](https://img.shields.io/packagist/dt/fanboykun/decissionsupportsystem.svg?style=flat-square)](https://packagist.org/packages/fanboykun/decissionsupportsystem)
![GitHub Actions](https://github.com/fanboykun/decissionsupportsystem/actions/workflows/main.yml/badge.svg) -->

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.




## Installation

You can install the package via composer:

```bash
composer require fanboykun/decissionsupportsystem
```

you don't have to configure anything, it's ready to use as it functionality is only to make calculation based on your method's choise (explained below)

## Usage
import the class on your controller
```php
use Fanboykun\DecissionSuppportSystem;
```
and then use it as a parameter on your function, example :

```php
public function calculate (DecissionSupportSystem $decissionSupportSystem)
{
    // for example, let's use moora as the method, you can choose what method you want to use, method list and the function are available below
    $result = $decissionSupportSystem->mooraService($your_data_to_calculate);

    return $result;
}
```
or you can make a new class instance, example :
```php
public function calculate ()
{
    // for example, let's use moora as the method, you can choose what method you want to use, method list and the function are available below
    $decissionSupportSystem = new DecissionSupportSystem();
    $result = $decissionSupportSystem->mooraService($your_data_to_calculate);

    return $result;
}
```
NOTE: the returned data type is array and the data that returned is like this example :
```php
$returned_data = [
    ['alternative_id' => 1, 'result' => 0.9, 'rank' => 1,],
    ['alternative_id' => 2, 'result' => 0.8, 'rank' => 2,],
    ['alternative_id' => 3, 'result' => 0.6, 'rank' => 3,],
    ['alternative_id' => 4, 'result' => 0.5, 'rank' => 4,],
    ['alternative_id' => 5, 'result' => 0.4, 'rank' => 5,],
]
```
 the key of an index is ```alternative_id ```, and sorted by value of ```result```, and add an index named ```rank```.

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
    $result = $decissionSupportSystem->mooraService($data_to_calculate);

    return $result;
}
 ```
in above example, we are getting data from the database with eloquent, we get the data from ```Criteria``` model that have ``` ManyToMany ``` relationship with ```Alternative``` model.
If you are still confuse about the many to many relationship, make sure to read the Laravel documentation.

## Available Method and It's Function

- waspas :
```php
waspasService()
```

- moora :
```php
mooraService()
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
