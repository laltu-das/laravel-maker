<div>
    <h2>Drag and Drop Form Builder</h2>

    <div wire:ignore>
        <ul id="sortable-list" wire:sortable="updateOrder">
            @foreach ($formFields as $field)
                <li wire:key="{{ $field['id'] }}">{{ $field['label'] }}</li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function () {
        new Sortable(document.getElementById('sortable-list'), {
            onEnd: function (event) {
                Livewire.emit('fieldOrderUpdated', event.from.children);
            },
        });
    });
</script>