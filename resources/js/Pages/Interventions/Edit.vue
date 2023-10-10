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
            <select-input
              v-model="form.beneficial"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="نوع المنتفع"
              :error="form.errors.beneficial"
            >
              <option :value="null" selected disabled hidden>إختر نوع المنتفع</option>
              <option value="family">عائلة معوزة</option>
              <option value="elderly">مسن</option>
              <option value="divorced">مطلقة</option>
              <option value="widow">أرملة</option>
              <option value="single_mother">أم عزباء</option>
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
              <option :value="null" v-if="filteredFamilies.length == 0" disabled>
                قائمة فارغة
              </option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'elderly'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المسن"
            >
              <option hidden disabled :value="null">إختر المسن</option>

              <option v-for="elderly in elderlies" :value="elderly.id">
                {{ elderly.caregiver_phone }} - {{ elderly.name }}
              </option>
              <option :value="null" v-if="elderlies.length == 0" disabled>قائمة فارغة</option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'divorced'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المطلقة"
            >
              <option hidden disabled :value="null">إختر المطلقة</option>

              <option v-for="divorced in divorceds" :value="divorced.id">
                {{ divorced.caregiver_phone }} - {{ divorced.name }}
              </option>
              <option disabled :value="null" v-if="divorceds.length == 0">قائمة فارغة</option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'single_mother'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الأم العزباء"
            >
              <option hidden disabled :value="null">إختر الأم العزباء</option>

              <option v-for="singleMother in singleMothers" :value="singleMother.id">
                {{ singleMother.caregiver_phone }} - {{ singleMother.name }}
              </option>
              <option :value="null" v-if="singleMothers.length == 0" disabled>قائمة فارغة</option>
            </select-input>

            <select-input
              v-if="form.beneficial == 'widow'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الأرملة"
            >
              <option hidden disabled :value="null">إختر الأرملة</option>

              <option v-for="widow in widows" :value="widow.id">
                {{ widow.caregiver_phone }} - {{ widow.name }}
              </option>
              <option :value="null" v-if="widows.length == 0" disabled>قائمة فارغة</option>
            </select-input>
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
    elderlies: Object,
    divorceds: Object,
    singleMothers: Object,
    widows: Object,
    families: Object,
    projects: Object,
    beneficial: String,
  },
  remember: "form",

  data() {
    return {
      display_restore_button: false,
      form: this.$inertia.form({
        beneficial: this.beneficial,
        family: this.intervention.family_id,
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
  computed: {
    filteredFamilies() {
      return this.families.filter((family) => family.name !== null);
    },
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
