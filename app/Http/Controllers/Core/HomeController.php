<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Simpanan\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Modules\User\Services\UserService;

class HomeController extends Controller
{
    protected $dashboardService;
    protected $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        DashboardService $dashboardService,
        UserService $userService
    ) {
        $this->middleware('auth');

        $this->dashboardService = $dashboardService;
        $this->userService = $userService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     public function index()
    {
        if (
            isset(auth()->user()->role_aktif) &&
            !empty(auth()->user()->role_aktif)
        ) {

            $view = 'dashboard.' . auth()->user()->role_aktif;

            if (View::exists($view)) {

                $data = [];

                // Dashboard Anggota
                if (auth()->user()->role_aktif === 'anggota') {
                    $data['summary'] = $this->dashboardService
                        ->getSummary(Auth::id());
                }

                // Dashboard Koordinator
                if (auth()->user()->role_aktif === 'koordinator') {
                    $data['summary'] = $this->dashboardService
                        ->getKoordinatorSummary();
                }

                // Dashboard Admin
                if (auth()->user()->role_aktif === 'admin') {
                    $data['summary'] = $this->userService
                        ->getDashboardSummary();
                }
                
                if (auth()->user()->role_aktif === 'bendahara') {
                    $data['summary'] = $this->dashboardService
                        ->getBendaharaSummary();
                }

                return view($view, $data);
            }
        }

        return view('home');
    }
} 
