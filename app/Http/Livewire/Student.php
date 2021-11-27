<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Kadai;
use App\Models\KadaiStatus;

class Student extends Component
{
    public $kadais;

    public function render()
    {
        $code = Auth::user()->code;
        $this->kadais = KadaiStatus::where('user_code', $code)
                            ->leftJoin('kadai', 'kadai.id', '=', 'kadai_status.kadai_id')
                            ->select('kadai.name', 'kadai_status.status')
                            ->orderBy('kadai_id', 'desc')
                            ->get();
        return view('livewire.student');
    }
}
