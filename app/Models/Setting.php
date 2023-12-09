<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'key',
        'value'
    ];

    public $timestamps = false;

    /**
     * @param string $key
     * @return mixed|null
     */
    public static function get(string $key): mixed
    {
        return static::findKey($key)->value ?? null;
    }

    /**
     * @param string $key
     * @return Setting|null
     */
    public static function findKey(string $key): Setting|null
    {
        return static::where('key', $key)->first();
    }

    /**
     * @param string $key
     * @param $value
     * @return mixed
     */
    public static function set(string $key, $value): Setting
    {
        $setting = static::findKey($key);

        if (is_null($setting)) {
            return static::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        $setting->update([
            'value' => $value
        ]);

        return $setting;
    }
}
