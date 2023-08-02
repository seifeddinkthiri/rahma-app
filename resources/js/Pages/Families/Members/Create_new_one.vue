<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link
        class="text-blue-400 hover:text-blue-600"
        :href="`/families/${Family.id}/edit`"
        >الأفراد</Link
      >
      <span class="text-blue-400 font-medium">/</span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <div class="w-full flex flex-row flex-nowrap">
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
            </div>
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
              :error="form.errors.birth_date"
              label="تاريخ الولادة"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
            >
              <option value="مدنين">مدنين</option>
              <option value="باجة">باجة</option>
              <option value="تونس">تونس</option>
              <option value="صفاقس">صفاقس</option>
              <option value="سوسة">سوسة</option>
              <option value="أريانة">أريانة</option>
              <option value="بن عروس">بن عروس</option>
              <option value="القصرين">القصرين</option>
              <option value="الكاف">الكاف</option>
              <option value="المهدية">المهدية</option>
              <option value="منوبة">منوبة</option>
              <option value="المنستير">المنستير</option>
              <option value="نابل">نابل</option>
              <option value="سيدي بوزيد">سيدي بوزيد</option>
              <option value="سليانة">سليانة</option>
              <option value="قابس">قابس</option>
              <option value="جندوبة">جندوبة</option>
              <option value="تطاوين">تطاوين</option>
              <option value="توزر">توزر</option>
              <option value="زغوان">زغوان</option>
            </select-input>

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
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              @click="active_step = 2"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="button"
            >
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
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
            >
              <option :value="null" />
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>
            <text-input
              v-model="form.monthly_income"
              :error="form.errors.monthly_income"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الدخل الشهري"
            />

            <text-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المستوى الدراسي"
            />
            <p class="w-full text-black font-bold text-18 pb-8 pr-6">البيانات الصحة</p>
            <div class="w-full flex flex-row flex-nowrap">
              <ToggleCheckbox
                :id="'health_insurance'"
                :class="'lg:w-1/2'"
                :isChecked="form.health_insurance"
                :label="'التغطية الصحية'"
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_health_insurance"
              />
              <ToggleCheckbox
                :id="'good'"
                :class="'lg:w-1/2'"
                :isChecked="form.good"
                :label="'الحالة الصحية'"
                :active_value="'جيدة'"
                :inactive_value="'عليلة '"
                @toggle="toggle_health_Status"
              />
            </div>
            <div v-if="form.good == false" class="w-full">
              <div class="w-full flex flex-row flex-nowrap">
                <ToggleCheckbox
                  :id="'disease_verif'"
                  :class="'pb-8 pr-6 w-full'"
                  :isChecked="form.disease_verif"
                  :label="'مرض مزمن'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_disease"
                />
                <text-input
                  v-if="form.disease_verif"
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="form.errors.disease"
                  v-model="form.disease"
                  label="أذكر المرض المزمن"
                />
                <text-input
                  v-else
                  disabled
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  label="لا يوجد"
                />
              </div>
              <div class="w-full flex flex-row flex-nowrap">
                <ToggleCheckbox
                  :id="'disability_verif'"
                  :isChecked="form.disability_verif"
                  :label="'إعاقة'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  @toggle="toggle_disability"
                />
                <text-input
                  v-if="form.disability_verif"
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="form.disability"
                  :error="form.errors.disability"
                  label=" أذكر الإعاقة"
                />
                <text-input
                  v-else
                  disabled
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  label="لا يوجد"
                />
                <text-input
                  v-if="form.disability_verif"
                  v-model="form.disability_card_number"
                  :error="form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                />
              </div>
            </div>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              @click="active_step = 1"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="button"
            >
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
                  d="M8.25 4.5l7.5 7.5-7.5 7.5"
                />
              </svg>
            </button>
            <loading-button
              :loading="form.processing"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
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
        good: true,
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
    toggle_disease() {
      this.form.disease_verif = !this.form.disease_verif;
      this.form.disease = "";
    },
    toggle_disability() {
      this.form.disability_verif = !this.form.disability_verif;
      this.form.disability = "";
      this.form.disability_card_number = "";
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
