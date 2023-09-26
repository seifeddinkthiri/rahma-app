<template>
  <div>
    <Head title="Interventions" />
    <trashed-message v-if="deleted_intervention_id" class="mb-6" @restore="restore(deleted_intervention_id)">تم حذف هذا التدخل . </trashed-message>
    <h1 class="mb-8 text-xl font-bold">التدخلات</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter :onlySearch="false" v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block mb-2 mt-2 text-gray-700"> نوع التدخل </label>
        <select v-model="form.type" class="form-select mt-1 w-full">
          <option value="shipments">عيني</option>
          <option value="cash">نقدي</option>
          <option value="other">آخر</option>
        </select>

        <label class="block text-gray-700">تم الحذف</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option value="with">مع المحذوف</option>
          <option value="only">فقط المحذوف</option>
        </select>
      </search-filter>
      <Link             class="btn-indigo"
 href="/interventions/create">
        <span>إنشاء</span>
        <span class="hidden md:inline">&nbsp;التدخل</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">النوع</th>
            <th class="pb-4 pt-6 px-6">القيمة</th>
            <th class="pb-4 pt-6 px-6">التاريخ</th>
            <th class="pb-4 pt-6 px-6">المنتفع </th>
            <th class="pb-4 pt-6 px-6">المسؤل</th>
            <th class="pb-4 pt-6 px-6">هاتف المسؤل</th>
            <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="intervention in interventions.data" :key="intervention.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
  <td class="border-t">
    <Link class="flex items-center px-6 py-4"  :href="`/interventions/${intervention.id}/edit`" tabindex="-1">
      <p v-if="intervention.type == 'shipments'" class="h-4">عيني</p>
      <p v-else-if="intervention.type == 'cash'" class="h-4">نقدي</p>
      <p v-else-if="intervention.type == 'other'" class="h-4">آخر</p>
    </Link>
  </td>
  <td class="border-t">
    <Link  class="flex items-center px-6 py-4" :href="`/interventions/${intervention.id}/edit`" tabindex="-1">
      <p class="h-4">{{ intervention.value }}</p>
    </Link>
  </td>
  <td class="border-t">
    <Link  class="flex items-center px-6 py-4" :href="`/interventions/${intervention.id}/edit`" tabindex="-1">
      <p class="h-4">{{ intervention.date }}</p>
    </Link>
  </td>
  <td class="border-t">
    <Link v-if=" intervention.family" class="flex items-center px-6 py-4" :href="`/families/${intervention.family.id}/edit`" tabindex="-1">
      <p class="h-4">{{ intervention.family.name }}</p>
    </Link>
  </td>
  <td class="border-t">
    <Link  class="focus:text-blue-500 flex items-center px-6 py-4" :href="`/interventions/${intervention.id}/edit`">
      <p class="h-4">{{ intervention.intervenor }}</p>
      <icon v-if="intervention.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
    </Link>
  </td>
  <td class="border-t">
    <Link  class="flex items-center px-6 py-4" :href="`/interventions/${intervention.id}/edit`" tabindex="-1">
      <p class="h-4">{{ intervention.intervenor_phone }}</p>
    </Link>
  </td>
  <td class="w-px border-t">
    <div class="flex items-center">
      <Link  class="flex items-center px-4" :href="`/interventions/${intervention.id}/edit`" tabindex="-1">
        <icon name="cheveron-right" class="block w-5 h-4 fill-gray-400" />
      </Link>
      <Link  class="flex items-center px-4" :href="`/interventions/${intervention.id}/show`" tabindex="-1">
        <icon name="eye" />
      </Link>
      <button v-if="intervention.deleted_at" class="flex items-center px-4" tabindex="-1" @click="restore(intervention.id)">
        <icon name="restore" />
      </button>
      <button  v-else class="flex items-center px-4" tabindex="-1" @click="destroy(intervention.id)">
        <icon name="delete" />
      </button>
    </div>
  </td>
</tr>

          <tr v-if="interventions.data.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>
    <pagination class="mt-6" :links="interventions.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3'
import Icon from '@/Shared/Icon'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination'
import SearchFilter from '@/Shared/SearchFilter'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    filters: Object,
    interventions: Object,
  },
  data() {
    return {
      deleted_intervention_id: null,
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        type: this.filters.type,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/interventions', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    destroy(id) {
      if (confirm('هل أنت متأكد أنك تريد حذف هذا التدخل ؟')) {
        this.$inertia.delete(`/interventions/${id}`)
        this.deleted_intervention_id = id
      }
    },
    restore(id) {
      if (confirm('هل أنت متأكد أنك تريد استعادة هذا التدخل ؟')) {
        this.$inertia.put(`/interventions/${id}/restore`)
        this.deleted_intervention_id = null
      }
    },
  },
}
</script>
