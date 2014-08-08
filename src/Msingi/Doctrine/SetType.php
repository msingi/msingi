<?php

namespace Msingi\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Class SetType
 *
 * @package Msingi\Doctrine
 */
abstract class SetType extends Type
{
    protected $name;
    protected $values = array();

    /**
     * @param array $fieldDeclaration
     * @param AbstractPlatform $platform
     * @return string
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $values = array_map(function ($val) {
            return "'" . $val . "'";
        }, $this->values);

        return "SET(" . implode(", ", $values) . ") COMMENT '(DC2Type:" . $this->name . ")'";
    }

    /**
     * @param string $value
     * @param AbstractPlatform $platform
     * @return array
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return explode(',', $value);
    }

    /**
     * @param array $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        foreach ($value as $item) {
            if (!in_array($item, $this->values)) {
                throw new \InvalidArgumentException(sprintf('Invalid "%s" value "%s"', $this->name, $item));
            }
        }

        return implode(',', $value);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
