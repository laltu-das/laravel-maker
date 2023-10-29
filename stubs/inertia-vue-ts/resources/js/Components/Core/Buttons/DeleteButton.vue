<script setup>
import DangerButton from '@/Components/Buttons/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/Buttons/SecondaryButton.vue';
import {nextTick, ref} from 'vue';
import {router} from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: [Array, Boolean],
        required: true,
    }
});

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value.focus());
};

const deleteData = () => {
    router.delete(props.href, {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

</script>

<template>
    <section class="space-y-6">
        <DangerButton @click="confirmUserDeletion">Delete</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>
                    <DangerButton class="ml-3" @click="deleteData">Delete</DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
