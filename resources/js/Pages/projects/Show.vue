<template>
  <div>
    <!-- Intervention Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <Head :title="project.type" />
      <div class="relative">
        <button
          @click="goBack"
          class="absolute left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none sm:text-xs md:text-sm"
        >
          عودة
        </button>
      </div>
      <h2 class="mt-12 sm:text-xl md:text-2xl font-bold">المشروع / {{ project.name }}</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full">
          <tbody>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">تاريخ المشروع</td>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">{{ project.date }}</td>
            </tr>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">تاريخ إنتهاء المشروع</td>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">{{ project.deadline }}</td>
            </tr>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">مشروع عرضي</td>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">
                {{ project.isSolitary ? "نعم " : " لا " }}
              </td>
            </tr>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">الحالة</td>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">
                {{ project.status ? "مكتمل" : "قيد التنفيذ" }}
              </td>
            </tr>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">تفاصيل المشروع</td>
              <td
                class="sm:px-2 md:px-4 py-2 h-16 border"
                style="white-space: pre-line; word-wrap: break-word"
              >
                {{ project.description }}
              </td>
            </tr>
            <tr>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">التدخلات</td>
              <td class="sm:px-2 md:px-4 py-2 h-16 border">
                <!-- interventions -->
                {{ project.interventions.length }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <h2 class="mt-12 sm:text-xl md:text-2xl font-bold">التدخلات</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-right font-bold">
              <th class="pb-4 pt-6 px-6">النوع</th>
              <th class="pb-4 pt-6 px-6">القيمة</th>
              <th class="pb-4 pt-6 px-6">التاريخ</th>
              <th class="pb-4 pt-6 px-6">المسؤل</th>
              <th class="pb-4 pt-6 px-6">هاتف المسؤل</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="intervention in project.interventions"
              :key="intervention.id"
              class="hover:bg-gray-100 focus-within:bg-gray-100"
            >
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  {{ intervention.type }}
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  {{ intervention.value }}
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  {{ intervention.date }}
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4 focus:text-blue-500"
                  :href="`/interventions/${intervention.id}/show`"
                >
                  {{ intervention.intervenor }}
                  <icon
                    v-if="intervention.deleted_at"
                    name="trash"
                    class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                  />
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  {{ intervention.intervenor_phone }}
                </Link>
              </td>
            </tr>
            <tr v-if="project.interventions.length === 0">
              <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
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
    project: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {};
  },
  methods: {
    getLabel(intervenor, family) {
      if (intervenor && family.name) {
        return `تدخل من طرف ${intervenor} لفائدة المنتفع ${family.name}`;
      } else if (intervenor) {
        return `تدخل من طرف ${intervenor}`;
      } else if (family.name) {
        return `تدخل لفائدة المنتفع ${family.name}`;
      } else {
        return "أطراف التدخل غير محددة";
      }
    },
    goBack() {
      window.history.back();
    },
  },
};
</script>
