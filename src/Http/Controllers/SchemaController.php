<?php

namespace Laltu\LaravelMaker\Http\Controllers;

use Laltu\LaravelMaker\Http\Requests\StoreSchemaRequest;
use Laltu\LaravelMaker\Http\Requests\UpdateSchemaRequest;
use Laltu\LaravelMaker\Models\Schema;

class SchemaController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schemas = Schema::latest()->paginate();

        return inertia('Schema/SchemaIndex', compact('schemas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Schema/SchemaCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchemaRequest $request)
    {
        Schema::create($request->all());

        return redirect()->route('product.index')->with(['success', 'Created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Schema $schema)
    {
        return inertia('Schema/SchemaShow');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schema $schema)
    {
        return inertia('Schema/SchemaEdit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchemaRequest $request, Schema $schema)
    {
        $schema->update($request->all());

        return redirect()->route('schema.index')->with(['success', 'Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schema $schema)
    {
        $schema->delete();

        return redirect()->route('schema.index')->with(['success', 'Deleted Successfully']);
    }
}
