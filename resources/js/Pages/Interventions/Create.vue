<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-200" href="/interventions"
        >التدخلات
      </Link>
      <span class="text-blue-400 font-medium">/</span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.beneficial"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="نوع المنتفع"
              :error="form.errors.beneficial"
            >
              <option :value="null" selected disabled hidden>إختر نوع المنتفع</option>
              <option value="family">عائلة</option>
              <option value="individual">فرد</option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'family'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العائلة"
            >
              <option hidden disabled :value="null">إختر العائلة</option>

              <option v-for="family in filteredFamilies" :value="family.id">
                {{ family.caregiver_phone }} - {{ family.name }}
              </option>
              <option :value="null" v-if="filteredFamilies.length == 0">
                قائمة فارغة
              </option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'individual'"
              v-model="form.individual"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الفرد"
            >
              <option hidden disabled :value="null">إختر المنتفع</option>

              <option v-for="indiv in individuals" :value="indiv.id">
                {{ indiv.phone }} - {{ indiv.name }}
              </option>
              <option :value="null" v-if="individuals.length == 0">قائمة فارغة</option>
            </select-input>
            <select-input
              v-model="form.type"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="نوع التدخل"
              :error="form.errors.type"
            >
              <option :value="null" selected disabled hidden>إختر نوع التدخل</option>
              <option value="shipments">عيني</option>
              <option value="cash ">نقدي</option>
            </select-input>

            <text-input
              v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
              :id="form.type === 'shipments' ? 'shipments' : 'cash'"
              v-model="form.value"
              :error="form.errors.value"
              label="قيمة التدخل"
              :placeholder="
                form.type === null
                  ? 'القيمة'
                  : form.type === 'shipments'
                  ? 'الكمية'
                  : 'المبلغ'
              "
            />

            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="intervenor"
              v-model="form.intervenor"
              :error="form.errors.intervenor"
              label="إسم المسؤل"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="intervenor"
              v-model="form.intervenor_phone"
              :error="form.errors.intervenor_phone"
              label="هاتف المسؤل"
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
            <text-input
              v-model="form.title"
              :error="form.errors.title"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="عنوان الملف"
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              @click="show_second_form_part = true"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
            >
              إضافة
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
  props: {
    families: Object,
    individuals: Object,
  },
  remember: "form",
  data() {
    return {
      show_second_form_part: false,
      form: this.$inertia.form({
        beneficial: null,
        type: null,
        value: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        notes: null,
        family: null,
        individual: null,
      }),
    };
  },
  computed: {
    filteredFamilies() {
      return this.families.filter((family) => family.name !== null);
    },
  },
  methods: {
    store() {
      this.form.post("/interventions", {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
        },
      });
    },
  },
};
</script>
