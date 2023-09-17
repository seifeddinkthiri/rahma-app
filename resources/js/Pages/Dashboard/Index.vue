<template>
  <Head title="Dashboard" />

  <div>
    <div class="bg-blue-500 py-8">
      <div class="container mx-auto text-center">
        <h1 class="text-3xl font-bold text-blue">مرحبًا بك في رحمة</h1>
      </div>
    </div>

    <div
      v-if="$page.props.auth.user && $page.props.auth.user.wait == false"
      class="container mx-auto mt-8 px-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"
    >
      <!-- projects Card -->
      <div class="bg-white rounded-lg shadow-lg p-4">
        <Link class="text-blue-400 hover:text-blue-600" href="/projects">
          <h2 class="text-xl font-semibold mb-2">إدارة المشاريع</h2>
          <p class="text-gray-600">
            ادارة المشاريع ، يحتوي المشروع على مجموعة من التدخلات
          </p></Link
        >
      </div>
      <!-- Beneficials Card -->
      <div class="bg-white rounded-lg shadow-lg p-4">
        <Link class="text-blue-400 hover:text-blue-600" href="/interventions">
          <h2 class="text-xl font-semibold mb-2">إدارة التدخلات</h2>

          <p class="text-gray-600">
            إدارة التدخلات المقدمة لفائدة المنتفععين مع إمكانية انتماء التدخل لمشروع معين
          </p></Link
        >
      </div>

      <!-- Interventions Card -->
      <Link href="beneficials" class="bg-white rounded-lg shadow-lg p-4">
        <h2 class="text-xl font-semibold mb-2">إدارة المنتفعين</h2>
        <ul>
          <li class="flex items-center">
            <button class="text-blue-400 hover:text-blue-600">
              <p class="text-gray-600">
                إدارة المنتفعين مع خدمة البحث و الفلترة حسب الحالة المدنية للمنتفع
              </p>
            </button>
          </li>
        </ul>
      </Link>
      <!-- Users Card -->
      <div
        class="bg-white rounded-lg shadow-lg p-4"
        v-if="$page.props.auth.user.admin || $page.props.auth.user.owner"
      >
        <Link class="text-blue-400 hover:text-blue-600" href="/users">
          <h2 class="text-xl font-semibold mb-2">إدارة المستخدمين</h2>
          <p class="text-gray-600">
            قبول أو حضر المستخدم مع منح صلاحية إدارة بقية المستخدمين
          </p></Link
        >
      </div>
    </div>

    <!-- Add any other creative elements or sections here, like statistics, charts, etc. -->
  </div>
</template>
<script>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Shared/Layout";
import { Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";

export default {
  components: {
    Head,
    Link,
    Icon,
  },
  layout: Layout,
};
</script>
