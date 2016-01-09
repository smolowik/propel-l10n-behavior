
/**
 * Gets the locale for translations.
 * Alias for getLocale(), for BC purpose.
 *
 * @return    string $locale Locale to use for the translation, e.g. 'de-DE'
 */
public function get<?php echo $alias ?>()
{
    return $this->get<?= $localeColumnName ?>();
}
