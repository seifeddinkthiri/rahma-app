<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/users">المستخدم</Link>
      <span class="text-blue-400 font-medium"> : </span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الاسم"
            placeholder="الإسم هنا"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الاسم الأخير"
            placeholder="الإسم الأخير هنا"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="البريد الإلكتروني"
            placeholder="البريد الإلكتروني هنا"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="password"
            autocomplete="new-password"
            label="كلمة المرور"
            placeholder="كلمة المرور هنا"
          />
          <select-input
            v-model="form.owner"
            :error="form.errors.owner"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="أدمن"
          >
            <option :value="true">نعم</option>
            <option :value="false">لا</option>
          </select-input>
          <file-input
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="الصورة"
          />
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button :loading="form.processing" class="btn-indigo" type="submit"
            >إنشاء مستخدم</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import FileInput from "@/Shared/FileInput";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        first_name: "",
        last_name: "",
        email: "",
        password: "",
        owner: false,
        photo: null,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/users");
    },
  },
};
</script>
