<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@vote.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $voter = User::create([
            'name' => 'Voter',
            'email' => 'voter@vote.local',
            'password' => Hash::make('password'),
            'role' => User::ROLE_VOTER,
            'email_verified_at' => now(),
        ]);

        $activePolls = [
            [
                'title' => 'Улюблена мова програмування',
                'description' => 'Оберіть мову, з якою вам найзручніше працювати щодня.',
                'allow_multiple' => false,
                'options' => ['PHP', 'JavaScript', 'Python', 'Go'],
            ],
            [
                'title' => 'Який фреймворк для бекенду використовуєте?',
                'description' => 'Голосування серед розробників бекенду.',
                'allow_multiple' => true,
                'options' => ['Laravel', 'Symfony', 'Django', 'Express'],
            ],
            [
                'title' => 'Найкращий редактор коду',
                'description' => null,
                'allow_multiple' => false,
                'options' => ['VS Code', 'PhpStorm', 'Vim'],
            ],
        ];

        $createdPolls = [];

        foreach ($activePolls as $data) {
            $poll = Poll::create([
                'user_id' => $admin->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'is_active' => true,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(14),
                'allow_multiple' => $data['allow_multiple'],
            ]);

            foreach ($data['options'] as $i => $text) {
                Option::create([
                    'poll_id' => $poll->id,
                    'text' => $text,
                    'order' => $i + 1,
                ]);
            }

            $createdPolls[] = $poll;
        }

        $finishedPoll = Poll::create([
            'user_id' => $admin->id,
            'title' => 'Підсумки року: подія №1 (завершено)',
            'description' => 'Голосування завершено, результати опубліковано.',
            'is_active' => false,
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subDay(),
            'allow_multiple' => false,
        ]);

        foreach (['Реліз Laravel 13', 'PHP 8.4', 'Next.js 15'] as $i => $text) {
            Option::create([
                'poll_id' => $finishedPoll->id,
                'text' => $text,
                'order' => $i + 1,
            ]);
        }

        $firstPoll = $createdPolls[0];
        Vote::create([
            'user_id' => $voter->id,
            'poll_id' => $firstPoll->id,
            'option_id' => $firstPoll->options()->first()->id,
        ]);

        $secondPoll = $createdPolls[1];
        $secondOptions = $secondPoll->options()->take(2)->get();
        foreach ($secondOptions as $option) {
            Vote::create([
                'user_id' => $voter->id,
                'poll_id' => $secondPoll->id,
                'option_id' => $option->id,
            ]);
        }
    }
}
