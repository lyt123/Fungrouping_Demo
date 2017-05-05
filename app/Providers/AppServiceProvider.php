<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public static $sql_listen = [];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_DEBUG', false)) {
            \DB::listen(function($sql, $binding, $time) {

                $status = [
                    'sql' => $sql,
                    'binding' => json_encode($binding),
                    'time' => $time.'ms'
                ];
                self::$sql_listen[] = $status;
            });
        }
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
