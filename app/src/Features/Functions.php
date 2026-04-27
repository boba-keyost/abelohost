<?php
namespace Features;

class Functions
{
    public static function cutText(string $text, int $len = 150): string
    {
        $cut = $text
                |> strip_tags(...)
                |> (fn ($x) => substr($x, 0, $len));

        return substr($cut, 0, strripos($cut, ' '));
    }
}
