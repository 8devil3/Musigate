<template>
  <footer class="p-4 font-sans text-sm leading-none">
        <div class="border-t md:border-0 mx-auto gap-8 border-gray-600 flex flex-col max-w-[1536px] md:flex-row items-center justify-between">
            <!-- copyright -->
            <div class="flex flex-col items-center text-center gap-x-2 gap-y-3 md:flex-row md:text-left">
                <span>
                    &copy; {{ new Date().getFullYear() }} OrangeWeb di Aldo Cocurullo
                </span>
                <span class="hidden md:inline">-</span>
                <span>
                    p.iva 04442720985
                </span>
            </div>
            <!-- / -->
    
            <!-- social -->
            <div class="space-x-3 text-lg">
                <a href="https://www.facebook.com/musigate.it" target="blank" title="Facebook" class="link"><i class="fa-brands fa-facebook"></i></a>
                <a href="https://www.instagram.com/musigate.it/" target="blank" title="Instagram" class="link"><i class="fa-brands fa-instagram"></i></a>
                <a href="https://www.linkedin.com/in/orangeweb/" target="blank" title="LinkedIn" class="link"><i class="fa-brands fa-linkedin"></i></a>
            </div>
            <!-- / -->
    
            <!-- legal -->
            <div class="flex flex-col items-center gap-3 text-center md:flex-row md:text-right">
                <Link :href="route('tos')" class="link">Termini e Condizioni</Link>
                <div class="space-x-3">
                    <Link :href="route('privacy')" class="link">Privacy</Link>
                    <Link :href="route('cookie')" class="link">Cookie</Link>
                </div>
            </div>
            <!-- / -->
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

</script>
