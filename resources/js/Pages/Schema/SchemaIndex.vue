<script setup>
import {ref} from 'vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import IconCog from "@/Components/Icons/IconCog.vue";
import IconCircle from "@/Components/Icons/IconCircle.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import {useForm} from 'laravel-precognition-vue-inertia';
import {VueFlow} from "@vue-flow/core";
import {Background} from "@vue-flow/background";

const props = defineProps({
  filters: {
    type: Object,
    default: () => ({}),
  },
  schemas: {
    type: Object,
    required: true,
    default: () => ({}),
  },
})

const form = useForm('post', route('laravel-maker.schema.store'), {
  password: '',
});

const modalType = ref(null);
const schemaToEdit = ref(null);
const isModalOpen = ref(false);

const openSchemaModal = (type, schemaId = null) => {
  isModalOpen.value = true;
  modalType.value = type;
  schemaToEdit.value = schemaId;
};

const closeModal = () => {
  isModalOpen.value = false;
  modalType.value = null;
  schemaToEdit.value = null;
};

const deleteSchema = (schemaId) => {
  if (confirm('Are you sure you want to delete this schema?')) {
    router.delete(route('laravel-maker.schema.destroy', schemaId));
  }
};


const saveSchema = () => form.submit({
  preserveScroll: true,
  onSuccess: () => form.reset(),
});

const elements = ref([
  {
    id: '1',
    position: { x: 50, y: 50 },
    label: 'Node 1',
  }
])

</script>
<template>
  <div class="mx-auto">
    <div class="flex gap-2 mb-4">
      <PrimaryButton @click="openSchemaModal('add')">Add Schema</PrimaryButton>
    </div>
    <VueFlow v-model="elements" fit-view-on-init class="vue-flow-basic-example">
      <Background />
    </VueFlow>
    <div v-for="schema in schemas.data" :key="schema.id">
      <div class="rounded overflow-hidden shadow-lg bg-white mb-2 p-4">
        <div class="px-6 py-4">
          <div class="font-bold text-xl mb-2">
            <span>{{ schema.model_name }}</span>
            <div class="flex justify-center">
              <IconCog @click="openSchemaModal('edit', schema.id)" class="btn-icon"/>
              <IconCircle @click="deleteSchema(schema.id)" class="btn-icon text-red-600"/>
            </div>
          </div>
          <ul class="list-none space-y-2">
            <li v-for="field in schema.fields" :key="field.field_name" class="text-gray-700 flex items-center">
              <span class="icon-user mr-2"></span>
              <span class="font-semibold">{{ field.field_name }}:</span>
              <span class="text-sm text-gray-500 ml-1">({{ field.dataType }})</span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <Modal :show="isModalOpen" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Are you sure you want to delete your account?
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          Once your account is deleted, all of its resources and data will be permanently deleted. Please
          enter your password to confirm you would like to permanently delete your account.
        </p>

        <div class="mt-6">
          <InputLabel for="password" value="Password" class="sr-only"/>
          <TextInput id="password" ref="passwordInput" v-model="form.password" type="password" class="mt-1 w-full" placeholder="Password"/>
          <InputError v-else-if="form.invalid('email')" :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

          <PrimaryButton
              class="ms-3"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click="saveSchema"
          >
            Save Schema
          </PrimaryButton>
        </div>
      </div>

    </Modal>
  </div>
</template>