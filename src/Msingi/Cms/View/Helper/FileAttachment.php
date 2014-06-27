<?php

namespace Msingi\Cms\View\Helper;

use Msingi\Cms\View\AbstractHelper;

/**
 * Class FileAttachment
 *
 * @package Msingi\Cms\View\Helper
 */
class FileAttachment extends AbstractHelper
{
    /**
     * @param $object
     * @param string $attachment
     * @param string $name
     * @return string
     */
    public function __invoke($object, $attachment, $name)
    {
        /* @var \Msingi\Cms\Service\ContentManager $contentManager */
        $contentManager = $this->serviceLocator->getServiceLocator()->get('Msingi\Cms\ContentManager');

        $file = $contentManager->getFile($object, $attachment, $name);
        if ($file == null) {
            return '';
        }

        return $contentManager->getContentUrl($file);
    }
}
