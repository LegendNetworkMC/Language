<?php

namespace legendnetwork\language\tag;

use pocketmine\player\Player;
use legendnetwork\language\Language;

/*
 * Helper methods for tags.
 */

final class TagUtils {

	private static string $REGEX = "";

	/**
	 * REGEX singleton
	 * Used to find all the tags ({tax.example}) in a string
	 *
	 * @return string
	 */
	public static function REGEX() : string {
		if (self::$REGEX === "") {
			self::$REGEX = "/(?:" . preg_quote("{") . ")((?:[A-Za-z0-9_\-]{2,})(?:\.[A-Za-z0-9_\-]+)+)(?:" . preg_quote("}") . ")/";
		}

		return self::$REGEX;
	}

	/**
	 * Returns an array of tags found in a string
	 *
	 * @param string $text
	 * @return string[]
	 */
	public static function findTags(string $text) : array {
		$tags = [];
		if (preg_match_all(self::REGEX(), $text, $matches)) {
			$tags = $matches[1];
		}
		return $tags;
	}

	/**
	 * Resolves the tag specified
	 *
	 * @param string $tag
	 * @param Player|null $player
	 * @param Language|null $language
	 * @return Tag
	 */
	public static function resolveTag(string $tag, ?Player $player = null, Language $language = null) : Tag {
		return TagStore::resolve($tag, $player, $language);
	}

	/**
	 * Resolves all the tags specified
	 *
	 * @param array $tags
	 * @param Player|null $player
	 * @param Language|null $language
	 * @return Tag[]
	 */
	public static function resolveAllTags(array $tags, ?Player $player = null, Language $language = null) : array {
		return array_map(function (string $tag) use ($player, $language) {
			return self::resolveTag($tag, $player, $language);
		}, $tags);
	}

	/**
	 * Finds and resolves all the tags in a string
	 *
	 * @param string $text
	 * @param Player|null $player
	 * @param Language|null $language
	 * @return string
	 */
	public static function parseText(string $text, ?Player $player = null, Language $language = null) : string {
		$tags = self::findTags($text);
		$resolvedTags = self::resolveAllTags($tags, $player, $language);
		foreach ($resolvedTags as $resolvedTag) {
			if ($resolvedTag->getName() === $resolvedTag->getValue()) {
				continue;
			}

			$text = str_replace("{" . $resolvedTag->getName() . "}", $resolvedTag->getValue(), $text);
		}
		return $text;
	}

}