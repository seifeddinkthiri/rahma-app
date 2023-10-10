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
            <select-input
              v-model="form.beneficial"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="تدخل لفائدة "
              :error="form.errors.beneficial"
              ref="beneficialSelect"
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
              ref="familySelect"
            >
              <option hidden disabled :value="null">إختر العائلة</option>

              <option v-for="family in filteredFamilies" :value="family.id">
                {{ family.caregiver_phone }} - {{ family.name }}
              </option>
              <option disabled :value="null" v-if="filteredFamilies.length == 0">
                قائمة فارغة
              </option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'elderly'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المسن"
              ref="familySelect"
            >
              <option hidden disabled :value="null">إختر المسن</option>

              <option v-for="elderly in elderlies" :value="elderly.id">
                {{ elderly.caregiver_phone }} - {{ elderly.name }}
              </option>
              <option disabled :value="null" v-if="elderlies.length == 0">قائمة فارغة</option>
            </select-input>
            <select-input
              v-if="form.beneficial == 'divorced'"
              v-model="form.family"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المطلقة"
              ref="familySelect"
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
              ref="familySelect"
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
              ref="familySelect"
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
    families: Object,
    elderlies: Object,
    divorceds: Object,
    singleMothers: Object,
    widows: Object,
    projects: Object,
  },
  remember: "form",
  data() {
    return {
      form: this.$inertia.form({
        beneficial: null,
        type: null,
        value: null,
        date: null,
        notes: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        family: null,
        project: null,
        isSolitary: false,
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
          this.$refs.beneficialSelect.clearSelection();
          this.$refs.projectSelect.clearSelection();
          this.$refs.typeSelect.clearSelection();
          this.$refs.familySelect.clearSelection();
        },
      });
    },
  },
};
</script>
