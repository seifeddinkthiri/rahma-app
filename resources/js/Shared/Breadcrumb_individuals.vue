<template>
  <div class="container mx-auto py-6 space-y-5" ref="breadcrumb">
    <h1 class="mb-8 text-3xl font-bold">
      {{ current_form_title }}
    </h1>
    <div class="px-4 py-2 bg-white rounded-lg shadow">
      <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
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
            <Link
              v-if="Individual_id"
              :href="`/individuals/${Individual_id}/create_B_C`"
              class="text-gray-500 hover:text-gray-800"
            >
              الفرد
            </Link>
            <Link v-else class="text-gray-500 hover:text-gray-800"> الفرد </Link>
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
              class="text-gray-500 hover:text-gray-800"
              :class="{ 'text-gray-900 font-bold': active_step === 'home' }"
            >
              المسكن
            </button>
            <p v-else class="text-gray-500 hover:text-gray-800">المسكن</p>
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
              type="button"
              @click="switch_form_BC('facilities', 'تعديل المرافق الإساسية')"
            >
              <span
                :class="{ 'text-gray-900 font-bold': active_step === 'facilities' }"
                class="text-gray-500 hover:text-gray-800"
                >المرافق الصحية</span
              >
            </button>
            <p v-else class="text-gray-500 hover:text-gray-800">المرافق الصحية</p>
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
              class="text-gray-500 hover:text-gray-800"
              :class="{ 'text-gray-900 font-bold': active_step === 'notes' }"
            >
              الملاحظات
            </button>
            <p v-else class="text-gray-500 hover:text-gray-800">الملاحظات</p>
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
              class="text-gray-500 hover:text-gray-800"
              :class="{ 'text-gray-900 font-bold': active_step === 'files' }"
            >
              الملفات
            </button>
            <p v-else class="text-gray-500 hover:text-gray-800">الملفات</p>
          </li>
        </ol>
      </nav>
    </div>
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
