<template>
  <vue-final-modal
      v-slot="{ params, close }"
      v-bind="$attrs"
      class="flex justify-center items-center"
      content-class="relative flex flex-col max-h-full m-5 top-5 bottom-5 impala-box"
  >
    <span class="mr-8 text-2xl font-bold">
      <slot name="title"></slot>
    </span>
    <div class="w-full overflow-y-scroll">
      <slot :params="params"></slot>
    </div>
  </vue-final-modal>
</template>

<script>
export default {
  name: 'VTailwindModal',
  inheritAttrs: false,
  //emits: ['cancel', 'confirm']
}
</script>
