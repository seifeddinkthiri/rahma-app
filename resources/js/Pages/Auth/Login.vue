<template>
  <Head title="Login" />
  <logo class="block mx-auto h-32 w-32 max-w-xs fill-white" height="50" />
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
    <div class="formButtom">
      <button :loading="form.processing" class="float-left p-4 font-bold" type="submit">
        تسجيل الدخول
      </button>
      <Link href="/register" class="float-left p-4"> إنشاء حساب </Link>
    </div>
  </form>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Pages/Auth/index";
import Logo from "@/Shared/Logo";
import TextInput from "@/Shared/TextInput";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Head,
    Link,
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
