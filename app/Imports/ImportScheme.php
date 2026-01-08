<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use App\Models\Scheme;


class ImportScheme implements ToCollection, WithValidation, WithHeadingRow
{
    use Importable;
    /**
    * @return array
    */
    public function rules(): array
    {
        return [
            '0' => 'required|string',
            '1' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^\d{4}-\d{2}$/', $value)) {
                        $fail('The '.$attribute.' format is invalid. The correct format is YYYY-YY.');
                    }
                    $year = substr($value, 0, 4);
                    $nextYear = substr($value, 5);
                    if ($year + 1 != $nextYear) {
                        $fail('The '.$attribute.' format is invalid. The year should be in YYYY-YY format.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            '0.required' => 'Please enter a value in column A.',
            '1.required' => 'Please enter a value in column B.',
            '1.regex' => 'The :attribute format is invalid. The correct format is YYYY-YY.',
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
       
        foreach ($collection as $key => $collection_items) {
            Scheme::create([
                'name' => $row[0],
            ]);
        }
    }
    
}
