<?php

namespace legendnetwork\language\tag;

/*
 * Represents a tag that can be substituted by a value.
 */
final class Tag {

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct(
			private string $name,
			private mixed $value
	) {
	}

	/**
	 * Get the name of the tag.
	 * @return string
	 */
	public function getName() : string {
		return $this->name;
	}

	/**
	 * Get the value of the tag.
	 * @return mixed
	 */
	public function getValue() : mixed {
		return $this->value;
	}

	public function __toString() : string {
		return "$this->value";
	}

}