<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\DepartmentHod;
use App\Models\Department;
use DB;
use Maatwebsite\Excel\Concerns\WithStartRow;

class HODImport implements ToCollection, WithStartRow
{
    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        
        $currentDepartment = null;

        foreach ($collection as $key => $row) {

            if($key > 0){
                if (!empty($row[1])) {
                   
                    $currentDepartment = Department::where('dept_name', 'like', '%' . $row[1] . '%')->value('dept_id');
                }
          
                if ($currentDepartment && !empty($row[2])) {
                    DepartmentHod::create([
                        'name' => $row[2],
                        'dept_id' => $currentDepartment,
                    ]);
                }
            }
           
        }

        return "Import Successfully Completed";
    }
}

