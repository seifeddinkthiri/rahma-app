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
      class="fixed z-50 inset-0 flex items-center justify-center rounded"
    >
      <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 rounded transition-opacity"
      ></div>
      <div class="z-10 w-96 max-h-screen bg-white rounded shadow-xl overflow-auto">
        <div class="px-4 py-6 bg-gray-50 rounded">
          <div class="grid gap-4 grid-cols-2 rounded">
            <button
              @click="store_family('elderly')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <img src="/svg-icons/aged.svg" width="140" height="35" />
              <p class="mt-4 text-base">مسن</p>
            </button>
            <button
              @click="store_family('widow')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <img src="/svg-icons/mother.svg" width="140" height="35" />
              <p class="mt-4 text-base">أرملة</p>
            </button>

            <button
              @click="store_family('divorced')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <img
                src="/svg-icons/woman.svg"
                width="140"
                height="35"
                alt=""
                class="logo_normal"
              />
              <p class="mt-4 text-base">مطلقة</p>
            </button>

            <button
              @click="store_family('single_mother')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <img src="/svg-icons/single_mother.svg" width="140" height="35" />
              <p class="mt-4 text-base">أم عزباء</p>
            </button>

            <button
              @click="store_family('family')"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <img
                src="/svg-icons/family.svg"
                width="140"
                height="35"
                alt=""
                class="logo_normal"
              />
              <p class="text-md mt-4">عائلة معوزة</p>
            </button>
            <button
              @click="show_modal = false"
              type="button"
              class="inline-flex flex-col items-center justify-center px-12 py-10 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
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
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
        <label class="block mb-2 mt-2 text-gray-700"> حالة المنتفع </label>
        <select v-model="form.status" class="form-select mt-1 w-full">
          <option value="active">نشط</option>
          <option value="disabled">محضور</option>
          <option value="inactive">غير نشط</option>
        </select>

        <label class="block mb-2 mt-2 text-gray-700"> نوع المنتفع </label>
        <select v-model="form.social_status" class="form-select mt-1 w-full">
          <option value="family">عائلة معوزة</option>
          <option value="elderly">مسن</option>
          <option value="widow">أرملة</option>
          <option value="single_mother">أم عزباء</option>
          <option value="divorced">مطلقة</option>
        </select>

        <label class="block mb-2 mt-2 text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
    </div>
    <div class="flex items-center justify-between mb-4 mt-4">
      <h1 class="text-xl font-bold" v-if="families.data.length > 0">قائمة المنتفعين</h1>
      <h1 class="text-xl font-bold" v-else></h1>

      <button @click="show_modal = true" class="btn-indigo">
        <span> إضافة منتفع</span>
      </button>
    </div>

    <div
      class="bg-white rounded-md shadow overflow-x-auto"
      v-if="families.data.length > 0"
    >
      <table class="w-full">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">المعيل</th>
            <th class="pb-4 pt-6 px-6">العنوان</th>
            <th class="pb-4 pt-6 px-6">الهاتف</th>
            <th class="pb-4 pt-6 px-6">الحالة</th>
            <th class="pb-4 pt-6 px-6">نوع المنتفع</th>
            <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
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
                v-if="family.is_family && family.members.length < 2 && family.name"
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/members/${family.id}/create_new_one`"
                tabindex="-1"
              >
                <div>
                  <p class="text-red-600">
                    <span> {{ family.name }} : </span>
                    تحتوي العائلة فردين على الأقل
                  </p>
                </div>
              </Link>

              <Link
                v-else
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                {{ family.name }}
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p class="h-4">{{ family.address }}</p>
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p class="h-4">{{ family.phone }}</p>
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p class="h-4" v-if="family.status == 'active'">نشط</p>
                <p class="h-4" v-if="family.status == 'inactive'">غير نشط</p>
                <p class="h-4" v-if="family.status == 'disabled'">محضور</p>
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p class="h-4" v-if="family.social_status == 'widow'">أرملة</p>
                <p class="h-4" v-if="family.social_status == 'divorced'">مطلقة</p>
                <p class="h-4" v-if="family.social_status == 'single_mother'">أم عزباء</p>
                <p class="h-4" v-if="family.social_status == 'elderly'">مسن</p>
                <p class="h-4" v-if="family.social_status == 'family'">عائلة معوزة</p>

                <p class="h-4" v-if="family.social_status == null"></p>
              </Link>
            </td>

            <td class="w-px border-t" v-if="family.name">
              <Link
                class="flex items-center px-4"
                :href="`/families/${family.id}/show`"
                tabindex="-1"
              >
                <icon name="eye" />
              </Link>
            </td>

            <td class="w-px border-t" v-if="family.name">
              <Link
                class="flex items-center px-4"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-4 h-4 fill-gray-400" />
              </Link>
            </td>

            <td class="w-px border-t" v-if="family.name">
              <button
                v-if="!family.deleted_at"
                class="flex items-center px-4"
                tabindex="-1"
                @click="delete_family(family.id)"
              >
                <icon name="delete" />
              </button>
              <button
                v-else
                class="flex items-center px-4"
                tabindex="-1"
                @click="restore(family.id)"
              >
                <icon name="restore" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div
      class="bg-white rounded-md shadow overflow-x-auto"
      v-if="families.data.length === 0"
    >
      <table class="w-full">
        <tr class="text-right font-bold">
          <th class="pb-4 pt-6 px-6">المعيل</th>
          <th class="pb-4 pt-6 px-6">العنوان</th>
          <th class="pb-4 pt-6 px-6">الهاتف</th>
          <th class="pb-4 pt-6 px-6">الحالة</th>
          <th class="pb-4 pt-6 px-6">الحالة المدنية</th>
          <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
        </tr>
        <tr>
          <td class="px-6 py-4 border-t" colspan="6">قائمة فارغة</td>
        </tr>
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
        status: this.filters.status,
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

      this.show_modal = false;
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
