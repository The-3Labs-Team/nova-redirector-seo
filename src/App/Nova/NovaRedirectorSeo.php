<?php

namespace The3LabsTeam\NovaRedirectorSeo\App\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaRedirectorSeo extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo>
     */
    public static $model = \The3LabsTeam\NovaRedirectorSeo\App\Models\NovaRedirectorSeo::class;

    public static function label()
    {
        return __('Redirect SEO');
    }

    public static function group()
    {
        return __('SEO');
    }

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'from_url';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'from_url',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Boolean::make(__('Enabled'), 'enabled')->default(true),
            Text::make(__('From URL'), 'from_url')
                ->rules('required', 'string', 'max:255')
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => __('posts/old-post or /posts\/(.*)')
                    ],
                ])
                ->help(__('The URL you want to redirect from.')),
            Text::make(__('To URL'), 'to_url')
                ->rules('required', 'string', 'max:255')
                ->withMeta(['extraAttributes' => [
                    'placeholder' => __('/posts/old-post or https://www.example.com/posts/old-post or /posts/$1')],
                ])
                ->help(__('The URL you want to redirect to.')),
            Select::make(__('Status Code'), 'status_code')->options([
                301 => __('301 (Permanent)'),
                302 => __('302 (Temporary)'),
            ])->rules('required', 'integer', 'min:301', 'max:302'),
            Boolean::make(__('Is Regex'), 'is_regex')->default(false)
                ->help(__('If you want to use regex for the from_url, check this box.')),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
