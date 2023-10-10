<template>
  <div>
    <Head :title="form.intervenor" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/interventions"
        >التدخلات
      </Link>
      <span class="text-blue-400 font-medium"> : </span>
      {{ form.intervenor }}
    </h1>
    <trashed-message v-if="display_restore_button" class="mb-6" @restore="restore"
      >تم حذف هذا التدخل .
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="في إطار مشروع"
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
              placeholder="عنوان الملف هنا"
              label="عنوان الملف"
            />
          </div>
        </div>
        <div
          class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100 space-x-3"
        >
          <button
            v-if="!intervention.deleted_at"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-red-500 hover:bg-red-600 rounded focus:outline-none focus:ring-red-600 focus:ring-opacity-50"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف التدخل
          </button>

          <loading-button
            :loading="form.processing"
            class="inline-flex items-center justify-center ml-auto px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل التدخل
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
import TextAreaInput from "@/Shared/TextareaInput";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    TextAreaInput,
  },
  layout: Layout,
  props: {
    intervention: Object,
    projects: Object,
  },
  remember: "form",

  data() {
    return {
      display_restore_button: false,
      form: this.$inertia.form({
        beneficial_name: this.intervention.beneficial_name,
        beneficial_phone: this.intervention.beneficial_phone,
        beneficial_cin: this.intervention.beneficial_cin,
        beneficial_address: this.intervention.beneficial_address,
        project: this.intervention.project_id,
        type: this.intervention.type,
        date: this.intervention.date,
        value: this.intervention.value,
        intervenor: this.intervention.intervenor,
        intervenor_phone: this.intervention.intervenor_phone,
        notes: this.intervention.notes,
        file: null,
        title: null,
      }),
      intervention: {
        type: this.intervention.type, // Assuming this is the expected type for the intervention
        value: this.intervention.value, // Assuming this is the value you want to assign to form.value
      },
    };
  },

  methods: {
    update() {
      this.form.post(`/interventions/${this.intervention.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا التدخل ؟")) {
        this.$inertia.delete(`/interventions/${this.intervention.id}`);
        this.display_restore_button = true;
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا التدخل ؟")) {
        this.$inertia.put(`/interventions/${this.intervention.id}/restore`);
        this.display_restore_button = false;
      }
    },
  },
};
</script>
