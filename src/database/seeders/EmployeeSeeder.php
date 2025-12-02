<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'last_name' => 'Иванов',
                'first_name' => 'Иван',
                'department' => 'Бухгалтерия',
                'position' => 'Главный бухгалтер',
                'salary' => 50000,
                'priority' => 1,
            ],
            [
                'last_name' => 'Петров',
                'first_name' => 'Петр',
                'department' => 'IT',
                'position' => 'Разработчик',
                'salary' => 70000,
                'priority' => 2,
            ],
            [
                'last_name' => 'Сидоров',
                'first_name' => 'Сергей',
                'department' => 'Продажи',
                'position' => 'Менеджер',
                'salary' => 45000,
                'priority' => 3,
            ],
            [
                'last_name' => 'Кузнецова',
                'first_name' => 'Анна',
                'department' => 'HR',
                'position' => 'Специалист по кадрам',
                'salary' => 40000,
                'priority' => 4,
            ],
            [
                'last_name' => 'Смирнов',
                'first_name' => 'Алексей',
                'department' => 'IT',
                'position' => 'Системный администратор',
                'salary' => 55000,
                'priority' => 5,
            ],
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
