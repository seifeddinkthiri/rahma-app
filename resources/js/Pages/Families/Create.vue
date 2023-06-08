<template>
  <div>
    <Head title="Create Family" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/families">العائلات</Link>
      <span class="text-indigo-400 font-medium">/</span> إنشاء
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">

      <div ref="part2" >
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="الاسم" />
          <text-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" label="الصورة" />
        </div>
        <div class="flex space-x-3 items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">

          <loading-button :loading="form.processing" class="btn-indigo" type="submit">إنشاء عائلة</loading-button>
        </div>
      </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Layout from '@/Shared/Layout'
import TextInput from '@/Shared/TextInput'
import SelectInput from '@/Shared/SelectInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        photo: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/families')
    },
  },
}
</script>
