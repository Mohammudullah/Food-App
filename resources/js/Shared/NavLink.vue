<template>
    <Link class="nav-link" :class="{'active' : $page.component == page}" :href="href"><slot></slot></Link>
</template>
<script setup>
    defineProps ({
        page: String,
        href: String
    });
</script>