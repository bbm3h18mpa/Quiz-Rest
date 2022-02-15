<?php

class MyDateTimeConverter {
	static function createFromString(string $string) {
		return DateTime::createFromFormat("j-M-y h.i.s.u a", $string);
	}
}