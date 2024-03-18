<?php

namespace Laltu\LaravelMaker\Support;

class VueComponentEnhancer
{

    protected array $options = [];

    public static function createComponent(): VueComponentEnhancer
    {
        return new self();
    }

    /**
     * Generates script parts dynamically based on a template and parameters.
     */
    protected function generateScriptPart(string $template, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $template = str_replace("{{{$key}}}", is_array($value) ? json_encode($value) : $value, $template);
        }
        return $template;
    }

    /**
     * Generates the defineProps part of the script with dynamic parameters.
     */
    protected function withProp(array $props): string
    {
        $propsString = implode("\n    ", array_map(function ($name, $prop) {
            return "{$name}: " . json_encode($prop) . ",";
        }, array_keys($props), $props));

        return $this->generateScriptPart("const props = defineProps({{\n    propsString\n}});", [
            'propsString' => $propsString
        ]);
    }

    /**
     * Generates the ref part of the script dynamically.
     */
    protected function generateRef(string $refName, $defaultValue): string
    {
        return $this->generateScriptPart("let {{refName}} = ref({{defaultValue}});", [
            'refName' => $refName,
            'defaultValue' => $defaultValue
        ]);
    }

    /**
     * Generates the watch part of the script dynamically.
     */
    protected function generateWatch(string $watchedVariable, string $callbackFunction): string
    {
        return $this->generateScriptPart("watch({{watchedVariable}}, (newValue, oldValue) => {\n    {{callbackFunction}}\n});", [
            'watchedVariable' => $watchedVariable,
            'callbackFunction' => $callbackFunction
        ]);
    }

    /**
     * Generates the onMounted part of the script dynamically.
     */
    protected function generateOnMounted(string $onMountedBody): string
    {
        return $this->generateScriptPart("onMounted(() => {\n    {{onMountedBody}}\n});", [
            'onMountedBody' => $onMountedBody
        ]);
    }

    /**
     * Generates the onBeforeMount part of the script dynamically.
     */
    protected function generateOnBeforeMount(string $body): string
    {
        return $this->generateScriptPart("onBeforeMount(() => {\n    {{body}}\n});", ['body' => $body]);
    }

    /**
     * Generates the onBeforeUpdate part of the script dynamically.
     */
    protected function generateOnBeforeUpdate(string $body): string
    {
        return $this->generateScriptPart("onBeforeUpdate(() => {\n    {{body}}\n});", ['body' => $body]);
    }

    /**
     * Generates the deleteData function part of the script dynamically.
     */
    protected function generateDeleteData(string $deleteFunctionBody): string
    {
        return $this->generateScriptPart("const deleteData = (id) => {\n    {{deleteFunctionBody}}\n};", [
            'deleteFunctionBody' => $deleteFunctionBody
        ]);
    }

    protected function generateMethodScript(string $methodName, string $methodBody): string
    {
        return $this->generateScriptPart("const {{methodName}} = () => {\n    {{methodBody}}\n};", [
            'methodName' => $methodName,
            'methodBody' => $methodBody
        ]);
    }

    protected function generateDataMethod(array $dataItems): string
    {
        $items = implode(',', array_map(fn($item) => "'$item'", $dataItems));
        return "data() { return { $items }; },";
    }

    protected function generateComputedProperty(string $propertyName, string $propertyLogic): string
    {
        return $this->generateScriptPart("get {{propertyName}}() {\n    {{propertyLogic}}\n },", [
            'propertyName' => $propertyName,
            'propertyLogic' => $propertyLogic
        ]);
    }


    /**
     * Combines all parts to generate the final Vue script with dynamic parameters.
     */
    public function appendVueScriptToStub(array $options): string
    {
        $vueScriptParts = [
            $this->withProp($options['props'] ?? []),
            $this->generateRef($options['refName'] ?? 'refVariable', $options['refDefaultValue'] ?? "''"),
            $this->generateWatch($options['watchedVariable'] ?? 'refVariable', $options['watchCallback'] ?? ''),
            $this->generateComputedProperty($options['computedPropertyName'] ?? '', $options['computedPropertyLogic'] ?? ''),
            $this->generateMethodScript($options['scriptMethodName'] ?? '', $options['scriptMethodBody'] ?? ''),
            $this->generateDataMethod($options['dataItems'] ?? []),
            $this->generateOnMounted($options['onMountedBody'] ?? ''),
            $this->generateOnBeforeMount($options['onBeforeMountBody'] ?? ''),
            $this->generateOnBeforeUpdate($options['onBeforeUpdateBody'] ?? ''),
            $this->generateDeleteData($options['deleteFunctionBody'] ?? ''),
        ];

        return implode("\n", $vueScriptParts);
    }
}