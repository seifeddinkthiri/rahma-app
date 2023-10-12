<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/members">الأفراد</Link>
      <span class="text-blue-400 font-medium"> : </span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="member.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا الفرد
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div ref="part1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
              placeholder="الإسم هنا"
            />
            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
              placeholder="العنوان هنا"
            />
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
              placeholder="بطاقة التعريف الوطنية هنا"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
              placeholder="الهاتف هنا"
            />

            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              label="تاريخ الولادة"
              placeholder="تاريخ الولادة هنا"
            />

            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
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
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          ></div>
        </div>
        <div ref="part2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية"
            >
              <option selected disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
              placeholder="العمل هنا"
            />
            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل"
              placeholder="مكان العمل هنا"
            />

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

            <select-input
              v-model="form.kinship"
              :error="form.errors.kinship"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="  نوع الفرد"
            >
              <option selected disabled hidden :value="null">إختر نوع الفرد</option>
              <option value="husband">زوج</option>
              <option value="wife">زوجة</option>
              <option value="child">إبن</option>
              <option value="elderly">مسن</option>
              <option value="single_mother">أم عزباء</option>
              <option value="other_member">فرد إضافي</option>
            </select-input>

            <file-input
              v-model="form.photo"
              :error="form.errors.photo"
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="file"
              accept="image/*"
              label="الصورة"
            />

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
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <div class="mr-3">
              <button
                @click="destroy"
                v-if="!member.deleted_at"
                class="px-4 py-2 text-white font-semibold bg-red-500 hover:bg-red-600 rounded focus:outline-none shadow-md focus:ring-2 focus:ring-red-600 focus:ring-opacity-50"
              >
                حذف
              </button>
            </div>
            <div class="mr-3">
              <loading-button
                :loading="form.processing"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                type="submit"
              >
                تسجيل التعديلات
              </loading-button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الحالة الصحية</h2>
<div class="bg-white rounded-md shadow overflow-hidden">
  <form @submit.prevent="update_health_status">
    <div class="flex flex-wrap -mb-8 -mr-6 p-8">
      <div class="flex flex-col lg:flex-row flex-wrap w-full">
        <ToggleCheckbox
          :id="'health_insurance'"
          :class="'w-full lg:w-1/2'"
          :isChecked="health_status_form.health_insurance"
          :label="'التغطية الصحية'"
          :active_value="'نعم'"
          :inactive_value="'لا'"
          @toggle="toggle_health_insurance"
        />
        <ToggleCheckbox
          :id="'good'"
          :class="'w-full lg:w-1/2'"
          :isChecked="health_status_form.good"
          :label="'الحالة الصحية'"
          :active_value="'جيدة'"
          :inactive_value="'عليلة '"
          @toggle="toggle_health_Status"
        />
      </div>
      <div v-if="health_status_form.good == false" class="w-full">
        <div class="flex flex-col lg:flex-row flex-wrap -mb-8 -mr-6">
          <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="disease"
            :error="health_status_form.errors.disease"
            v-model="health_status_form.disease"
            label="مرض مزمن"
            placeholder="  المرض المزمن هنا"
          />
          <text-input
            class="pb-8 pr-6 w-full lg:w-1/2"
            id="disability"
            v-model="health_status_form.disability"
            :error="health_status_form.errors.disability"
            label=" إعاقة"
            placeholder="الإعاقة هنا"
          />

          <text-input
            v-model="health_status_form.disability_card_number"
            :error="health_status_form.errors.disability_card_number"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="رقم بطاقة الإعاقة"
            placeholder="الرقم هنا"
          />
        </div>
      </div>
    </div>
    <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
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
import ToggleCheckbox from "../../../Shared/ToggleCheckbox.vue";
import FileInput from "@/Shared/FileInput";

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
    FileInput,
  },
  layout: Layout,
  props: {
    member: Object,
  },
  remember: "form",
  created() {
    this.member.healthStatus.forEach((element) => {
      this.health_status_form.health_insurance = element.health_insurance;
      this.health_status_form.good = element.good;
      this.health_status_form.disease = element.disease;
      this.health_status_form.disability = element.disability;
      this.health_status_form.disability_card_number = element.disability_card_number;
    });

    if (this.member.grant[0].source == null) {
      this.form.grant = false;
    } else {
      this.form.grant = true;
    }
  },

  data() {
    return {
      healthStatusFields: [],
      health_status_form: this.$inertia.form({
        good: this.member.healthStatus.good,
        health_insurance: this.member.healthStatus.health_insurance,
        disease: this.member.healthStatus.disease,
        disability: this.member.healthStatus.disability,
        disability_card_number: this.member.healthStatus.disability_card_number,
        member_id: this.member.id,
      }),
      form: this.$inertia.form({
        name: this.member.name,
        address: this.member.address,
        cin: this.member.cin,
        phone: this.member.phone,
        photo: null,
        birth_date: this.member.birth_date,
        birth_city: this.member.birth_city,
        social_status: this.member.social_status,
        kinship: this.member.kinship,
        education_level: this.member.education_level,
        job: this.member.job,
        job_place: this.member.job_place,
        family_id: this.member.family_id,
        grant: false,
        grant_source: this.member.grant[0].source,
        grant_value: this.member.grant[0].value,
      }),
    };
  },
  methods: {
    toggle_grant() {
      this.form.grant = !this.form.grant;
    },
    update_health_status() {
      this.health_status_form.put(`/healthStatusMemeber/${this.member.id}`);
    },

    toggle_health_insurance() {
      this.health_status_form.health_insurance = !this.health_status_form
        .health_insurance;
    },
    toggle_health_Status() {
      this.health_status_form.good = !this.health_status_form.good;
    },
    update() {
      this.form.post(`/members_update/${this.member.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا الفرد ؟")) {
        this.$inertia.delete(`/members/${this.member.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا الفرد ؟")) {
        this.$inertia.put(`/members/${this.member.id}/restore`);
      }
    },
  },
};
</script>
