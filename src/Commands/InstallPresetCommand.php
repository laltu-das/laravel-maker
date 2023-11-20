<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use function Laravel\Prompts\multiselect;

class InstallPresetCommand extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-maker-preset:install {stack : The development stack that should be installed (react,vue)}
                            {--typescript : Indicates if TypeScript is preferred for the Inertia stack (Experimental)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Breeze controllers and resources';

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules(): void
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle(): ?int
    {
        if ($this->argument('stack') === 'vue') {
            return $this->installInertiaVueStack();
        } elseif ($this->argument('stack') === 'react') {
            return $this->installInertiaReactStack();
        }

        $this->components->error('Invalid stack. Supported stacks are [blade], [livewire], [livewire-functional], [react], [vue], and [api].');

        return 1;
    }

    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaVueStack()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                    "@inertiajs/vue3" => "^1.0.14",
                    "@tailwindcss/forms" => "^0.5.7",
                    "@vitejs/plugin-vue" => "^4.5.0",
                    "@vue/server-renderer" => "^3.3.8",
                    "@vueuse/core" => "^10.6.1",
                    "autoprefixer" => "^10.4.16",
                    "classnames" => "^2.3.2",
                    "floating-vue" => "^2.0.0-beta.24",
                    "laravel-vite-plugin" => "^0.8.1",
                    "lodash-es" => "^4.17.21",
                    "postcss" => "^8.4.31",
                    "tailwind-merge" => "^2.0.0",
                    "tailwindcss" => "^3.3.5",
                    "vite" => "^5.0.0",
                    "vue" => "^3.3.8",
                    "laravel-precognition-vue-inertia" => "^0.5.2"
                ] + $packages;
        });

//        // NPM Packages...
//        $this->updateNodePackages(function ($packages) {
//            return [
//                    "laravel-precognition-vue-inertia" => "^0.5.2"
//                ] + $packages;
//        }, false);

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-vue/resources/js/Pages', resource_path('js/Pages'));
        }

        // Tailwind / Vite...
//        copy(__DIR__ . '/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
//        copy(__DIR__ . '/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
//        copy(__DIR__ . '/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
//        copy(__DIR__ . '/../../stubs/inertia-vue/vite.config.js', base_path('vite.config.js'));

        $this->components->info('Installing and building Node dependencies.');

        $this->runCommands(['npm install', 'npm run build']);

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }

    /**
     * Update the "package.json" file.
     *
     * @param callable $callback
     * @param bool $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, bool $dev = true): void
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    /**
     * Run the given commands.
     *
     * @param array $commands
     * @return void
     */
    protected function runCommands($commands): void
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> ' . $e->getMessage() . PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    ' . $line);
        });
    }

    /**
     * Install the Inertia React Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaReactStack()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                    '@headlessui/react' => '^1.4.2',
                    '@inertiajs/react' => '^1.0.0',
                    '@tailwindcss/forms' => '^0.5.3',
                    '@vitejs/plugin-react' => '^4.0.3',
                    'autoprefixer' => '^10.4.12',
                    'postcss' => '^8.4.18',
                    'tailwindcss' => '^3.2.1',
                    'react' => '^18.2.0',
                    'react-dom' => '^18.2.0',
                ] + $packages;
        });

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__ . '/../../stubs/inertia-react/resources/js/Pages', resource_path('js/Pages'));
        }


        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        $stack = $input->getArgument('stack');

        if (in_array($stack, ['react', 'vue'])) {
            collect(multiselect(
                label: 'Would you like any optional features?',
                options: [
                    'typescript' => 'TypeScript (experimental)',
                ]
            ))->each(fn($option) => $input->setOption($option, true));
        }

    }

}