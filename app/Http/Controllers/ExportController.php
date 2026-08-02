<?php

namespace App\Http\Controllers;

use App\Exports\ActivityLogsExport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * Download users export as Excel file.
     */
    public function exportUsers(): BinaryFileResponse
    {
        Gate::authorize('users.export');

        $fileName = 'users-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new UsersExport, $fileName);
    }

    /**
     * Download activity logs export as Excel file.
     */
    public function exportLogs(): BinaryFileResponse
    {
        Gate::authorize('logs.export');

        $fileName = 'activity-logs-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new ActivityLogsExport, $fileName);
    }
}
