<?php

namespace App\Http\Controllers;

use App\Models\CashEntry;
use App\Models\Payout;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Расчет общего поступления наличных
        $totalCash = CashEntry::sum('amount');
        
        // Расчет общей выданной суммы
        $totalPaid = Payout::sum('amount');
        
        // Оставшееся количество денег в кассе
        $remaining = $totalCash - $totalPaid;
        
        // Последние поступления
        $recentCashEntries = CashEntry::with('admin')
            ->latest('received_at')
            ->take(10)
            ->get();
        
        // Количество сотрудников
        $employeeCount = Employee::count();

        return view('dashboard', [
            'totalCash' => $totalCash,
            'totalPaid' => $totalPaid,
            'remaining' => $remaining,
            'recentCashEntries' => $recentCashEntries,
            'employeeCount' => $employeeCount,
        ]);
    }
}
