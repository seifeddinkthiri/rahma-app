<template>
  <div class="container mx-auto py-3 space-y-5" ref="breadcrumb">
    <!-- Breadcrumb -->
    <div class="px-4 py-2 bg-white rounded-lg shadow">
      <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
          <li>
            <button
              @click="updateHorW('husband')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              :class="{ 'text-gray-900 font-bold': HorW == 'husband' }"
            >
              الزوج
            </button>
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
              @click="updateHorW('wife')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-black-800 transition-colors"
              :class="{ 'text-gray-900 font-bold': HorW == 'wife' }"
            >
              الزوجة
            </button>
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
              @click="update_step('part1', 'أضف بيانات الأفراد')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-gray-900 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'part1' && !HorW }"
            >
              الأفراد
            </button>
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
              type="button"
              @click="update_step('facilities', 'تعديل المرافق الإساسية')"
              class="border-blue-500 border rounded-lg"
            >
              <span
                :class="{ 'text-gray-900 font-bold': active_step === 'facilities' }"
                class="text-gray-500 hover:text-gray-900"
                >المرافق
              </span>
            </button>
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
              @click="update_step('home', 'أضف بيانات المسكن')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-gray-900 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'home' }"
            >
              المسكن
            </button>
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
              @click="update_step('notes', 'أضف  ملاحظة')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-gray-900 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'notes' }"
            >
              الملاحظات
            </button>
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
              @click="update_step('files', 'أضف ملف')"
              class="border-blue-500 border rounded-lg px-3 py-1 text-gray-500 hover:text-gray-900 transition-colors"
              :class="{ 'text-gray-900 font-bold': active_step === 'files' }"
            >
              الملفات
            </button>
          </li>
        </ol>
      </nav>
    </div>

    <!-- Other content of the child component -->
    <h1 class="mb-8 text-xl font-bold">{{ current_form_title }}</h1>
    <!-- ... -->
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
    HorW: String,
    Family_id: Number,
    active_step: {
      type: String, // Change this to Number if active_step can also be a number
      default: "",
    },
  },
  data() {
    return {};
  },
  methods: {
    update_step(step, title) {
      this.$emit("update-active-step", step); // Emit the 'update-active-step' event with the new step value
      this.$emit("update-current-form-title", title); // Emit the 'update-current-form-title' event with the new title value
      this.$emit("update-H-or-W", "");
    },
    updateHorW(HorW) {
      if (HorW == "husband") {
        this.$emit("update-current-form-title", "أدخل بيانات الزوج");
      } else {
        this.$emit("update-current-form-title", "أدخل بيانات الزوجة");
      }
      this.$emit("update-active-step", "part1");

      this.$emit("update-H-or-W", HorW);
    },
  },
};
</script>
