<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

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
		DB::listen(function ($query) {
			$sql = $query->sql;
			$bindings = $query->bindings;
			$time = $query->time;
			if ($bindings) {
				file_put_contents('querylog.txt', "[" . date("Y-m-d H:i:s") . "]" . $sql . "\r\nparmars:" . json_encode($bindings, 320) . "\r\n\r\n", FILE_APPEND);
			} else {
				file_put_contents('querylog.txt', "[" . date("Y-m-d H:i:s") . "]" . $sql . "\r\n\r\n", FILE_APPEND);
			}
		});
    }
}
