<template>
  <div class="relative">
    <!-- Member Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <Head :title="member.name" />
      <ul class="absolute left-0 top-0 space-y-3 pt-4 pl-4">
        <li>
          <img
            v-if="member.photo"
            :src="`/uploads/${member.photo}`"
            alt="member Image"
            class="w-32 h-32 rounded block"
          />
        </li>
        <li>
          <button
            @click="goBack"
            class="absolute mx-8 left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          >
            عودة
          </button>
        </li>
      </ul>
      <br />
      <br /><br /><br />
      <h2 class="mt-12 text-2xl font-bold">الفرد / {{ member.name }}</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow">
        <table class="w-full">
          <tbody>
            <tr>
              <td class="h-16 px-4 py-2 border">تغطية صحية</td>
              <td
                class="h-16 px-4 py-2 border"
                v-if="health_status_form.health_insurance"
              >
                <icon name="check" />
              </td>
              <td class="h-16 px-4 py-2 border" v-else><icon name="minus" /></td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">معيل الأسرة</td>
              <td class="h-16 px-4 py-2 border" v-if="member.caregiver">
                <icon name="check" />
              </td>
              <td class="h-16 px-4 py-2 border" v-else><icon name="minus" /></td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">الاسم</td>
              <td class="h-16 px-4 py-2 border">{{ member.name }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">العنوان</td>
              <td class="h-16 px-4 py-2 border">{{ member.address }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">رقم البطاقة الوطنية</td>
              <td class="h-16 px-4 py-2 border">{{ member.cin }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">رقم الهاتف</td>
              <td class="h-16 px-4 py-2 border">{{ member.phone }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">تاريخ الميلاد</td>
              <td class="h-16 px-4 py-2 border">{{ member.birth_date }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">مدينة الميلاد</td>
              <td class="h-16 px-4 py-2 border">{{ member.birth_city }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">الحالة الاجتماعية</td>
              <td class="h-16 px-4 py-2 border">
                <p v-if="member.social_status == 'single'">أعزب/عزباء</p>
                <p v-if="member.social_status == 'divorced'">مطلق/مطلقة</p>
                <p v-if="member.social_status == 'widower'">أرمل/أرملة</p>
                <p v-if="member.social_status == 'married'">متزوج/متزوجة</p>
              </td>
            </tr>

            <tr>
              <td class="h-16 px-4 py-2 border">صلة القرابة</td>
              <td class="h-16 px-4 py-2 border">
                <p v-if="member.kinship == 'husband'">زوج</p>
                <p v-if="member.kinship == 'wife'">زوجة</p>
                <p v-if="member.kinship == 'child'">إبن</p>
                <p v-if="member.kinship == 'elderly'">مسن</p>
                <p v-if="member.kinship == 'other_member'">فرد إضافي</p>
              </td>
            </tr>

            <tr>
              <td class="h-16 px-4 py-2 border">المستوى الدراسي</td>
              <td class="h-16 px-4 py-2 border">
                <p v-if="member.education_level == 'illiterate'">لا شيء</p>
                <p v-if="member.education_level == 'primary'">إبتدائي</p>
                <p v-if="member.education_level == 'preparatory'">إعدادي</p>
                <p v-if="member.education_level == 'secondary'">ثاناوي</p>
                <p v-if="member.education_level == 'university'">جامعي</p>
              </td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">الوظيفة</td>
              <td class="h-16 px-4 py-2 border">{{ member.job }}</td>
            </tr>
            <tr>
              <td class="h-16 px-4 py-2 border">مكان العمل</td>
              <td class="h-16 px-4 py-2 border">{{ member.job_place }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">منحة إجتماعية</td>
              <td class="px-4 py-2 border h-16" v-if="member.grant[0].source !== null">
                <p>{{ member.grant[0].source }} : {{ member.grant[0].value }}</p>
              </td>
            </tr>
            <tr v-if="health_status_form.disease">
              <td class="h-16 px-4 py-2 border">مرض مزمن</td>
              <td class="h-16 px-4 py-2 border">{{ health_status_form.disease }}</td>
            </tr>

            <tr v-if="health_status_form.disability">
              <td class="h-16 px-4 py-2 border">إعاقة</td>
              <td class="h-16 px-4 py-2 border">{{ health_status_form.disability }}</td>
            </tr>
            <tr v-if="health_status_form.disability">
              <td class="h-16 px-4 py-2 border">رقم بطاقة الإعاقة</td>
              <td class="h-16 px-4 py-2 border">
                {{ health_status_form.disability_card_number }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Head,
    Icon,
  },
  layout: Layout,
  props: {
    member: {
      type: Object,
      required: true,
    },
  },
  created() {
    this.member.healthStatus.forEach((element) => {
      this.health_status_form.health_insurance = element.health_insurance;
      this.health_status_form.good = element.good;
      this.health_status_form.disease = element.disease;
      this.health_status_form.disability = element.disability;
      this.health_status_form.disability_card_number = element.disability_card_number;
    });
  },
  data() {
    return {
      health_status_form: this.$inertia.form({
        good: this.member.healthStatus.good,
        health_insurance: this.member.healthStatus.health_insurance,
        disease: this.member.healthStatus.disease,
        disability: this.member.healthStatus.disability,
        disability_card_number: this.member.healthStatus.disability_card_number,
        member_id: this.member.id,
      }),
    };
  },
  methods: {
    goBack() {
      window.history.back();
    },
  },
};
</script>
