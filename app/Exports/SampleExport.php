<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SampleExport implements FromCollection, WithHeadings
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Name of Study',
            'Department',
            'Convener of the Department',
            'Scheme/ Programme to be evaluated',
            'Reference year',
            'Major Objective',
            'Major Monitoring Indicators',
            'HOD Contact No',
            'Nodal Officer Name',
            'Nodal Officer (HoD) Designation',
            'Financial Adviser Name',
            'Financial Adviser Designation',
            'HOD',
            'Background of the scheme',
            'Objectives of the scheme',
            'Name of Sub-schemes',
            'Year of actual commencement scheme',
            'Community selection Criteria',
            'Expected Major Benefits Derived',
            'Implementing procedure of the Scheme',
            'From State to beneficiaries'
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            [
                'Name of Study' => 'Test Scheme Name',
                'Department' => 'Test Department',
                'Convener of the Department' => 'Test Convener Name',
                'Scheme/ Programme to be evaluated' => 'Test Programme',
                'Reference year' => '1996-97',
                'Major Objective' => 'Test Major1, Test Major2',
                'Major Monitoring Indicators' => 'Test Indicators Monitor 1, Test Indicators Monitor 2',
                'HOD Contact No' => '1234567890',
                'Nodal Officer Name' => 'Test Name',
                'Nodal Officer (HoD) Designation' => 'Test Desc',
                'Financial Adviser Name' => 'Test Financial Name',
                'Financial Adviser Designation' => 'Test Adviser',
                'HOD' => 'Hod Name',
                'Background of the scheme' => 'Test Background Scheme',
                'Objectives of the scheme' => 'Test Objective',
                'Name of Sub-schemes' => 'Test Sub-schemes',
                'Year of actual commencement scheme' => '2023-24',
                'Community selection Criteria' => 'District Name',
                'Expected Major Benefits Derived' => 'Test',
                'Implementing procedure of the Scheme' => 'Test',
                'From State to beneficiaries' => 'Test'
            ],
        ]);
    }
}

