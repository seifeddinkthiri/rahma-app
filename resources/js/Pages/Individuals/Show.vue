<template>
  <div>
    <!-- individual Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <Head :title="form.name" />


      <div class="relative">
        <img
          v-if="individual.photo"
          :src="`/uploads/${individual.photo}`"
          alt="individual Image"
          class="w-32 h-32 rounded absolute top-0 left-0 -mt-6 -ml-6 pt-6 pl-6"
        />
        <h1 class="pl-28 text-3xl font-bold">
          <Link class="text-blue-400 hover:text-blue-600" href="/families">المنتفع</Link>
          <span class="text-blue-400 font-medium">/</span>
          {{ form.name }}
        </h1>
      </div>
      <div class="relative">
        <button
          @click="goBack"
          class="absolute left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        >
          عودة
        </button>
      </div>
      <h2 class="mt-12 text-2xl font-bold">الفرد</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow">
        <table class="w-full">
          <tbody>
            <tr>
              <td class="px-4 py-2 border h-16">الاسم</td>
              <td class="px-4 py-2 border h-16">{{ form.name }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">الهاتف</td>
              <td class="px-4 py-2 border h-16">{{ form.phone }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">العنوان</td>
              <td class="px-4 py-2 border h-16">{{ form.address }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 border h-16">الجنس</td>
              <td class="px-4 py-2 border h-16">
                <p v-if="individual.gender == 'male'">ذكر</p>
                <p v-else>أنثى</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Facilities -->
    <h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
    <br />
    <div class="p-4 bg-white rounded-md shadow">
      <table class="table-auto w-full">
        <tbody v-for="(facility, index) in individual.facilities" :key="index">
          <tr>
            <td class="px-4 py-2 border">الصرف الصحي</td>
            <td class="px-4 py-2 border" v-if="facility.Sanitation">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">الكهرباء</td>
            <td class="px-4 py-2 border" v-if="facility.electricity">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">الماء</td>
            <td class="px-4 py-2 border" v-if="facility.water">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="px-4 py-2 border">التهوئة</td>
            <td class="px-4 py-2 border" v-if="facility.ventilation">
              <icon name="check" />
            </td>
            <td class="px-4 py-2 border" v-else><icon name="minus" /></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Notes -->
    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="note in individual.notes.data" :key="note.id">
        <div class="p-4 bg-white rounded-md shadow">
          <h3 class="mb-2 text-xl font-bold">{{ note.title }}</h3>
          <p class="text-gray-700 truncate">{{ note.value }}</p>
        </div>
      </div>
      <div v-if="individual.notes.data.length === 0">
        <p class="text-center text-xl font-bold">لا يوجد ملاحظات</p>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="home in individual.home" :key="home.id">
        <div class="p-4 bg-white rounded-md shadow">
          <h3 class="mb-2 text-xl font-bold">
            <span v-if="home.status == 'Ownership'">ملك</span>
            <span v-else-if="home.status == 'without compensation'">بدون مقابل</span>
            <span v-else-if="home.status == 'inherited'">ورثة</span>
            <span v-else-if="home.status == 'lease'">إيجار</span>
          </h3>
          <p class="mb-2 text-gray-700" v-if="home.status == 'lease'">
            سعر الكراء: {{ home.allocation_price }}
          </p>
          <p class="text-gray-700 truncate">{{ home.desciption }}</p>
        </div>
      </div>
      <div v-if="individual.home.length === 0">
        <p class="text-center text-xl font-bold">لا يوجد مساكن</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    individual: Object,
  },
  remember: "form",

  data() {
    return {
      note_id: null,

      form: this.$inertia.form({
        name: this.individual.name,
        phone: this.individual.phone,
        address: this.individual.address,
        photo: this.individual.photo,
        id: this.individual.id,
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
