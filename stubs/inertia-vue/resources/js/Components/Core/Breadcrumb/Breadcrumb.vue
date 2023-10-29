<template>
  <nav :class="breadcrumbWrapperClasses" aria-label="Breadcrumb">
    <ol :class="breadcrumbClasses">
      <slot name="default" />
    </ol>
  </nav>
</template>
<script lang="ts" setup>
import { toRefs } from 'vue'
import { useBreadcrumbClasses } from '@/Components/Core/Breadcrumb/useBreadcrumbClasses'

const props = defineProps({
  solid: {
    type: Boolean,
    default: false,
  },
})

const { breadcrumbWrapperClasses, breadcrumbClasses } = useBreadcrumbClasses(toRefs(props))
</script>
