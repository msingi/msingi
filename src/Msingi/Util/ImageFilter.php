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
        for($y = 0;$y < $size[0];$y += $pixel+1)
        {
            for($x = 0;$x < $size[1];$x += $pixel+1)
            {
                // get the color for current pixel
                $rgb = imagecolorsforindex($image, imagecolorat($image, $x, $y));

                // get the closest color from palette
                $color = imagecolorclosest($image, $rgb['red'], $rgb['green'], $rgb['blue']);
                imagefilledrectangle($image, $x, $y, $x+$pixel, $y+$pixel, $color);
            }
        }
        return $image;
    }
}
