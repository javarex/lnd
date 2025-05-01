<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class SchoolImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        DB::beginTransaction();
        try {
            $data = [];
            foreach ($collection as $key => $row) {
                if($key == 0 || $row[1] === null) {
                    continue;
                }
                array_push($data, [
                    'district' => $row[0],
                    'no' => $row[1],
                    'school' => $row[2],
                    'school_code' => $row[3],
                    'purok' => $row[4],
                    'barangay' => $row[5],
                    'municipality' => $row[6],
                    'created_at' => now()
                ]);

            }

            $data = array_chunk($data, 100);

            foreach ($data as $key => $schools) {
                DB::table('schools')->insert($schools);
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd(collect($schools)->firstWhere('school_code', null));
            dd($th->getMessage());
        }
    }
}
