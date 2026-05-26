<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Casual Leave',              'code' => 'CL',  'default_days' => 10],
            ['name' => 'Sick Leave',                 'code' => 'SL',  'default_days' => 21],
            ['name' => 'Earned Leave',               'code' => 'EL',  'default_days' => 15],
            ['name' => 'Maternity Leave',            'code' => 'ML',  'default_days' => 90],
            ['name' => 'Paternity Leave',            'code' => 'PL',  'default_days' => 15],
            ['name' => 'Annual Leave',               'code' => 'AL',  'default_days' => 21],
            ['name' => 'Emergency Leave',            'code' => 'EML', 'default_days' => 5],
            ['name' => 'Bereavement Leave',          'code' => 'BL',  'default_days' => 5],
            ['name' => 'Compensatory Off',           'code' => 'CO',  'default_days' => 0],
            ['name' => 'Unpaid Leave',               'code' => 'LWP', 'default_days' => 0],
            ['name' => 'Study Leave',                'code' => 'STL', 'default_days' => 10],
            ['name' => 'Public Holiday Leave',       'code' => 'PHL', 'default_days' => 0],
            ['name' => 'Quarantine Leave',           'code' => 'QL',  'default_days' => 14],
        ];

        foreach ($types as $type) {
            LeaveType::firstOrCreate(['code' => $type['code']], $type);
        }
    }
}
