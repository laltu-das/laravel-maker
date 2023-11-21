<script setup>
import {ref} from 'vue';
import Modal from "@/Components/Core/Modal/Modal.vue";
import Button from "@/Components/Core/Button/Button.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import {Link} from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import AdminSidebar from "@/Components/Core/Sidebar/AdminSidebar.vue";
import SidebarItem from "@/Components/Core/Sidebar/SidebarItem.vue";
import SidebarDropdownItem from "@/Components/Core/Sidebar/SidebarDropdownItem.vue";

const showingNavigationDropdown = ref(false);
const isShowModal = ref(false)

</script>

<template>
  <Modal v-if="isShowModal" persistent>
    <template #header>
      <div class="flex items-center text-lg">
        Terms of Service
      </div>
    </template>
    <template #body>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        With less than a month to go before the European Union enacts new consumer privacy laws for its
        citizens, companies around the world are updating their terms of service agreements to comply.
      </p>
      <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
        The European Union’s General Data Protection Regulation (G.D.P.R.) goes into effect on May 25 and is
        meant to ensure a common set of data rights in the European Union. It requires organizations to notify
        users as soon as possible of high-risk data breaches that could personally affect them.
      </p>
    </template>
  </Modal>

  <AdminSidebar>
    <SidebarItem>
      <template #icon>
        <svg
            aria-hidden="true"
            class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 22 21"
            xmlns="http://www.w3.org/2000/svg"
        >
          <path
              d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
          <path
              d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
        </svg>
      </template>
      <template #default>Dashboard</template>
    </SidebarItem>
    <SidebarItem>
      <template #icon>
        <svg
            aria-hidden="true"
            class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 18 18"
            xmlns="http://www.w3.org/2000/svg"
        >
          <path
              d="M6.143 0H1.857A1.857 1.857 0 0 0 0 1.857v4.286C0 7.169.831 8 1.857 8h4.286A1.857 1.857 0 0 0 8 6.143V1.857A1.857 1.857 0 0 0 6.143 0Zm10 0h-4.286A1.857 1.857 0 0 0 10 1.857v4.286C10 7.169 10.831 8 11.857 8h4.286A1.857 1.857 0 0 0 18 6.143V1.857A1.857 1.857 0 0 0 16.143 0Zm-10 10H1.857A1.857 1.857 0 0 0 0 11.857v4.286C0 17.169.831 18 1.857 18h4.286A1.857 1.857 0 0 0 8 16.143v-4.286A1.857 1.857 0 0 0 6.143 10Zm10 0h-4.286A1.857 1.857 0 0 0 10 11.857v4.286c0 1.026.831 1.857 1.857 1.857h4.286A1.857 1.857 0 0 0 18 16.143v-4.286A1.857 1.857 0 0 0 16.143 10Z"
          />
        </svg>
      </template>
      <template #default>Kanban</template>
      <template #suffix>
                <span
                    class="inline-flex items-center justify-center px-2 ml-3 text-sm font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-300">Pro</span>
      </template>
    </SidebarItem>
    <SidebarDropdownItem>
      <template #icon>
        <svg
            aria-hidden="true"
            class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
            fill="currentColor"
            viewBox="0 0 22 21"
            xmlns="http://www.w3.org/2000/svg"
        >
          <path
              d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z"/>
          <path
              d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z"/>
        </svg>
      </template>
      <template #trigger> E-commerce</template>
      <template #default>
        <SidebarItem class="pl-11"> Products</SidebarItem>
        <SidebarItem class="pl-11"> Sign In</SidebarItem>
        <SidebarItem class="pl-11"> Sign Up</SidebarItem>
      </template>
    </SidebarDropdownItem>
  </AdminSidebar>
  <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <Link :href="route('dashboard')">
              <ApplicationLogo
                  class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
            </Link>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
            <NavLink :active="route().current('dashboard')" :href="route('dashboard')">
              Dashboard
            </NavLink>
            <NavLink :active="route().current('dashboard')" :href="route('dashboard')">
              Dashboard
            </NavLink>
          </div>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ml-6">
          <!-- Settings Dropdown -->
          <div class="ml-3 relative">
            <Dropdown align="right" width="48">
              <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <Button
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                                type="button"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        clip-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        fill-rule="evenodd"
                                                    />
                                                </svg>
                                            </Button>
                                        </span>
              </template>

              <template #content>
                <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                <DropdownLink :href="route('logout')" as="Button" method="post">
                  Log Out
                </DropdownLink>
              </template>
            </Dropdown>
          </div>
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
          <Button
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
              @click="showingNavigationDropdown = !showingNavigationDropdown"
          >
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path
                  :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                  d="M4 6h16M4 12h16M4 18h16"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
              />
              <path
                  :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                  d="M6 18L18 6M6 6l12 12"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
              />
            </svg>
          </Button>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div
        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
        class="sm:hidden"
    >
      <div class="pt-2 pb-3 space-y-1">
        <ResponsiveNavLink :active="route().current('dashboard')" :href="route('dashboard')">
          Dashboard
        </ResponsiveNavLink>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
        <div class="px-4">
          <div class="font-medium text-base text-gray-800 dark:text-gray-200">
            {{ $page.props.auth.user.name }}
          </div>
          <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
          <ResponsiveNavLink :href="route('logout')" as="Button" method="post">
            Log Out
          </ResponsiveNavLink>
        </div>
      </div>
    </div>
  </nav>
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
