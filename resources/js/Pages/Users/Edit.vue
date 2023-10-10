<template>
  <div class="p-6 bg-white rounded-md shadow overflow-x-auto">
    <Head :title="`${form.first_name} ${form.last_name}`" />
   
    <br />
    <div class="flex justify-start mb-8 max-w-xl">
      <h1 class="text-xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/users"
          >المستخدم</Link
        >
        <span class="text-indigo-400 font-medium"> : </span>
        {{ form.first_name }} {{ form.last_name }}
      </h1>
    </div>

    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      تم حذف هذا المستخدم
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
            v-if="$page.props.auth.user.admin"
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
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!user.deleted_at"
            class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف المستخدم
          </button>
          <loading-button
            :loading="form.processing"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none ml-auto"
            type="submit"
            >تحديث المستخدم</loading-button
          >
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    FileInput,
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        _method: "put",
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: "",
        owner: this.user.owner,
        photo: null,
      }),
    };
  },
  methods: {
    update() {
      this.form.post(`/users/${this.user.id}`, {
        onSuccess: () => this.form.reset("password", "photo"),
      });
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المستخدم؟")) {
        this.$inertia.delete(`/users/${this.user.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المستخدم؟")) {
        this.$inertia.put(`/users/${this.user.id}/restore`);
      }
    },
  },
};
</script>
