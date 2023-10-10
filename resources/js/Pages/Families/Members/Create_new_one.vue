<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-xl font-bold">
      <Link
        class="text-blue-400 hover:text-blue-600"
        :href="`/families/${Family.id}/edit`"
        >الأفراد</Link
      >
      <span class="text-blue-400 font-medium"> : </span> إنشاء
    </h1>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div ref="part1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              placeholder=" الإسم هنا"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
              :disabled="isFormDisabled"
            />

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
              <option selected disabled hidden :value="null">إختر المدينة</option>
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
              <option value="قبلي">قبلي</option>
              <option value="بنزرت">بنزرت</option>
              <option value="القيروان">القيروان</option>
              <option value="قفصة">قفصة</option>
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
          </div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
              :disabled="isFormDisabled"
            >
              <option selected disabled hidden :value="null">إختر الحالة</option>

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
              <option value="illiterate ">لا شيء</option>
              <option value="primary ">إبتدائي</option>
              <option value="preparatory">إعدادي</option>
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
            <select-input
              v-if="HorW !== 'husband' && HorW !== 'wife'"
              v-model="form.kinship"
              :error="form.errors.kinship"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الفرد"
              :disabled="isFormDisabled"
            >
              <option selected disabled hidden :value="null">إختر نوع الفرد</option>
              <option value="child">إبن</option>
              <option value="elderly">مسن</option>
              <option value="other_member">فرد آخر</option>
              <option value="single_mother">أم عزباء</option>
              <option v-if="!hasHusband" value="husband">زوج</option>
              <option v-if="!hasWife" value="wife">زوجة</option>
            </select-input>
            <ToggleCheckbox
              :id="'grant'"
              :class="'lg:w-1/2'"
              :isChecked="form.grant"
              :label="'منحة إجتماعية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_grant"
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
            <p class="text-xl pb-8 pr-6 w-full font-bold">البيانات الصحية</p>
            <div class="flex flex-wrap -mb-8 -mr-6">
  <ToggleCheckbox
    :id="'health_insurance'"
    :class="'w-full lg:w-1/2'"
    :isChecked="form.health_insurance"
    :label="'التغطية الصحية'"
    :active_value="'نعم'"
    :inactive_value="'لا'"
    @toggle="toggle_health_insurance"
  />
  <ToggleCheckbox
    :id="'good'"
    :class="'w-full lg:w-1/2'"
    :isChecked="form.good"
    :label="'الحالة الصحية'"
    :active_value="'جيدة'"
    :inactive_value="'عليلة '"
    @toggle="toggle_health_Status"
  />
</div>

<!-- Section 2 -->
<div v-if="form.good == false" class="w-full mt-6">
  <div class="flex flex-wrap -mb-8 -mr-6">
    <text-input
      class="pb-8 pr-6 w-full lg:w-1/2"
      id="disease"
      :error="form.errors.disease"
      v-model="form.disease"
      label="مرض مزمن"
      placeholder="  المرض المزمن هنا"
      :disabled="isFormDisabled"
    />
    <text-input
      class="pb-8 pr-6 w-full lg:w-1/2"
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
            <loading-button :loading="form.processing" class="btn-indigo" type="submit">
              إضافة
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
import ToggleCheckbox from "../../../Shared/ToggleCheckbox.vue";
import FileInput from "@/Shared/FileInput";

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ToggleCheckbox,
    FileInput,
  },
  layout: Layout,
  remember: "form",
  props: {
    Family: Object,
    hasHusband: Boolean,
    hasWife: Boolean,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        photo: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
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
        grant: false,
        grant_source: null,
        grant_value: null,
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

    store() {
      this.form
        .post("/newMembers", {
          preserveScroll: true,
          onSuccess: () => {
            this.form.reset();
          },
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
