<template>
  <div>
    <!-- Intervention Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <Head :title="intervention.type" />
      <div class="relative">
        <button
          @click="goBack"
          class="absolute left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        >
          عودة
        </button>
      </div>

      <h2 class="mt-12 text-2xl font-bold">تفاصيل التدخل / {{ intervention.type }}</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow">
        <table class="w-full">
          <tbody>
            <tr
              v-if="intervention.family[0]"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="px-4 py-2 h-16 border">
                <Link :href="`/families/${intervention.family[0].id}/show`">العائلة</Link>
              </td>
              <td class="px-4 py-2 h-16 border">
                <Link :href="`/families/${intervention.family[0].id}/show`">{{
                  intervention.family[0].name
                }}</Link>
              </td>
            </tr>
            <tr
              v-if="intervention.individual[0]"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="px-4 py-2 h-16 border">
                <Link :href="`/individuals/${intervention.individual[0].id}/show`"
                  >الفرد</Link
                >
              </td>
              <td class="px-4 py-2 h-16 border">
                <Link :href="`/individuals/${intervention.individual[0].id}/show`">{{
                  intervention.individual[0].name
                }}</Link>
              </td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">نوع التدخل</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.type }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">قيمة التدخل</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.value }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">المتدخل</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.intervenor }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">رقم هاتف المتدخل</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.intervenor_phone }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">الملفات</td>
              <td class="px-4 py-2 h-16 border">
                <!-- files -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                  <div v-for="file in intervention.files" :key="file.id" class="relative">
                    <div
                      class="p-4 bg-white rounded-md shadow-md hover:shadow-lg transition-all"
                    >
                      <a :href="getFileUrl(file.file)" class="text-blue-600">{{
                        file.title
                      }}</a>
                      <button
                        @click="deleteFile(file.id)"
                        type="button"
                        class="absolute top-0 left-0 m-2"
                      >
                        <icon name="close" class="text-red-600" />
                      </button>
                    </div>
                  </div>
                  <div v-if="intervention.files.length === 0">
                    <p class="text-center">لا يوجد ملفات</p>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">الملاحظات</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.notes }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">تاريخ الحذف</td>
              <td class="px-4 py-2 h-16 border">{{ intervention.deleted_at }}</td>
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
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    intervention: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {};
  },
  methods: {
    deleteFile(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا الملف ؟")) {
        this.$inertia.delete(`/files/${id}`);
      }
    },
    goBack() {
      window.history.back();
    },
    getFileUrl(fileName) {
      const baseUrl = "http://127.0.0.1:8000";
      return `${baseUrl}/${fileName}`;
    },
  },
};
</script>
