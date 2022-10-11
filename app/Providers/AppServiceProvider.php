<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Providers\Globalprovider;

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
        view()->composer('*', function($view){
            if (Auth::check()) {
                
                // menus
                $gF       = new Globalprovider();
                $userMenus = $gF->userMenus();
                $result    = [];
                foreach ($userMenus as $key => $value) {
                    $subMenus = $gF->userSubMenus($value->id);
                    $count_submenus = count($subMenus);
                    if($count_submenus == 0){
                        $result[] = [
                            'menu_type' => $value->menu_type,
                            'menu_icon' => $value->menu_icon,
                            'menu_name' => $value->menu_name,
                            'menu_route' => $value->menu_route,
                            'count_submenus' => $count_submenus
                        ];
                    }else{
                        $result[] = [
                            'menu_type' => $value->menu_type,
                            'menu_icon' => $value->menu_icon,
                            'menu_name' => $value->menu_name,
                            'menu_route' => $value->menu_route,
                            'count_submenus' => $count_submenus,
                            'subMenus' => $subMenus
                        ];
                    }
                }
                // dd($result);
                View::share([
                    'userMenus' => $result,
                ]);
            }
        });
    }
}
