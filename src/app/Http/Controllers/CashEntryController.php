<?php

namespace App\Http\Controllers;

use App\Models\CashEntry;
use App\Models\Notification;
use Illuminate\Http\Request;

class CashEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('manage-cash');
        $entries = CashEntry::with('admin')->latest('received_at')->paginate(20);
        $totalCash = CashEntry::sum('amount');
        return view('cash.index', compact('entries', 'totalCash'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('manage-cash');
        return view('cash.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('manage-cash');
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'source' => 'required|string|max:255',
            'received_at' => 'required|datetime',
        ]);

        $entry = CashEntry::create([
            ...$validated,
            'admin_id' => auth()->id(),
        ]);

        // Отправляем уведомления
        $this->notifyAdmins('cash_intake', $entry);

        return redirect()->route('cash.index')->with('success', 'Поступление наличных зафиксировано');
    }

    /**
     * Display the specified resource.
     */
    public function show(CashEntry $cashEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CashEntry $cashEntry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CashEntry $cashEntry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CashEntry $cashEntry)
    {
        $this->authorize('manage-cash');
        $cashEntry->delete();
        return back()->with('success', 'Запись удалена');
    }

    /**
     * Уведомляет администраторов
     */
    private function notifyAdmins($type, $entry)
    {
        $admins = \App\Models\User::role(['super-admin', 'admin-write'])->get();
        
        foreach ($admins as $admin) {
            $setting = $admin->setting;
            
            if ($type === 'cash_intake' && $setting && $setting->receive_on_intake) {
                Notification::create([
                    'type' => 'cash_intake',
                    'message' => "Поступление наличных: {$entry->amount} руб. от {$entry->source}",
                    'user_id' => $admin->id,
                    'is_email' => true,
                    'is_push' => true,
                ]);
            }
        }
    }
}
