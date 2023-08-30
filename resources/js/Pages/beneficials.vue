<template>
  <div>
    <Head title="المنتفعين" />
    <div
      v-if="show_modal"
      class="fixed inset-0 flex items-center justify-center z-50 rounded"
    >
      <div
        class="bg-gray-500 bg-opacity-75 fixed inset-0 transition-opacity rounded"
      ></div>
      <div class="bg-white w-96 rounded shadow-xl z-10">
        <div class="px-4 py-6 bg-gray-50 rounded">
          <div class="flex justify-center flex-col items-center rounded">
            <button
              @click="store_family('elderly')"
              type="button"
              class="my-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <icon name="indiv" class="mr-2 w-4 h-4 ml-4" /> مسن
            </button>
            <button
              @click="store_family('widow')"
              type="button"
              class="my-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <icon name="indiv" class="mr-2 w-4 h-4 ml-4" /> أرملة
            </button>

            <button
              @click="store_family('divorced')"
              type="button"
              class="my-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <icon name="indiv" class="mr-2 w-4 h-4 ml-4" /> مطلقة
            </button>

            <button
              @click="store_family('single_mother')"
              type="button"
              class="my-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <icon name="indiv" class="mr-2 w-4 h-4 ml-4" /> أم عزباء
            </button>

            <button
              @click="store_family('family')"
              type="button"
              class="my-2 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            >
              <icon name="group" class="mr-2 w-4 h-4 ml-4" /> عائلة معوزة
            </button>
            <button
              @click="show_modal = false"
              type="button"
              class="mt-3 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
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
        <label class="block text-gray-700 mt-2 mb-2">حالة المنتفع</label>
        <select v-model="form.social_status" class="form-select mt-1 w-full">
          <option value="family">عائلة</option>
          <option value="elderly">مسن</option>
          <option value="widow">أرملة</option>
          <option value="single_mother">أم متفردة</option>
          <option value="divorced">مطلقة</option>
        </select>

        <label class="block text-gray-700 mt-2 mb-2">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
    </div>
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="text-xl font-bold" v-if="families.data.length > 0">قائمة المنتفعين</h1>
      <h1 class="text-xl font-bold" v-else></h1>

      <button
        @click="show_modal = true"
        class="px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
      >
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
            <th class="pb-4 pt-6 px-6">الحالة المدنية</th>
            <th class="pb-4 pl-3 pt-6">الصورة</th>
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
                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                {{ family.address }}
                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                {{ family.phone }}
                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
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
                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/families/${family.id}/edit`"
              >
                <p v-if="family.social_status == 'widow'">أرملة</p>
                <p v-if="family.social_status == 'divorced'">مطلقة</p>
                <p v-if="family.social_status == 'single_mother'">أم متفردة</p>
                <p v-if="family.social_status == 'elderly'">مسن</p>
                <p v-if="family.social_status == null"></p>

                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t" v-if="family.name">
              <Link
                class="flex items-center mr-8 py-4"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <img
                  v-if="family.photo"
                  class="h-110 block -my-2 mr-2 w-10 rounded"
                  :src="'uploads/' + family.photo"
                />

                <icon
                  v-if="family.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
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
              <Link
                class="flex items-center px-4"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
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
          </tr>
          <tr v-if="families.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="3">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>

    <pagination class="mt-6" :links="families.links" />
  </div>
  <div v-if="individuals.data.length > 0">
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="text-xl font-bold">الأفراد</h1>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">الهاتف</th>
            <th class="pb-4 pt-6 px-6">الحالة</th>
            <th class="pb-4 pt-6 px-6">العنوان</th>
            <th class="pb-4 pt-6 px-6">بطاقة التعريف الوطنية</th>
            <th class="pb-4 pt-6 px-6">الجنس</th>
            <th class="pb-4 pt-6 px-6">الصورة</th>
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
                class="flex items-center px-6 py-4 focus:text-blue-500"
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
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/individuals/${individual.id}/edit`"
              >
                <p v-if="individual.status == 'active'">نشط</p>
                <p v-if="individual.status == 'inactive'">غير نشط</p>
                <p v-if="individual.status == 'disabled'">محضور</p>
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
                <p v-if="individual.gender == 'male'">ذكر</p>
                <p v-else>أنثى</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/individuals/${individual.id}/edit`"
                tabindex="-1"
              >
                <img
                  v-if="individual.photo"
                  class="block -my-2 mr-2 w-10 h-110 rounded"
                  :src="'uploads/' + individual.photo"
                />

                <icon
                  v-if="individual.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
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
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="`/individuals/${individual.id}/show`"
                tabindex="-1"
              >
                <icon name="eye" />
              </Link>
            </td>
          </tr>
          <tr v-if="individuals.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
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
import SelectInput from "@/Shared/SelectInput";

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    SelectInput,
  },
  layout: Layout,
  props: {
    filters: Object,
    families: Object,
    individuals: Object,
  },

  data() {
    return {
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
      if (confirm("هل أنت متأكد أنك تريد حذف هذه العائلة")) {
        this.$inertia.delete(`/families/${id}`);
      }
    },

    reset() {
      this.form = mapValues(this.form, () => null);
    },
  },
};
</script>
