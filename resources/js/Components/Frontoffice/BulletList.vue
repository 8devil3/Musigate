<template>
   <ul>
      <li v-for="item in props.items">
         <i class="fa-solid fa-check text-orange-500 text-sm mr-1"></i>
         {{ item.text }}
      </li>
   </ul>
</template>

<script setup>
const props = defineProps({
   items: Array
})

</script>
