<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\PayrollMonthly;
use App\Models\CashEntry;
use App\Models\Notification;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    /**
     * Display payouts history for an employee
     */
    public function history($payrollId)
    {
        $this->authorize('manage-payroll');
        
        $payroll = PayrollMonthly::with('employee.payouts')->findOrFail($payrollId);
        $payouts = $payroll->employee->payouts()
            ->whereYear('paid_at', $payroll->year)
            ->whereMonth('paid_at', $payroll->month)
            ->get();
        
        return view('payouts.history', compact('payroll', 'payouts'));
    }

    /**
     * Store a new payout
     */
    public function store(Request $request)
    {
        $this->authorize('manage-payroll');
        
        $validated = $request->validate([
            'payroll_id' => 'required|exists:payroll_monthlies,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payroll = PayrollMonthly::findOrFail($validated['payroll_id']);
        
        // Проверяем доступность средств
        $totalCash = CashEntry::sum('amount');
        $totalPaid = Payout::sum('amount');
        $available = $totalCash - $totalPaid;

        if ($validated['amount'] > $available) {
            return back()->withErrors(['amount' => 'Недостаточно средств в кассе']);
        }

        // Создаём запись о выплате
        $payout = Payout::create([
            'employee_id' => $payroll->employee_id,
            'amount' => $validated['amount'],
            'paid_at' => now(),
            'admin_id' => auth()->id(),
        ]);

        // Обновляем payroll данные
        $payroll->increment('paid', $validated['amount']);
        $payroll->remaining = $payroll->to_cash - $payroll->paid;
        $payroll->save();

        // Отправляем уведомления
        $this->notifyAdmins('payout', $payout, $payroll->employee);

        return back()->with('success', 'Выплата записана');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payout $payout)
    {
        $this->authorize('manage-payroll');
        
        $amount = $payout->amount;
        
        // Обновляем payroll
        $payroll = PayrollMonthly::where('employee_id', $payout->employee_id)
            ->where('year', now()->year)
            ->where('month', now()->month)
            ->first();
        
        if ($payroll) {
            $payroll->decrement('paid', $amount);
            $payroll->remaining = $payroll->to_cash - $payroll->paid;
            $payroll->save();
        }

        $payout->delete();
        return back()->with('success', 'Выплата удалена');
    }

    /**
     * Уведомляет администраторов о выплате
     */
    private function notifyAdmins($type, $payout, $employee)
    {
        $admins = \App\Models\User::role(['super-admin', 'admin-write'])->get();
        
        foreach ($admins as $admin) {
            $setting = $admin->setting;
            
            if ($type === 'payout' && $setting && $setting->receive_on_payout) {
                Notification::create([
                    'type' => 'payout',
                    'message' => "Выплата сотруднику {$employee->full_name}: {$payout->amount} руб.",
                    'user_id' => $admin->id,
                    'is_email' => true,
                    'is_push' => true,
                ]);
            }
        }
    }
}
