<template>
  <div>
    <Head title="Create Family" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/families"
        >المنتفعين</Link
      >
      <span class="text-indigo-400 font-medium">/</span> إنشاء
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="caregiver_phone"
            v-model="form.caregiver_phone"
            :error="form.errors.caregiver_phone"
            label="رقم هاتف معيل الأسرة"
          />
          <file-input
            id="photo"
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="الصورة"
          />
          <ToggleCheckbox
            :id="'husband'"
            :isChecked="form.husband"
            :label="'الزوج'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            @toggle="toggle_husband"
          />
          <ToggleCheckbox
            :id="'wife'"
            :isChecked="form.wife"
            :label="'الزوجة'"
            :active_value="'نعم'"
            :inactive_value="'لا'"
            @toggle="toggle_wife"
          />
          <div>
            <ToggleCheckbox
              :id="'childrens'"
              :isChecked="form.childrens"
              :label="'الأبناء'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_childrens"
            />
            <text-input
              v-if="form.childrens"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="childrens_number"
              v-model="form.childrens_number"
              label="عددهم"
            />
            <text-input
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="childrens_number"
              label="لا يوجد"
            />
          </div>

          <div>
            <ToggleCheckbox
              :id="'elderlies'"
              :isChecked="form.elderlies"
              :label="'المسنين'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_elderlies"
            />
            <text-input
              v-if="form.elderlies"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="elderlies_number"
              v-model="form.elderlies_number"
              label="عددهم"
            />
            <text-input
              hidden
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="elderlies_number"
              label="لا يوجد "
            />
          </div>
          <div>
            <ToggleCheckbox
              :id="'other_members'"
              :isChecked="form.other_members"
              :label="'أفراد آخرين'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_other_members"
            />
            <text-input
              v-if="form.other_members"
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="other_members_number"
              v-model="form.other_members_number"
              label="عددهم"
            />
            <text-input
              v-else
              disabled
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="other_members_number"
              label="لا يوجد"
            />
          </div>
          <button class="btn-indigo" type="submit">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              class="w-6 h-4"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M15.75 19.5L8.25 12l7.5-7.5"
              />
            </svg>
          </button>
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
export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    ToggleCheckbox,
  },
  layout: Layout,
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        photo: null,
        caregiver_phone: null,
        wife: false,
        husband: false,
        elderlies: false,
        childrens: false,
        other_members: false,
        childrens_number: 0,
        elderlies_number: 0,
        other_members_number: 0,
      }),
    };
  },
  methods: {
    toggle_husband() {
      this.form.husband = !this.form.husband;
    },
    toggle_wife() {
      this.form.wife = !this.form.wife;
    },
    toggle_childrens() {
      this.form.childrens = !this.form.childrens;
      this.form.childrens_number = 0;
    },
    toggle_elderlies() {
      this.form.elderlies = !this.form.elderlies;
      this.form.elderlies_number = 0;
    },
    toggle_other_members() {
      this.form.other_members_number = 0;
      this.form.other_members = !this.form.other_members;
    },
    store() {
      if (
        (this.form.husband && this.form.wife)||
        (this.form.husband && (this.form.childrens_number > 0 || this.form.elderlies_number > 0 || this.form.other_members_number > 0))||
        (this.form.wife && (this.form.childrens_number > 0 || this.form.elderlies_number > 0 || this.form.other_members_number > 0))||
        this.form.childrens_number > 1 ||
        this.form.elderlies_number > 1 ||
        this.form.other_members_number > 1
      ) {
        this.form.post("/families");
      } else {
        window.alert("تحتوي العائلة على  فردين علا الأقل");
      }
    },
  },
};
</script>
