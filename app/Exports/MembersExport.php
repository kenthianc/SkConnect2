<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Get collection of members
     */
    public function collection()
    {
        return Member::orderBy('member_id')->get();
    }

    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'Member ID',
            'Full Name',
            'Email',
            'Phone',
            'Birthdate',
            'Age',
            'Gender',
            'Purok',
            'Address',
            'Guardian Name',
            'Guardian Contact',
            'Role',
            'Registered Via',
            'Date Joined',
            'Status',
        ];
    }

    /**
     * Map data for each row
     */
    public function map($member): array
    {
        return [
            $member->member_id,
            $member->name,
            $member->email,
            $member->phone,
            $member->birthdate->format('Y-m-d'),
            $member->age,
            $member->gender,
            $member->purok,
            $member->address,
            $member->guardian_name,
            $member->guardian_contact,
            $member->role,
            $member->registered_via,
            $member->date_joined->format('Y-m-d'),
            $member->is_active ? 'Active' : 'Inactive',
        ];
    }

    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
