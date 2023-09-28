<template>
  <div>
    <Head title="Users" />
    <div>
      <h1 class="mb-8 text-xl font-bold">قائمة الانتظار</h1>
      <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">البريد الإلكتروني</th>
            <th class="pb-4 pt-6 px-6" colspan="2"></th>
          </tr>
          </thead>
          <tbody class="text-right">
            <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t" v-if="user.wait">
              <div
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                {{ user.name }}
              </div>
            </td>
            <td class="border-t" v-if="user.wait">
              <div
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                {{ user.email }}
              </div>
            </td>
            <td class="w-px border-t" v-if="user.wait">
              <Link
                class="flex items-center px-4"
                :href="`/wait_list/${user.id}/edit`"
                tabindex="-1"
              >
                <button
                  :loading="form.processing"
                  class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                  قبول
                </button>
              </Link>
            </td>
            <td class="w-px border-t" v-if="user.wait">
              <Link
                class="flex items-center px-4"
                :href="`/wait_list/${user.id}/delete_demonde`"
                tabindex="-1"
              >
                <button
                  :loading="form.processing"
                  class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                  حذف
                </button>
              </Link>
            </td>
          </tr>
          <tr v-if="users.length === 0"             class="hover:bg-gray-100 focus-within:bg-gray-100"
>
            <td class="px-6 py-4 border-t" colspan="4">لا يوجد مستخدمين</td>
          </tr>
          </tbody>

        </table>
      </div>
    </div>
    <div>
      <h1 class="mb-8 mt-8 text-xl font-bold">قائمة المقبولين</h1>
      <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">البريد الإلكتروني</th>
            <th class="pb-4 pt-6 px-6" colspan="2"></th>
          </tr>
          </thead>
          <tbody class="text-right">
            <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t" v-if="user.wait == false">
              <div
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                {{ user.name }}
              </div>
            </td>
            <td class="border-t" v-if="user.wait == false">
              <div
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                {{ user.email }}
              </div>
            </td>
            <td class="w-px border-t" v-if="user.wait == false">
              <Link
                class="flex items-center px-4"
                :href="`/wait_list/${user.id}/edit`"
                tabindex="-1"
              >
                <button
                  :loading="form.processing"
                  class="px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                  حظر
                </button>
              </Link>
            </td>
          </tr>
          <tr v-if="users.length === 0"             class="hover:bg-gray-100 focus-within:bg-gray-100"
>
            <td class="px-6 py-4 border-t" colspan="4">لا يوجد مستخدمين</td>
          </tr>
          </tbody>

        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import LoadingButton from "@/Shared/LoadingButton";
import Icon from "@/Shared/Icon";
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import SearchFilter from "@/Shared/SearchFilter";

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    users: Array,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        role: this.filters.role,
        trashed: this.filters.trashed,
      },
    };
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get("/users", pickBy(this.form), { preserveState: true });
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
