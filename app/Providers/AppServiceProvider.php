<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
     public function boot()
     {
        \Carbon\Carbon::setlocale('id');
          Schema::defaultStringLength(191);
          // Validator::extend('not_contains', function($attribute, $value, $parameters)
          // {
          //     // Banned words
          //     $words = array('$***', '0***');
          //     foreach ($words as $word)
          //     {
          //         if (stripos($value, $word) !== true) return false;
          //     }
          //     return true;
          // });
            View::composer('*', function($view)
            {
              if (auth()->user() != null) {
                $access = auth()->user()->roles()->first()->accesses()->get();
                $acc = array();
                foreach ($access as $value) {
                  $acc[] = $value->name;
                }
                $view->with('acc',$acc);
              }
            });

     }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
