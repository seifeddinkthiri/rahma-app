<template>
  <Head title="Dashboard" />

  <div>
    <div class="bg-blue-500 py-8">
      <div class="container mx-auto text-center">
        <h1 class="text-3xl font-bold text-blue">مرحبًا بك في رحمة</h1>
      </div>
    </div>

    <div
      class="container mx-auto mt-8 px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"
    >
      <!-- Users Card -->
      <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-xl font-semibold mb-2">إدارة المستخدمين</h2>
        <p class="text-gray-600">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
      </div>

      <!-- Interventions Card -->
      <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-xl font-semibold mb-2">إدارة المنتفعين</h2>
        <p class="text-gray-600">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
      </div>

      <!-- Beneficials Card -->
      <div class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-xl font-semibold mb-2">إدارة التدخلات</h2>
        <p class="text-gray-600">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
        </p>
      </div>
    </div>

    <!-- Add any other creative elements or sections here, like statistics, charts, etc. -->
  </div>
</template>
<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
export default {
  components: {
    Head,
  },
  layout: Layout,
};
</script>
