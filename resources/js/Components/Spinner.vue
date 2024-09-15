<template>
    <div :class="props.isOrange ? 'orange' : 'white'"></div>
</template>

<script setup>
const props = defineProps({
    isOrange: {
        type: Boolean,
        default: false
    }
});

</script>

<style scoped>
.white {
    border-radius: 50%;
    background: radial-gradient(farthest-side,#fff 96%, #ffffff00) top/4px 4px no-repeat, conic-gradient(#ffffff00 24%,#fff);
    mask: radial-gradient(farthest-side,#ffffff00 calc(100% - 4px),#fff 0);
    animation: spinner 0.3s infinite linear;
}

.orange {
    border-radius: 50%;
    background: radial-gradient(farthest-side,#f60 96%, #ff660000) top/8px 8px no-repeat, conic-gradient(#ff660000 24%,#f60);
    mask: radial-gradient(farthest-side,#ff660000 calc(100% - 8px),#f60 0);
    animation: spinner 0.3s infinite linear;
}

@keyframes spinner {
    100% {
        transform: rotate(1turn);
    }
}
</style>
