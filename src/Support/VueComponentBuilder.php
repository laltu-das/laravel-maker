<?php

namespace Laltu\LaravelMaker\Support;

class VueComponentBuilder
{
    protected array $scriptParts = [];
    protected array $propsDefinitions = [];

    public static function createComponent(): VueComponentBuilder
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
     * Adds script logic directly for complex scenarios.
     */
    public function addRawScript(string $script): VueComponentBuilder
    {
        $this->scriptParts[] = $script;
        return $this;
    }


    public function withProp(string $name, array $prop): VueComponentBuilder
    {
        $this->propsDefinitions[$name] = $prop;
        return $this;
    }

    public function preparePropsScript(): string
    {
        $propsString = implode(",\n", array_map(function ($name, $prop) {
            $propDefinition = json_encode($prop, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            if (isset($prop['default']) && is_callable($prop['default'])) {
                $defaultFunctionBody = $prop['default']();
                unset($prop['default']);
                return "$name: " . trim($propDefinition, '}') . ", default: $defaultFunctionBody }";
            } else {
                return "$name: " . $propDefinition;
            }
        }, array_keys($this->propsDefinitions), $this->propsDefinitions));

        return "const props = defineProps({\n$propsString\n});";
    }

    /**
     * Adds a ref to the component.
     */
    public function withRef(string $refName, $defaultValue): VueComponentBuilder
    {
        $this->scriptParts[] = $this->generateScriptPart("const {{refName}} = ref({{defaultValue}});", [
            'refName' => $refName,
            'defaultValue' => json_encode($defaultValue)
        ]);

        return $this;
    }

    /**
     * Adds a watch to the component.
     */
    public function withWatch(string $watchedVariable, ?string $watchParams, mixed $callbackFunction): VueComponentBuilder
    {
        // Check if callbackFunction is callable, execute it to get the string representation of the callbackFunction
        if (is_callable($callbackFunction)) {
            $callbackFunction = call_user_func($callbackFunction);
        }

        $this->scriptParts[] = $this->generateScriptPart("watch({{watchedVariable}}, ($watchParams) => {\n    {{callbackFunction}}\n});", [
            'watchedVariable' => $watchedVariable,
            'callbackFunction' => $callbackFunction,
            'watchParams' => $watchParams !== null ? "($watchParams)" : '', // Include the watchParams if provided
        ]);

        return $this;
    }




    /**
     * Enhances the method to include parameters and support a dynamic method body.
     *
     * @param string $methodName Name of the method to add.
     * @param string $methodParams String containing the method parameters.
     * @param mixed $methodBody The body of the method, either a string or a callable that returns a string.
     * @return VueComponentBuilder
     */
    public function withMethod(string $methodName, string $methodParams, mixed $methodBody): VueComponentBuilder
    {
        // Check if methodBody is a callable, execute it to get the string representation of the method body
        if (is_callable($methodBody)) {
            $methodBody = call_user_func($methodBody);
        }

        $this->scriptParts[] = $this->generateScriptPart("const {{methodName}} = ({{methodParams}}) => {\n    {{methodBody}}\n};", [
            'methodName' => $methodName,
            'methodParams' => $methodParams,
            'methodBody' => $methodBody
        ]);

        return $this;
    }

    /**
     * Adds an event handler to the component.
     */
    public function withEventHandler(string $eventName, string $handlerBody): VueComponentBuilder
    {
        // Assuming an example implementation, adjust as needed
        $this->scriptParts[] = $this->generateScriptPart("const handle{{eventName}} = () => {\n    {{handlerBody}}\n};", [
            'eventName' => ucfirst($eventName),
            'handlerBody' => $handlerBody
        ]);

        return $this;
    }

    /**
     * Adds a computed property to the component.
     */
    public function withComputedProperty(string $propertyName, string $propertyLogic): VueComponentBuilder
    {
        $this->scriptParts[] = $this->generateScriptPart("const {{propertyName}} = computed(() => {\n    {{propertyLogic}}\n});", [
            'propertyName' => $propertyName,
            'propertyLogic' => $propertyLogic
        ]);

        return $this;
    }

    /**
     * Adds data properties to the component.
     */
    public function withData(array $data): VueComponentBuilder
    {
        $dataString = json_encode($data);

        $this->scriptParts[] = "const data = reactive($dataString);";

        return $this;
    }

    /**
     * Combines all parts to generate the final Vue script with dynamic parameters.
     */
    public function build(): string
    {
        // Generate the prop script and include it directly in the build output
        $propsScript = $this->preparePropsScript();

        // Include the prop script as the first part of the final script
        return $propsScript . "\n" . implode("\n", $this->scriptParts);
    }
}
