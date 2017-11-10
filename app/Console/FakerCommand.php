<?php

namespace App\Console;

use App\Models\Promotion;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Console\Command;

class FakerCommand extends Command
{
    /**
     * @var string
     * {argument : The ID of the user}
     * {--queue= : Whether the job should be queued}
     */
    protected $signature = 'faker:rune';

    /**
     * @var string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected $description;

    /**
     * CloneCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * run command
     */
    public function fire()
    {
        $faker = Factory::create();

        $i = 0;
        while($i < 100) {

            try {

                $title = $faker->text(rand(30, 50));
                Promotion::create([
                    'title' => $title,
                    'slug' => str_slug($title),
                    'content' => $faker->text(1000),
                    'start_date' => (new Carbon())->format('Y-m-d H:i:s'),
                    'end_date' => (new Carbon())->format('Y-m-d H:i:s'),
                    'status' => 'enable',
                ]);

            } catch (\Exception $e) {
                dd($e);
            }

            $i++;
        }

        $this->info('Done!');
    }
}