<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapApiWifiRoutes();	// api wifi interface
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapOperatorRoutes();
        $this->mapSchoolManagerRoutes();
        $this->mapTeacherRoutes();
        $this->mapStudentRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * ApiWifi
     */
    protected function mapApiWifiRoutes()
    {
        Route::middleware('api')
            ->namespace($this->namespace. '\Api')
            ->group(base_path('routes/api_wifi.php'));
    }

    /**
     * Admin
     */
    protected function mapAdminRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace. '\Admin')
             ->group(base_path('routes/admin.php'));
    }

    /**
     * Operator
     */
    protected function mapOperatorRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace. '\Operator')
            ->group(base_path('routes/operator.php'));
    }

    /**
     * Operator
     */
    protected function mapSchoolManagerRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace. '\Operator')
            ->group(base_path('routes/school_manager.php'));
    }

    /**
     * Teacher
     */
    protected function mapTeacherRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace. '\Teacher')
             ->group(base_path('routes/teacher.php'));
    }

    /**
     * Student 学生路由
     */
    protected function mapStudentRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace. '\Student')
            ->group(base_path('routes/verified_student.php'));
    }
}
