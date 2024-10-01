<template>
    <label :for="props.id" class="flex items-start gap-1.5 cursor-pointer">
        <input
            type="radio"
            v-model="vModel"
            :value="props.value"
            :required="props.required"
            :name="props.name"
            :id="props.id"
            class="text-orange-500 bg-transparent border-2 border-orange-500 appearance-none cursor-pointer focus:ring-1 focus:outline-none focus:ring-offset-transparent focus:ring-orange-500/50"
        />
        <span class="text-[13px] font-normal leading-tight text-white pt-[1px]">
            <slot />
        </span>
    </label>
</template>

<script setup>
const props = defineProps({
    name: String,
    id: String,
    value: [Array, Number, Boolean, String],
    required: Boolean
});

const vModel = defineModel();

</script>
