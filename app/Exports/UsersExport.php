<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::all();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Created At',
            'Updated At',
            'Action At',
            'Phone',
            'Organzation',
            'Access Level',
            'Hits',
            'Active / Inactive',
            'Miss',
        ];
    }
}
