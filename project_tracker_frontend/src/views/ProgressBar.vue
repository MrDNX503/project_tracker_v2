<template>
  <div class="w-full bg-gray-200 rounded-full h-4">
    <div
      class="bg-blue-500 h-4 rounded-full"
      :style="{ width: progress + '%' }"
    ></div>
  </div>
</template>

<script>
export default {
  props: {
    progress: {
      type: Number,
      required: true,
    },
  },
};
</script>