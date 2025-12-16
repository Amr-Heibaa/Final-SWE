<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Size;
use App\Enums\SizeEnum;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SizeEnum::cases() as $case) {
            Size::updateOrCreate(
                ['name' => $case->value],
                ['sort_order' => $case->sortOrder()]
            );
        }
    }
}
