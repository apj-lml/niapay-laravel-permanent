<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use NumberFormatter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('numberToWord', function ($num) {
            // return $num;
            // print($num);
            list($whole, $decimal) = explode('.', $num);
            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            // $f->setTextAttribute(NumberFormatter::DEFAULT_RULESET, "%spellout-numbering-verbose");
            $final = $f->format((int)$whole) . ' & ' .$decimal. '/100 Pesos Only';
                return ucwords($final);
            });

    }
    
}
