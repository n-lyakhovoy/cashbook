<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm font-medium">Поступлено наличных</div>
                    <div class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalCash, 2, ',', ' ') }} ₽</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm font-medium">Выдано</div>
                    <div class="text-3xl font-bold text-red-600 mt-2">{{ number_format($totalPaid, 2, ',', ' ') }} ₽</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm font-medium">В кассе</div>
                    <div class="text-3xl font-bold text-green-600 mt-2">{{ number_format($remaining, 2, ',', ' ') }} ₽</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-600 text-sm font-medium">Сотрудников</div>
                    <div class="text-3xl font-bold text-blue-600 mt-2">{{ $employeeCount }}</div>
                </div>
            </div>

            <!-- Recent Entries -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Последние поступления</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2 px-4">Дата</th>
                                    <th class="text-left py-2 px-4">Источник</th>
                                    <th class="text-left py-2 px-4">Сумма</th>
                                    <th class="text-left py-2 px-4">Админ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCashEntries as $entry)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-2 px-4">{{ $entry->received_at->format('d.m.Y H:i') }}</td>
                                        <td class="py-2 px-4">{{ $entry->source }}</td>
                                        <td class="py-2 px-4 font-semibold">{{ number_format($entry->amount, 2, ',', ' ') }} ₽</td>
                                        <td class="py-2 px-4">{{ $entry->admin->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">Нет поступлений</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                @can('manage-cash')
                    <a href="{{ route('cash.create') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition">
                        <div class="font-semibold">+ Добавить поступление</div>
                    </a>
                @endcan
                @can('manage-employees')
                    <a href="{{ route('employees.index') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition">
                        <div class="font-semibold">Управление сотрудниками</div>
                    </a>
                @endcan
                @can('manage-payroll')
                    <a href="{{ route('payroll.index') }}" class="bg-purple-600 text-white p-4 rounded-lg text-center hover:bg-purple-700 transition">
                        <div class="font-semibold">Зарплата и выплаты</div>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
