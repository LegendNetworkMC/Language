<?php

namespace legendnetwork\language;

use RuntimeException;

/*
 * Represents a real-world language.
 */
final class Language {

	/** @var self[] */
	private static array $registeredLanguages = [];

	private LanguageMeta $meta;
	/** @var string[] */
	private array $translations;

	/**
	 * Checks if a language has been registered.
	 *
	 * @param string $shortName
	 * @return bool
	 */
	public static function isRegistered(string $shortName) : bool {
		return isset(self::$registeredLanguages[$shortName]);
	}

	/**
	 * Registers a new language.
	 *
	 * @param Language $language
	 * @throws RuntimeException if the language is already registered.
	 */
	public static function register(self $language) : void {
		$shortName = $language->getMeta()->getShortName();
		if (self::isRegistered($shortName))
			throw new RuntimeException("Language '$shortName' already exists.");
		self::$registeredLanguages[$shortName] = $language;
	}

	/**
	 * Registers a list of languages.
	 *
	 * @param Language ...$languages
	 * @throws RuntimeException ({@link Language::register()})
	 */
	public static function registerAll(self...$languages) : void {
		foreach ($languages as $language) {
			self::register($language);
		}
	}

	/**
	 * Unregisters an existing language.
	 *
	 * @param string $shortName
	 * @throws RuntimeException if the language is not already registered.
	 */
	public static function unregister(string $shortName) : void {
		if (!self::isRegistered($shortName))
			throw new RuntimeException("Language '$shortName' does not exist.");
		unset(self::$registeredLanguages[$shortName]);
	}

	/**
	 * Gets an existing language.
	 *
	 * @param string $shortName
	 * @return Language
	 * @throws RuntimeException if the language is not registered.
	 */
	public static function get(string $shortName) : self {
		if (!self::isRegistered($shortName))
			throw new RuntimeException("Language $shortName is not registered");
		return self::$registeredLanguages[$shortName];
	}

	/**
	 * Attempts to load a language from JSON.
	 *
	 * @param string $jsonString
	 * @return Language
	 * @throws RuntimeException if the language file is not valid.
	 */
	public static function loadLanguage(string $jsonString) : self {
		$json = json_decode($jsonString, true);
		if (!isset($json["translations"]))
			throw new RuntimeException("Language file is not valid");

		return new Language(LanguageMeta::fromJson($json), $json['translations']);
	}

	/**
	 * @see Language::loadLanguage()
	 */
	private function __construct(LanguageMeta $meta, array $translations) {
		$this->meta = $meta;
		$this->translations = $translations;
	}

	/**
	 * @return LanguageMeta
	 */
	public function getMeta() : LanguageMeta {
		return $this->meta;
	}

	/**
	 * Gets the translation for the given key or the key if it doesn't exist.
	 *
	 * @param string $key
	 * @param string|null $default
	 * @return string
	 */
	public function getTranslation(string $key, ?string $default = null) : string {
		return $this->translations[$key] ?? ($default ?? $key);
	}

	/**
	 * @return string[]
	 */
	public function getTranslations() : array {
		return $this->translations;
	}

}