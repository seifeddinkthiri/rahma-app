<template>
  <div>
    <Head title="المنتفعين" />
    <trashed-message
      v-if="deleted_family_id"
      class="mb-6"
      @restore="restore(deleted_family_id)"
      >تم حذف هذا المنتفع .
    </trashed-message>
    <div
      v-if="show_modal"
      class="fixed inset-0 z-50 flex items-center justify-center rounded"
    >
      <div
        class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 rounded"
      ></div>
      <div class="z-10 max-h-screen overflow-auto bg-white rounded shadow-xl w-96">
        <div class="px-4 py-6 rounded bg-gray-50">
          <div class="grid grid-cols-2 gap-4 rounded">
            <button
              @click="store_family('elderly')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
               <img src="/svg-icons/aged.svg" width="140" height="35">
              <p class="mt-4 text-base">مسن</p>
            </button>
            <button
              @click="store_family('widow')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
              <img src="/svg-icons/mother.svg" width="140" height="35" >
              <p class="mt-4 text-base">أرملة</p>
            </button>

            <button
              @click="store_family('divorced')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
              <img src="/svg-icons/woman.svg" width="140" height="35" alt="" class="logo_normal">
              <p class="mt-4 text-base">مطلقة</p>
            </button>

            <button
              @click="store_family('single_mother')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
            <img src="/svg-icons/single_mother.svg" width="140" height="35">
              <p class="mt-4 text-base">أم عزباء</p>
            </button>

            <button
              @click="store_family('family')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
              <img src="/svg-icons/family.svg" width="140" height="35" alt="" class="logo_normal">
              <p class="mt-4 text-md">عائلة معوزة</p>
            </button>
            <button
              @click="show_modal = false"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
            >
              إلغاء
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-end">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="w-full max-w-md mr-4"
        @reset="reset"
      >
        <label class="block mt-2 mb-2 text-gray-700">حالة المنتفع</label>
        <select v-model="form.social_status" class="w-full mt-1 form-select">
          <option value="family">عائلة معوزة</option>
          <option value="elderly">مسن</option>
          <option value="widow">أرملة</option>
          <option value="single_mother">أم عزباء</option>
          <option value="divorced">مطلقة</option>
        </select>

        <label class="block mt-2 mb-2 text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="w-full mt-1 form-select">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
    </div>
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="text-xl font-bold" v-if="families.data.length > 0">قائمة المنتفعين</h1>
      <h1 class="text-xl font-bold" v-else>قائمة فارغة</h1>

      <button
        @click="show_modal = true"
        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
      >
        <span> إضافة منتفع</span>
      </button>
    </div>

    <div
      class="overflow-x-auto bg-white rounded-md shadow"
      v-if="families.data.length > 0"
    >
      <table class="w-full">
        <thead>
          <tr class="font-bold text-right">
            <th class="px-6 pt-6 pb-4">المعيل</th>
            <th class="px-6 pt-6 pb-4">العنوان</th>
            <th class="px-6 pt-6 pb-4">الهاتف</th>
            <th class="px-6 pt-6 pb-4">الحالة</th>
            <th class="px-6 pt-6 pb-4">الحالة المدنية</th>
            <th class="pt-6 pb-4 pl-3">الصورة</th>
            <th class="pt-6 pb-4 pl-3" colspan="3">إجراءات</th>
          </tr>
        </thead>
        <tbody class="text-right">
          <tr
            v-for="family in families.data"
            :key="family.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                {{ family.name }}
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                {{ family.address }}
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                {{ family.phone }}
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p v-if="family.status == 'active'">نشط</p>
                <p v-if="family.status == 'inactive'">غير نشط</p>
                <p v-if="family.status == 'disabled'">محضور</p>
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p v-if="family.social_status == 'widow'">أرملة</p>
                <p v-if="family.social_status == 'divorced'">مطلقة</p>
                <p v-if="family.social_status == 'single_mother'">أم عزباء</p>
                <p v-if="family.social_status == 'elderly'">مسن</p>
                <p v-if="family.social_status == null"></p>
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center py-4 mr-8"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <img
                  v-if="family.photo"
                  class="block w-10 h-10 mr-2 -my-2 rounded"
                  :src="'uploads/' + family.photo"
                />
              </Link>
            </td>
            <td class="w-px border-t" v-if="family.name">
              <Link
                class="flex items-center px-4"
                :href="`/members/${family.id}/create_new_one`"
                tabindex="-1"
              >
                <div
                  v-if="family.is_family && family.members.length < 2 && family.name"
                  class="flex items-center justify-between px-4 sm:px-20"
                >
                  <p class="flex-grow px-3 py-2 text-red-600 whitespace-nowrap">
                    تحتوي العائلة فردين على الأقل
                  </p>
                </div>
              </Link>
            </td>

            <td class="w-px border-t" v-if="family.name">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/families/${family.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/families/${family.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="delete_family(family.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="families.links" />
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
import SelectInput from "@/Shared/SelectInput";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    SelectInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    filters: Object,
    families: Object,
  },

  data() {
    return {
      deleted_family_id: null,
      beneficial: null,
      show_modal: false,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        social_status: this.filters.social_status,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/beneficials", pickBy(this.form), { preserveState: true });
      }, 150),
    },
  },
  methods: {
    store_family(type) {
      this.$inertia.post(`/families/${type}`);

      show_modal = false;
    },
    delete_family(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف  هذا المنتفع")) {
        this.$inertia.delete(`/families/${id}`);
        this.deleted_family_id = id;
      }
    },

    restore(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المنتفع ؟")) {
        this.$inertia.put(`/families/${id}/restore`);
        this.deleted_family_id = null;
      }
    },
    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
