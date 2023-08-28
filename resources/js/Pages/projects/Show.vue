<template>
  <div>
    <!-- Intervention Card -->
    <div class="p-6 bg-white rounded-md shadow">
      <Head :title="project.type" />
      <div class="relative">
        <button
          @click="goBack"
          class="absolute left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        >
          عودة
        </button>
      </div>
      <h2 class="mt-12 text-2xl font-bold">المشروع / {{ project.name }}</h2>

      <div class="mt-8 p-4 bg-white rounded-md shadow">
        <table class="w-full">
          <tbody>
            <tr>
              <td class="px-4 py-2 h-16 border">تاريخ المشروع</td>
              <td class="px-4 py-2 h-16 border">{{ project.date }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">تاريخ إنتهاء المشروع</td>
              <td class="px-4 py-2 h-16 border">{{ project.deadline }}</td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">الحالة</td>
              <td class="px-4 py-2 h-16 border">
                {{ project.status ? "مكتمل" : "قيد التنفيذ" }}
              </td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">تفاصيل المشروع</td>
              <td
                class="px-4 py-2 h-16 border"
                style="white-space: pre-line; word-wrap: break-word"
              >
                {{ project.description }}
              </td>
            </tr>
            <tr>
              <td class="px-4 py-2 h-16 border">التدخلات</td>
              <td class="px-4 py-2 h-16 border">
                <!-- interventions -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                  <div
                    v-for="intervention in project.interventions"
                    :key="intervention.id"
                    class="relative"
                  >
                    <div
                      class="p-4 bg-white rounded-md shadow-md hover:shadow-lg transition-all"
                    >
                      <Link
                        :href="`/interventions/${intervention.id}/show`"
                        class="text-blue-600"
                      >
                        {{ intervention.intervenor }}
                      </Link>
                    </div>
                  </div>
                  <div v-if="project.interventions.length === 0">
                    <p class="text-center">لا يوجد تدخلات</p>
                  </div>
                </div>
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
    goBack() {
      window.history.back();
    },
  },
};
</script>
