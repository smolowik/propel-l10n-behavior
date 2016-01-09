Propel l10n Behavior
====================

This behavior let's you globally define your locales with its dependencies and retrieve the most recent locale for your accessor methods, without further interaction about setting the new locale on a model. It is based on propels own i18n behavior.

## Installation


## Locale and Dependencies

Whenever you retrieve a localized field, the behavior will use the following algorithm to find the contents for the field in the most recent locale:

1. Set default locale as locale.
2. Try to get the field in the set locale
3. If empty, check if the locale has a dependency and if yes, set dependency as new locale, continue with step 2
4. If no dependecy is set for that locale, try to use the primary language, e.g. locale: 'de-DE' -> primary language: 'de'. If locale and primary language are unequal, set the primary language as locale, continue with step 2
4. If primary language is empty, use fallback as new locale and continue with step 2
5. Last step: giving up, return null

### Set Default locale

Default locale is set to `en` but of course you can change this:

```php
PropelL10n::setLocale('de-DE');

echo PropelL10n::getLocale(); // de-DE
```

### Set Fallback locale

Fallback locale also is defaulted to `en`, yet you can change that:

```php
PropelL10n::setFallback('de-DE');

echo PropelL10n::getFallback(); // de-DE
```

### Working with Dependencies

There is some API to work with dependencies:

```php
// get and set one dependency
PropelL10n::addDependency('de-CH', 'de-DE');
echo PropelL10n::getDependency('de-CH'); // de-DE

// get and set all dependencies
PropelL10n::setDependencies([
	'de-CH' => 'de-DE',
	'de-AT' => 'de-DE',
	'de-DE' => 'en-US',
	'en-US' => 'en'
]);
print_r(PropelL10n::getDependencies()); // outputs the array from above

// check if dependencies exist
PropelL10n::hasDependency('de-DE'); // true
PropelL10n::hasDepdedency('ja-JP'); // false

// count depdencies for a given locale
PropelL10n::setDependencies(['de-DE' => 'en-US', 'de-CH' => 'de-DE']);
PropelL10n::countDependencies('de-CH'); // 2
```

## Usage

There is three things you need to do once for your app and you are ready to go and use propel as if they weren't any l10n/i18n behaviors used at all.

1. Set the default locale
2. Set a fallback locale
3. Set your dependencies

Example:

```php
PropelL10n::setLocale('de-CH'); // this is mostly the language a user decided to use
PropelL10n::setFallback('en'); // that's the default language of your app
PropelL10n::setDepdencies([ // that's the locales you have available
	'de-CH' => 'de-DE',
	'de-AT' => 'de-DE',
	'de-DE' => 'en-US',
	'ja-JP' => 'en-US',
	'en-US' => 'en'
]);
```

And you are ready to go.

## Performance

I'm pretty sure this is a performance nightmare. Only propel API methods are used, so no manual queries so far. Performance optimization can begin after propel will merge the `data-mapper` branch into `master`. Suggestions are welcome, please post the to the issue tracker.


