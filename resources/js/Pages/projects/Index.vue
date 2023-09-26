<template>
  <div>
    <Head title="Interventions" />
    <trashed-message
      v-if="deleted_project_id"
      class="mb-6"
      @restore="restore(deleted_project_id)"
      >تم حذف هذا المشروع .
    </trashed-message>
    <h1 class="mb-8 text-xl font-bold">المشاريع</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >


      <label class="block text-gray-700 mt-2 mb-2">حالة المشروع</label>
<select v-model="form.status" class="form-select mt-1 w-full">
  <option value="completed">مكتمل</option>
   <option value="ongoing">قيد التنفيذ</option>

</select>




        <label class="block text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
      <Link
        class="btn-indigo"
        href="/projects/create"
      >
        <span>إنشاء</span>
        <span class="hidden md:inline">&nbsp;المشروع</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">اسم المشروع</th>
            <th class="pb-4 pt-6 px-6">وصف المشروع</th>
            <th class="pb-4 pt-6 px-6">تاريخ بدء المشروع</th>
            <th class="pb-4 pt-6 px-6">تاريخ الإنتهاء المتوقع</th>
            <th class="pb-4 pt-6 px-6">الحالة</th>
            <th class="pb-4 pt-6 px-6">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="project in projects.data"
            :key="project?.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/projects/${project.id}/edit`"
                tabindex="-1"
              >
                {{ project.name }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/projects/${project.id}/edit`"
                tabindex="-1"
              >
                <div class="overflow-x-auto w-80 ...">
                  <p class="truncate">
                    {{ project.description }}
                  </p>
                </div>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/projects/${project.id}/edit`"
                tabindex="-1"
              >
                {{ project.date }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/projects/${project.id}/edit`"
                tabindex="-1"
              >
                {{ project.deadline }}
              </Link>
            </td>
            <td class="border-t">
              <span
                class="px-4 py-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ project.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
              >
                {{ project.status ? "مكتمل" : "قيد التنفيذ" }}
              </span>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/projects/${project.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/projects/${project.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(project.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="projects.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="6">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="projects.links" />
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import Pagination from "@/Shared/Pagination";
import SearchFilter from "@/Shared/SearchFilter";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    filters: Object,
    projects: Object,
  },
  data() {
    return {
      deleted_project_id: null,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        status: this.filters.status,

      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/projects", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    destroy(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المشروع ؟")) {
        this.$inertia.delete(`/projects/${id}`);
        this.deleted_project_id = id;
      }
    },
    restore(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المشروع ؟")) {
        this.$inertia.put(`/projects/${id}/restore`);
        this.deleted_project_id = null;
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
