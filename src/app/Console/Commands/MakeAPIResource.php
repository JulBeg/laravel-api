<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class MakeAPIResource extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:api-resource {name : The name of the API resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API resource';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->info("Creating API resource: {$name}");

        $this->call('make:model', [
            'name' => $name,
            '--controller' => true,
            '--factory' => true,
            '--migration' => true,
            '--resource' => true,
            '--requests' => true,
            '--seed' => true,            
            '--api' => true,
            '--test' => true,
        ]);

        $this->call('make:resource', [
            'name' => $name . 'Resource',
        ]);
        
        $this->info("API resource created successfully: {$name}");
    }
}
