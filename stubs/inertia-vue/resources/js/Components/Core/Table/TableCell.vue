<template>
  <td :class="tableCellClasses">
    <slot></slot>
  </td>
</template>
<script lang="ts" setup>

import { useTableCellClasses } from '@/Components/Core/Table/useTableCellClasses'

const { tableCellClasses } = useTableCellClasses()
</script>
