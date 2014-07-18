<?php

namespace Msingi\Cms\Service;

use Msingi\Util\ImageFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ContentManager
 *
 * @package Msingi\Cms\Service
 */
class ContentManager implements FactoryInterface
{
    /** @var array */
    protected $config;

    /** @var int */
    protected $urlCalls = 0;

    /**
     * Get Root URL for content
     *
     * @param string $file file path
     * @return string Content root URL
     */
    public function getContentUrl($file = '')
    {
        $hostnames = $this->config['root_url'];

        if (is_array($hostnames)) {
            $load = isset($this->config['load']) ? $this->config['load'] : 4;

            $index = ($this->urlCalls / $load) % count($hostnames);

            $url = $hostnames[$index];
        } else {
            $url = $hostnames;
        }

        if ($file != '') {
            $url .= '/' . ltrim($file, '/');
        }

        $this->urlCalls += 1;

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
            $dir .= '/' . ltrim($file, '/');
        }
        return $dir;
    }

    /**
     * Attach file
     *
     * @param $object
     * @param string $attachment
     * @param array $file
     */
    public function attachFile($object, $attachment, $file)
    {
        // check if attachement definition exists
        $class = get_class($object);
        if (!isset($this->config['attachments'][$class][$attachment])) {
            throw new \Exception(sprintf(_('Content definition for class %s is not set'), $class));
        }

        // get storage directory
        $storage_dir = $this->config['store_dir'] . '/' . $this->getStorageDir($object, $attachment);

        // create storage directory if not exist
        if (!is_dir($storage_dir)) {
            if (!mkdir($storage_dir, 0775, true))
                return false;
        }

        $source = $file['tmp_name'];
        $dest = $storage_dir . '/' . $file['name'];

        copy($source, $dest);

        return true;
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
        if (!isset($this->config['attachments'][$class][$attachment])) {
            throw new \Exception(sprintf(_('Content definition for class %s is not set'), $class));
        }

        $image_file = $image['tmp_name'];
        $image_size = getimagesize($image_file);
        if ($image_size === false) {
            // not an image
            return false;
        }

        $storage = $this->config['attachments'][$class][$attachment];

        // get storage directory
        $storage_dir = $this->config['store_dir'] . '/' . $this->getStorageDir($object, $attachment);

        // create storage directory if not exist
        if (!is_dir($storage_dir)) {
            if (!mkdir($storage_dir, 0775, true))
                return false;
        }

        /** @var resource $image */
        $image = $this->loadImage($image_file);

        //
        $imageFilter = new ImageFilter();

        // store all sizes
        foreach ($storage['sizes'] as $size => $spec) {
            if ($spec == 'original') {
                // name of resized file
                $resized_file = $storage_dir . '/' . $this->getImageFileName($object->getId() . '-' . $attachment . '-original', 'jpg');
                $this->saveImage($image, $resized_file);
            } else {
                $filtered_image = $this->duplicateImage($image, $image_size);

                $filters = explode(',', $spec);
                foreach ($filters as $filter) {
                    $filtered_image = $imageFilter->filter($filtered_image, $image_size, trim($filter));
                }

                // name of resized file
                $resized_file = $storage_dir . '/' . $this->getImageFileName($object->getId() . '-' . $attachment . '-' . $size, 'jpg');

                $this->saveImage($filtered_image, $resized_file);

                imagedestroy($filtered_image);
            }
        }

        imagedestroy($image);

        return true;
    }

    /**
     * @param string $name
     * @param string $extension
     * @return string
     */
    protected function getImageFileName($name, $extension)
    {
        $filename = base_convert(crc32($name), 10, 26);

        return $filename . '.' . $extension;
    }

    /**
     *
     * @param string $imageFile
     * @return bool|resource
     */
    protected function loadImage($imageFile)
    {
        $size = getimagesize($imageFile);

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = "imagecreatefrom" . $format;
        if (!function_exists($icfunc)) {
            return false;
        }

        return $icfunc($imageFile);
    }

    /**
     * @param resource $image
     * @param array $size
     * @return resource
     */
    protected function duplicateImage($image, $size)
    {
        $idst = imagecreatetruecolor($size[0], $size[1]);

        imagecopy($idst, $image, 0, 0, 0, 0, $size[0], $size[1]);

        return $idst;
    }

    /**
     * @param $image
     * @param $imageFile
     * @param int $quality
     */
    protected function saveImage($image, $imageFile, $quality = 80)
    {
        //
        imageinterlace($image, true);

        // create destination image
        imagejpeg($image, $imageFile, $quality);

        // set access rights
        chmod($imageFile, 0664);
    }

    /**
     * @param $object
     * @param $attachment
     */
    public function deleteFile($object, $attachment)
    {

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
     * @param string $name
     * @return string
     */
    public function getFile($object, $attachment, $name)
    {
        if ($object == null) {
            return null;
        }

        $storage_dir = $this->getStorageDir($object, $attachment);

        $file = $storage_dir . '/' . $name;

        //echo $resized_file; die;
        if (!is_file($this->getContentDir() . '/' . $file))
            return null;

        return $file;
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
        if ($object == null) {
            return null;
        }

        $storage_dir = $this->getStorageDir($object, $attachment);

        $resized_file = $storage_dir . '/' . $this->getImageFileName($object->getId() . '-' . $attachment . '-' . $size, 'jpg');
        //echo $resized_file; die;
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
        if ($object instanceof \Doctrine\ORM\Proxy\Proxy) {
            $class = get_parent_class($object);
        } else {
            $class = get_class($object);
        }

        // get class related storage settings
        if (!isset($this->config['attachments'][$class]) || !isset($this->config['attachments'][$class][$attachment]))
            return '';

        $storage = $this->config['attachments'][$class][$attachment];
        if ($storage == null)
            return '';

        // get storage directory path
        $storage_dir = $storage['dir'];

        // replace tokens
        if (preg_match_all('/\[([a-z0-9_]+)\]/i', $storage_dir, $matches)) {
            foreach ($matches[1] as $match) {

                $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $match)));

                $value = $object->$method();

                //$value = $object->__get($match);
                $storage_dir = str_replace("[$match]", $value, $storage_dir);
            }
        }

        return $storage_dir;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $service_config = $config['content'];

        $content_manager = new ContentManager();

        $content_manager->setConfig($service_config);

        return $content_manager;
    }

    /**
     * @param $service_config
     */
    protected function setConfig($service_config)
    {
        $this->config = $service_config;
    }
}
