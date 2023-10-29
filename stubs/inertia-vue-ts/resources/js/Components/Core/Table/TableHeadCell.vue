<template>
  <th scope="col" :class="tableHeadCellClasses">
    <slot></slot>
  </th>
</template>
<script lang="ts" setup>
import { useTableHeadCellClasses } from '@/Components/Core/Table/useTableHeadCellClasses'

const { tableHeadCellClasses } = useTableHeadCellClasses()
</script>
