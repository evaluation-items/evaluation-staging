<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LibraryReport implements FromCollection,WithHeadings, WithStyles
{
    protected $data;

    function __construct($data) {
        $this->data = $data;
        
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name',
            'Year',
            'Department Name',
            'Organization Name',
        ];
    }

     /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [];

        foreach ($this->data as $key => $value) {
            $data[] = [
                'Name' => $value->study_name,
                'Year' => $value->year,
                'Department Name' => department_name($value->dept_id),
                'Organization Name' => $value->org_name,
            ];
        }
    

        return collect($data);
    }
    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Bold the first row (headings)
            1 => ['font' => ['bold' => true]],
        ];
    }
}
