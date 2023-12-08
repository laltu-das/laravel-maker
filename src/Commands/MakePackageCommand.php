<?php

namespace Laltu\LaravelMaker\Commands;

use Exception;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakePackageCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:package
                            {name : The vendor name part of the namespace}
                            {vendor : The vendor name part of the namespace}
                            {package : The name of package for the namespace}
                            {description : The description of package for the namespace}
                            {author_name : The author name for the namespace}
                            {author_email : The author email for the namespace}
                            {keywords : The keywords for the package}
                            {require_package : The require packages for the package}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new package.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Package';


    /**
     * Execute the console command.
     *
     * @return void
     * @throws FileNotFoundException|FileNotFoundException
     */
    public function handle(): void
    {
        parent::handle();

        $package = $this->argument('package');
        $vendor = Str::kebab($this->argument('vendor'));

        $packageFolderName = Str::kebab($package);

        $relPackagePath = "packages/$packageFolderName";
        $packagePath = base_path($relPackagePath);

        try {
            $this->makeDirectory($packagePath);
            $this->copySkeleton($packagePath);
            $this->initRepo($packagePath);
            $this->registerPackage($vendor, Str::kebab($this->argument('package')), $relPackagePath);
            $this->composerUpdatePackage($vendor, $package);
            $this->composerDumpAutoload();

            $this->info('Finished. Are you ready to write awesome package.');

        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Copy skeleton to package folder.
     *
     * @param string $packagePath
     */
    protected function copySkeleton(string $packagePath): void
    {
        echo File::copyDirectory(__DIR__ . '/../../stubs/package/preset', $packagePath);
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return string|array|bool
     */
    public function getStubContents($stub, array $stubVariables = []): string|array|bool
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace) {
            $contents = preg_replace("/\{\{\s*$search\s*\}\}/", $replace, $contents);
        }

        return $contents;

    }

    /**
     **
     * Map the stub variables present in stub to its value
     *
     * @return array
     *
     */
    public function getStubVariables(): array
    {
        $namespace = Str::ucfirst(Str::kebab($this->argument('vendor'))) . '\\' . Str::ucfirst(Str::studly($this->argument('package')));

        return [
            'phpVersion' => "^8.1",
            'packageLicense' => "MIT",
            'vendor' => Str::kebab($this->argument('vendor')),
            'vendorNamespace' => Str::ucfirst(Str::kebab($this->argument('vendor'))),
            'package' => Str::kebab($this->argument('package')),
            'packageNamespace' => Str::ucfirst(Str::studly($this->argument('package'))),
            'packageName' => Str::lower($this->argument('vendor')) . "/" . Str::kebab($this->argument('package')),
            'packageDescription' => $this->argument('description'),
            'packageKeywords' => $this->argument('keywords'),
            'serviceProviderClassName' => Str::ucfirst(Str::studly($this->argument('package'))) . 'ServiceProvider',
            'namespace' => $namespace,
            'facadeName' => Str::ucfirst(Str::studly($this->argument('package'))),
            'facadeNamespace' => $namespace . '\\' . 'Facade',
            'year' => date('Y'),
            'authorName' => $this->argument('author_name'),
            'authorEmail' => $this->argument('author_email'),
            'githubPackageUrl' => "https://github.com/" . $this->argument('vendor') . "/" . $this->argument('vendor'),
        ];
    }

    /**
     * Copy source file to destination with needed directories creating.
     *
     * @param string $src
     * @param string $dest
     */
    protected function copyFileWithDirCreating(string $src, string $dest): void
    {
        $dirPathOfDestFile = dirname($dest);

        if (!File::exists($dirPathOfDestFile)) {
            File::makeDirectory($dirPathOfDestFile, 0755, true);
        }

        if (!File::exists($dest)) {
            File::copy($src, $dest);
        }
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): array
    {
        return [
            base_path('/stubs/package/package.composer.stub'),
            base_path('/stubs/package/package.readme.stub'),
            base_path('/stubs/package/package.license.stub'),
            base_path('/stubs/package/package.provider.stub'),
            base_path('/stubs/package/package.facade.stub'),
            base_path('/stubs/package/package.class.stub'),
            base_path('/stubs/package/package.config.stub'),
        ];
    }

    /**
     * Init git repo.
     * @param string $repoPath
     */
    protected function initRepo(string $repoPath): void
    {
        $command = "git init $repoPath";
        $this->info("Run \"$command\".");

        $output = [];
        exec($command, $output, $returnStatusCode);

//        if ($returnStatusCode !== 0) {
//            throw RuntimeException::commandExecutionFailed(
//                $command, $returnStatusCode
//            );
//        }

        $this->info("\"$command\" was successfully ran.");
    }

    /**
     * Register package in composer.json.
     *
     * @param $vendor
     * @param $package
     * @param $relPackagePath
     *
     * @throws RuntimeException|FileNotFoundException
     */
    protected function registerPackage($vendor, $package, $relPackagePath): void
    {
        $this->info('Register package in composer.json.');

        $composerJson = $this->loadComposerJson();

        if (!isset($composerJson['repositories'])) {
            Arr::set($composerJson, 'repositories', []);
        }

        $filtered = array_filter($composerJson['repositories'], function ($repository) use ($relPackagePath) {
            return $repository['type'] === 'path'
                && $repository['url'] === $relPackagePath;
        });

        if (count($filtered) === 0) {
            $this->info('Register composer repository for package.');

            $composerJson['repositories'][] = (object)[
                'type' => 'path',
                'url' => $relPackagePath,
                'options' => [
                    'symlink' => true
                ]
            ];
        } else {
            $this->info('Composer repository for package is already registered.');
        }

        Arr::set($composerJson, "require.$vendor/$package", 'dev-main');

        $this->saveComposerJson($composerJson);

        $this->info('Package was successfully registered in composer.json.');
    }

    /**
     * Load and parse content of composer.json.
     *
     * @return array
     *
     * @throws FileNotFoundException
     * @throws RuntimeException
     */
    protected function loadComposerJson(): array
    {
        $composerJsonPath = $this->getComposerJsonPath();

        if (!File::exists($composerJsonPath)) {
            throw new FileNotFoundException('composer.json does not exist');
        }

        $composerJsonContent = File::get($composerJsonPath);
        $composerJson = json_decode($composerJsonContent, true);

        if (!is_array($composerJson)) {
            throw new RuntimeException("Invalid composer.json file [$composerJsonPath]");
        }

        return $composerJson;
    }

    /**
     * Get composer.json path.
     *
     * @return string
     */
    protected function getComposerJsonPath(): string
    {
        return base_path('composer.json');
    }

    /**
     * @param array $composerJson
     *
     * @throws RuntimeException
     */
    protected function saveComposerJson(array $composerJson): void
    {
        $newComposerJson = json_encode(
            $composerJson,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        $composerJsonPath = $this->getComposerJsonPath();
        if (File::put($composerJsonPath, $newComposerJson) === false) {
            throw new RuntimeException("Cannot write to composer.json [$composerJsonPath]");
        }
    }

    /**
     * Unregister package from composer.json.
     *
     * @param $vendor
     * @param $package
     * @param $relPackagePath
     * @throws FileNotFoundException
     */
    protected function unregisterPackage($vendor, $package, $relPackagePath): void
    {
        $this->info('Unregister package from composer.json.');

        $composerJson = $this->loadComposerJson();

        unset($composerJson['require']["$vendor\\$package\\"]);

        $repositories = array_filter($composerJson['repositories'], function ($repository) use ($relPackagePath) {
            return $repository['type'] !== 'path'
                || $repository['url'] !== $relPackagePath;
        });

        $composerJson['repositories'] = $repositories;

        if (count($composerJson['repositories']) === 0) {
            unset($composerJson['repositories']);
        }

        $this->saveComposerJson($composerJson);

        $this->info('Package was successfully unregistered from composer.json.');
    }

    /**
     * Run "composer dump-autoload".
     */
    protected function composerDumpAutoload(): void
    {
        $this->composerRunCommand('composer dump-autoload');
    }

    /**
     * Run arbitrary composer command.
     *
     * @param $command
     */
    protected function composerRunCommand($command): void
    {
        $this->info("Run \"$command\".");

        $output = [];
        exec($command, $output, $returnStatusCode);

        if ($returnStatusCode !== 0) {
            throw RuntimeException::commandExecutionFailed($command, $returnStatusCode);
        }

        $this->info("\"$command\" was successfully ran.");
    }

    /**
     * Run "composer update $vendor/$package".
     *
     * @param string $vendor
     * @param string $package
     */
    protected function composerUpdatePackage(string $vendor, string $package): void
    {
        $this->composerRunCommand("composer update --ignore-platform-reqs $vendor/$package");
    }

    /**
     * Run "composer remove $vendor/$package".
     *
     * @param string $vendor
     * @param string $package
     */
    protected function composerRemovePackage(string $vendor, string $package): void
    {
        $this->composerRunCommand("composer remove --ignore-platform-reqs $vendor/$package");
    }
}
