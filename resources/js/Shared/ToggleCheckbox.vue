<template>
  <div class="pb-8 pr-6 w-full lg:w-1/2">
    <label :for="inputId" class="form-label">{{ label }}</label>
    <div class="form-input flex items-center">
      <input
        :id="inputId"
        type="checkbox"
        class="hidden"
        :checked="isChecked"
        @change="toggleHandle"
      />

      <label :for="inputId" class="flex items-center cursor-pointer">
        <div class="relative">
          <div
            id="second_div"
            class="block w-10 h-6 rounded-full"
            :class="{ 'bg-gray-400': !isChecked, 'bg-green-500': isChecked }"
          ></div>

          <div
            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transform transition"
            :class="{ 'translate-x-4': isChecked }"
          ></div>
        </div>
        &nbsp; &nbsp;
        <div class="ml-3 text-gray-700" id="first_div">
          <span v-if="isChecked">{{ active_value }}</span>
          <span v-else>{{ inactive_value }}</span>
        </div>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isChecked: Boolean,
    label: String,
    active_value: String,
    inactive_value: String,
  },
  computed: {
    inputId() {
      // Generate a unique ID for the input element
      return `toggle-${Math.random().toString(36).substr(2, 10)}`;
    },
  },
  methods: {
    toggleHandle() {
      this.$emit("toggle");
    },
  },
};
</script>
