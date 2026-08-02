<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * Fetch logs collection for export.
     */
    public function collection()
    {
        return Activity::with(['causer', 'subject'])->orderBy('id', 'desc')->get();
    }

    /**
     * Map headings.
     */
    public function headings(): array
    {
        return [
            'Log ID',
            'Event',
            'Description',
            'Subject Type',
            'Performed By',
            'Timestamp',
        ];
    }

    /**
     * Map rows.
     *
     * @param  Activity  $log
     */
    public function map($log): array
    {
        return [
            $log->id,
            strtoupper($log->event ?? 'SYSTEM'),
            $log->description,
            $log->subject_type ? class_basename($log->subject_type) : '-',
            $log->causer ? $log->causer->name.' ('.$log->causer->email.')' : 'System Bot',
            $log->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
