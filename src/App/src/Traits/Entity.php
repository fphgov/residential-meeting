<?php

declare(strict_types=1);

namespace App\Traits;

trait Entity
{
    public function setDefaults()
    {
        foreach (get_class_vars(self::class) as $dk => $dv) {
            $hasDefault = defined(self::class . '::DISABLE_DEFAULT_SET');

            if ($hasDefault && in_array($dk, self::DISABLE_DEFAULT_SET)) {
                continue;
            }

            $this->{'set' . $this->normalizeProperty($dk)}($dv);
        }

        return $this;
    }

    public function setProps($datas)
    {
        foreach ($datas as $dk => $dv) {
            if (!property_exists(self::class, $dk)) {
                continue;
            }

            $this->{'set' . $this->normalizeProperty($dk)}($dv);
        }
    }

    public function getProp($prop)
    {
        if (property_exists($this, $prop)) {
          return $this->{'get' . $this->normalizeProperty($prop)}();
        }
    }

    public function getProps()
    {
        $props = [];

        foreach (get_class_vars(self::class) as $dk => $dv) {
            $hasDefault = defined(self::class . '::DISABLE_SHOW_DEFAULT');

            if ($hasDefault && in_array($dk, self::DISABLE_SHOW_DEFAULT)) {
                continue;
            }

            $props[$dk] = $this->{'get' . $this->normalizeProperty($dk)}();
        }

        return $props;
    }

    private function normalizeProperty($name)
    {
        $s = explode('_', $name);
        foreach ($s as $key => $value) {
            $s[$key] = ucfirst($value);
        }
        return implode('', $s);
    }

    public function jsonSerialize()
    {
        return $this->getProps();
    }

    public function toArray()
    {
        return (array)json_decode(json_encode($this->jsonSerialize()), true);
    }

    public function getClassName()
    {
        if ($pos = strrpos(self::class, '\\')) {
            return substr(self::class, $pos + 1);
        }

        return $pos;
    }
}
