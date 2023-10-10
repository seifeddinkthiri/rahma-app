<template>
  <div>
    <Head title="Users" />
    <trashed-message
      v-if="deleted_user_id"
      class="mb-6"
      @restore="restore(deleted_user_id)"
      >تم حذف هذا المستخدم .
    </trashed-message>

    <h1 class="mb-8 text-xl font-bold">المستخدم</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter
        :onlySearch="false"
        v-model="form.search"
        class="mr-4 w-full max-w-md"
        @reset="reset"
      >
        <label class="block text-gray-700">الدور</label>
        <select v-model="form.role" class="form-select mt-1 w-full">
          <option value="user">مستخدم</option>
          <option value="owner">مستخدم متميز</option>
        </select>
        <label class="block mt-4 text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
      <Link class="btn-indigo" href="/users/create">
        <span>إنشاء</span>
        <span class="hidden md:inline">&nbsp;المستخدم</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الصورة</th>
            <th class="pb-4 pt-6 px-6">الاسم</th>
            <th class="pb-4 pt-6 px-6">البريد الإلكتروني</th>
            <th class="pb-4 pt-6 px-6">الدور</th>
            <th class="pb-4 pt-6 px-6">إجراءات</th>
          </tr>
        </thead>
        <tbody class="text-right">
          <tr
            v-for="user in users"
            :key="user.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                v-if="user.photo"
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                <img class="block -my-2 mr-2 w-10 h-8 rounded" :src="user.photo" />
              </Link>
              <Link
                v-else
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                لا يوجد
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                :href="`/users/${user.id}/edit`"
              >
                {{ user.name }}
                <icon
                  v-if="user.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/users/${user.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">
                  {{ user.email }}
                </p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center transform -translate-x-6"
                :href="`/users/${user.id}/edit`"
                tabindex="-1"
              >
                {{ user.owner ? "أدمن " : "مستخدم " }}
              </Link>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/users/${user.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-4 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/users/${user.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  v-if="user.deleted_at"
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="restore(user.id)"
                >
                  <icon name="restore" />
                </button>
                <button
                  v-else
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(user.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr
            v-if="users.length === 0"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="px-6 py-4 border-t" colspan="4">لا يوجد مستخدمين</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import pickBy from "lodash/pickBy";
import Layout from "@/Shared/Layout";
import throttle from "lodash/throttle";
import mapValues from "lodash/mapValues";
import SearchFilter from "@/Shared/SearchFilter";
import TrashedMessage from "@/Shared/TrashedMessage";

export default {
  components: {
    Head,
    Icon,
    Link,
    SearchFilter,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    filters: Object,
    users: Array,
  },
  data() {
    return {
      deleted_user_id: null,
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
    destroy(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المستخدم ؟")) {
        this.$inertia.delete(`/users/${id}`);
        this.deleted_user_id = id;
      }
    },
    restore(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المستخدم ؟")) {
        this.$inertia.put(`/users/${id}/restore`);
        this.deleted_user_id = null;
      }
    },
  },
};
</script>
