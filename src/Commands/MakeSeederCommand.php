<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeSeederCommand extends SeederMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:seeder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        parent::handle();
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable")'];
        $options[] = ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'];

        return $options;
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/'))) ? $customPath : __DIR__ . $stub;
    }

}
