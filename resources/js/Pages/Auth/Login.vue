<template>
  <Head title="Login" />
  <logo class="block mx-auto w-full max-w-xs fill-white" height="50" />
  <form
    class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
    @submit.prevent="login"
  >
    <div class="px-10 py-12">
      <h1 class="text-center text-3xl font-bold">مرحبًا بعودتك!</h1>
      <div class="mt-6 mx-auto w-24 border-b-2" />
      <text-input
        v-model="form.email"
        :error="form.errors.email"
        class="mt-10"
        label="البريد الإلكتروني"
        type="email"
        autofocus
        autocapitalize="off"
      />
      <text-input
        v-model="form.password"
        :error="form.errors.password"
        class="mt-6"
        label="كلمة المرور"
        type="password"
      />
      <label class="flex items-center mt-6 select-none" for="remember">
        <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
        <span class="text-sm">تذكرنى</span>
      </label>
    </div>
    <div class="formButton flex items-center justify-between">
      <a href="/register" class="px-4 py-2 rounded-md">إنشاء حساب</a>
      <loading-button :loading="form.processing" class="btn-indigo" type="submit">
        تسجيل الدخول
      </loading-button>
    </div>
  </form>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Pages/Auth/index";
import Logo from "@/Shared/Logo";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";

export default {
  components: {
    Head,
    LoadingButton,
    Logo,
    TextInput,
  },
  layout: Layout,
  data() {
    return {
      form: this.$inertia.form({
        email: "admin@gmail.com",
        password: "admin",
        remember: false,
      }),
    };
  },
  methods: {
    login() {
      this.form.post("/login");
    },
  },
};
</script>
