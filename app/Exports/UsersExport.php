<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Kadai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $year, $kadais, $cols;

    public function __construct($num)
    {
        $this->year = $num;
        $this->kadais = Kadai::where('target', '=', $this->year)
                        ->orderBy('id', 'ASC')
                        ->get();
        $this->cols = [
            'code', 
            'password',
            'name',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $selectCol = $this->cols;
        $selectCol[1] = DB::raw("''");
        foreach($this->kadais as $kadai) {
            array_push($selectCol, DB::raw("cast(ks".$kadai->id.".status as char)"));
        }
        $qry = User::select($selectCol)
                    ->where('code', 'LIKE', $this->year.'%');
        foreach($this->kadais as $kadai){
            $colName = 'ks'.$kadai->id;
            $qry->leftJoin('kadai_status as '.$colName, function($join) use($kadai, $colName) {
                $join->on('users.code', '=', $colName.'.user_code')
                    ->where($colName.'.kadai_id', '=', $kadai->id);
                });
        }
        Log::debug($qry->toSql());
        return $qry->get();
    }

	public function headings():array
	{
        $result = $this->cols;
        foreach($this->kadais as $kadai) {
            array_push($result, $kadai->id . ":" . $kadai->name);
        }
		return $result;
	}
}
