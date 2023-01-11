<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use App\Models\Kadai;
use App\Models\KadaiStatus;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Exports\LogExport;

class Admin extends Component
{
    public $kadais, $name, $target, $kadai_id, $users1, $users2, $users3, $user_code, $user_name, $del_year;
    public $isOpen = false;
    public $isConfirmOpen = false;
    public $isStudentConfirmOpen = false;

    public function render()
    {
        $this->kadais = Kadai::sortable()->get();
        //1年生
        $this->users1  = User::where('code', 'LIKE', '1%')->orderBy('code', 'ASC')->get();
        for($i = 0; $i < count($this->users1); $i++) {
            $user1 = $this->users1 [$i];
            $kadai_list = DB::table('kadai_status')
            ->leftJoin('kadai', 'kadai_status.kadai_id', '=', 'kadai.id')
            ->select('kadai.name as kadai_name', 'kadai_status.status')
            ->where('kadai_status.user_code', '=', $user1->code)
            ->get();
            $user1->kadais = $kadai_list;
            $this->users1[$i] = $user1;
        }
        // 2年生
        $this->users2  = User::where('code', 'LIKE', '2%')->orderBy('code', 'ASC')->get();
        for($i = 0; $i < count($this->users2); $i++) {
            $user2 = $this->users2 [$i];
            $kadai_list = DB::table('kadai_status')
            ->leftJoin('kadai', 'kadai_status.kadai_id', '=', 'kadai.id')
            ->select('kadai.name as kadai_name', 'kadai_status.status')
            ->where('kadai_status.user_code', '=', $user2->code)
            ->get();
            $user2->kadais = $kadai_list;
            $this->users2[$i] = $user2;
        }
        // 3年生
        $this->users3  = User::where('code', 'LIKE', '3%')->orderBy('code', 'ASC')->get();
        for($i = 0; $i < count($this->users3); $i++) {
            $user3 = $this->users3 [$i];
            $kadai_list = DB::table('kadai_status')
            ->leftJoin('kadai', 'kadai_status.kadai_id', '=', 'kadai.id')
            ->select('kadai.name as kadai_name', 'kadai_status.status')
            ->where('kadai_status.user_code', '=', $user3->code)
            ->get();
            $user3->kadais = $kadai_list;
            $this->users3[$i] = $user3;
        }
        return view('livewire.admin');
    }

    public function createKadai() {
        $this->resetInputFields();
        $this->openModal();
    }

    //課題作成Modal
    public function openModal() {
        $this->isOpen = true;
    }

    public function closeModal() {
        $this->isOpen = false;
    }

    //課題削除Confirm
    public function openConfirm() {
        $this->isConfirmOpen = true;
    }

    public function closeConfirm() {
        $this->isConfirmOpen = false;
    }

    //生徒削除Confirm
    public function openStudentConfirm() {
        $this->isStudentConfirmOpen = true;
    }

    public function closeStudentConfirm() {
        $this->isStudentConfirmOpen = false;
    }

    public function resetInputFields() {
        $this->name = '';
        $this->target = 1;
        $this->kadai_id = null;
    }

    public function storeKadai() {
        $this->validate([
            'name' => 'required',
            'target' => 'required'
        ]);
        Kadai::updateOrCreate(
            ['id' => $this->kadai_id],
            ['name' => $this->name, 'target' => $this->target]
        );
        $newest = Kadai::max('id');
        if ($this->kadai_id) {
            //INSERT成功メッセージ
            session()->flash('message', 'Kadai Updated Successfully.');
        } else {
            // 対象学年の全生徒分statusテーブルにレコードをINSERT
            $users = User::where('code', 'LIKE', $this->target.'%')->get();
            Log::debug(count($users));
            foreach($users as $user) {
                KadaiStatus::create([
                    'kadai_id' => $newest,
                    'user_code' => $user->code,
                ]);
            }
            // UPDATE成功メッセージ
            session()->flash('message', 'Kadai Created Successfully.');
        }
        
        $this->closeModal();
        $this->resetInputFields();
    }

    public function editKadai($id)
    {
        $kadai = Kadai::findOrFail($id);
        $this->kadai_id = $kadai->id;
        $this->name = $kadai->name;
        $this->target = $kadai->target;
        $this->openModal();
    }

    public function deleteConfirm($id, $name)
    {
        $this->kadai_id = $id;
        $this->name = $name;
        $this->openConfirm();
    }

    public function deleteKadai()
    {
        KadaiStatus::where('kadai_id', $this->kadai_id)->delete();
        Kadai::find($this->kadai_id)->delete();
        session()->flash('message', 'Kadai Deleted Successfully.');
        $this->resetInputFields();
        $this->closeConfirm();
    }

    public function deleteStudentConfirm($code, $name)
    {
        $this->user_code = $code;
        $this->user_name = $name;
        $this->openStudentConfirm();
    }

    public function deleteStudentsConfirm($year)
    {
        $this->del_year = $year;
        $this->openStudentConfirm();
    }

    public function deleteStudent()
    {
        if ($this->del_year) {
            KadaiStatus::where('user_code', 'LIKE', $this->del_year.'%')->delete();
            User::where('code', 'LIKE', $this->del_year.'%')->delete();
        } else {
            KadaiStatus::where('user_code', $this->user_code)->delete();
            User::whereCode($this->user_code) -> first() -> delete();
        }
        
        session()->flash('message', 'User Deleted Successfully.');
        $this->del_year = null;
        $this->closeStudentConfirm();
    }
    
    public function import(Request $request){

		$file = $request->file('file');
        if ($file == null) {
            session()->flash('message', 'Please select import csv file!!');
            return redirect('/admin');
        }

		Excel::import(new UsersImport, $file);

        session()->flash('message', 'Import Successfully.');
        return redirect('/admin');
    }

    public function export(Request $request) {
        $num = $request->year;
        $data = new UsersExport($num); 
        $content = Excel::raw($data, \Maatwebsite\Excel\Excel::CSV); 
        $content = mb_convert_encoding($content, 'SJIS', 'auto');
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users.csv"'
            ];
        return \Response::make($content, 200, $headers);
        // return Excel::download(new UsersExport($num), 'users.csv');
    }

    public function downloadLog(Request $request) {
        $data = new LogExport();
        $content = Excel::raw($data, \Maatwebsite\Excel\Excel::CSV); 
        $content = mb_convert_encoding($content, 'SJIS', 'auto');
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="login_log_' . date('Ymd_Hi') . '.csv"'
            ];
        return \Response::make($content, 200, $headers);
    }
}
