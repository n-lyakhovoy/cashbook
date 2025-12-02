<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Поступления наличных (Всего: {{ number_format($totalCash, 2, ',', ' ') }} ₽)
            </h2>
            <a href="{{ route('cash.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + Добавить поступление
            </a>
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
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left py-3 px-4">Дата и время</th>
                                <th class="text-left py-3 px-4">Источник</th>
                                <th class="text-left py-3 px-4">Сумма</th>
                                <th class="text-left py-3 px-4">Администратор</th>
                                <th class="text-left py-3 px-4">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $entry)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4">{{ $entry->received_at->format('d.m.Y H:i') }}</td>
                                    <td class="py-3 px-4">{{ $entry->source }}</td>
                                    <td class="py-3 px-4 font-semibold">{{ number_format($entry->amount, 2, ',', ' ') }} ₽</td>
                                    <td class="py-3 px-4">{{ $entry->admin->name }}</td>
                                    <td class="py-3 px-4">
                                        <form action="{{ route('cash.destroy', $entry) }}" method="POST" class="inline" onsubmit="return confirm('Удалить?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-4 text-center text-gray-500">Нет поступлений</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $entries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
