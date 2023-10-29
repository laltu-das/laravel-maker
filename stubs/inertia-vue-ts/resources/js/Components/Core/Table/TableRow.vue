<template>
  <tr :class="tableRowClasses">
    <slot></slot>
  </tr>
</template>
<script lang="ts" setup>
import { useTableRowClasses } from '@/Components/Core/Table/useTableRowClasses'

const { tableRowClasses } = useTableRowClasses()
</script>
