<?php

namespace App\Support;

/**
 * Generates simple local placeholder images for demo products.
 *
 * The original code pointed missing product images at placeholdit.imgix.net,
 * which no longer resolves. These are drawn offline with GD instead, so a fresh
 * install has working catalog images with no network access.
 */
class PlaceholderImage
{
    /** Background palette, picked deterministically from the label. */
    private static $palette = [
        [0x2E, 0x7D, 0x32], [0xC6, 0x28, 0x28], [0x15, 0x65, 0xC0],
        [0xEF, 0x6C, 0x00], [0x6A, 0x1B, 0x9A], [0x00, 0x83, 0x8F],
        [0x55, 0x6B, 0x2F], [0xAD, 0x14, 0x57],
    ];

    /**
     * Draw a square placeholder for $label at $path, unless it already exists.
     *
     * @return bool true if a file was written
     */
    public static function make($path, $label, $size = 400)
    {
        if (file_exists($path)) {
            return false;
        }

        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $img = imagecreatetruecolor($size, $size);
        $rgb = self::$palette[abs(crc32($label)) % count(self::$palette)];

        $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($img, 0, 0, $size, $size, $bg);

        // Lighter band behind the text so it stays readable on every colour.
        $band = imagecolorallocatealpha($img, 255, 255, 255, 105);
        imagefilledrectangle($img, 0, (int) ($size * 0.38), $size, (int) ($size * 0.62), $band);

        $white = imagecolorallocate($img, 255, 255, 255);
        $font = __DIR__ . '/../../public/assets/font/roboto/Roboto-Bold.ttf';

        if (is_readable($font)) {
            self::drawWrapped($img, $label, $font, $size, $white);
        } else {
            imagestring($img, 5, 10, (int) ($size / 2), $label, $white);
        }

        imagepng($img, $path);
        imagedestroy($img);

        return true;
    }

    /** Centre the label, wrapping onto at most three lines. */
    private static function drawWrapped($img, $label, $font, $size, $color)
    {
        $fontSize = 22;
        $lines = explode("\n", wordwrap($label, 16, "\n", true));
        $lines = array_slice($lines, 0, 3);
        $lineHeight = $fontSize + 10;
        $top = ($size / 2) - ((count($lines) - 1) * $lineHeight / 2);

        foreach ($lines as $i => $line) {
            $box = imagettfbbox($fontSize, 0, $font, $line);
            $x = ($size - ($box[2] - $box[0])) / 2;
            imagettftext($img, $fontSize, 0, (int) $x, (int) ($top + $i * $lineHeight), $color, $font, $line);
        }
    }
}
