<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'student_id' => 'SAL/' . $this->faker->year . '/' . $this->faker->unique()->numerify('###'),
            'full_name' => $this->faker->name,
            'faculty' => $this->faker->randomElement([
                'Faculty of Computing',
                'Faculty of Business',
                'Faculty of Engineering',
                'Faculty of Natural Sciences',
                'Faculty of Agriculture',
                'Faculty of Social Sciences',
            ]),
            'department' => $this->faker->word,
            'year' => $this->faker->numberBetween(1, 6),
            'semester' => $this->faker->randomElement(['First', 'Second', 'Summer']),
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'photo' => null,
        ];
    }
}