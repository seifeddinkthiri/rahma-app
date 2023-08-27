<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-200" href="/projects">مشروع </Link>
      <span class="text-blue-400 font-medium"> : </span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
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
              placeholder="تاريخ بدء المشروع هنا"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.deadline"
              :error="form.errors.deadline"
              label="تاريخ الإنتهاء المتوقع"
              placeholder="تاريخ الإنتهاء المتوقع هنا"
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
            >
              إضافة المشروع
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import FileInput from "@/Shared/FileInput";
import TextAreaInput from "@/Shared/TextareaInput";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextAreaInput,
    SelectInput,
    TextInput,
    FileInput,
  },
  layout: Layout,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        description: null,
        date: null,
        deadline: null,
        status: false,
      }),
    };
  },

  methods: {
    store() {
      this.form.post("/projects", {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
        },
      });
    },
  },
};
</script>
