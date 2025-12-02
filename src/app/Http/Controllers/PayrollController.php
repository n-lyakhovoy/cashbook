<?php

namespace App\Http\Controllers;

use App\Models\PayrollMonthly;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage-payroll');
        
        $month = request()->get('month', Carbon::now()->subMonth()->month);
        $year = request()->get('year', Carbon::now()->subMonth()->year);
        
        $employees = Employee::with(['payrollMonthly' => function($query) use ($month, $year) {
            $query->where('month', $month)->where('year', $year);
        }])->orderBy('priority')->get();

        // Создаём записи payroll для сотрудников, если они отсутствуют
        foreach ($employees as $employee) {
            if ($employee->payrollMonthly->isEmpty()) {
                PayrollMonthly::create([
                    'employee_id' => $employee->id,
                    'year' => $year,
                    'month' => $month,
                    'salary' => $employee->salary,
                ]);
                $employee->refresh();
            }
        }

        return view('payroll.index', [
            'employees' => $employees,
            'month' => $month,
            'year' => $year,
            'monthName' => Carbon::createFromDate($year, $month, 1)->format('F Y'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollMonthly $payroll)
    {
        $this->authorize('manage-payroll');
        return view('payroll.edit', compact('payroll'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PayrollMonthly $payroll)
    {
        $this->authorize('manage-payroll');
        
        $validated = $request->validate([
            'salary' => 'required|numeric|min:0',
            'bonus' => 'required|numeric|min:0',
            'penalty' => 'required|numeric|min:0',
            'advance' => 'required|numeric|min:0',
            'official_salary' => 'required|numeric|min:0',
            'vacation' => 'required|numeric|min:0',
        ]);

        // Вычисляем "к выдаче наличными"
        $validated['to_cash'] = $validated['salary'] + $validated['bonus'] - $validated['penalty'] - $validated['advance'] - $validated['official_salary'] - $validated['vacation'];
        $validated['remaining'] = $validated['to_cash'];

        $payroll->update($validated);
        
        return back()->with('success', 'Зарплата обновлена');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
