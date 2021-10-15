<?php

declare(strict_types=1);

namespace App\Traits;

use Ramsey\Uuid\Lazy\LazyUuidFromString;

use function defined;
use function explode;
use function get_class_vars;
use function implode;
use function in_array;
use function is_array;
use function is_object;
use function json_decode;
use function json_encode;
use function property_exists;
use function strrpos;
use function substr;
use function ucfirst;

trait EntityTrait
{
    public function setDefaults(): self
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

    public function setProps(array $datas): void
    {
        foreach ($datas as $dk => $dv) {
            if (! property_exists(self::class, $dk)) {
                continue;
            }

            $this->{'set' . $this->normalizeProperty($dk)}($dv);
        }
    }

    /** @return mixed */
    public function getProp(string $prop)
    {
        if (property_exists($this, $prop)) {
            return $this->{'get' . $this->normalizeProperty($prop)}();
        }
    }

    public function getProps(int $depthLvl = 2): array
    {
        $props = [];

        foreach (get_class_vars(self::class) as $dk => $dv) {
            $hasDefault = defined(self::class . '::DISABLE_SHOW_DEFAULT');

            if ($hasDefault && in_array($dk, self::DISABLE_SHOW_DEFAULT)) {
                continue;
            }

            $prop = $this->{'get' . $this->normalizeProperty($dk)}();

            if (is_array($prop) && $depthLvl > 0) {
                $depthLvl--;

                $props[$dk] = $prop;

                if (is_array($props[$dk])) {
                    foreach ($props[$dk] as $pk => $p) {
                        if ($p instanceof LazyUuidFromString) {
                            $props[$dk][$pk] = $p->serialize();
                        } else if (is_object($p)) {
                            $props[$dk][$pk] = $p->getProps($depthLvl);
                        }
                    }
                }
            } else {
                $props[$dk] = $prop;
            }
        }

        return $props;
    }

    private function normalizeProperty(string $name): string
    {
        $s = explode('_', $name);
        foreach ($s as $key => $value) {
            $s[$key] = ucfirst($value);
        }
        return implode('', $s);
    }

    public function jsonSerialize(): array
    {
        return $this->getProps();
    }

    public function toArray(): array
    {
        return (array) json_decode(json_encode($this->jsonSerialize()), true);
    }

    public function getClassName(): ?string
    {
        if ($pos = strrpos(self::class, '\\')) {
            return substr(self::class, $pos + 1);
        }

        return null;
    }
}
