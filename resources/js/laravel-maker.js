import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import Clipboard from '@ryangjchandler/alpine-clipboard'
import collapse from '@alpinejs/collapse'
import ui from '@alpinejs/ui'

Alpine.plugin(Clipboard)
Alpine.plugin(collapse)
Alpine.plugin(ui)

Livewire.start()