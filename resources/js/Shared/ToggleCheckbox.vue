<template>
  <div class="pb-8 pr-6 w-full lg:w-1/2">
    <label for="toggle" class="form-label">{{ label }}</label>
    <div class="form-input flex items-center">
      <input id="toggle" type="checkbox" class="hidden" :checked="isChecked" @change="toggleHandle" />

      <label for="toggle" class="flex items-center cursor-pointer">
        <div class="relative">
          <div id="second_div" class="block w-10 h-6 rounded-full" :class="{ 'bg-gray-400': !isChecked, 'bg-green-500': isChecked }"></div>


          <div  class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transform transition" :class="{ 'translate-x-4': isChecked }"></div>

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
    label:String,
    active_value:String,
    inactive_value:String,

  },
  methods: {
    toggleHandle() {
      this.$emit('toggle')
    },
  },
}
</script>
