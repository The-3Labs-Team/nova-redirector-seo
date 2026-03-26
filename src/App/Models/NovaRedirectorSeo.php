<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $from_url
 * @property string $to_url
 * @property int $status_code
 * @property bool $enabled
 * @property bool $is_regex
 */
class NovaRedirectorSeo extends Model
{
    public $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'enabled',
        'is_regex',
    ];

    protected $table = 'nova_redirector_seo';

    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'enabled' => 'boolean',
            'is_regex' => 'boolean',
        ];
    }

    public static function cacheKey(string $fromUrl): string
    {
        return "nova-redirector-seo.{$fromUrl}";
    }

    public function clearCache(): void
    {
        cache()->forget(self::cacheKey($this->from_url));
    }
}
