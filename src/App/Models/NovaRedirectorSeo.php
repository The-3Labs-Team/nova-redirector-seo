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

    /**
     * The single entry holding every enabled rule.
     */
    public static function cacheKey(): string
    {
        return 'nova-redirector-seo.rules';
    }

    public function clearCache(): void
    {
        cache()->forget(self::cacheKey());
    }
}
