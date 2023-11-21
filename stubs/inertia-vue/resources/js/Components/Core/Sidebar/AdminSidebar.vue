<script setup>
import {useAttrs} from 'vue'
import {twMerge} from 'tailwind-merge'
import {Link, usePage} from "@inertiajs/vue3";
import {reactive, ref} from 'vue';
import Icon from "@/Components/Icon.vue";

defineOptions({
    inheritAttrs: false,
})

const attrs = useAttrs()
const wrapperClasses = twMerge('absolute top-0 left-0 z-40 w-64 h-screen transition-transform', attrs.class)

// Data
const user = ref('null');
const menu_items = reactive([
    {'name': 'Dashboard', 'route': 'dashboard', 'url': 'dashboard', 'icon': 'dashboard'},
    {'name': 'Tickets', 'route': 'category.index', 'url': 'tickets', 'icon': 'ticket'},
    {'name': 'Tickets', 'route': 'city.index', 'url': 'tickets', 'icon': 'ticket'},
]);
const enable_option = reactive({});

// Methods
const isUrl = function (...urls) {
    let currentUrl = usePage().url.substr(1)
    currentUrl = currentUrl.replace('dashboard/', '')
    if (urls[0] === '') {
        return currentUrl === ''
    }
    return urls.filter(url => currentUrl.startsWith(url)).length
}

const addActiveClass = function (e) {
    e.currentTarget.classList.toggle('hover')
}


// Created
user.value = usePage().props.auth.user;
const user_access = user.value.access

if (usePage().props.enable_options) {
    let options = JSON.parse(this.$page.props.enable_options.value)
    options.forEach(option => {
        enable_option[option.slug] = !!option.value
    })
}


// menu_items.push({'name': 'Chat', 'route': 'chat', 'url': 'chat', 'icon': 'chat'})
// menu_items.push({'name': 'FAQs', 'route': 'faqs', 'url': 'faqs', 'icon': 'faq'})
// menu_items.push({'name': 'Blog', 'route': 'posts', 'url': 'posts', 'icon': 'post'})
// menu_items.push({'name': 'Knowledge Base', 'route': 'knowledge_base', 'url': 'knowledge_base', 'icon': 'knowledge'})
// menu_items.push({'name': 'Customers', 'route': 'customers', 'url': 'customers', 'icon': 'all_users'})
// menu_items.push( {'name': 'Notes', 'route': 'notes', 'url': 'notes', 'icon': 'notes'} )
// menu_items.push({'name': 'Contacts', 'route': 'contacts', 'url': 'contacts', 'icon': 'contact'})
// menu_items.push({'name': 'Organizations', 'route': 'organizations', 'url': 'organizations', 'icon': 'office'})
// menu_items.push({'name': 'Manage Users', 'route': 'users', 'url': 'users', 'icon': 'users'})

// const settingSubmenus = [];
//
//     settingSubmenus.push({'name': 'User Roles', 'route': 'roles', 'url': 'settings/roles', 'icon': 'user_role'})
//     settingSubmenus.push({'name': 'Global', 'route': 'global', 'url': 'settings/global', 'icon': 'global_setting'})
//     settingSubmenus.push({'name': 'Categories', 'route': 'categories', 'url': 'settings/categories', 'icon': 'category'})
//     settingSubmenus.push({'name': 'Status', 'route': 'statuses', 'url': 'settings/statuses', 'icon': 'status'})
//     settingSubmenus.push({'name': 'Priorities', 'route': 'priorities', 'url': 'settings/priorities', 'icon': 'priorities'})
//     settingSubmenus.push({'name': 'Departments', 'route': 'departments', 'url': 'settings/departments', 'icon': 'departments'})
//     settingSubmenus.push({'name': 'Types', 'route': 'types', 'url': 'settings/types', 'icon': 'types'})
//     settingSubmenus.push({'name': 'Languages', 'route': 'languages', 'url': 'settings/languages', 'icon': 'edit'})
//     settingSubmenus.push({'name': 'Email Templates', 'route': 'templates', 'url': 'settings/templates', 'icon': 'email'})
//     settingSubmenus.push({'name': 'SMTP Mail', 'route': 'settings.smtp', 'url': 'settings/smtp', 'icon': 'email_template'})
//     settingSubmenus.push({'name': 'Pusher Chat', 'route': 'settings.pusher', 'url': 'settings/pusher', 'icon': 'chat'})
//     settingSubmenus.push({'name': 'Email to ticket', 'route': 'settings.piping', 'url': 'settings/piping', 'icon': 'ticket'})
//
//     menu_items.push({'name': 'Settings', 'route': '', 'url': 'settings', 'icon': 'settings', 'submenu': settingSubmenus })
//     menu_items.push(
//         {'name': 'Front Pages', 'route': '', 'url': 'front_pages', 'icon': 'gear',
//             'submenu': [
//                 {'name': 'Home', 'route': 'front_pages.page', 'url': 'front_pages/home', 'icon': 'page', 'param': 'home'},
//                 {'name': 'Contact', 'route': 'front_pages.page', 'url': 'front_pages/contact', 'icon': 'page', 'param': 'contact'},
//                 {'name': 'Services', 'route': 'front_pages.page', 'url': 'front_pages/services', 'icon': 'page', 'param': 'services'},
//                 {'name': 'Privacy Policy', 'route': 'front_pages.page', 'url': 'front_pages/privacy', 'icon': 'page', 'param': 'privacy'},
//                 {'name': 'Terms of services', 'route': 'front_pages.page', 'url': 'front_pages/terms', 'icon': 'page', 'param': 'terms'},
//                 {'name': 'Footer', 'route': 'front_pages.page', 'url': 'front_pages/footer', 'icon': 'page', 'param': 'footer'},
//             ]
//         },
//     )

</script>
<template>
    <aside :class="wrapperClasses" aria-label="Sidebar" v-bind="$attrs">
        <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
            <div class="space-y-2 font-medium">
                <template>
                    <div>
                        <div v-for="(menu_item, m_index) in menu_items" :key="m_index" :class="isUrl(menu_item.url) ? ' active' : ''"
                             class="menu-item"
                             v-on="menu_item.submenu?{click: (e) => addActiveClass(e)}:{}">
                            <Link :class="{'have-sub-menu': menu_item.submenu}"
                                  :href="menu_item.route?route(menu_item.route):'#'"
                                  class="flex items-center group py-3 menu-link">
                                <icon :name="menu_item.icon" class="w-6 h-6 mr-3 rtl:ml-3 menu__icon"/>
                                <div class="menu__name">{{ __(menu_item.name) }}</div>
                            </Link>
                            <div v-if="menu_item.submenu" class="sub-menu-items">
                                <Link v-for="(sub_menu_item, s_m_index) in menu_item.submenu" :key="s_m_index"
                                      :class="this.isUrl(sub_menu_item.url) ? ' active' : ''"
                                      :href="sub_menu_item.param?route(sub_menu_item.route,sub_menu_item.param):route(sub_menu_item.route)"
                                      class="sub-menu-item">
                                    <icon v-if="sub_menu_item.icon" :name="sub_menu_item.icon"
                                          class="w-4 h-4 mr-1 rtl:ml-1 menu__icon"/>
                                    <icon v-else class="w-4 h-4 mr-1 rtl:mr-1 menu__icon" name="dash"/>
                                    <div class="menu__name">{{ __(sub_menu_item.name) }}</div>
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </aside>
</template>
