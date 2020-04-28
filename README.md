<p align="center"><img src=".github/icon.png" width="150"></p>
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-1-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->

<p align="center">
<a href="https://packagist.org/packages/kurozora/laravel-cooldown"><img src="https://img.shields.io/packagist/v/kurozora/laravel-cooldown.svg?style=flat-square" alt="Latest version on packagist"></a>
<a href="https://github.com/kurozora/laravel-cooldown/actions?query=workflow%3Arun-tests+branch%3Amaster"><img src="https://img.shields.io/github/workflow/status/kurozora/laravel-cooldown/run-tests?label=tests" alt="GitHub Tests Action Status"></a>
<a href="https://scrutinizer-ci.com/g/kurozora/laravel-cooldown"><img src="https://img.shields.io/scrutinizer/g/kurozora/laravel-cooldown.svg?style=flat-square" alt="Quality score"></a>
<a href="https://packagist.org/packages/kurozora/laravel-cooldown"><img src="https://img.shields.io/packagist/dt/kurozora/laravel-cooldown.svg?style=flat-square" alt="Total downloads"></a>
</p>

<p align="center">
  <sup><em>plug-and-play global and model-specific cooldowns</em></sup>
</p>

# Laravel Cooldowns

This Laravel package makes it easier to implement cooldowns into your app.  
Consider the following example:
```php
// The user will be able to post again 5 minutes from now
$user->cooldown('create-post')->for('5 minutes');
```

## Installation

You can install the package via composer:

```bash
composer require kurozora/laravel-cooldown
```

## Usage
### Global cooldowns  
Global cooldowns aren't tied to any model and are the same throughout your entire app.  
Use the `cooldown` helper to create one:

```php
cooldown('registration')->for('1 hour');
```

Here's an example of how you could limit registration to once per hour:

```php
if(cooldown('registration')->notPassed())
    return 'Registration is currently unavailable.';

// ... perform account registration ...

cooldown('registration')->for('1 hour');
```

### Model-specific cooldowns
Of course, a more useful use-case would be to tie cooldowns to models. In order to make use of this, you'll need to add the trait to your model:

```php
use Illuminate\Database\Eloquent\Model;
use Kurozora\Cooldown\HasCooldowns;

class User extends Model
{
    use HasCooldowns;
}
```

The API used to interact with model-specific cooldowns is the exact same as global cooldowns, however you use the `cooldown` method on the model itself:  

```php
if($user->cooldown('create-post')->notPassed())
    return 'You cannot create a post right now.';

// ... create the post ...

$user->cooldown('create-post')->for('5 minutes');
````

### All cooldown methods
These methods are available for both global and model-specific cooldowns.

`for()` **Cooldown for a timespan**  
Pass along a string with the desired timespan.
```php
cooldown('create-post')->for('1 day 3 hours');
```

`until()` **Cooldown for a given datetime**  
Pass along a Carbon object with the desired datetime.
```php
$tomorrow = now()->addDay();

cooldown('create-post')->until($tomorrow);
```

`reset()` **Reset the cooldown**  
The cooldown will be reset, and the action will be available immediately.
```php
cooldown('create-post')->reset();
```

`passed()`  
Checks whether the cooldown has passed. Returns true if the cooldown hasn't ever been initiated.
```php
cooldown('create-post')->passed(); // true/false
```

`notPassed()`  
Checks whether the cooldown is still active, and thus hasn't passed yet.
```php
cooldown('create-post')->notPassed(); // true/false
```

`expiresAt()` **Get the expiration date**  
Returns the datetime at which the cooldown will pass.
```php
cooldown('create-post')->expiresAt(); // Illuminate\Support\Carbon object
````

`get()`  
Returns the underlying Cooldown model.
```php
cooldown('create-post')->get(); // Kurozora\Cooldown\Models\Cooldown object
````

### Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email kurozoraapp@gmail.com instead of using the issue tracker.

## Credits

Credits go to [musa11971](https://github.com/musa11971) for creating and maintaining the package.  

Special thanks  
- .. to [all contributors](../../contributors) for contributing to the project.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tr>
    <td align="center"><a href="http://musa11971.me"><img src="https://avatars1.githubusercontent.com/u/21341801?v=4" width="100px;" alt=""/><br /><sub><b>Musa</b></sub></a><br /><a href="https://github.com/Kurozora/laravel-cooldown/commits?author=musa11971" title="Code">💻</a> <a href="https://github.com/Kurozora/laravel-cooldown/commits?author=musa11971" title="Documentation">📖</a></td>
  </tr>
</table>

<!-- markdownlint-enable -->
<!-- prettier-ignore-end -->
<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!