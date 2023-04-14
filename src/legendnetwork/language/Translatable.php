<?php

namespace legendnetwork\language;

use pocketmine\utils\TextFormat;
use legendnetwork\language\tag\TagUtils;

/*
 * Represents text that can be translated.
 */
class Translatable {

	/**
	 * @param Language $language
	 * @param string $translation
	 * @param string|null $defaultTranslation
	 * @param string[] $parameters
	 */
	public function __construct(
			private Language $language,
			private string $translation,
			private ?string $defaultTranslation,
			private array $parameters = []
	) {
	}

	/**
	 * Gets the language this will translate to.
	 * @return Language
	 */
	public function getLanguage() : Language {
		return $this->language;
	}

	/**
	 * Sets the language this will translate to.
	 * @param Language $language
	 */
	public function setLanguage(Language $language) : void {
		$this->language = $language;
	}

	/**
	 * Gets the translation key.
	 * @return string
	 */
	public function getTranslation() : string {
		return $this->translation;
	}

	/**
	 * Sets the translation key.
	 * @param string $translation
	 */
	public function setTranslation(string $translation) : void {
		$this->translation = $translation;
	}

	/**
	 * Gets the default translation.
	 * @return string|null
	 */
	public function getDefaultTranslation() : ?string {
		return $this->defaultTranslation;
	}

	/**
	 * Sets the default translation.
	 * @param string|null $defaultTranslation
	 */
	public function setDefaultTranslation(?string $defaultTranslation) : void {
		$this->defaultTranslation = $defaultTranslation;
	}

	/**
	 * Gets the parameters that will be used during translation.
	 * @return string[]
	 */
	public function getParameters() : array {
		return $this->parameters;
	}

	/**
	 * Sets the parameters that will be used during translation.
	 * @param string[] $parameters
	 */
	public function setParameters(array $parameters) : void {
		$this->parameters = $parameters;
	}

	/**
	 * Translates the text based off the language and parameters.
	 *
	 * @param bool $colorize
	 * @param string $colorCode
	 * @return string
	 */
	public function translate(bool $colorize = true, string $colorCode = '&') : string {
		$translation = $this->processParameters($this->language->getTranslation($this->translation, $this->defaultTranslation));
		return $colorize ? TextFormat::colorize($translation, $colorCode) : $translation;
	}

	/**
	 * Processes the tags in the translated text and replaces them using parameters.
	 *
	 * @param string $translation
	 * @return string
	 */
	private function processParameters(string $translation) : string {
		$translation = TagUtils::parseText($translation);
		foreach ($this->parameters as $key => $value) {
			$translation = str_replace("{" . $key . "}", $value, $translation);
		}

		return $translation;
	}

	/**
	 * @return string
	 * @see Translatable::translate()
	 */
	public function __toString() : string {
		return $this->translate();
	}

}