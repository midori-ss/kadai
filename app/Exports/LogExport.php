<?php

namespace App\Exports;

use App\Models\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogExport implements FromCollection, WithHeadings
{
    public function __construct()
    {
        $this->cols = [
            'time',
            'user_code',
            'user_name'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $selectCol = $this->cols;
        return Log::selectRaw('DATE_FORMAT(created_at, "%Y/%m/%d %H:%i")')
                ->selectRaw('user_code')
                ->selectRaw('user_name')
                ->where('type', '=', 'login')
                ->orderBy('id', 'DESC')
                ->get();
    }

    public function headings():array
	{
        $result = $this->cols;
		return $result;
	}
}