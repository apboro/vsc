<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class Settings
{
    public const string = 0;
    public const int = 1;
    public const bool = 2;
    public const datetime = 3;
    public const array = 4;

    /** @var string[][] Settings store. */
    protected static array $settings;

    /** @var bool Loaded state. */
    protected static bool $loaded = false;

    /**
     * Get value from settings.
     *
     * @param string $key
     * @param mixed $default
     * @param int $type
     *
     * @return  mixed
     */
    public static function get(?int $organizationId, string $key, $default = null, int $type = Settings::string)
    {
        if (!self::$loaded) {
            self::load();
        }

        return (self::$settings && array_key_exists($organizationId, self::$settings) && array_key_exists($key, self::$settings[$organizationId]))
            ? self::castTo(self::$settings[$organizationId][$key], $type)
            : $default;
    }

    /**
     * Set value to settings.
     *
     * @param string $key
     * @param mixed $value
     * @param int $type
     *
     * @return  void
     */
    public static function set(?int $organizationId, string $key, $value, int $type = Settings::string): void
    {
        if (!self::$loaded) {
            self::load();
        }

        if (!isset(self::$settings[$organizationId])) {
            self::$settings[$organizationId] = [];
        }
        self::$settings[$organizationId][$key] = self::castFrom($value, $type);
    }

    /**
     * Load settings.
     *
     * @return  void
     */
    public static function load(): void
    {
        $settings = DB::table('settings')->get();

        self::$settings = [];

        foreach ($settings as $setting) {
            if (!isset(self::$settings[$setting->organization_id])) {
                self::$settings[$setting->organization_id] = [];
            }
            self::$settings[$setting->organization_id][$setting->key] = $setting->value;
        }

        self::$loaded = true;
    }

    /**
     * Save settings.
     *
     * @return  void
     */
    public static function save(): void
    {
        if (self::$loaded === false) {
            throw new RuntimeException('Settings must be loaded before saving.');
        }
        $values = [];
        foreach (self::$settings as $organizationId => $settings) {
            foreach ($values as $key => $value) {
                $values[] = ['key' => $key, 'organization_id' => empty($organizationId) ? null : $organizationId, 'value' => $value];
            }
        }
        DB::table('settings')->truncate();
        DB::table('settings')->insert($values);
    }

    /**
     * Cast value to type.
     *
     * @param string|null $value
     * @param int $type
     *
     * @return  bool|int|string
     */
    protected static function castTo(?string $value, int $type)
    {
        switch ($type) {
            case self::int:
                return $value !== null ? (int)$value : null;
            case self::bool:
                return (bool)$value;
            case self::datetime:
                return $value !== null ? Carbon::parse($value) : null;
            case self::array:
                return !empty($value) ? json_decode($value, true, 512, JSON_THROW_ON_ERROR) : null;
            case self::string:
            default:
        }

        return $value !== null ? (string)$value : null;
    }

    /**
     * Cast value from type.
     *
     * @param bool|int|string|null $value
     * @param int $type
     *
     * @return  string|null
     */
    protected static function castFrom($value, int $type): ?string
    {
        switch ($type) {
            case self::int:
            case self::bool:
            case self::datetime:
                $value = $value !== null ? (string)$value : null;
                break;
            case self::array:
                $value = !empty($value) ? json_encode($value, JSON_THROW_ON_ERROR) : null;
                break;
            case self::string:
            default:
        }

        return $value;
    }
}
