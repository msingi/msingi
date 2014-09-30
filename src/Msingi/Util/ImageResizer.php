<?php

namespace Msingi\Util;

/**
 * Class ImageResizer
 *
 * @todo use ImageFilter
 *
 * @package Msingi\Util
 */
class ImageResizer
{
    /**
     * Resize image and apply filters
     *
     * @param string $sourceImage path to source image file
     * @param string $targetImage path to target image file
     * @param int $width width of target image
     * @param int $height height of target image
     * @param array $filters Array of filters to apply
     * @return bool
     */
    public static function resize($sourceImage, $targetImage, $width, $height, $filters = array())
    {
        if (!ImageResizer::isImage($sourceImage))
            return false;

        //
        $size = getimagesize($sourceImage);

        //
        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) {
            return false;
        }

        $isrc = $icfunc($sourceImage);

        if ($width == null && $height == null) {
            return false;
        } else if ($width != null && $height == null) {
            $x_ratio = $width / $size[0];
            $y_ratio = $x_ratio;
            $height = $height * $y_ratio;
        } else if ($width == null && $height != null) {
            $y_ratio = $height / $size[1];
            $x_ratio = $y_ratio;
            $width = $height * $x_ratio;
        } else if ($width != null && $height != null) {
            $x_ratio = $width / $size[0];
            $y_ratio = $height / $size[1];
        }

        $crop = in_array('crop', $filters);

        //
        if ($x_ratio < 1.0 || $y_ratio < 1.0) {
            if ($crop) {
                $ratiomax = max($x_ratio, $y_ratio);

                $src_left = floor(($size[0] - $width / $ratiomax) / 2);
                $src_top = floor(($size[1] - $height / $ratiomax) / 2);

                $idest = imagecreatetruecolor($width, $height);

                imagecopyresampled($idest, $isrc, 0, 0, $src_left, $src_top, $width, $height, $width / $ratiomax, $height / $ratiomax);
            } else {
                $ratio = min($x_ratio, $y_ratio);

                $width = floor($size[0] * $ratio);
                $height = floor($size[1] * $ratio);

                $idest = imagecreatetruecolor($width, $height);

                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
            }
        } else {
            $width = $size[0];
            $height = $size[1];

            $idest = imagecreatetruecolor($width, $height);

            imagecopy($idest, $isrc, 0, 0, 0, 0, $width, $height);
        }

        $pixelate = in_array('pixelate', $filters);
        if ($pixelate) {
            // pixelate image
            $pixelated_width = $width / 20;
            $pixelated_height = $height / 20;
            $pixelated = imagecreatetruecolor($pixelated_width, $pixelated_height);

            // resize image to 20 times smaller
            imagecopyresampled($pixelated, $idest, 0, 0, 0, 0, $pixelated_width, $pixelated_height, $width, $height);

            // recreate idest to get pixelated image
            imagedestroy($idest);
            $idest = imagecreatetruecolor($width, $height);

            // resize it back to original size
            imagecopyresampled($idest, $pixelated, 0, 0, 0, 0, $width, $height, $pixelated_width, $pixelated_height);

            imagedestroy($pixelated);
        }

        //
        imageinterlace($idest, true);

        // create destination image
        imagejpeg($idest, $targetImage, 80);

        // set access rights
        chmod($targetImage, 0664);

        // clean up
        imagedestroy($isrc);
        imagedestroy($idest);

        return true;
    }

    /**
     * @param $file
     * @return bool
     */
    protected static function isImage($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        $size = getimagesize($file);
        if ($size === false) {
            return false;
        }

        return true;
    }

}
