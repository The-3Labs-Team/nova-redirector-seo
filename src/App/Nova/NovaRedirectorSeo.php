<?php

namespace App\Nova;

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

    public static function label() {
        return 'Redirect SEO';
    }

    public static function group() {
        return 'SEO';
    }

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
     * @param NovaRequest $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('From URL', 'from_url')->rules('required', 'string', 'max:255'),
            Text::make('To URL', 'to_url')->rules('required', 'string', 'max:255'),
            Select::make('Status Code', 'status_code')->options([
                301 => '301 (Permanent)',
                302 => '302 (Temporary)',
            ])->rules('required', 'integer', 'min:301', 'max:302'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
