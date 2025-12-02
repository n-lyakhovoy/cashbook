<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage-employees');
        $employees = Employee::orderBy('last_name')->paginate(15);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage-employees');
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-employees');
        
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'priority' => 'required|integer|min:1|max:5',
        ]);

        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно добавлен');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $this->authorize('manage-employees');
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $this->authorize('manage-employees');
        
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'salary' => 'required|numeric|min:0',
            'priority' => 'required|integer|min:1|max:5',
        ]);

        $employee->update($validated);
        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $this->authorize('manage-employees');
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Сотрудник удален');
    }
}
