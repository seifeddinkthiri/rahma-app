<template>
  <div>
    <Head :title="form.intervenor" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/interventions"
        >التدخلات
      </Link>
      <span class="text-blue-400 font-medium">/</span>
      {{ form.intervenor }}
    </h1>
    <trashed-message v-if="intervention.deleted_at" class="mb-6" @restore="restore"
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
        </div>
        <div
          class="flex items-center space-x-3 px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <button
            v-if="!intervention.deleted_at"
            class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف التدخل
          </button>

          <loading-button
            :loading="form.processing"
            class="ml-auto inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
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
    families: Object,
    individuals: Object,
  },
  remember: "form",
  created() {
    if (this.intervention.family_id !== null) {
      this.form.beneficial = "family";
    } else if (this.intervention.individual_id !== null) {
      this.form.beneficial = "individual";
    }
  },
  data() {
    return {
      form: this.$inertia.form({
        beneficial: null,
        family: this.intervention.family_id,
        individual: this.intervention.individual_id,
        type: this.intervention.type,
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
    selectedType() {
      if (this.form.type === this.intervention.type) {
        this.form.value = this.intervention.value;
      } else {
        this.form.value = null;
      }
    },
    update() {
      this.form.post(`/interventions/${this.intervention.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا التدخل ؟")) {
        this.$inertia.delete(`/interventions/${this.intervention.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا التدخل ؟")) {
        this.$inertia.put(`/interventions/${this.intervention.id}/restore`);
      }
    },
  },
};
</script>
