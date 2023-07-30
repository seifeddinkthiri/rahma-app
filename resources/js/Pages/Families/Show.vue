<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/families">المنتفعين</Link>
      <span class="text-blue-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <!-- Family Card -->
    <h2 class="mt-12 text-2xl font-bold">العائلة</h2>
    <br />
    <div class="bg-white rounded-md shadow p-4">
      <table class="table-auto w-full">
        <tbody>
          <tr class="w-10 h-10">
            <td class="border px-4 py-2">الاسم</td>
            <td class="border px-4 py-2">
              {{ form.name }}
            </td>
          </tr>
          <tr>
            <td class="border px-4 py-2">الهاتف</td>
            <td class="border px-4 py-2">
              {{ form.phone }}
            </td>
          </tr>
          <tr>
            <td class="border px-4 py-2">العنوان</td>
            <td class="border px-4 py-2">
              {{ form.address }}
            </td>
          </tr>
          <tr v-if="form.photo">
            <td class="border px-4 py-2">الصورة</td>
            <td class="border px-4 py-2">
              <img
                :src="form.photo"
                alt="صورة المنتفع"
                class="h-32 w-32 object-contain"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الأفراد</h2>
    <br />
    <div>
      <div class="bg-white rounded-md shadow overflow-hidden">
        <br />
        <div class="flex items-center w-full flex-row justify-around">
          <p v-if="form.name == null" class="text-red-600 px-6">
            يجب تعيين معيل أسرة لهذه العائلة
          </p>
        </div>
        <div class="mt-6 bg-white rounded shadow overflow-x-auto">
          <table class="w-full whitespace-nowrap">
            <thead>
              <tr class="text-right font-bold">
                <th class="pb-4 pt-6 px-6">الاسم</th>
                <th class="pb-4 pt-6 px-6">القرابة</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="member in family.members.data"
                :key="member.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100"
              >
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                    :to="`/members/${member.id}/edit`"
                  >
                    {{ member.name }}
                    <icon
                      v-if="member.deleted_at"
                      name="trash"
                      class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                    />
                  </Link>
                </td>
                <td class="border-t">
                  <Link
                    class="flex items-center px-6 py-4"
                    :to="`/members/${member.id}/edit`"
                    tabindex="-1"
                  >
                    <p v-if="member.kinship == 'husband'">زوج</p>
                    <p v-if="member.kinship == 'wife'">زوجة</p>
                    <p v-if="member.kinship == 'child'">إبن</p>
                    <p v-if="member.kinship == 'elderly'">مسن</p>
                    <p v-if="member.kinship == 'other_member'">فرد إضافي</p>
                  </Link>
                </td>
                <td class="border-t">
                  <p v-if="member.caregiver">معيل الأسرة</p>
                </td>
              </tr>
              <tr v-if="family.members.data.length === 0">
                <td class="px-6 py-4 border-t" colspan="4">لم يتم العثور على الأفراد</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Facilities -->
    <h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
    <br />
    <div class="bg-white rounded-md shadow p-4">
      <table class="table-auto w-full">
        <tbody>
          <tr>
            <td class="border px-4 py-2">الصرف الصحي</td>
            <td class="border px-4 py-2" v-if="family.facilities[0].Sanitaion">
              <icon name="check" />
            </td>
            <td class="border px-4 py-2" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="border px-4 py-2">الكهرباء</td>
            <td class="border px-4 py-2" v-if="family.facilities[0].electricity">
              <icon name="check" />
            </td>
            <td class="border px-4 py-2" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="border px-4 py-2">الماء</td>
            <td class="border px-4 py-2" v-if="family.facilities[0].water">
              <icon name="check" />
            </td>
            <td class="border px-4 py-2" v-else><icon name="minus" /></td>
          </tr>
          <tr>
            <td class="border px-4 py-2">التهوئة</td>
            <td class="border px-4 py-2" v-if="family.facilities[0].ventilation">
              <icon name="check" />
            </td>
            <td class="border px-4 py-2" v-else><icon name="minus" /></td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Notes -->
    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="note in family.notes.data" :key="note.id">
        <div class="bg-white rounded-md shadow p-4">
          <h3 class="text-xl font-bold mb-2">{{ note.title }}</h3>
          <p class="text-gray-700 truncate">{{ note.value }}</p>
        </div>
      </div>
      <div v-if="family.notes.data.length === 0">
        <p class="text-xl font-bold text-center">لم يتم العثور على ملاحظات</p>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
    <br />
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
      <div v-for="home in family.home" :key="home.id">
        <div class="bg-white rounded-md shadow p-4">
          <h3 class="text-xl font-bold mb-2">
            <span v-if="home.status == 'Ownership'">ملك</span>
            <span v-else-if="home.status == 'without compensation'">بدون مقابل</span>
            <span v-else-if="home.status == 'inherited'">ورثة</span>
            <span v-else-if="home.status == 'lease'">إيجار</span>
          </h3>
          <p class="text-gray-700 mb-2" v-if="home.status == 'lease'">
            سعر الكراء: {{ home.allocation_price }}
          </p>
          <p class="text-gray-700 truncate">{{ home.desciption }}</p>
        </div>
      </div>
      <div v-if="family.home.length === 0">
        <p class="text-xl font-bold text-center">لم يتم العثور على مساكن</p>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";

export default {
  components: {
    Head,
    Icon,
    Link,
  },
  layout: Layout,
  props: {
    family: Object,
  },
  remember: "form",

  data() {
    return {
      note_id: null,

      form: this.$inertia.form({
        name: this.family.name,
        phone: this.family.phone,
        address: this.family.address,
        photo: null,
        id: this.family.id,
      }),
    };
  },
};
</script>
