<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovaRedirectorSeo extends Model
{
    public $fillable = [
        'from_url',
        'to_url',
        'status_code',
        'enabled',
    ];

    protected $table = 'nova_redirector_seo';

    public function clearCache()
    {
        cache()->forget("nova-redirector-seo.{$this->from_url}");
    }
}
