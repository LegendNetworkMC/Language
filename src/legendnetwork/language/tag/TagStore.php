<?php

namespace legendnetwork\language\tag;

use Closure;
use pocketmine\player\Player;
use RuntimeException;
use legendnetwork\language\Language;

/*
 * Registry for known tags.
 * Provides a closure to resolve the value for a specific tag at a given moment.
 */
final class TagStore {

	/** @var Closure[] (Player, Language) */
	private static array $tags = [];

	/**
	 * Check if a tag has been registered
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isRegistered(string $name) : bool {
		return isset(self::$tags[$name]);
	}

	/**
	 * Register a tag
	 *
	 * @param string $name
	 * @param Closure $closure
	 * @throws RuntimeException if the tag is already registered
	 */
	public static function register(string $name, Closure $closure) : void {
		if (self::isRegistered($name)) {
			throw new RuntimeException("Tag $name is already registered");
		}
		self::$tags[$name] = $closure;
	}

	/**
	 * Unregister a tag
	 *
	 * @param string $name
	 * @throws RuntimeException if the tag is not registered
	 */
	public static function unregister(string $name) : void {
		if (!self::isRegistered($name)) {
			throw new RuntimeException("Tag $name is not registered");
		}
		unset(self::$tags[$name]);
	}

	/**
	 * Resolve a tag
	 *
	 * @param string $name
	 * @param Player|null $player
	 * @param Language|null $language
	 * @return Tag
	 */
	public static function resolve(string $name, ?Player $player = null, ?Language $language = null) : Tag {
		if (!self::isRegistered($name)) {
			return new Tag($name, $name);
		}

		return new Tag($name, (self::$tags[$name])($player, $language));
	}

}