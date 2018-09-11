<?php

use Illuminate\Support\Carbon;
use \Laravelrus\LocalizedCarbon\Traits\LocalizedEloquentTrait;
/**
 * Return a Carbon instance.
 */
function carbon(string $parseString = '', string $tz = null): Carbon
{
    return new Carbon($parseString, $tz);
}

/**
 * Return a formatted Carbon date.
 */
function humanize_date(Carbon $date, string $format = 'd F Y, H:i'): string
{
    return $date->formatLocalized('%d %B %Y');
}
