<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/organizations"
        >الأفراد</Link
      >
      <span class="text-indigo-400 font-medium">/</span> إنشاء
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
            />
            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
            />
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              label="تاريخ الولادة"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
            >
              <option value="Medenine">Medenine</option>
              <option value="Beja">Beja</option>
              <option value="Tunis">Tunis</option>
              <option value="Sfax">Sfax</option>
              <option value="Sousse">Sousse</option>
              <option value="Ariana">Ariana</option>
              <option value="Ben Arous">Ben Arous</option>
              <option value="Kasserine">Kasserine</option>
              <option value="Le Kef">Le Kef</option>
              <option value="Mahdia">Mahdia</option>
              <option value="Manouba">Manouba</option>
              <option value="Monastir">Monastir</option>
              <option value="Nabeul">Nabeul</option>
              <option value="Sidi Bouzid">Sidi Bouzid</option>
              <option value="Siliana">Siliana</option>
              <option value="Gabes">Gabes</option>
              <option value="Jendouba">Jendouba</option>
              <option value="Tataouine">Tataouine</option>
              <option value="Tozeur">Tozeur</option>
              <option value="Zaghouan">Zaghouan</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 2" class="btn-indigo" type="button">
              التالي
            </button>
          </div>
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الوضعية الأجتماعية"
            >
              <option value="single">أعزب</option>
              <option value="married">متزوج</option>
              <option value="divorced">مطلق</option>
            </select-input>
            <text-input
              v-model="form.monthly_income"
              :error="form.errors.monthly_income"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الدخل الشهري"
            />
            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
            />
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
            />

            <text-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المستوى الدراسي"
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 1" class="btn-indigo" type="button">
              العودة
            </button>
            &nbsp;&nbsp;
            <button @click="active_step = 3" class="btn-indigo" type="button">
              التالي
            </button>
          </div>
        </div>
        <div ref="part3" v-if="active_step == 3">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <ToggleCheckbox
              :id="'good'"
              :isChecked="form.good"
              :label="'الحالة الصحية'"
              :active_value="'جيدة'"
              :inactive_value="'عليلة'"
              @toggle="toggle_health_Status"
            />
            <ToggleCheckbox
              :id="'health_insurance'"
              :isChecked="form.health_insurance"
              :label="'التغطية الصحية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_health_insurance"
            />

            <select-input
              v-model="form.kinship"
              :error="form.errors.kinship"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="القرابة العائلية"
            >
              <option value="husband">زوج</option>
              <option value="wife">زوجة</option>
              <option value="child">إبن</option>
              <option value="elderly">مسن</option>
              <option value="other_member">فرد إضافي</option>
            </select-input>
            <text-input
              v-model="form.disease"
              :error="form.errors.disease"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مرض مزمن"
            />
            <text-input
              v-model="form.disability"
              :error="form.errors.disability"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="إعاقة"
            />
            <text-input
              v-model="form.disability_card_number"
              :error="form.errors.disability_card_number"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="رقم بطاقة الإعاقة"
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 2" class="btn-indigo" type="button">
              العودة
            </button>
            <loading-button :loading="form.processing" class="btn-indigo" type="submit"
              >إنشاء الفرد</loading-button
            >
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
import ToggleCheckbox from "../../../Shared/ToggleCheckbox.vue";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ToggleCheckbox,
  },
  layout: Layout,
  remember: "form",
  props: {
    Family: Object,
  },
  data() {
    return {
      active_step: 1,

      form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
        monthly_income: null,
        health_insurance: false,
        kinship: null,
        caregiver: false,
        education_level: null,
        job: null,
        job_place: null,
        good: false,
        disease: null,
        disability: null,
        disability_card_number: null,
      }),
    };
  },
  methods: {
    toggle_health_insurance() {
      this.form.health_insurance = !this.form.health_insurance;
    },

    toggle_health_Status() {
      this.form.good = !this.form.good;
    },
    store() {
      this.form
        .post("/newMembers", {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
            this.active_step = 1;
          },
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
