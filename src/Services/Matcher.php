<?php

namespace Laltu\LaravelMaker\Services;

/**
 * Class Matcher
 * @package Laltu\LaravelMaker\Services
 */
class Matcher
{
    /**
     * @var array The constraints for matching.
     */
    protected array $constraints;

    /**
     * Matcher constructor.
     *
     * @param array $constraints The constraints for matching.
     */
    public function __construct(array $constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * Match the given item against the constraints.
     *
     * @param array $item The item to match against.
     *
     * @return bool True if the item matches the constraints, false otherwise.
     */
    public function match(array $item): bool
    {
        foreach ($this->constraints as $key => $expectedValue) {
            if (!array_key_exists($key, $item)) {
                return false;
            }
            if ($item[$key] !== $expectedValue) {
                return false;
            }
        }
        return true;
    }

    /**
     * Create a new Matcher instance with the provided constraints.
     *
     * @param array $compareWith The constraints to use for matching.
     *
     * @return Matcher The new Matcher instance.
     */
    public static function factory(array $compareWith): Matcher
    {
        return new self($compareWith);
    }
}
