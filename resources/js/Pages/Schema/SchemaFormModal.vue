<script setup>
import {computed, ref} from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
  type: {
    type: String,
    required: true,
  },
  schemaId: {
    type: Number,
    default: null,
  },
});

const isModalOpen = ref(false);

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

const modalTitle = computed(() => {

  console.log(props.type)
  if (props.type === 'edit') {
    return 'Edit Schema';
  } else if (props.type === 'add') {
    return 'Add Schema';
  } else {
    // For 'schema-sql-from'
    return 'Import SQL Schema';
  }
});
</script>

<template>
  <Modal :show="openModal"  @close="closeModal">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ modalTitle }}
      </h2>
    </div>
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        Are you sure you want to delete your account?
      </h2>

      <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Once your account is deleted, all of its resources and data will be permanently deleted. Please
        enter your password to confirm you would like to permanently delete your account.
      </p>

      <div class="mt-6">
        <InputLabel for="password" value="Password" class="sr-only" />

        <TextInput
            id="password"
            ref="passwordInput"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="Password"
            @keyup.enter="deleteUser"
        />

        <InputError :message="form.errors.password" class="mt-2" />
      </div>

      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

        <DangerButton
            class="ms-3"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            @click="deleteUser"
        >
          Delete Account
        </DangerButton>
      </div>
    </div>

  </Modal>
</template>