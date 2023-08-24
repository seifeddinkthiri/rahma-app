<template>
  <div>
    <Head title="Create Organization " />
    <Breadcrumb_individuals
      :active_step="'individual'"
      :current_form_title="'  تعديل بيانات الفرد '"
    />
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              placeholder=" الإسم هنا"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
              :disabled="isFormDisabled"
            />
            <select-input
              v-model="form.gender"
              :error="form.errors.gender"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label=" الجنس "
            >
              <option hidden disabled :value="null">إختر الجنس</option>
              <option value="male">ذكر</option>
              <option value="female">أنثى</option>
            </select-input>
            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
              placeholder=" العنوان هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
              placeholder=" بطاقة التعريف الوطنية هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
              placeholder="الهاتف هنا"
              :disabled="isFormDisabled"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
              :disabled="isFormDisabled"
            >
              <option :value="null" disabled hidden>إختر المدينة</option>
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
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              :error="form.errors.birth_date"
              label="تاريخ الولادة"
              placeholder=" تاريخ الولادة هنا"
              :disabled="isFormDisabled"
            />

            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
              placeholder="  مكان العمل هنا"
              :disabled="isFormDisabled"
            />
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
              placeholder="   العمل هنا"
              :disabled="isFormDisabled"
            />

            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
              :disabled="isFormDisabled"
            >
              <option disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <select-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label=" المستوى الدراسي "
            >
              <option hidden disabled selected :value="null">إختر المستوى الدراسي</option>
              <option value="primary">إعدادي</option>
              <option value="secondary">ثاناوي</option>
              <option value="university ">جامعي</option>
            </select-input>

            <file-input
              v-model="form.photo"
              :error="form.errors.photo"
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="file"
              accept="image/*"
              label="الصورة "
            />
            <ToggleCheckbox
              :id="'grant'"
              :class="'lg:w-1/2'"
              :isChecked="form.grant"
              :label="'منحة إجتماعية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_grant"
              :isDisabled="isFormDisabled"
            />

            <div v-if="form.grant" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="source"
                  :error="form.errors.grant_source"
                  v-model="form.grant_source"
                  label="المصدر"
                  placeholder="المصدر هنا"
                  :disabled="isFormDisabled"
                />
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="value"
                  v-model="form.grant_value"
                  :error="form.errors.grant_value"
                  label="القيمة"
                  placeholder="القيمة بالدينار هنا"
                  :disabled="isFormDisabled"
                />
              </div>
            </div>
            <p class="text-18 pb-8 pr-6 w-full text-black font-bold">البيانات الصحة</p>
            <div class="flex flex-row flex-nowrap w-full">
              <ToggleCheckbox
                :id="'health_insurance'"
                :class="'lg:w-1/2'"
                :isChecked="form.health_insurance"
                :label="'التغطية الصحية'"
                :active_value="'نعم'"
                :inactive_value="'لا'"
                @toggle="toggle_health_insurance"
                :isDisabled="isFormDisabled"
              />
              <ToggleCheckbox
                :id="'good'"
                :class="'lg:w-1/2'"
                :isChecked="form.good"
                :label="'الحالة الصحية'"
                :active_value="'جيدة'"
                :inactive_value="'عليلة '"
                @toggle="toggle_health_Status"
                :isDisabled="isFormDisabled"
              />
            </div>
            <div v-if="form.good == false" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="form.errors.disease"
                  v-model="form.disease"
                  label="مرض مزمن"
                  placeholder="  المرض المزمن هنا"
                  :disabled="isFormDisabled"
                />
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="form.disability"
                  :error="form.errors.disability"
                  label=" إعاقة"
                  placeholder="الإعاقة هنا"
                  :disabled="isFormDisabled"
                />

                <text-input
                  v-model="form.disability_card_number"
                  :error="form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                  placeholder="الرقم هنا"
                  :disabled="isFormDisabled"
                />
              </div>
            </div>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <!-- Add a spacer element to create space between the buttons -->
            <div class="w-4"></div>
            <loading-button
              :loading="form.processing"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
            >
              التالي
            </loading-button>
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
import ToggleCheckbox from "@/Shared/ToggleCheckbox.vue";
import Breadcrumb_individuals from "@/Shared/Breadcrumb_individuals";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    FileInput,
    Breadcrumb_individuals,
    ToggleCheckbox,
  },
  layout: Layout,
  remember: "form",
  props: {
    Individual: Object,
  },
  created() {
    if (this.Individual.grant[0].source == null) {
      this.form.grant = false;
    } else {
      this.form.grant = true;
    }
  },
  data() {
    return {
      active_step: 1,
      form: this.$inertia.form({
        name: this.Individual.name,
        photo: null,
        phone: this.Individual.phone,
        address: this.Individual.address,
        cin: this.Individual.cin,
        gender: this.Individual.gender,
        birth_date: this.Individual.birth_date,
        birth_city: this.Individual.birth_city,
        social_status: this.Individual.social_status,
        education_level: this.Individual.education_level,
        job: this.Individual.job,
        job_place: this.Individual.job_place,
        good: this.Individual.healthStatus.good,
        health_insurance: this.Individual.healthStatus.health_insurance,
        disease: this.Individual.healthStatus.disease,
        disability: this.Individual.healthStatus.disability,
        disability_card_number: this.Individual.healthStatus.disability_card_number,
        Individual_id: this.Individual.id,
        grant: false,
        grant_source: this.Individual.grant[0].source,
        grant_value: this.Individual.grant[0].value,
      }),
    };
  },
  methods: {
    toggle_grant() {
      this.form.grant = !this.form.grant;
    },

    toggle_health_insurance() {
      this.form.health_insurance = !this.form.health_insurance;
    },
    toggle_health_Status() {
      this.form.good = !this.form.good;
    },
    update() {
      this.form.post(`/individuals/${this.Individual.id}/create_B_C_update`, {
        preserveScroll: true,
        onSuccess: () => {
          this.form.reset();
          this.active_step = 1;
        },
      });
    },
  },
};
</script>
