<template>
  <div>
    <Head title="Interventions" />
    <trashed-message
      v-if="deleted_intervention_id"
      class="mb-6"
      @restore="restore(deleted_intervention_id)"
      >تم حذف هذا التدخل .
    </trashed-message>
    <h1 class="mb-8 text-xl font-bold">التدخلات</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
        <label class="block mb-2 mt-2 text-gray-700"> تدخل عرضي </label>
        <select v-model="form.isSolitary" class="form-select mt-1 w-full">
          <option value="isSolitary">نعم</option>
          <option value="isNormal">لا</option>
        </select>

        <label class="block mb-2 mt-2 text-gray-700"> نوع التدخل </label>
        <select v-model="form.type" class="form-select mt-1 w-full">
          <option value="shipments">عيني</option>
          <option value="cash">نقدي</option>
          <option value="other">آخر</option>
        </select>

        <label class="block text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
      <button @click="open_types_modal = true" class="btn-indigo">
        <span>إنشاء</span>
        <span class="hidden md:inline">&nbsp;التدخل</span>
      </button>
    </div>

    <div
      v-if="open_types_modal"
      class="fixed z-50 inset-0 flex items-center justify-center rounded"
    >
      <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 rounded transition-opacity"
      ></div>
      <div class="z-10 w-96 max-h-screen bg-white rounded shadow-xl overflow-auto">
        <div class="px-4 py-6 bg-gray-50 rounded">
          <div class="grid gap-4 grid-cols-2 rounded">
            <Link
              href="/interventions/createSolitary"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <p class="text-md mt-4">عرضي</p>
            </Link>
            <Link
              href="/interventions/create"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <p class="text-md mt-4">عادي</p>
            </Link>
            <button
              @click="open_types_modal = false"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              إلغاء
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">النوع</th>
            <th class="pb-4 pt-6 px-6">القيمة</th>
            <th class="pb-4 pt-6 px-6">تدخل عرضي</th>
            <th class="pb-4 pt-6 px-6">التاريخ</th>
            <th class="pb-4 pt-6 px-6">المنتفع</th>
            <th class="pb-4 pt-6 px-6">المسؤل</th>
            <th class="pb-4 pt-6 px-6">هاتف المسؤل</th>
            <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="intervention in normalInterventions"
            :key="intervention.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p v-if="intervention.type == 'shipments'" class="h-4">عيني</p>
                <p v-else-if="intervention.type == 'cash'" class="h-4">نقدي</p>
                <p v-else-if="intervention.type == 'other'" class="h-4">آخر</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.value }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.isSolitary ? "نعم" : "لا" }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.date }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                v-if="intervention.family"
                class="flex items-center px-6 py-4"
                :href="`/families/${intervention.family.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.family.name }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="focus:text-blue-500 flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
              >
                <p class="h-4">{{ intervention.intervenor }}</p>
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
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.intervenor_phone }}</p>
              </Link>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-5 h-4 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  v-if="intervention.deleted_at"
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="restore(intervention.id)"
                >
                  <icon name="restore" />
                </button>
                <button
                  v-else
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(intervention.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr
            v-for="intervention in solitaryInterventions"
            :key="intervention.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p v-if="intervention.type == 'shipments'" class="h-4">عيني</p>
                <p v-else-if="intervention.type == 'cash'" class="h-4">نقدي</p>
                <p v-else-if="intervention.type == 'other'" class="h-4">آخر</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.value }}</p>
              </Link>
            </td>

            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.isSolitary ? "نعم" : "لا" }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.date }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.beneficial_name }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="focus:text-blue-500 flex items-center px-6 py-4"
                :href="`/solitaryInterventions/${intervention.id}/edit`"
              >
                <p class="h-4">{{ intervention.intervenor }}</p>
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
                :href="`/solitaryInterventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.intervenor_phone }}</p>
              </Link>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/solitaryInterventions/${intervention.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-5 h-4 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  v-if="intervention.deleted_at"
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="restore(intervention.id)"
                >
                  <icon name="restore" />
                </button>
                <button
                  v-else
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(intervention.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="interventions.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="interventions.links" />
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
    interventions: Object,
  },
  computed: {
    solitaryInterventions() {
      return this.interventions.data.filter(
        (intervention) => intervention.isSolitary == true
      );
    },
    normalInterventions() {
      return this.interventions.data.filter(
        (intervention) => intervention.isSolitary == false
      );
    },
  },
  data() {
    return {
      deleted_intervention_id: null,
      open_types_modal: false,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        type: this.filters.type,
        isSolitary: this.filters.isSolitary,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/interventions", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null);
    },
    destroy(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا التدخل ؟")) {
        this.$inertia.delete(`/interventions/${id}`);
        this.deleted_intervention_id = id;
      }
    },
    restore(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا التدخل ؟")) {
        this.$inertia.put(`/interventions/${id}/restore`);
        this.deleted_intervention_id = null;
      }
    },
  },
};
</script>
