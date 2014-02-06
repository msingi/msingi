<?php

namespace Msingi\Util;

class ImageResizer
{
    /**
     * @param $sourceImage
     * @param $targetImage
     * @param $width
     * @param $height
     * @param bool $crop
     * @return bool
     */
    public static function resize($sourceImage, $targetImage, $width, $height, $crop = false)
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

                $new_width = floor($size[0] * $ratio);
                $new_height = floor($size[1] * $ratio);

                $idest = imagecreatetruecolor($new_width, $new_height);

                imagecopyresampled($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
            }
        } else {
            $new_width = $size[0];
            $new_height = $size[1];

            $idest = imagecreatetruecolor($new_width, $new_height);

            imagecopy($idest, $isrc, 0, 0, 0, 0, $new_width, $new_height);
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