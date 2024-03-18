<?php

namespace Laltu\LaravelMaker\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VueTableBuilder
{
    public string $model;
    public array $fields;
    public string $routeName;

    public Collection $imports;

    public function __construct($model, array $fields, $routeName)
    {
        $this->model = $model;
        $this->fields = $fields;
        $this->routeName = $routeName;

        $this->imports = new Collection();
    }

    public function generateScriptSetupContent(): string
    {
        // Revised example usage
        return VueComponentBuilder::createComponent()
                ->withProp('filters', ['type' => 'Object', 'default' => '() => ({})']) // Default as a function
                ->withProp('customers', ['type' => 'Object', 'required' => true])
                ->withRef('search', "props.filters.search || ''")
                ->withWatch('search', 'value', function () {
                    return "router.visit(route('admin.product.edit', product.id), {
                                method: 'get',
                                data: {searchMedia: value},
                                replace: false,
                                preserveState: true,
                                preserveScroll: true,
                                only: ['images']
                            })";
                })
                ->withMethod('deleteData', "id", function () {
                    return "if(confirm('Are you sure you want to delete this data?')) {
                                router.delete(route('admin.customer.destroy', id), {
                                    replace: true,
                                    onSuccess: (page) => alert('Successfully deleted'),
                                });
                            }";
                })
                ->build();
    }

    public function setImportMethod(array $additionalImports = []): void
    {
        foreach ($additionalImports as $import => $from) {
            $this->imports->put($import, $from);
        }
    }

    public function getDynamicImports(): string
    {
        $this->setImportMethod([
            "AdminLayout" => '@/Layouts/AdminLayout.vue',
            "TableHead" => '@/Components/TableHead.vue',
            "TableBody" => '@/Components/TableBody.vue',
            "TableRow" => '@/Components/TableRow.vue',
            "Pagination" => '@/Components/Pagination.vue',
            "LinkButton" => '@/Components/Button/LinkButton.vue',
            "DeleteButton" => '@/Components/Button/DeleteButton.vue',
        ]);

        return $this->imports->map(function ($from, $import) {
            return "import $import from '$from';";
        })->implode("\n");
    }

    public function generateTableColumns(): string
    {
        $this->setImportMethod([
            "TableHeadCell" => '@/Components/Table/TableHeadCell.vue',
        ]);

        // Generate dynamic header content here based on your fields
        return collect($this->fields)->map(function ($field) {
            // Use the Indentation class to add tabs as needed
            return "<TableHeadCell>" . Str::ucfirst($field['name']) . "</TableHeadCell>";
        })->implode("\n                             ");
    }


    public function generateTableData(): string
    {
        $this->setImportMethod([
            "TableCell" => '@/Components/Table/TableCell.vue',
        ]);

        return collect($this->fields)->map(function ($field) {
            return "<TableCell>{{ {$this->model}.{$field['name']} }}</TableCell>";
        })->implode("\n                                 ");
    }
}
