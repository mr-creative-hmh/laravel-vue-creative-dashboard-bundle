<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * Fetch user collection for export.
     */
    public function collection()
    {
        return User::with('roles')->orderBy('id', 'desc')->get();
    }

    /**
     * Map headings.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Roles',
            'Status',
            'Created At',
        ];
    }

    /**
     * Map rows.
     *
     * @param  User  $user
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->getRoleNames()->implode(', '),
            $user->is_active ? 'Active' : 'Disabled',
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
