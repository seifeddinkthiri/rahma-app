<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/individuals">
        المنتفعين الأفراد
      </Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="individual.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المنتفع
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
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
              <option value="Medenine">مدنين</option>
              <option value="Beja">باجة</option>
              <option value="Tunis">تونس</option>
              <option value="Sfax">صفاقس</option>
              <option value="Sousse">سوسة</option>
              <option value="Ariana">أريانة</option>
              <option value="Ben Arous">بن عروس</option>
              <option value="Kasserine">القصرين</option>
              <option value="Le Kef">الكاف</option>
              <option value="Mahdia">المهدية</option>
              <option value="Manouba">منوبة</option>
              <option value="Monastir">المنستير</option>
              <option value="Nabeul">نابل</option>
              <option value="Sidi Bouzid">سيدي بوزيد</option>
              <option value="Siliana">سليانة</option>
              <option value="Gabes">قابس</option>
              <option value="Jendouba">جندوبة</option>
              <option value="Tataouine">تطاوين</option>
              <option value="Tozeur">توزر</option>
              <option value="Zaghouan">زغوان</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              v-if="!individual.deleted_at"
              class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
              tabindex="-1"
              type="button"
              @click="destroy"
            >
              حذف
            </button>

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
            <div class="mr-3">
              <loading-button
                :loading="form.processing"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                type="submit"
                >تسجيل التعديلات</loading-button
              >
            </div>
          </div>
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
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
            <select-input
              v-model="form.gender"
              :error="form.errors.gender"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label=" الجنس "
            >
              <option :value="null" />
              <option value="male">ذكر</option>
              <option value="female">أنثى</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              v-if="!individual.deleted_at"
              class="text-red-600 hover:underline"
              tabindex="-1"
              type="button"
              @click="destroy"
            >
              حذف
            </button>
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
              >تسجيل التعديلات</loading-button
            >
          </div>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الحالة الصحية</h2>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update_health_status">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <div class="w-full flex flex-row flex-nowrap">
            <ToggleCheckbox
              :id="'health_insurance'"
              :class="'lg:w-1/2'"
              :isChecked="health_status_form.health_insurance"
              :label="'التغطية الصحية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_health_insurance"
            />
            <ToggleCheckbox
              :id="'good'"
              :class="'lg:w-1/2'"
              :isChecked="health_status_form.good"
              :label="'الحالة الصحية'"
              :active_value="'جيدة'"
              :inactive_value="'عليلة '"
              @toggle="toggle_health_Status"
            />
          </div>
          <div v-if="health_status_form.good == false" class="w-full">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
              <div class="w-full flex flex-row flex-nowrap">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="health_status_form.errors.disease"
                  v-model="health_status_form.disease"
                  label=" المرض المزمن"
                />
              </div>
              <div class="w-full flex flex-row flex-nowrap">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="health_status_form.disability"
                  :error="health_status_form.errors.disability"
                  label=" الإعاقة"
                />
                <text-input
                  v-model="health_status_form.disability_card_number"
                  :error="health_status_form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                />
              </div>
            </div>
          </div>
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button
            :loading="form.processing"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
            >تسجيل التعديلات
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
import ToggleCheckbox from "@/Shared/ToggleCheckbox.vue";

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
    ToggleCheckbox,
  },
  layout: Layout,
  props: {
    individual: Object,
  },
  remember: "form",
  created() {
    this.individual.healthStatus.forEach((element) => {
      this.health_status_form.health_insurance = element.health_insurance;
      this.health_status_form.good = element.good;
      this.health_status_form.disease = element.disease;
      this.health_status_form.disability = element.disability;
      this.health_status_form.disability_card_number = element.disability_card_number;
    });
  },

  data() {
    return {
      active_step: 1,
      healthStatusFields: [],
      health_status_form: this.$inertia.form({
        health_insurance: this.individual.healthStatus.health_insurance,
        good: this.individual.healthStatus.good,
        disease: this.individual.healthStatus.disease,
        disability: this.individual.healthStatus.disability,
        disability_card_number: this.individual.healthStatus.disability_card_number,
        individual_id: this.individual.id,
      }),
      form: this.$inertia.form({
        photo: this.individual.photo,
        phone: this.individual.phone,
        name: this.individual.name,
        address: this.individual.address,
        cin: this.individual.cin,
        gender: this.individual.gender,
        birth_date: this.individual.birth_date,
        birth_city: this.individual.birth_city,
        social_status: this.individual.social_status,
        monthly_income: this.individual.monthly_income,
        education_level: this.individual.education_level,
        job: this.individual.job,
        job_place: this.individual.job_place,
      }),
    };
  },
  methods: {
    toggle_health_Status() {
      this.health_status_form.good = !this.health_status_form.good;
    },

    toggle_health_insurance() {
      this.health_status_form.health_insurance = !this.health_status_form
        .health_insurance;
    },
    update() {
      this.form.put(`/individuals/${this.individual.id}`);
    },
    update_health_status() {
      this.health_status_form.put(`/healthStatus/${this.individual.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا الفرد ؟")) {
        this.$inertia.delete(`/individuals/${this.individual.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا الفرد ؟")) {
        this.$inertia.put(`/individuals/${this.individual.id}/restore`);
      }
    },
  },
};
</script>
