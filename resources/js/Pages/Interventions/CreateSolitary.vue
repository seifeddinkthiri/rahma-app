<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-200" href="/interventions"
        >التدخلات
      </Link>
      <span class="text-blue-400 font-medium"> : </span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="beneficial_name"
              v-model="form.beneficial_name"
              :error="form.errors.beneficial_name"
              placeholder="إسم المنتفع هنا"
              label="إسم المنتفع "
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="beneficial_phone"
              v-model="form.beneficial_phone"
              :error="form.errors.beneficial_phone"
              placeholder="هاتف المنتفع هنا"
              label="هاتف المنتفع "
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="beneficial_cin"
              v-model="form.beneficial_cin"
              :error="form.errors.beneficial_cin"
              placeholder="بطاقة تعريف المنتفع هنا"
              label="بطاقة تعريف المنتفع"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="beneficial_address"
              v-model="form.beneficial_address"
              :error="form.errors.beneficial_address"
              placeholder="عنوان  المنتفع  هنا"
              label="عنوان  المنتفع "
            />
            <select-input
              v-model="form.project"
              :error="form.errors.project"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="في إطار مشروع"
              ref="projectSelect"
            >
              <option hidden disabled :value="null">إختر المشروع</option>

              <option v-for="project in projects" :value="project.id">
                {{ project.name }}
              </option>
              <option :value="null" v-if="projects.length == 0" disabled>قائمة فارغة</option>
            </select-input>
            <select-input
              v-model="form.type"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="نوع التدخل"
              :error="form.errors.type"
              ref="typeSelect"
            >
              <option :value="null" selected disabled hidden>إختر نوع التدخل</option>
              <option value="shipments">عيني</option>
              <option value="cash">نقدي</option>
              <option value="other">آخر</option>
            </select-input>

            <text-input
              v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
              v-model="form.value"
              :error="form.errors.value"
              label="قيمة التدخل"
              :id="
                form.type === 'shipments'
                  ? 'shipments'
                  : form.type === 'cash'
                  ? 'cash'
                  : 'other'
              "
              :placeholder="
                form.type === null
                  ? 'القيمة'
                  : form.type === 'shipments'
                  ? 'الكمية'
                  : form.type === 'cash'
                  ? 'المبلغ'
                  : 'القيمة الأخرى'
              "
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="date"
              v-model="form.date"
              :error="form.errors.date"
              label="تاريخ التدخل"
              placeholder=" تاريخ التدخل هنا"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="intervenor"
              v-model="form.intervenor"
              :error="form.errors.intervenor"
              placeholder="إسم المسؤل هنا"
              label="إسم المسؤل"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="intervenor"
              v-model="form.intervenor_phone"
              :error="form.errors.intervenor_phone"
              placeholder="هاتف المسؤل هنا"
              label="هاتف المسؤل"
            />
            <TextAreaInput
              class="pb-8 pr-6 w-full lg:w-1/2"
              id="notes"
              v-model="form.notes"
              :error="form.errors.notes"
              placeholder="اكتب ملاحظاتك هنا"
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
              placeholder="عنوان الملف هنا"
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button class="btn-indigo" type="submit">إضافة</button>
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
    projects: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        beneficial_name: null,
        beneficial_phone: null,
        beneficial_cin: null,
        beneficial_address: null,
        type: null,
        value: null,
        date: null,
        notes: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        project: null,
        isSolitary: true,
      }),
    };
  },

  methods: {
    store() {
      this.form.post("/solitaryInterventions", {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
        },
      });
    },
  },
};
</script>
