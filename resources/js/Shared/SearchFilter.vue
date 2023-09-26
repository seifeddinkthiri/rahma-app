<template>
  <div class="flex items-center">
    <div class="flex w-full bg-white rounded shadow">
      <dropdown
        v-if="!onlySearch"
        :auto-close="false"
        class="px-4 border-r rounded-l focus:z-10 hover:bg-gray-100 focus:border-white focus:ring md:px-6"
        placement="bottom-end"
      >
        <template #default>
          <div class="flex items-baseline">
            <span class="hidden text-gray-700 md:inline">فلترة </span>
            <svg
              class="w-2 h-2 fill-gray-700 md:ml-2"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 961.243 599.998"
            >
              <path
                d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z"
              />
            </svg>
          </div>
        </template>
        <template #dropdown>
          <div
            class="w-screen px-4 py-6 mt-2 bg-white rounded shadow-xl"
            :style="{ maxWidth: `${maxWidth}px` }"
          >
            <slot />
          </div>
        </template>
      </dropdown>
      <input
        class="w-full px-6 py-3 rounded  focus:shadow-outline"
        autocomplete="off"
        type="text"
        name="search"
        placeholder="بحث ... "
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      />
    </div>
    <div class="mr-3">
      <button
        v-if="!onlySearch"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded hover:bg-gray-300 focus:bg-gray-300 focus:outline-none"
        type="button"
        @click="$emit('reset')"
      >
        إعادة
      </button>
    </div>
  </div>
</template>

<script>
import Dropdown from "@/Shared/Dropdown";

export default {
  components: {
    Dropdown,
  },
  props: {
    onlySearch: Boolean,
    modelValue: String,
    maxWidth: {
      type: Number,
      default: 300,
    },
  },
  emits: ["update:modelValue", "reset"],
};
</script>
