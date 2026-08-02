<script lang="ts" setup>
import type { ToasterProps } from "vue-sonner"
import { CircleCheckIcon, InfoIcon, Loader2Icon, OctagonXIcon, TriangleAlertIcon, XIcon } from "@lucide/vue"
import { Toaster as Sonner } from "vue-sonner"
import { usePage } from "@inertiajs/vue3"
import { computed, onMounted, watch } from "vue"
import { useTrans } from "@/composables/useTrans"
import { processPageToasts } from "@/lib/flashToast"
import { cn } from "@/lib/utils"

import 'vue-sonner/style.css';

const props = defineProps<ToasterProps>()
const page = usePage()
const { isRtl } = useTrans()

const computedPosition = computed(() => {
  if (props.position) {
    return props.position;
  }
  return isRtl.value ? 'bottom-left' : 'bottom-right';
})

watch(
  () => [page.props.flash, page.props.errors],
  () => {
    processPageToasts(page.props);
  },
  { deep: true }
)

onMounted(() => {
  processPageToasts(page.props);
})
</script>

<template>
  <Sonner
    v-bind="props"
    :position="computedPosition"
    :class="cn('toaster group', props.class)"
    :style="{
      '--normal-bg': 'var(--popover)',
      '--normal-text': 'var(--popover-foreground)',
      '--normal-border': 'var(--border)',
      '--border-radius': 'var(--radius)',
    }"
  >
    <template #success-icon>
      <CircleCheckIcon class="size-4" />
    </template>
    <template #info-icon>
      <InfoIcon class="size-4" />
    </template>
    <template #warning-icon>
      <TriangleAlertIcon class="size-4" />
    </template>
    <template #error-icon>
      <OctagonXIcon class="size-4" />
    </template>
    <template #loading-icon>
      <div>
        <Loader2Icon class="size-4 animate-spin" />
      </div>
    </template>
    <template #close-icon>
      <XIcon class="size-4" />
    </template>
  </Sonner>
</template>
