<script setup>
import IconCog from "@/Components/Icons/IconCog.vue";
import IconCircle from "@/Components/Icons/IconCircle.vue";

const props = defineProps({
  schema: {
    type: Object,
    required: true,
  },
});
const emit = defineEmits(['edit', 'delete']);
</script>

<template>
</template>