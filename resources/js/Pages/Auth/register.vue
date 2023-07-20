<template>
  <div>
    <Head title="Create User" />
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form
        class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden"
        @submit.prevent="store"
      >
        <h1 class="text-center text-3xl font-bold">إنشاء حساب</h1>
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full"
            label="الإسم"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full"
            label="اللقب"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full"
            label="البريد الإلكتروني"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="pb-8 pr-6 w-full"
            type="password"
            autocomplete="new-password"
            label="كلمة المرور"
          />
        </div>
        <div class="formButtom">
          <button
            loading-button
            :loading="form.processing"
            class="float-left p-4 font-bold"
            type="submit"
          >
            تسجيل
          </button>
          <Link href="/login" class="float-left p-4"> لديك حساب ؟ </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Pages/Auth/index";
import TextInput from "@/Shared/TextInput";

export default {
  components: {
    Head,
    Link,

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
        owner: true,
        photo: null,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/register");
    },
  },
};
</script>
