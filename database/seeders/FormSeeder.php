<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $formType = \App\Models\FormType::create([
            'id' => '01hxzjzjzjzjzjzjzjzjzjzj6', // Acknowledgement Form Type
            'title' => 'Acknowledgement Form'
        ]);

        $formType->forms()->create([
            'id' => '01hxzjzjzjzjzjzjzjzjzjzj7', // Standard Acknowledgement Form
            'title' => 'Standard acknowledgement', 
            'status' => 1
        ]);
    }
}
