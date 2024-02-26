Travels - Tours
===============

This is the deliverable of an assessment project by [Theofanis Vardatsikos](https://www.linkedin.com/in/theofanis-vardatsikos/) ([vardtheo@gmail.com](mailto:vardtheo@gmail.com)).

The initial request of the test is attached at the end.

## Installation

The system is built with PHP 8.1 & Laravel 10.
For docker setup [Laravel Sail](https://laravel.com/docs/10.x/sail) is used, you must have Docker installed already.

To download project and install required dependencies and start Sail (Docker) run:

```bash
git clone https://github.com/theofanisv/travels-assessment
cd travels-assessment

docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    
./vendor/bin/sail up -d

# Log into container's bash
./vendor/bin/sail bash

cp -n .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```

> Extra commands have been added to `Dockerfile` to install additional dependencies for image manipulation.

Optionally generate IDE helper files.

```bash
php artisan ide-helper:generate
php artisan ide-helper:eloquent
php artisan ide-helper:meta
php artisan ide-helper:models -N
```


## Testing

Tests written with [Pest](https://pestphp.com). **Coverage 88%**

```bash
# either from host
./vendor/bin/sail test

# or from inside container's bash
php artisan test
php artisan test --coverage
```

[Larastan](https://github.com/larastan/larastan) is used for static analysis. **Hint: No errors!**

```bash
./vendor/bin/phpstan analyse
```

## Notes


> [!IMPORTANT]
> Postman documentation published [here](https://documenter.getpostman.com/view/1408338/2sA2rCV2ak)!
> Create tokens for each role via:
> 
> ```bash
> php artisan user:create-token admin@example.com
> php artisan user:create-token editor@example.com
> ``` 

> [!IMPORTANT]
>
> 1. I am using UUIDs for primary keys.
  Primary key on users, personal access tokens and media (photos) have also changed to UUID.
  So the corresponding migrations/models have been updated/overridden.
>
> 2. I assume a user can have only one role (belongTo) instead of multiple (pivot).
  `roleId` is nullable and in case of null it means the user is an ordinary customer.
  For the scope of this assessment roles are predefined in enum `\App\Enums\Role` and used by `App\Models\Role`.
>
> 3. In `.editorconfig` I have exported the options I use via PhpStorm.
  However, these parameters are recognized by other products of IntelliJ only, so not VScode.
  I have used Laravel Pint in the past, but I do not like the format it produces.
>
> 4. For the simplicity of the project travel moods are an embedded json to the model.
  The list of the moods is hardcoded at `App\Enums\Mood`.
  Another implementation for more performant queries would be to create a `Mood` model (without the enum) and bind it with `Travel` via pivot table.
  The pivot table would also have a `score` column.
>
> 5. Route parameter `travel` binds to either `id` or `slug` via `\App\Models\Travel::resolveRouteBindingQuery`.
>
> 7. Since DB columns are in `camelCase` I also use the variables in camel case, because I suppose this is your general guideline.
>


> [!Note]
>
> [spatie/laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary/v11) is used for image manipulation.
> Images are stored in `public/media`.
>
> After scanning your site I see you use a collection for
>   - thumbnail (cover) with variants `mobile`, `desktop` (300x300).
>   - photos with variants `small`, `medium`, `large`, `xlarge`
>
> For the simplicity of the assessment `Travel` can have only one `thumbnail` and multiple `photos` and for each image
> two variants are generated (mobile & desktop) using `sync` queue instead of Horizon.


-------------------------------------------------------


-------------------------------------------------------


# Assessment Test

Create a Laravel APIs application similar to our structure. They will have both public and private endpoints with roles as well.

## Glossary

- **User** will have an email and a password, used for testing.
- **Travel** is the basic, fundamental unit: it contains all the necessary information, like the number of days, the images, title, what's included and everything about its *appearance*. An example is `Jordan 360°` or `Iceland: hunting for the Northern Lights`;
- **Tour** is a specific dates-range of a travel with its own price and details. `Jordan 360°` may have a *tour* from 20 to 27 January at €899, another one from 10 to 15 March at €1099 etc. At the end you will book a *tour*, not a *travel*.
- **Role** can be one of `admin` or `editor`.

## Goals

At the end, the project should have:

1. A private (admin) endpoint to create new travels;
2. A private (admin) endpoint to create new tours for a travel;
3. A private (editor) endpoint to update a travel;
4. A public (no auth) endpoint to get a list of paginated tours by the travel `slug` (e.g. all the tours of the travel `foo-bar`). Users can filter (search) the results by `priceFrom`, `priceTo`, `dateFrom` (from that `startingDate`) and `dateTo` (until that `startingDate`). User can sort the list by `price` asc and desc. They will **always** be sorted, after every additional user-provided filter, by `startingDate` asc.

## Models

**Travel** has a flag to determine if it's public, a slug, a name, a description, the number of days, the number of nights (computed by `numberOfDays - 1`) and a set of moods (see the samples to learn more).

**Tour** has the relationship with its travel, a name, a starting date, an ending date and the price (see below details).

### Notes

- Feel free to use the native Laravel authentication; don't reinvent the wheel!
- We use UUIDs as primary keys instead of incremental IDs, but it's not required for you to use them, although highly appreciated;
- Our tables are in `snake_case`, but their columns are in `camelCase`.
- **Tours prices** are integer multiplied by 100: for example, €999 euro will be `99900`, but, when returned to Frontends, they will be formatted (`99900 / 100`);
- **Tours names** inside the `samples` are a kind-of what we use internally, but you can use whatever you want;
- In the `samples` folder you can find JSON files containing fake data to get started with;
- Unit/Integration/Feature tests are required to evaluate the Business Case;
- Usage of linter and static analysis tools are a **really appreciated**;
- Creating docs is **big plus**, even a README is fine;
- Users needed for the tests should be created with a seeder or whatever you feel more comfortable with;
- Code should be uploaded in a GitHub repository.

Feel free to add to the project whatever you want! 
