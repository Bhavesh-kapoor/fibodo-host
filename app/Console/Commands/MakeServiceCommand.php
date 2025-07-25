<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service';

    /**
     * Create a new instance of the command.
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
        $name = $this->argument('name');
        $filesystem = new Filesystem();

        // Create the service file
        $servicePath = app_path('Services/' . $name . '.php');
        $filesystem->makeDirectory(dirname($servicePath), 0755, true, true);
        $filesystem->put($servicePath, $this->getServiceStub());


        $this->info('Service created successfully!');
    }

    /**
     * Get the service stub.
     *
     * @return string
     */
    protected function getServiceStub()
    {
        return str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ['App\Services', $this->argument('name')],
            file_get_contents(__DIR__ . '/stubs/service.stub')
        );
    }

    /**
     * Get the interface stub.
     *
     * @return string
     */
    protected function getInterfaceStub()
    {
        return str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ['App\Services', $this->argument('name')],
            file_get_contents(__DIR__ . '/stubs/interface.stub')
        );
    }
}
