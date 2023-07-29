<template>
  <div>
    {{ form.value }}
    <Head :title="form.intervenor" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/interventions"
        >التدخلات</Link
      >
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.intervenor }}
    </h1>
    <trashed-message v-if="intervention.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا التدخل .
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <select-input
            @change="selectedType"
            v-model="form.type"
            id="type"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="نوع التدخل"
          >
            <option value="shipmets">بضائع</option>
            <option value="cash ">نقد</option>
          </select-input>

          <text-input
            v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
            :id="form.type === 'shipmets' ? 'shipmets' : 'cash'"
            v-model="form.value"
            :error="form.errors.value"
            label="قيمة التدخل"
            :placeholder="
              form.type === null
                ? 'القيمة'
                : form.type === 'shipmets'
                ? 'الكمية'
                : 'المبلغ'
            "
          />
          <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="intervenor"
            v-model="form.intervenor"
            :error="form.errors.intervenor"
            label="إسم المتدخل"
          />
          <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="intervenor"
            v-model="form.intervenor_phone"
            :error="form.errors.intervenor_phone"
            label="هاتف المتدخل"
          />
          <TextAreaInput
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="notes"
            v-model="form.notes"
            :error="form.errors.notes"
            label="ملاحظات"
          />
          <file-input
            v-model="form.file"
            :error="form.errors.file"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            label="أضف ملف"
          />
        </div>
        <div
          class="flex items-center space-x-3 px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <button
            v-if="!intervention.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف التدخل
          </button>

          <loading-button
            :loading="form.processing"
            class="ml-auto inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل التدخل
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
  },
  layout: Layout,
  props: {
    intervention: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        type: this.intervention.type,
        value: this.intervention.value,
        intervenor: this.intervention.intervenor,
        intervenor_phone: this.intervention.intervenor_phone,
        file: null,
        notes: this.intervention.notes,
      }),
    };
  },
  methods: {
    selectedType() {
      console.log(this.form.type, this.intervention.type);
      if (this.form.type !== this.intervention.type) {
        this.form.value = null;
      } else {
        this.form.value = this.intervention.value;
      }
    },
    update() {
      this.form.put(`/interventions/${this.intervention.id}`);
    },
    destroy() {
      if (confirm("Are you sure you want to delete this intervention?")) {
        this.$inertia.delete(`/interventions/${this.intervention.id}`);
      }
    },
    restore() {
      if (confirm("Are you sure you want to restore this intervention?")) {
        this.$inertia.put(`/interventions/${this.intervention.id}/restore`);
      }
    },
  },
};
</script>
