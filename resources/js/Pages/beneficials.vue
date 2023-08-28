<template>
  <div>
    <Head title="المنتفعين" />
    <div class="flex items-center justify-end">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
        <label class="block text-gray-700"> نوع المنتفع</label>
        <select v-model="form.type" class="form-select mt-1 w-full">
          <option value="family">عائلة</option>
          <option value="individual">فرد</option>
          <option value="all">الكل</option>
        </select>

        <label class="block text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
    </div>
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="text-xl font-bold">العائلات</h1>
      <button
        @click="store_family"
        class="px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
      >
        <span>إنشاء</span>
      </button>
    </div>

    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">المعيل</th>
            <th class="pb-4 pt-6 px-6">العنوان</th>
            <th class="pb-4 pt-6 px-6">الهاتف</th>
            <th class="pb-4 pt-6 px-6">الحالة</th>
            <th class="pb-4 pl-3 pt-6" colspan="2">الصورة</th>
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
            <td class="w-px border-t" v-if="family.name && family.members.length > 1">
              <Link
                class="flex items-center px-4"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </Link>
            </td>
            <td class="w-px border-t" v-if="family.name && family.members.length > 1">
              <Link
                class="flex items-center px-4"
                :href="`/families/${family.id}/show`"
                tabindex="-1"
              >
                <icon name="eye" />
              </Link>
            </td>
            <td
              class="relative border-t"
              colspan="5"
              v-if="family.members.length < 2 && family.name"
            >
              <div class="flex items-center justify-between px-4 sm:px-20">
                <p class="flex-grow px-3 py-2 text-red-600 whitespace-nowrap">
                  تحتوي العائلة فردين على الأقل
                </p>
                <div class="flex items-center">
                  <div>
                    <Link
                      class="underline"
                      :href="`/members/${family.id}/create_new_one`"
                      type="button"
                    >
                      إضافة
                    </Link>
                  </div>
                  <div class="ml-4 mr-4">
                    <Link class="underline" :href="`/families/${family.id}/edit`">
                      تعديل
                    </Link>
                  </div>
                  <div>
                    <button
                      class="underline"
                      type="button"
                      @click="delete_family(family.id)"
                    >
                      حذف
                    </button>
                  </div>
                </div>
              </div>
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
  <div>
    <div class="flex items-center justify-between mt-4 mb-4">
      <h1 class="text-xl font-bold">الأفراد</h1>
      <Link
        href="/individuals/create"
        class="px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
      >
        <span>إنشاء</span>
      </Link>
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
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        type: this.filters.type,
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
    store_family() {
      this.$inertia.post("/families");
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
