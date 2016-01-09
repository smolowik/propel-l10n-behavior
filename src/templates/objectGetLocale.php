
/**
 * Gets the locale for translations
 *
 * @return    string $locale Locale to use for the translation, e.g. 'de-DE'
 */
public function get<?= $localeColumnName ?>()
{
    return $this->currentLocale;
}
