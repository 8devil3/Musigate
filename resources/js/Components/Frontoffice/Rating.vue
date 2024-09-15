<template>
   <div class="flex items-center gap-1 text-orange-500">
      <i v-for="star in props.rating" class="fa-solid fa-star"></i>
      <i v-for="star in 5 - props.rating" class="fa-regular fa-star"></i>
   </div>
</template>

<script setup>
const props = defineProps({
   rating: {
      type: Number,
      default: 0
   }
})

</script>
