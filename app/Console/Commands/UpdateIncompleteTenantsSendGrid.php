<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SendgridModel;

class UpdateIncompleteTenantsSendGrid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendgrid:updateIncompleteTenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza o Agrega contactos a sendgrid en la lista Arrendatarios Incompletos Uhomie';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $sg = new SendgridModel();
        $sg->updateIncompleteTenantsList();
    }
}
