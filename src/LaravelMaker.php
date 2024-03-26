<?php

namespace Laltu\LaravelMaker;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

class LaravelMaker
{

    protected array $css = [__DIR__.'/../dist/laravel-maker.css'];

    /**
     * Register or return CSS for the Pulse dashboard.
     *
     * @param string|Htmlable|array|null $css
     * @return string|LaravelMaker
     */
    public function css(string|Htmlable|array|null $css = null): string|self
    {
        if (func_num_args() === 1) {
            $this->css = array_values(array_unique(array_merge($this->css, Arr::wrap($css))));

            return $this;
        }

        return collect($this->css)->reduce(function ($carry, $css) {
            if ($css instanceof Htmlable) {
                return $carry.Str::finish($css->toHtml(), PHP_EOL);
            } else {
                $contents = $this->loadCssContent($css);
                return $carry."<style>$contents</style>".PHP_EOL;
            }
        }, '');
    }

    /**
     * Load CSS content from the given file path.
     *
     * @param string $filePath
     * @return string
     * @throws RuntimeException if unable to load the file
     */
    private function loadCssContent(string $filePath): string
    {
        if (($contents = @file_get_contents($filePath)) === false) {
            throw new RuntimeException("Unable to load Pulse dashboard CSS path [$filePath].");
        }
        return $contents;
    }

    /**
     * Return the compiled JavaScript from the vendor directory.
     */
    public function js(): string
    {
        if (($content = file_get_contents(__DIR__.'/../dist/laravel-maker.js')) === false) {
            throw new RuntimeException('Unable to load the Pulse dashboard JavaScript.');
        }

        return "<script>$content</script>".PHP_EOL;
    }
}
