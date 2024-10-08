<template>
  <footer class="px-4 py-8 font-sans text-sm leading-none lg:p-6">
        <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
            <!-- copyright -->
            <div class="flex flex-col items-center text-center gap-x-2 gap-y-3 md:flex-row md:text-left">
                &copy; {{ new Date().getFullYear() }}
                <a href="https://www.orangeweb.biz" class="font-normal text-orange-500 transition-colors hover:text-orange-400">
                    OrangeWeb
                </a>
                <span class="hidden md:inline">-</span>
                <span>
                    P.IVA 04442720985
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
