<template>
  <div>
    <Head title="individuals" />
    <h1 class="mb-8 text-3xl font-bold">المنتفعين الأفراد</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
        <label class="block text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/individuals/create">
        <span>إنشاء</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">الهاتف</th>
            <th class="pb-4 pt-6 px-6">العنوان</th>
            <th class="pb-4 pt-6 px-6">الرقم الوطني</th>
            <th class="pb-4 pt-6 px-6">الجنس</th>
            <th class="pb-4 pt-6 px-6">تاريخ الميلاد</th>
            <th class="pb-4 pt-6 px-6">مكان الميلاد</th>
            <th class="pb-4 pt-6 px-6">الحالة الاجتماعية</th>
            <th class="pb-4 pt-6 px-6">الدخل الشهري</th>
            <th class="pb-4 pt-6 px-6">التأمين الصحي</th>
            <th class="pb-4 pt-6 px-6">مستوى التعليم</th>
            <th class="pb-4 pt-6 px-6">الوظيفة</th>
            <th class="pb-4 pt-6 px-6">مكان العمل</th>
            <th class="pb-4 pt-6 px-6" colspan="2">Actions</th>
          </tr>
        </thead>
        <tbody class="text-right">
          <tr
            v-for="individual in individuals.data"
            :key="individual.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/individuals/${individual.id}/edit`"
              >
                {{ individual.name }}
                <icon
                  v-if="individual.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.phone }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.address }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.cin }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.gender }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.birth_date }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.birth_city }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.social_status }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.monthly_income }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.health_insurance }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.education_level }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.job }}
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                {{ individual.job_place }}
              </Link>
            </td>
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
          </tr>
          <tr v-if="individuals.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="14">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="individuals.links" />
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

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    individuals: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/individuals", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
