<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;
use App\Models\Kadai;
use App\Models\KadaiStatus;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class Admin extends Component
{
    public $kadais, $name, $target, $kadai_id, $users1, $users2, $users3;
    public $isOpen = false;
    public $isConfirmOpen = false;

    public function render()
    {
        $this->kadais = Kadai::all();
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

    public function openModal() {
        $this->isOpen = true;
    }

    public function closeModal() {
        $this->isOpen = false;
    }

    public function openConfirm() {
        $this->isConfirmOpen = true;
    }

    public function closeConfirm() {
        $this->isConfirmOpen = false;
    }

    public function resetInputFields() {
        $this->name = '';
        $this->target = 0;
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
        $newest = Kadai::max('id').get();
        if ($this->kadai_id) {
            //INSERT成功メッセージ
            session()->flash('message', 'Kadai Updated Successfully.');
        } else {
            // 対象学年の全生徒分statusテーブルにレコードをINSERT
            $users = User::where('code', 'LIKE', $this->target.'%');
            foreach($ususer_listers as $user) {
                $ks = new KadaiStatus;
                $ks->kadai_id = $newest;
                $ks->user_code = $user->code;
                $ks->status = 0;
                KadaiStatus::save();
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
    
    public function import(Request $request){

		$file = $request->file('file');

		Excel::import(new UsersImport, $file);

        session()->flash('message', 'Import Successfully.');
        return redirect('/admin');
    }
}
