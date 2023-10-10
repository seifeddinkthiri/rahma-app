<template>
  <div>
    <!-- User Card -->
    <div class="p-6 bg-white rounded-md shadow">

<Head :title="user.name" />

<div class="relative">
  <div>

    <ul class="absolute left-0 top-0 space-y-3 pt-4 pl-4">
      <li>
        <img class="w-16 h-16 rounded block" v-if="user.photo" :src="user.photo" />
      </li>
      <li>
        <button
          @click="goBack"
          class="absolute mx-8 left-0 pl-2 px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
        >
          عودة
        </button>
      </li>
    </ul>

    <br />
    <br />
    <br />
    <br />

  </div>
</div>

<h2 class="mt-12 text-2xl font-bold">User / {{ user.name }}</h2>

<div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">

  <table class="w-full table-auto">
    <tbody>
      <tr>
        <td class="px-4 py-2 border">الاسم الكامل</td>
        <td class="px-4 py-2 border">
          {{ user.first_name }} {{ user.last_name }}
        </td>
      </tr>
      <tr>
        <td class="px-4 py-2 border">البريد الإلكتروني</td>
        <td class="px-4 py-2 border">{{ user.email }}</td>
      </tr>
      <tr>
        <td class="px-4 py-2 border">أدمن</td>
        <td class="px-4 py-2 border">
          {{ user.owner ? "نعم" : "لا" }}
        </td>
      </tr>
    </tbody>
  </table>

</div>

</div>

  </div>
</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import Icon from "@/Shared/Icon";
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    user: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {};
  },
  methods: {
    goBack() {
      window.history.back();
    },
  },
};
</script>
