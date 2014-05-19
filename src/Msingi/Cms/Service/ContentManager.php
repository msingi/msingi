<?php

namespace Msingi\Cms\Service;

use Msingi\Util\ImageResizer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ContentManager
 *
 * @package Msingi\Cms\Service
 */
class ContentManager implements FactoryInterface
{
    protected $config;

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
        // @todo exception?
        $class = get_class($object);
        if (!isset($this->config['attachments'][$class][$attachment]))
            return false;

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
            if ($spec == 'original') {
                //
                $size = getimagesize($image_file);

                // name of resized file
                $resized_file = $storage_dir . '/' . $attachment . '-original.jpg';

                ImageResizer::resize($image_file, $resized_file, $size[0], $size[1], false);

            } else {
                // parse params
                $params = explode(',', $spec);

                // get width and height
                list($width, $height) = explode('x', $params[0]);

                // crop
                //$crop = in_array('crop', $params);
                $filters = array_slice($params, 1);

                // name of resized file
                $resized_file = $storage_dir . '/' . $attachment . '-' . $size . '.jpg';

                ImageResizer::resize($image_file, $resized_file, $width, $height, $filters);
            }
        }

        return true;
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
        $storage_dir = $this->getStorageDir($object, $attachment);

        $resized_file = $storage_dir . '/' . $attachment . '-' . $size . '.jpg';
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