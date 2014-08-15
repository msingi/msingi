<?php

namespace Msingi\Util;

/**
 * Class ImageFilter
 * @package Msingi\Util
 */
class ImageFilter
{
    /**
     * @param resource $image
     * @param array $size
     * @param string $filter
     * @return resource
     */
    public function filter($image, $size, $filter)
    {
        if (preg_match('/scale\((\d+)x(\d+)\)/', $filter, $matches)) {
            $width = intval($matches[1]);
            $height = intval($matches[2]);
            return $this->scaleImage($image, $size, $width, $height);
        }
        if (preg_match('/crop\((\d+)x(\d+)\)/', $filter, $matches)) {
            $width = intval($matches[1]);
            $height = intval($matches[2]);
            return $this->cropImage($image, $size, $width, $height);
        }
        if (preg_match('/pixelate\((\d+)\)/', $filter, $matches)) {
            $pixel = intval($matches[1]);
            return $this->pixelateImage($image, $size, $pixel);
        }
        // unknown filter
        return $image;
    }

    /**
     * @param resource $image
     * @param array $size
     * @param int $width
     * @param int $height
     * @return resource
     */
    protected function scaleImage($image, $size, $width, $height)
    {
        // @todo implement this function
        throw new Exception('@todo - implement this function');
    }

    /**
     * @param resource $image
     * @param array $size
     * @param int $width
     * @param int $height
     * @return resource
     */
    protected function cropImage($image, $size, $width, $height)
    {
        $x_ratio = $width / $size[0];
        $y_ratio = $height / $size[1];

        $ratiomax = max($x_ratio, $y_ratio);

        $src_left = round(($size[0] - $width / $ratiomax) / 2);
        $src_top = round(($size[1] - $height / $ratiomax) / 2);

        /** @var resource $croppedImage */
        $croppedImage = imagecreatetruecolor($width, $height);

        imagealphablending($croppedImage, false);
        imagesavealpha($croppedImage, true);

        imagecopyresampled($croppedImage, $image, 0, 0, $src_left, $src_top, $width, $height,
            $width / $ratiomax, $height / $ratiomax);

        imagedestroy($image);

        return $croppedImage;
    }

    /**
     * @param resource $image
     * @param array $size
     * @param int $pixel
     * @return resource
     */
    protected function pixelateImage($image, $size, $pixel)
    {
        // pixelate image
        $pixelated_width = $size[0] / $pixel;
        $pixelated_height = $size[1] / $pixel;

        $pixelated = imagecreatetruecolor($pixelated_width, $pixelated_height);

        imagealphablending($pixelated, false);
        imagesavealpha($pixelated, true);

        // resize image to 20 times smaller
        imagecopyresampled($pixelated, $image, 0, 0, 0, 0, $pixelated_width, $pixelated_height, $size[0], $size[1]);

        // resize it back to original size
        imagecopyresampled($image, $pixelated, 0, 0, 0, 0, $size[0], $size[1], $pixelated_width, $pixelated_height);

        imagedestroy($pixelated);

        return $image;
    }
}
