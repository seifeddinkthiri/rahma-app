<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/individuals">
        المنتفعين الأفراد
      </Link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="individual.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المنتفع
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
              label="تقتعريف"
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
            <button
              v-if="!individual.deleted_at"
              class="text-red-600 hover:underline"
              tabindex="-1"
              type="button"
              @click="destroy"
            >
              حذف
            </button>

            <button @click="active_step = 2" class="btn-indigo" type="button">
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
            <loading-button :loading="form.processing" class="btn-indigo" type="submit"
              >تسجيل التعديلات</loading-button
            >
          </div>
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
            />
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
            <ToggleCheckbox
              :id="'health_insurance'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              :label="'التغطية الصحية'"
              :isChecked="form.health_insurance"
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
              <option value="other_individual">فرد إضافي</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 1" class="btn-indigo" type="button">
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
            <loading-button :loading="form.processing" class="btn-indigo" type="submit"
              >تسجيل التعديلات</loading-button
            >
            <button @click="active_step = 3" class="btn-indigo" type="button">
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
        <div ref="part3" v-if="active_step == 3">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
            />
            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
            />
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button @click="active_step = 2" class="btn-indigo" type="button">
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
            <loading-button :loading="form.processing" class="btn-indigo" type="submit"
              >تسجيل التعديلات</loading-button
            >
          </div>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الحالة الصحية</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <form @submit.prevent="update_health_status">
        <div ref="part1" v-if="active_step == 1">
          <div
            class="flex flex-wrap -mb-8 -mr-6 p-8"
            v-for="status in individual.healthStatus"
            :key="status.id"
          >
            <text-input
              :label="'مرض مزمن'"
              v-model="health_status_form.disease"
              :error="form.errors.disease"
              class="pb-8 pr-6 w-full lg:w-1/2"
            />
            <text-input
              :label="'إعاقة'"
              v-model="health_status_form.disability"
              :error="form.errors.disability"
              class="pb-8 pr-6 w-full lg:w-1/2"
            />
            <text-input
              :label="'رقم بطاقة الإعاقة'"
              v-model="health_status_form.disability_card_number"
              :error="form.errors.disability_card_number"
              class="pb-8 pr-6 w-full lg:w-1/2"
            />
            <ToggleCheckbox
              :id="'good'"
              :active_value="'جيدة'"
              :inactive_value="'عليلة'"
              :label="'صفة الحالة'"
              :isChecked="health_status_form.good"
              @toggle="toggle_health"
            />
          </div>
          <div v-if="individual.healthStatus.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">لا يوجد تفاصيل الحالة الصحية</td>
          </div>
          <loading-button :loading="form.processing" class="btn-indigo" type="submit"
            >تسجيل التعديلات</loading-button
          >
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
    ToggleCheckbox,
  },
  layout: Layout,
  props: {
    individual: Object,
  },
  remember: "form",
  created() {
    if (this.individual.health_insurance == 0) {
      this.form.health_insurance = false;
    }
    if (this.individual.health_insurance == 1) {
      this.form.health_insurance = true;
    }

    this.individual.healthStatus.forEach((element) => {
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
        good: this.individual.healthStatus.good,
        disease: null,
        disability: null,
        disability_card_number: null,
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
        health_insurance: this.individual.health_insurance,
        education_level: this.individual.education_level,
        job: this.individual.job,
        job_place: this.individual.job_place,
      }),
    };
  },
  methods: {
    update_health_status() {
      this.health_status_form.put(`/healthStatus/${this.individual.id}`);
    },

    toggle_health_insurance() {
      this.form.health_insurance = !this.form.health_insurance;
    },
    toggle_health() {
      this.health_status_form.good = !this.health_status_form.good;
    },
    update() {
      this.form.put(`/individuals/${this.individual.id}`);
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
