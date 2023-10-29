<script lang="ts" setup>
import {ref} from 'vue';
import NavbarCollapse from "@/Components/Core/Navbar/NavbarCollapse.vue";
import NavbarLogo from "@/Components/Core/Navbar/NavbarLogo";
import Navbar from "@/Components/Core/Navbar/Navbar.vue";
import NavbarLink from "@/Components/Core/Navbar/NavbarLink";

</script>

<template>
    <Navbar>
        <template #logo>
            <NavbarLogo alt="Flowbite logo" image-url="/images/logo.svg" link="#">
                Flowbite
            </NavbarLogo>
        </template>
        <template #default="{isShowMenu}">
            <NavbarCollapse :is-show-menu="isShowMenu">
                <NavbarLink is-active link="#">
                    Home
                </NavbarLink>
                <NavbarLink link="#">
                    Services
                </NavbarLink>
                <NavbarLink link="#">
                    Pricing
                </NavbarLink>
                <NavbarLink link="#">
                    Contact
                </NavbarLink>
            </NavbarCollapse>
        </template>
        <template #menu-icon>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
        </template>
    </Navbar>
    <!-- Page Heading -->
    <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <slot name="header"/>
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <slot/>
    </main>
</template>
