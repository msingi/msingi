<?php

namespace Msingi\Cms\Service;

use Msingi\Util\ImageResizer;

class ContentManager
{
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get Root URL for content
     *
     * @param string $file file path
     * @return string Content root URL
     */
    public function getContentUrl($file = '')
    {
        $url = $this->config['root_url'];
        if ($file != '') {
            $url .= '/' . ltrim($file, '/');
        }
        return $url;
    }

    /**
     * Get Storage Directory for content
     *
     * @param string $file file path
     * @return string
     */
    public function getContentDir($file = '')
    {
        $dir = $this->config['store_dir'];
        if ($file != '') {
            $dir .= '/' . ltri($file, '/');
        }
        return $dir;
    }

    /**
     * Attach image
     *
     * @param $object
     * @param string $attachment Attachment name
     * @param array $image
     * @return bool
     */
    public function attachImage($object, $attachment, $image)
    {
        // check if attachement definition exists
        // @todo exception?
        $class = get_class($object);
        if (!isset($this->config['attachments'][$class][$attachment]))
            return false;

        $image_file = $image['tmp_name'];

        $storage = $this->config['attachments'][$class][$attachment];

        // get storage directory
        $storage_dir = $this->config['store_dir'] . '/' . $this->getStorageDir($object, $attachment);

        // create storage directory if not exist
        if (!is_dir($storage_dir)) {
            if (!mkdir($storage_dir, 0775, true))
                return false;
        }

        // store all sizes
        foreach ($storage['sizes'] as $size => $spec) {
            // parse params
            $params = explode(',', $spec);

            // get width and height
            list($width, $height) = explode('x', $params[0]);

            // crop
            $crop = in_array('crop', $params);

            // name of resized file
            $resized_file = $storage_dir . '/' . $attachment . '-' . $size . '.jpg';

            ImageResizer::resize($image_file, $resized_file, $width, $height, $crop);
        }

        return true;
    }

    /**
     * @param $object
     * @param $attachment
     */
    public function deleteImage($object, $attachment)
    {

    }

    /**
     *
     * @param $object
     * @param string $attachment Attachment name
     * @param string $size Image size
     * @return string
     */
    public function getImage($object, $attachment, $size)
    {
        $storage_dir = $this->getStorageDir($object, $attachment);

        $resized_file = $storage_dir . '/' . $attachment . '-' . $size . '.jpg';
        if (!is_file($this->getContentDir() . '/' . $resized_file))
            return null;

        return $resized_file;
    }

    /**
     * Get storage directory for the attachment
     *
     * @param $object
     * @param string $attachment Attachment name
     * @return string
     */
    protected function getStorageDir($object, $attachment)
    {
        // get object's class
        $class = get_class($object);

        // get class related storage settings
        $storage = $this->config['attachments'][$class][$attachment];
        if ($storage == null)
            return '';

        // get storage directory path
        $storage_dir = $storage['dir'];

        // replace tokens
        if (preg_match_all('/\[([a-z0-9_]+)\]/i', $storage_dir, $matches)) {
            foreach ($matches[1] as $match) {
                $value = $object->__get($match);
                $storage_dir = str_replace("[$match]", $value, $storage_dir);
            }
        }

        return $storage_dir;
    }
}