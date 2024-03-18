<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('make:package', 'Create a new Laravel package')]
class MakePackageCommand extends Command implements PromptsForMissingInput
{
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The package name.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['vendor', null, InputOption::VALUE_REQUIRED, 'Vendor name'],
            ['keywords', null, InputOption::VALUE_REQUIRED, 'Package keywords (comma-separated)'],
            ['php-version', null, InputOption::VALUE_REQUIRED, 'Required PHP version for the package'],
            ['external-package', null, InputOption::VALUE_NONE, 'Include external packages in composer.json'],
        ];
    }

    public function handle(): void
    {
        $packageName = $this->argument('name');
        $vendorName = $this->option('vendor');
        $keywords = $this->option('keywords');
        $phpVersion = $this->option('php-version');
        $includeExternalPackages = $this->option('external-package');

        $studlyName = Str::studly($packageName);
        $basePath = base_path("packages/{$studlyName}");
        $this->setupPackageStructure($basePath, $vendorName, $packageName, $studlyName, $keywords, $phpVersion, $includeExternalPackages);

        $this->info("Package {$packageName} created successfully.");
    }

    protected function setupPackageStructure($basePath, $vendorName, $packageName, $studlyName, $keywords, $phpVersion, $includeExternalPackages): void
    {
        $this->makeDirectories($basePath);
        $this->createBaseFiles($basePath, $packageName, $studlyName);
        $this->createComposerJson($basePath, $vendorName, $packageName, $studlyName, $keywords, $phpVersion, $includeExternalPackages);
        $this->createGitHubWorkflows($basePath);

        $this->createFromStub($basePath, $studlyName, 'Facade');
        $this->createFromStub($basePath, $studlyName, 'ServiceProvider');
    }

    protected function makeDirectories($basePath): void
    {
        $directories = ['', '.github/workflows', 'config', 'src'];
        foreach ($directories as $dir) {
            $fullPath = "{$basePath}/{$dir}";
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                $this->info("Created directory: {$fullPath}");
            }
        }
    }

    protected function createBaseFiles($basePath, $packageName, $studlyName): void
    {
        $files = [
            'CHANGELOG.md' => "# Changelog\n\nAll notable changes to `{$packageName}` will be documented in this file.",
            '.styleci.yml' => "preset: laravel",
            'LICENSE.md' => "MIT License\n\nCopyright (c) [year] [fullname]",
            'README.md' => "# {$studlyName}\n\nA brief description of {$studlyName}.",
            'CONTRIBUTING.md' => "# Contributing\n\nYour contributions are always welcome!",
            'config/config.php' => "<?php\n\nreturn [\n    // Configurations\n];",
        ];

        foreach ($files as $file => $content) {
            file_put_contents("{$basePath}/{$file}", $content);
            $this->info("Created file: {$file}");
        }
    }

    protected function createComposerJson($basePath, $vendorName, $packageName, $studlyName, $keywords, $phpVersion, $includeExternalPackages): void
    {
        // Define the composer.json structure
        $composerContent = [
            "name" => "{$vendorName}/$packageName",
            "description" => "Secure your laravel routes with otps. (one time passwords)",
            "keywords" => explode(',', $keywords),
            "homepage" => "https://github.com/{$vendorName}/$packageName",
            "license" => "MIT",
            "type" => "library",
            "authors" => [
                [
                    "name" => "laltu das",
                    "email" => "laltu.lspl@gmail.com",
                    "role" => "Developer"
                ]
            ],
            "php" => $phpVersion,
            "require" => $includeExternalPackages ? ["php" => "^8.0"] : [],
            "require-dev" => [
                "orchestra/testbench" => "^6.0",
                "phpunit/phpunit" => "^9.0"
            ],
            "autoload" => [
                "psr-4" => [
                    "{$vendorName}\\{$packageName}\\" => "src/"
                ]
            ],
            "autoload-dev" => [
                "psr-4" => [
                    "{$vendorName}\\{$packageName}\\Tests\\" => "tests/"
                ]
            ],
            "scripts" => [
                "test" => "vendor/bin/phpunit",
                "test-coverage" => "vendor/bin/phpunit --coverage-html coverage"
            ],
            "config" => [
                "sort-packages" => true
            ],
            "extra" => [
                "laravel" => [
                    "providers" => [
                        "{$vendorName}\\{$packageName}\\{$packageName}ServiceProvider"
                    ],
                    "aliases" => [
                        "{$packageName}" => "{$vendorName}\\{$packageName}\\{$packageName}Facade"
                    ]
                ]
            ]
        ];

        // Write the composer.json file
        file_put_contents("{$basePath}/composer.json", json_encode($composerContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info("Created composer.json with provided structure.");
    }


    protected function createGitHubWorkflows($basePath): void
    {
        $workflowContent = "name: CI\n\non: [push]\n\njobs:\n  test:\n    runs-on: ubuntu-latest\n\n    steps:\n    - uses: actions/checkout@v2\n    - name: Run Tests\n      run: vendor/bin/phpunit";
        file_put_contents("{$basePath}/.github/workflows/ci.yml", $workflowContent);
        $this->info("Created GitHub workflow: ci.yml");
    }

    protected function createFromStub($basePath, $studlyName, $type): void
    {
        $stubTypeMap = [
            'Facade' => 'facade',
            'ServiceProvider' => 'service-provider',
        ];

        $stubFileName = $stubTypeMap[$type] ?? null;
        if (!$stubFileName) {
            $this->error("Invalid stub type: {$type}");
            return;
        }

        $stubPath = $this->resolveStubPath("/stubs/package/{$stubFileName}.stub");
        if (!file_exists($stubPath)) {
            $this->error("Stub file does not exist: {$stubPath}");
            return;
        }

        $content = file_get_contents($stubPath);
        $content = str_replace(
            ['DummyNamespace', 'DummyClass', 'dummy-lower'],
            [$studlyName, "{$studlyName}{$type}", strtolower($studlyName)],
            $content
        );

        $filePath = "{$basePath}/src/{$studlyName}{$type}.php";
        file_put_contents($filePath, $content);

        $this->info("Created {$type}: " . basename($filePath));
    }


    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
    }

    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if (!$input->getOption('vendor')) {
            $vendorName = $this->ask('Vendor name:');
            $input->setOption('vendor', $vendorName);
        }

        if (!$input->getOption('keywords')) {
            $keywords = $this->ask('Package keywords (comma-separated):');
            $input->setOption('keywords', $keywords);
        }

        if (!$input->getOption('php-version')) {
            $phpVersion = $this->ask('Required PHP version for the package:');
            $input->setOption('php-version', $phpVersion);
        }
    }

}
