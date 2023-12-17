<?php

namespace Laltu\LaravelMaker;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

class LaravelMaker
{
    /**
     * The CSS paths to include on the dashboard.
     *
     * @var list<string|Htmlable>
     */
    protected array $css = [__DIR__.'/../dist/laravel-maker.css'];

    /**
     * Register or return CSS for the Pulse dashboard.
     *
     * @param  string|Htmlable|list<string|Htmlable>|null  $css
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
                if (($contents = @file_get_contents($css)) === false) {
                    throw new RuntimeException("Unable to load Pulse dashboard CSS path [$css].");
                }

                return $carry."<style>{$contents}</style>".PHP_EOL;
            }
        }, '');
    }

    /**
     * Return the compiled JavaScript from the vendor directory.
     */
    public function js(): string
    {
        if (($content = file_get_contents(__DIR__.'/../dist/laravel-maker.js')) === false) {
            throw new RuntimeException('Unable to load the Pulse dashboard JavaScript.');
        }

        return "<script>{$content}</script>".PHP_EOL;
    }
}
