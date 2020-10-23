<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\SendgridModel;

class UpdateCompleteTenantsSendGrid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendgrid:updateCompleteTenants';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza o Agrega contactos a sendgrid en la lista Arrendatarios Completos Uhomie';

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
        $sg->updateCompleteTenantsList();
    }
}
