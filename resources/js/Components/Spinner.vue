<template>
    <div />
</template>

<script setup>
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
    background: radial-gradient(farthest-side,#f60 96%, #ff660000) top/4px 4px no-repeat, conic-gradient(#ff660000 24%,#f60);
    mask: radial-gradient(farthest-side,#ff660000 calc(100% - 4px),#f60 0);
    animation: spinner 0.3s infinite linear;
}

@keyframes spinner {
    100% {
        transform: rotate(1turn);
    }
}
</style>
