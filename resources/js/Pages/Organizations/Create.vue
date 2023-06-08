<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/organizations">العائلات</Link>
      <span class="text-indigo-400 font-medium">/</span> إنشاء
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
      <div ref="part1" v-if="!show_second_form_part">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input  class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 1 " />
          <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 2" />
          <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 3" />
          <text-input class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 4" />
          <text-input  class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 5" />
          <select-input  class="pb-8 pr-6 w-full lg:w-1/2" label="الخانة 6">
            <option :value="null" />
            <option value="CA">Canada</option>
            <option value="US">United States</option>
          </select-input>
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button @click="show_second_form_part = true"  class="btn-indigo" type="button">التالي </button>
        </div>
      </div>
      <div ref="part2" v-else>
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="الاسم" />
          <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="البريد الإلكتروني" />
          <text-input v-model="form.phone" :error="form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="الهاتف" />
          <text-input v-model="form.address" :error="form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="العنوان" />
          <text-input v-model="form.city" :error="form.errors.city" class="pb-8 pr-6 w-full lg:w-1/2" label="المدينة" />
          <text-input v-model="form.region" :error="form.errors.region" class="pb-8 pr-6 w-full lg:w-1/2" label="المحافظة/الولاية" />
          <select-input v-model="form.country" :error="form.errors.country" class="pb-8 pr-6 w-full lg:w-1/2" label="البلد">
            <option :value="null" />
            <option value="CA">Canada</option>
            <option value="US">United States</option>
          </select-input>
          <text-input v-model="form.postal_code" :error="form.errors.postal_code" class="pb-8 pr-6 w-full lg:w-1/2" label="الرمز البريدي" />
        </div>
        <div class="flex space-x-3 items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button @click="show_second_form_part = false"  class="btn-indigo" type="button">العودة </button>

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
      show_second_form_part : false,
      form: this.$inertia.form({
        name: null,
        email: null,
        phone: null,
        address: null,
        city: null,
        region: null,
        country: null,
        postal_code: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/organizations')
    },
  },
}
</script>
