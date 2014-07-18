<?php

namespace Msingi\Cms\View\Helper;

use Msingi\Cms\View\AbstractHelper;

/**
 * Class ImageAttachment
 *
 * @package Msingi\Cms\View\Helper
 */
class ImageAttachment extends AbstractHelper
{
    /**
     * @param $object
     * @param $attachment
     * @param $size
     * @param $params
     * @return string
     */
    public function __invoke($object, $attachment, $size, $params = array())
    {
        if ($object == null) {
            return '';
        }

        /* @var \Msingi\Cms\Service\ContentManager $contentManager */
        $contentManager = $this->serviceLocator->getServiceLocator()->get('Msingi\Cms\ContentManager');

        $image = $contentManager->getImage($object, $attachment, $size);
        if ($image == null) {
            return isset($params['placeholder']) ? $params['placeholder'] : '';
        }

        return $contentManager->getContentUrl($image);
    }
}
