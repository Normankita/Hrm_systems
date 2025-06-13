<?php

namespace App\Http\Utils\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasDateFilter
{
    /**
     * Scope to filter query by date if 'dateEnabled' and 'date' are present in the request.
     *
     * @param  Builder  $query
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $column
     * @return Builder
     */
    public function scopeFilterByDate(Builder $query, $request, $column = 'created_at'): Builder
    {
        // dd($request->dateEnabled);
        if ($request->has('dateEnabled') && $request->input('dateEnabled') && $request->input('dateEnabled') == "on" && $request->filled('date')) {
            $query->whereDate($column, $request->input('date'));
            // set this two variables into flash sessions
            session(['dateEnabled' => true, 'date' => $request->input('date')]);
        }
        return $query;
    }
}
