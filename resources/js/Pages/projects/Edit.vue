<template>
  <div>
    <Head :title="'المشاريع'" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/project">المشاريع </Link>
      <span class="text-blue-400 font-medium"> : </span>
    </h1>
    <trashed-message v-if="project.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المشروع .
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="اسم المشروع"
              :error="form.errors.name"
              placeholder="اسم المشروع هنا"
            />
            <text-area-input
              v-model="form.description"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="وصف المشروع"
              :error="form.errors.description"
              placeholder="وصف المشروع هنا"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.date"
              :error="form.errors.date"
              label="تاريخ بدء المشروع"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.deadline"
              :error="form.errors.deadline"
              label="تاريخ الإنتهاء المتوقع"
            />

            <select-input
              v-model="form.status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="حالة المشروع"
              :error="form.errors.status"
            >
              <option value="" disabled hidden>إختر حالة المشروع</option>
              <option :value="false">قيد التنفيذ</option>
              <option :value="true">مكتمل</option>
            </select-input>
          </div>
        </div>
        <div
          class="flex items-center space-x-3 px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <button
            v-if="!project.deleted_at"
            class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف المشروع
          </button>

          <loading-button
            :loading="form.processing"
            class="ml-auto inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل المشروع
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
import TextAreaInput from "@/Shared/TextareaInput";

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
    TextAreaInput,
  },
  layout: Layout,
  props: {
    project: Object,
  },
  remember: "form",
  created() {
    if (this.project.status == 1) {
      this.form.status = true;
    } else {
      this.form.status = false;
    }
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.project.name,
        description: this.project.description,
        date: this.project.date,
        deadline: this.project.deadline,
        status: this.project.status,
      }),
    };
  },

  methods: {
    update() {
      this.form.post(`/projects/${this.project.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المشروع ؟")) {
        this.$inertia.delete(`/projects/${this.project.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المشروع ؟")) {
        this.$inertia.put(`/projects/${this.project.id}/restore`);
      }
    },
  },
};
</script>
