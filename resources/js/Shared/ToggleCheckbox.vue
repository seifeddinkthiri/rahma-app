<template>
  <div>
    <input
      id="toggle"
      type="checkbox"
      class="hidden"
      :checked="isChecked"
      @change="toggleHandle"
    />
    <div>
      <label for="toggle" class="flex items-center cursor-pointer">
        <span class="relative">
          <span
            class="block w-10 h-6 rounded-full"
            :class="{'bg-gray-400': !isChecked, 'bg-green-500': isChecked}"
          ></span>
          <span
            class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition transform"
            :class="{ 'translate-x-4': isChecked }"
          ></span>
        </span>
      </label>
    </div>
    &nbsp;&nbsp;
    <div>
      <span class="ml-3 text-gray-700" v-if="isChecked">جيدة</span>
      <span class="ml-3 text-gray-700" v-else>عليلة</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isChecked: Boolean,
  },
  methods: {
    toggleHandle() {
      this.$emit('toggle');
    },
  },
};
</script>
