<template>
  <div>
    <Head title="Create Family" />
    <Breadcrumb :active_step="'family'" :form_title="'إنشاء عائلة'" />

    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <!--    <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="caregiver_phone"
            v-model="form.caregiver_phone"
            :error="form.errors.caregiver_phone"
            label="رقم هاتف معيل الأسرة"
          />-->

          <div>
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="childrens_number"
              v-model="form.childrens_number"
              label="الأبناء"
            />
          </div>

          <div>
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="elderlies_number"
              v-model="form.elderlies_number"
              label="المسنين "
            />
          </div>
          <div>
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="other_members_number"
              v-model="form.other_members_number"
              label="أفراد آخرين"
            />
          </div>

          <button
            class="inline-flex h-12 items-center justify-center px-4 py-2 text-white hover:text-white text-sm font-medium bg-gray-600 hover:bg-gray-800 rounded focus:outline-none"
            type="submit"
          >
            أنشاء</button
          ><file-input
            id="photo"
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label=" الصورة (اخيتاري)  "
          />
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
import ToggleCheckbox from "../../Shared/ToggleCheckbox.vue";
import Breadcrumb from "@/Shared/Breadcrumb";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    ToggleCheckbox,
    Breadcrumb,
    Icon,
  },
  layout: Layout,
  remember: "form",
  mounted() {
    localStorage.setItem("stored_childrens", 0);
    localStorage.setItem("stored_elderlies", 0);
    localStorage.setItem("stored_other_members", 0);
  },
  data() {
    return {
      form: this.$inertia.form({
        photo: null,
        // caregiver_phone: null,

        childrens_number: 0,
        elderlies_number: 0,
        other_members_number: 0,
      }),
    };
  },
  methods: {
    store() {
      this.form.post("/families");
    },
  },
};
</script>
