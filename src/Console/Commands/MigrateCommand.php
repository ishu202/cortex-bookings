<?php

declare(strict_types=1);

namespace Cortex\Bookings\Console\Commands;

use Rinvex\Bookings\Console\Commands\MigrateCommand as BaseMigrateCommand;

class MigrateCommand extends BaseMigrateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cortex:migrate:bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Cortex Bookings Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        parent::handle();

        $this->call('migrate', ['--step' => true, '--path' => 'app/cortex/bookings/database/migrations']);
    }
}
