<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Расчётные ведомости - {{ $monthName }}
            </h2>
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="month" class="px-3 py-2 border border-gray-300 rounded-md">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $month)>{{ \Carbon\Carbon::createFromDate(now()->year, $m, 1)->format('F') }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Показать</button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-2 px-2">№</th>
                                <th class="text-left py-2 px-2">ФИО</th>
                                <th class="text-left py-2 px-2">Приоритет</th>
                                <th class="text-left py-2 px-2">Оклад</th>
                                <th class="text-left py-2 px-2">Бонусы</th>
                                <th class="text-left py-2 px-2">Штрафы</th>
                                <th class="text-left py-2 px-2">Аванс</th>
                                <th class="text-left py-2 px-2">Офиц. ЗП</th>
                                <th class="text-left py-2 px-2">К выдаче</th>
                                <th class="text-left py-2 px-2">Выдано</th>
                                <th class="text-left py-2 px-2">Осталось</th>
                                <th class="text-left py-2 px-2">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $idx => $employee)
                                @php
                                    $payroll = $employee->payrollMonthly->first();
                                    $toCash = $payroll->to_cash ?? 0;
                                    $paid = $payroll->paid ?? 0;
                                    $remaining = $payroll->remaining ?? $toCash;
                                    
                                    if ($remaining < 0) $rowClass = 'bg-red-50';
                                    elseif ($remaining == 0) $rowClass = 'bg-green-50';
                                    else $rowClass = 'bg-yellow-50';
                                @endphp
                                <tr class="border-b {{ $rowClass }} hover:opacity-90">
                                    <td class="py-2 px-2">{{ $idx + 1 }}</td>
                                    <td class="py-2 px-2 font-semibold">{{ $employee->full_name }}</td>
                                    <td class="py-2 px-2">{{ $employee->priority }}</td>
                                    <td class="py-2 px-2">{{ number_format($employee->salary, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($payroll->bonus, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($payroll->penalty, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($payroll->advance, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($payroll->official_salary, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2 font-bold">{{ number_format($toCash, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($paid, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2">{{ number_format($remaining, 2, ',', ' ') }}</td>
                                    <td class="py-2 px-2 flex gap-1">
                                        <button onclick="openEditModal({{ $payroll->id }}, '{{ $employee->full_name }}')" class="text-blue-600 hover:text-blue-900 text-xs">Редактировать</button>
                                        <button onclick="openPayoutModal({{ $payroll->id }}, '{{ $employee->full_name }}')" class="text-green-600 hover:text-green-900 text-xs">Выплатить</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="py-4 px-4 text-center text-gray-500">Нет сотрудников</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Modal -->
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold mb-4" id="editTitle"></h3>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Оклад</label>
                            <input type="number" name="salary" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Бонусы</label>
                            <input type="number" name="bonus" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Штрафы</label>
                            <input type="number" name="penalty" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Аванс</label>
                            <input type="number" name="advance" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Официальная ЗП</label>
                            <input type="number" name="official_salary" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Отпускные</label>
                            <input type="number" name="vacation" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Сохранить</button>
                            <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-600 text-white py-2 rounded hover:bg-gray-700">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payout Modal -->
            <div id="payoutModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                    <h3 class="text-lg font-semibold mb-4" id="payoutTitle"></h3>
                    <form id="payoutForm" method="POST" action="{{ route('payouts.store') }}">
                        @csrf
                        <input type="hidden" name="payroll_id" id="payoutPayrollId">
                        
                        <div class="mb-3">
                            <label class="block text-sm font-medium mb-1">Сумма к выплате (₽)</label>
                            <input type="number" name="amount" step="0.01" class="w-full px-2 py-1 border border-gray-300 rounded" required>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-green-600 text-white py-2 rounded hover:bg-green-700">Выплатить</button>
                            <button type="button" onclick="closePayoutModal()" class="flex-1 bg-gray-600 text-white py-2 rounded hover:bg-gray-700">Отмена</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                function openEditModal(payrollId, name) {
                    document.getElementById('editTitle').textContent = 'Редактировать - ' + name;
                    document.getElementById('editForm').action = '/payroll/' + payrollId;
                    document.getElementById('editModal').classList.remove('hidden');
                }

                function closeEditModal() {
                    document.getElementById('editModal').classList.add('hidden');
                }

                function openPayoutModal(payrollId, name) {
                    document.getElementById('payoutTitle').textContent = 'Выплата - ' + name;
                    document.getElementById('payoutPayrollId').value = payrollId;
                    document.getElementById('payoutModal').classList.remove('hidden');
                }

                function closePayoutModal() {
                    document.getElementById('payoutModal').classList.add('hidden');
                }
            </script>
        </div>
    </div>
</x-app-layout>
