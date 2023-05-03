<script setup>
import { h } from 'vue';
import Navbar from './Navbar.vue';

defineProps({
    flash: Object
});
</script>
<template>
    <Navbar></Navbar>
    <section>
        <div class="container my-5">
            <div class="alert fade hide" :class="{'show' : flash.message, 'alert-success': flash.type =='success', 'alert-warning': flash.type == 'warning'}" role="alert">
                {{ flash.message }}
            </div>
            <slot/>
        </div>
    </section>
</template>
