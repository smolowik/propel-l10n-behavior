<?php
namespace gossi\propel\behavior\l10n;

class PropelL10n {
	
	private static $dependencies = [];
	
	private static $locale = 'en';
	
	private static $fallback = 'en';
	
	public static function normalize($locale) {
		return str_replace('_', '-', \Locale::composeLocale(\Locale::parseLocale($locale)));
	}
	
	/**
	 * Sets the fallback locale
	 * 
	 * @param string $locale
	 */
	public static function setFallback($locale) {
		self::$fallback = PropelL10n::normalize($locale);
	}

	/**
	 * Returns the fallback locale
	 * 
	 * @return string
	 */
	public static function getFallback() {
		return self::$fallback;
	}
	
	/**
	 * Sets the current locale
	 * 
	 * @param string $locale
	 */
	public static function setLocale($locale) {
		self::$locale = PropelL10n::normalize($locale);
	}
	
	/**
	 * Gets the current locale
	 * 
	 * @return string
	 */
	public static function getLocale() {
		return self::$locale;
	}
	
	/**
	 * Adds a dependency
	 * 
	 * @param string $locale the new locale which has a dependency
	 * @param string $dependsOn the locale on which it depends on
	 */
	public static function addDependency($locale, $dependsOn) {
		$locale = self::normalize($locale);
		$dependsOn = self::normalize($dependsOn);
		self::$dependencies[$locale] = $dependsOn;
	}
	
	/**
	 * Removes a dependeny
	 * 
	 * @param string $locale
	 */
	public static function removeDependency($locale) {
		unset(self::$dependencies[PropelL10n::normalize($locale)]);
	}
	
	/**
	 * Sets multiple dependencies at once. The array must be in the following format:
	 * 
	 * ['de-DE' => 'en-US', 'de-CH' => 'de-DE']
	 * 
	 * which means de-DE depends on en-US and de-CH depends on de-DE.
	 * 
	 * @param array $dependencies
	 */
	public static function setDependencies($dependencies) {
		self::$dependencies = [];
		foreach ($dependencies as $k => $v) {
			self::addDependency($k, $v);
		}
	}
	
	/**
	 * Returns the current dependencies
	 * 
	 * @return array
	 */
	public static function getDependencies() {
		return self::$dependencies;
	}
	
	/**
	 * Returns the dependency for the given locale or null if locale doesn't exist
	 * 
	 * @param string $locale
	 * @return string|null
	 */
	public static function getDependency($locale) {
		$locale = PropelL10n::normalize($locale);
		if (self::hasDependency($locale)) {
			return self::$dependencies[$locale];
		}
		
		return null;
	}

	/**
	 * Checks wether there is a dependency registered for the given locale
	 * 
	 * @param string $locale
	 * @return boolean
	 */
	public static function hasDependency($locale) {
		$locale = PropelL10n::normalize($locale);
		return isset(self::$dependencies[$locale]);
	}
	
	/**
	 * Counts the dependencies a locale may have.
	 *
	 * E.g. given these dependencies: 
	 * ['de-DE' => 'en-US', 'de-CH' => 'de-DE']
	 * 
	 * Then de-CH has 2 dependencies in total.
	 * 
	 * @param string $locale
	 * @return number
	 */
	public static function countDependencies($locale) {
		$locale = PropelL10n::normalize($locale);
		$count = 0;
		
		while (isset(self::$dependencies[$locale])) {
			$locale = self::$dependencies[$locale];
			$count++;
		}
		
		return $count;
	}
	
}
