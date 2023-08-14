<template>
  <div class="container mx-auto py-3 space-y-5" ref="breadcrumb">
    <!-- Breadcrumb -->
    <div class="px-4 py-2 bg-white rounded-lg shadow">
      <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
          <li></li>
          <li v-if="Individual_id">
            <Link
              :href="`/individuals/${Individual_id}/create_B_C`"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              الفرد
            </Link>
          </li>
          <li v-else>
            <Link
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              الفرد
            </Link>
          </li>
          <!--  -->
          <li>
            <svg
              class="w-6 h-6 text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              ></path>
            </svg>
          </li>
          <li>
            <button
              v-if="Individual_id"
              @click="switch_form_BC('home', 'أضف بيانات المسكن')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'home' }"
            >
              المسكن
            </button>
            <p
              v-else
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              المسكن
            </p>
          </li>

          <!--  -->
          <li>
            <svg
              class="w-6 h-6 text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              ></path>
            </svg>
          </li>
          <li>
            <button
              v-if="Individual_id"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              type="button"
              @click="switch_form_BC('facilities', 'تعديل المرافق الإساسية')"
            >
              <span
                :class="{ 'text-gray-900 font-bold': active_step === 'facilities' }"
                class="text-gray-500 hover:text-gray-800"
                >المرافق
              </span>
            </button>
            <p
              v-else
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              المرافق
            </p>
          </li>

          <!--  -->
          <li>
            <svg
              class="w-6 h-6 text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              ></path>
            </svg>
          </li>
          <li>
            <button
              v-if="Individual_id"
              @click="switch_form_BC('notes', 'أضف  ملاحظة')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'notes' }"
            >
              الملاحظات
            </button>
            <p
              v-else
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              الملاحظات
            </p>
          </li>
          <li>
            <svg
              class="w-6 h-6 text-gray-500"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 19l-7-7 7-7"
              ></path>
            </svg>
          </li>
          <li>
            <button
              v-if="Individual_id"
              @click="switch_form_BC('files', 'أضف  ملف')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'files' }"
            >
              الملفات
            </button>
            <p
              v-else
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
            >
              الملفات
            </p>
          </li>
        </ol>
      </nav>
    </div>
    <h1 class="mb-8 text-xl font-bold">
      {{ current_form_title }}
    </h1>
  </div>
</template>
<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
  props: {
    current_form_title: String,
    Individual_id: Number,
    active_step: {
      type: String, // Change this to Number if active_step can also be a number
      default: "",
    },
    members_form_title: String,
  },
  data() {
    return {};
  },
  methods: {
    switch_form_BC(step, title) {
      this.$emit("update-active-step", step); // Emit the 'update-active-step' event with the new step value
      this.$emit("update-current-form-title", title); // Emit the 'update-current-form-title' event with the new title value
    },
  },
};
</script>
