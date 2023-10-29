<script setup>
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <Link class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
        <slot />
    </Link>
</template>
