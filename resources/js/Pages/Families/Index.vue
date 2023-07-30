<template>
  <div>
    <Head title="families" />
    <h1 class="mb-8 text-3xl font-bold">المنتفعين العائلات</h1>
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
      <Link
        class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        href="/families/create"
      >
        <span>إنشاء</span>
        <span class="hidden md:inline">&nbsp;المنتفع</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">معيل أسرة</th>
            <th class="pb-4 pt-6 px-6">العنوان</th>
            <th class="pb-4 pt-6 px-6">الهاتف</th>
            <th class="pb-4 pt-6 px-6" colspan="2">الصورة</th>
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
                class="flex items-center px-6 py-4"
                :href="`/families/${family.id}/edit`"
                tabindex="-1"
              >
                <img
                  v-if="family.photo"
                  class="block -my-2 mr-2 w-10 h-110 rounded"
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
            <td class="border-t relative" colspan="5" v-else>
              <div
                class="flex items-center focus:text-indigo-500 flex-row justify-center px-20"
              >
                <div
                  class="flex items-center px-6 py-4 flex-row justify-around"
                  v-if="family.members.length > 1"
                >
                  <p class="text-red-600 px-6">يجب تعيين معيل لهذه العائلة</p>

                  <button
                    class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                    type="button"
                    @click="show_cargiver_modal = true"
                  >
                    إختيار
                  </button>
                </div>
                <div class="flex items-center px-6 py-4 flex-row justify-around" v-else>
                  <p class="text-red-600 px-6">تحتوي العائلة فردين علا الأقل</p>

                  <Link
                    :href="`/members/${family.id}/create_new_one`"
                    class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                    type="button"
                  >
                    إضافة
                  </Link>
                </div>

                <div
                  class="mt-6 bg-white rounded shadow overflow-x-auto"
                  v-if="show_cargiver_modal"
                >
                  <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                  ></div>
                  <div class="fixed inset-0 flex items-center justify-center">
                    <form>
                      <div class="w-96 h-auto bg-white rounded shadow-xl">
                        <div class="p-6">
                          <table class="w-full border-collapse">
                            <thead>
                              <tr>
                                <th class="py-3 px-4 text-right">الإسم</th>
                                <th class="py-3 px-4 text-right">الهاتف</th>
                                <th class="py-3 px-4 text-right">القرابة</th>
                              </tr>
                            </thead>
                            <tbody v-if="family.members.length > 0">
                              <tr
                                v-for="member in family.members"
                                :key="member.id"
                                class="inline-aflex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium hover:bg-gray-200 focus:bg-gray-300 rounded focus:outline-none"
                              >
                                <td class="py-3 px-4">
                                  <Link :href="`/members/${member.id}/edit_caregiver`">{{
                                    member.name
                                  }}</Link>
                                </td>
                                <td class="py-3 px-4">
                                  <Link :href="`/members/${member.id}/edit_caregiver`">{{
                                    member.phone
                                  }}</Link>
                                </td>
                                <td class="py-3 px-4">
                                  <Link :href="`/members/${member.id}/edit_caregiver`">{{
                                    member.kinship
                                  }}</Link>
                                </td>
                              </tr>
                            </tbody>
                            <tbody v-else>
                              <tr
                                class="inline-aflex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium rounded"
                              >
                                <td class="py-3 px-4">قائمة فارغة</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="flex justify-end px-4 py-3 bg-gray-50">
                          <button
                            @click="show_cargiver_modal = false"
                            type="button"
                            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                          >
                            عودة
                          </button>
                        </div>
                      </div>
                    </form>
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
  },
  data() {
    return {
      show_cargiver_modal: false,
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
        this.$inertia.get("/families", pickBy(this.form), { preserveState: true });
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
