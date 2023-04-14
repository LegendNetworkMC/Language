<?php

namespace legendnetwork\language;

use RuntimeException;

/*
 * Represents a languages' information.
 */
class LanguageMeta {

	/**
	 * Attempts to create language meta from JSON.
	 *
	 * @param array $languageJson
	 * @return LanguageMeta
	 * @throws RuntimeException if the JSON is not valid.
	 */
	public static function fromJson(array $languageJson) : LanguageMeta {
		if (!isset($languageJson['meta']) || !isset($languageJson['meta']['name']) || !isset($languageJson['meta']['shortName']) || !isset($languageJson['meta']['version'])) {
			throw new RuntimeException('Language meta not found or is not valid');
		}

		return new LanguageMeta($languageJson['meta']['name'], $languageJson['meta']['shortName'], $languageJson['meta']['version']);
	}

	/**
	 * @see LanguageMeta::fromJson()
	 */
	private function __construct(
			private string $name,
			private string $shortName,
			private int $version
	) {
	}

	/**
	 * Gets the language full name.
	 * @return string
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * Gets the language short name.
	 * @return string
	 */
	public function getShortName() : string {
		return $this->shortName;
	}

	/**
	 * Gets the language version.
	 * @return int
	 */
	public function getVersion() : int {
		return $this->version;
	}

}