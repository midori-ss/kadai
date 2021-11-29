<?php

namespace App\Imports;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Kadai;
use App\Models\KadaiStatus;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    // public function model(array $row)
    // {
    //     //Log::debug($row);
    //     return new User([
    //         'code' => $row[0],
    //         'password' => Hash::make($row[1]),
    //         'name' => $row[2],
    //     ]);
    // }

    public function collection(Collection $rows) {
        $kadais_1 = Kadai::where('target', 1)->get();
        $kadais_2 = Kadai::where('target', 2)->get();
        $kadais_3 = Kadai::where('target', 3)->get();
        foreach ($rows as $row) {
            if ($row['password'] == null || $row['password'] == '') {
                User::updateOrCreate(
                    ['code' => $row['code']],
                    ['name' => $row['name'],]
                );
            } else {
                User::updateOrCreate(
                    ['code' => $row['code']],
                    ['password' => Hash::make($row['password']), 'name' => $row['name'],]
                );
            }
            switch (substr((string)$row['code'], 0, 1)) {
                case '1':
                    foreach($kadais_1 as $kadai1) {
                        if ($row->has((string)($kadai1->id))) {
                            $status = $row[(string)$kadai1->id];
                            $st = 0;
                            if ($status == '済') { $st = 1; }
                            else if ($status == '不要') { $st = 2; }
                            KadaiStatus::updateOrCreate(
                                ['kadai_id' => $kadai1->id, 'user_code' => $row['code']],
                                ['status' => $st]
                            );
                        }
                    }
                    break;
                case '2':
                    foreach($kadais_2 as $kadai2) {
                        if ($row->has((string)($kadai2->id))) {
                            $status = $row[(string)$kadai2->id];
                            $st = 0;
                            if ($status == '済') { $st = 1; }
                            else if ($status == '不要') { $st = 2; }
                            KadaiStatus::updateOrCreate(
                                ['kadai_id' => $kadai2->id, 'user_code' => $row['code']],
                                ['status' => $st]
                            );
                        }
                    }
                    break;
                case '3':
                    foreach($kadais_3 as $kadai3) {
                        if ($row->has((string)($kadai3->id))) {
                            $status = $row[(string)$kadai3->id];
                            $st = 0;
                            if ($status == '済') { $st = 1; }
                            else if ($status == '不要') { $st = 2; }
                            KadaiStatus::updateOrCreate(
                                ['kadai_id' => $kadai3->id, 'user_code' => $row['code']],
                                ['status' => $st]
                            );
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }
}
