<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateRequest;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public function index()
    {
        if (Auth::check()) {
            $role = Auth::user()->role;
            if ($role === 1) {
                return redirect('/admin');
            } else {
                return redirect('/student');
            }
        } else {
            return redirect('/login');
        }   
    }
}
