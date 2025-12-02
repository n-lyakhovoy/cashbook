<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                + Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                                <th class="text-left py-3 px-4">ФИО</th>
                                <th class="text-left py-3 px-4">Отдел</th>
                                <th class="text-left py-3 px-4">Должность</th>
                                <th class="text-left py-3 px-4">Оклад</th>
                                <th class="text-left py-3 px-4">Приоритет</th>
                                <th class="text-left py-3 px-4">Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3 px-4 font-semibold">{{ $employee->last_name }} {{ $employee->first_name }}</td>
                                    <td class="py-3 px-4">{{ $employee->department ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $employee->position ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ number_format($employee->salary, 2, ',', ' ') }} ₽</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded">
                                            {{ $employee->priority }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 flex gap-2">
                                        <a href="{{ route('employees.edit', $employee) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Confirm delete?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">No employees found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $employees->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
