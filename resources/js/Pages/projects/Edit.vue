<template>
  <div>
    <Head :title="'المشاريع'" />
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/project">المشاريع </Link>
      <span class="text-blue-400 font-medium"> : </span>
    </h1>
    <trashed-message
      v-if="deleted_intervention_id"
      class="mb-6"
      @restore="restore_intervention(deleted_intervention_id)"
      >تم حذف هذا التدخل .
    </trashed-message>
    <trashed-message v-if="project.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المشروع .
    </trashed-message>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="اسم المشروع"
              :error="form.errors.name"
              placeholder="اسم المشروع هنا"
            />
            <text-area-input
              v-model="form.description"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="وصف المشروع"
              :error="form.errors.description"
              placeholder="وصف المشروع هنا"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.date"
              :error="form.errors.date"
              label="تاريخ بدء المشروع"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              v-model="form.deadline"
              :error="form.errors.deadline"
              label="تاريخ الإنتهاء المتوقع"
            />

            <select-input
              v-model="form.status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="حالة المشروع"
              :error="form.errors.status"
            >
              <option value="" disabled hidden>إختر حالة المشروع</option>
              <option :value="false">قيد التنفيذ</option>
              <option :value="true">مكتمل</option>
            </select-input>
          </div>
        </div>
        <div class="text-right px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button
            :loading="form.processing"
            class="ml-3 inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل المشروع
          </loading-button>

          <button
            v-if="!project.deleted_at"
            class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف المشروع
          </button>
        </div>
      </form>
    </div>

    <h2 class="mt-12 text-2xl font-bold">التدخلات</h2>
    <br />

  
    <div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">
      <div class="flex items-center pr-4 mt-4">
        <button @click="show_intervention_modal = true" class="btn-indigo">
          إنشاء تدخل
        </button>
      </div>
        <table class="w-full whitespace-nowrap">
        <thead class="text-right">
          <tr class="h-12 text-right font-bold">
            <th class="pb-4 pt-6 px-6">النوع</th>
            <th class="pb-4 pt-6 px-6">القيمة</th>
            <th class="pb-4 pt-6 px-6">التاريخ</th>
            <th class="pb-4 pt-6 px-6">المنتفع</th>
            <th class="pb-4 pt-6 px-6">المسؤل</th>
            <th class="pb-4 pt-6 px-6">هاتف المسؤل</th>
            <th class="pb-4 pl-3 pt-6" colspan="3">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="intervention in normalInterventions"
            :key="intervention.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p v-if="intervention.type == 'shipments'" class="h-4">عيني</p>
                <p v-else-if="intervention.type == 'cash'" class="h-4">نقدي</p>
                <p v-else-if="intervention.type == 'other'" class="h-4">آخر</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.value }}</p>
              </Link>
            </td>

            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.date }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                v-if="intervention.family"
                class="flex items-center px-6 py-4"
                :href="`/families/${intervention.family.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.family.name }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="focus:text-blue-500 flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
              >
                <p class="h-4">{{ intervention.intervenor }}</p>
                <icon
                  v-if="intervention.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.intervenor_phone }}</p>
              </Link>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-5 h-4 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  v-if="intervention.deleted_at"
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="restore(intervention.id)"
                >
                  <icon name="restore" />
                </button>
                <button
                  v-else
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(intervention.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr
            v-for="intervention in solitaryInterventions"
            :key="intervention.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
                tabindex="-1"
              >
                <p v-if="intervention.type == 'shipments'" class="h-4">عيني</p>
                <p v-else-if="intervention.type == 'cash'" class="h-4">نقدي</p>
                <p v-else-if="intervention.type == 'other'" class="h-4">آخر</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.value }}</p>
              </Link>
            </td>

            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.date }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.beneficial_name }}</p>
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="focus:text-blue-500 flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
              >
                <p class="h-4">{{ intervention.intervenor }}</p>
                <icon
                  v-if="intervention.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </Link>
            </td>
            <td class="border-t">
              <Link
                class="flex items-center px-6 py-4"
                :href="
                  project.isSolitary
                    ? `/solitaryInterventions/${intervention.id}/edit`
                    : `/interventions/${intervention.id}/edit`
                "
                tabindex="-1"
              >
                <p class="h-4">{{ intervention.intervenor_phone }}</p>
              </Link>
            </td>
            <td class="w-px border-t">
              <div class="flex items-center">
                <Link
                  class="flex items-center px-4"
                  :href="
                    project.isSolitary
                      ? `/solitaryInterventions/${intervention.id}/edit`
                      : `/interventions/${intervention.id}/edit`
                  "
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-5 h-4 fill-gray-400" />
                </Link>
                <Link
                  class="flex items-center px-4"
                  :href="`/interventions/${intervention.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" />
                </Link>
                <button
                  v-if="intervention.deleted_at"
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="restore(intervention.id)"
                >
                  <icon name="restore" />
                </button>
                <button
                  v-else
                  class="flex items-center px-4"
                  tabindex="-1"
                  @click="destroy(intervention.id)"
                >
                  <icon name="delete" />
                </button>
              </div>
            </td>
          </tr>
          <tr v-if="project.interventions.length === 0">
            <td class="px-6 py-4 border-t" colspan="4">قائمة فارغة</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- add Solitary intervention for Solitary project -->

    <div
      class="bg-white rounded-md shadow overflow-hidden mt-6"
      v-if="project.isSolitary"
    >
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <div v-if="show_intervention_modal">
          <div
            class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50"
          >
            <div
              class="relative bg-white p-6 sm:p-8 rounded-md shadow max-h-full overflow-y-auto w-full sm:w-2/3 md:w-1/2 lg:w-1/3"
            >
              <button
                class="absolute top-0 right-0 p-4"
                @click="show_intervention_modal = false"
              >
                &times;
              </button>
              <form @submit.prevent="store_intervention">
                <div>
                  <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="beneficial_name"
                      v-model="intervention_form.beneficial_name"
                      :error="intervention_form.errors.beneficial_name"
                      placeholder="إسم المنتفع هنا"
                      label="إسم المنتفع "
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="beneficial_phone"
                      v-model="intervention_form.beneficial_phone"
                      :error="intervention_form.errors.beneficial_phone"
                      placeholder="هاتف المنتفع هنا"
                      label="هاتف المنتفع "
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="beneficial_cin"
                      v-model="intervention_form.beneficial_cin"
                      :error="intervention_form.errors.beneficial_cin"
                      placeholder="بطاقة تعريف المنتفع هنا"
                      label="بطاقة تعريف المنتفع"
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="beneficial_address"
                      v-model="intervention_form.beneficial_address"
                      :error="intervention_form.errors.beneficial_address"
                      placeholder="عنوان  المنتفع  هنا"
                      label="عنوان  المنتفع "
                    />

                    <select-input
                      v-model="intervention_form.type"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      label="نوع التدخل"
                      :error="intervention_form.errors.type"
                      ref="typeSelect"
                    >
                      <option :value="null" selected disabled hidden>
                        إختر نوع التدخل
                      </option>
                      <option value="shipments">عيني</option>
                      <option value="cash">نقدي</option>
                      <option value="other">آخر</option>
                    </select-input>

                    <text-input
                      v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                      v-model="intervention_form.value"
                      :error="intervention_form.errors.value"
                      label="قيمة التدخل"
                      :id="
                        intervention_form.type === 'shipments'
                          ? 'shipments'
                          : intervention_form.type === 'cash'
                          ? 'cash'
                          : 'other'
                      "
                      :placeholder="
                        intervention_form.type === null
                          ? 'القيمة'
                          : intervention_form.type === 'shipments'
                          ? 'الكمية'
                          : intervention_form.type === 'cash'
                          ? 'المبلغ'
                          : 'القيمة الأخرى'
                      "
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      type="date"
                      id="date"
                      v-model="intervention_form.date"
                      :error="intervention_form.errors.date"
                      label="تاريخ التدخل"
                      placeholder=" تاريخ التدخل هنا"
                    />
                    <select-input
                      v-model="intervention_form.beneficial"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="نوع المنتفع"
                      :error="intervention_form.errors.beneficial"
                    >
                      <option :value="null" selected disabled hidden>
                        إختر نوع المنتفع
                      </option>
                      <option value="family">عائلة معوزة</option>
                      <option value="elderly">مسن</option>
                      <option value="divorced">مطلقة</option>
                      <option value="widow">أرملة</option>
                      <option value="single_mother">أم عزباء</option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'family'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="العائلة"
                    >
                      <option hidden disabled :value="null">إختر العائلة</option>

                      <option v-for="family in filteredFamilies" :value="family.id">
                        {{ family.caregiver_phone }} - {{ family.name }}
                      </option>
                      <option :value="null" disabled v-if="filteredFamilies.length == 0">
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'elderly'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2  min-w-full sm:min-w-0"
                      label="المسن"
                    >
                      <option hidden disabled :value="null">إختر المسن</option>

                      <option v-for="elderly in elderlies" :value="elderly.id">
                        {{ elderly.caregiver_phone }} - {{ elderly.name }}
                      </option>
                      <option disabled :value="null" v-if="elderlies.length == 0">
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'divorced'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="المطلقة"
                    >
                      <option hidden disabled :value="null">إختر المطلقة</option>

                      <option v-for="divorced in divorceds" :value="divorced.id">
                        {{ divorced.caregiver_phone }} - {{ divorced.name }}
                      </option>
                      <option disabled :value="null" v-if="divorceds.length == 0">
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'single_mother'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2  min-w-full sm:min-w-0"
                      label="الأم العزباء"
                    >
                      <option hidden disabled :value="null">إختر الأم العزباء</option>

                      <option
                        v-for="singleMother in singleMothers"
                        :value="singleMother.id"
                      >
                        {{ singleMother.caregiver_phone }} - {{ singleMother.name }}
                      </option>
                      <option disabled :value="null" v-if="singleMothers.length == 0">
                        قائمة فارغة
                      </option>
                    </select-input>

                    <select-input
                      v-if="intervention_form.beneficial == 'widow'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2  min-w-full sm:min-w-0"
                      label="الأرملة"
                    >
                      <option hidden disabled :value="null">إختر الأرملة</option>

                      <option v-for="widow in widows" :value="widow.id">
                        {{ widow.caregiver_phone }} - {{ widow.name }}
                      </option>
                      <option :value="null" v-if="widows.length == 0" disabled>قائمة فارغة</option>
                    </select-input>
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="intervenor"
                      v-model="intervention_form.intervenor"
                      :error="intervention_form.errors.intervenor"
                      placeholder="إسم المسؤل هنا"
                      label="إسم المسؤل"
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="intervenor"
                      v-model="intervention_form.intervenor_phone"
                      :error="intervention_form.errors.intervenor_phone"
                      placeholder="هاتف المسؤل هنا"
                      label="هاتف المسؤل"
                    />
                    <TextAreaInput
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="notes"
                      v-model="intervention_form.notes"
                      :error="intervention_form.errors.notes"
                      placeholder="اكتب ملاحظاتك هنا"
                      label="ملاحظات"
                    />

                    <file-input
                      v-model="intervention_form.file"
                      :error="intervention_form.errors.file"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      type="file"
                      label="أضف ملف"
                    />
                    <text-input
                      v-model="intervention_form.title"
                      :error="intervention_form.errors.title"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      label="عنوان الملف"
                      placeholder="عنوان الملف هنا"
                    />
                  </div>

                  <button class="btn-indigo" type="submit">إضافة</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- add normal intervention for normal project -->

    <div
      class="bg-white rounded-md shadow overflow-hidden mt-6"
      v-if="!project.isSolitary"
    >
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <div v-if="show_intervention_modal">
          <div
            class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50"
          >
            <div
              class="relative bg-white p-6 sm:p-8 rounded-md shadow max-h-full overflow-y-auto w-full sm:w-2/3 md:w-1/2 lg:w-1/3"
            >
              <button
                class="absolute top-0 right-0 p-4"
                @click="show_intervention_modal = false"
              >
                &times;
              </button>
              <form @submit.prevent="store_intervention">
                <div>
                  <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <select-input
                      v-model="intervention_form.type"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      label="نوع التدخل"
                      :error="intervention_form.errors.type"
                      ref="typeSelect"
                    >
                      <option :value="null" selected disabled hidden>
                        إختر نوع التدخل
                      </option>
                      <option value="shipments">عيني</option>
                      <option value="cash">نقدي</option>
                      <option value="other">آخر</option>
                    </select-input>

                    <text-input
                      v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                      v-model="intervention_form.value"
                      :error="intervention_form.errors.value"
                      label="قيمة التدخل"
                      :id="
                        intervention_form.type === 'shipments'
                          ? 'shipments'
                          : intervention_form.type === 'cash'
                          ? 'cash'
                          : 'other'
                      "
                      :placeholder="
                        intervention_form.type === null
                          ? 'القيمة'
                          : intervention_form.type === 'shipments'
                          ? 'الكمية'
                          : intervention_form.type === 'cash'
                          ? 'المبلغ'
                          : 'القيمة الأخرى'
                      "
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      type="date"
                      id="date"
                      v-model="intervention_form.date"
                      :error="intervention_form.errors.date"
                      label="تاريخ التدخل"
                      placeholder=" تاريخ التدخل هنا"
                    />

                    <select-input
                      v-model="intervention_form.beneficial"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="نوع المنتفع"
                      :error="intervention_form.errors.beneficial"
                    >
                      <option :value="null" selected disabled hidden>
                        إختر نوع المنتفع
                      </option>
                      <option value="family">عائلة معوزة</option>
                      <option value="elderly">مسن</option>
                      <option value="divorced">مطلقة</option>
                      <option value="widow">أرملة</option>
                      <option value="single_mother">أم عزباء</option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'family'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="العائلة"
                    >
                      <option hidden disabled :value="null">إختر العائلة</option>

                      <option v-for="family in filteredFamilies" :value="family.id">
                        {{ family.caregiver_phone }} - {{ family.name }}
                      </option>
                      <option :value="null" v-if="filteredFamilies.length == 0" disabled>
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'elderly'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="المسن"
                    >
                      <option hidden disabled :value="null">إختر المسن</option>

                      <option v-for="elderly in elderlies" :value="elderly.id">
                        {{ elderly.caregiver_phone }} - {{ elderly.name }}
                      </option>
                      <option :value="null" v-if="elderlies.length == 0" disabled>
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'divorced'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="المطلقة"
                    >
                      <option hidden disabled :value="null">إختر المطلقة</option>

                      <option v-for="divorced in divorceds" :value="divorced.id">
                        {{ divorced.caregiver_phone }} - {{ divorced.name }}
                      </option>
                      <option :value="null" v-if="divorceds.length == 0" disabled>
                        قائمة فارغة
                      </option>
                    </select-input>
                    <select-input
                      v-if="intervention_form.beneficial == 'single_mother'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="الأم العزباء"
                    >
                      <option hidden disabled :value="null">إختر الأم العزباء</option>

                      <option
                        v-for="singleMother in singleMothers"
                        :value="singleMother.id"
                      >
                        {{ singleMother.caregiver_phone }} - {{ singleMother.name }}
                      </option>
                      <option :value="null" v-if="singleMothers.length == 0" disabled>
                        قائمة فارغة
                      </option>
                    </select-input>

                    <select-input
                      v-if="intervention_form.beneficial == 'widow'"
                      v-model="intervention_form.family"
                      class="pb-8 pr-6 w-full lg:w-1/2"
                      label="الأرملة"
                    >
                      <option hidden disabled :value="null">إختر الأرملة</option>

                      <option v-for="widow in widows" :value="widow.id">
                        {{ widow.caregiver_phone }} - {{ widow.name }}
                      </option>
                      <option :value="null" v-if="widows.length == 0" disabled>قائمة فارغة</option>
                    </select-input>

                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="intervenor"
                      v-model="intervention_form.intervenor"
                      :error="intervention_form.errors.intervenor"
                      placeholder="إسم المسؤل هنا"
                      label="إسم المسؤل"
                    />
                    <text-input
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="intervenor"
                      v-model="intervention_form.intervenor_phone"
                      :error="intervention_form.errors.intervenor_phone"
                      placeholder="هاتف المسؤل هنا"
                      label="هاتف المسؤل"
                    />
                    <TextAreaInput
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      id="notes"
                      v-model="intervention_form.notes"
                      :error="intervention_form.errors.notes"
                      placeholder="اكتب ملاحظاتك هنا"
                      label="ملاحظات"
                    />

                    <file-input
                      v-model="intervention_form.file"
                      :error="intervention_form.errors.file"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      type="file"
                      label="أضف ملف"
                    />
                    <text-input
                      v-model="intervention_form.title"
                      :error="intervention_form.errors.title"
                      class="pb-8 pr-6 lg:w-1/2 min-w-full sm:min-w-0"
                      label="عنوان الملف"
                      placeholder="عنوان الملف هنا"
                    />
                  </div>

                  <button class="btn-indigo" type="submit">إضافة</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import SelectInput from "@/Shared/SelectInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import FileInput from "@/Shared/FileInput";
import TextAreaInput from "@/Shared/TextareaInput";
import ToggleCheckbox from "../../Shared/ToggleCheckbox.vue";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    FileInput,
    TextAreaInput,
    ToggleCheckbox,
  },
  layout: Layout,
  props: {
    project: Object,
    elderlies: Object,
    divorceds: Object,
    singleMothers: Object,
    widows: Object,
    families: Object,
  },
  remember: "form",
  created() {
    if (this.project.status == 1) {
      this.form.status = true;
    } else {
      this.form.status = false;
    }
  },
  data() {
    return {
      show_intervention_modal: false,
      deleted_intervention_id: null,
      intervention_form: this.$inertia.form({
        isSolitary: this.project.isSolitary,
        beneficial_name: null,
        beneficial_phone: null,
        beneficial_cin: null,
        beneficial_address: null,
        beneficial: null,
        date: null,
        type: null,
        value: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        notes: null,
        family: null,
        project: this.project.id,
      }),
      form: this.$inertia.form({
        name: this.project.name,
        description: this.project.description,
        date: this.project.date,
        deadline: this.project.deadline,
        status: this.project.status,
      }),
    };
  },
  computed: {
    filteredFamilies() {
      return this.families.filter((family) => family.name !== null);
    },
    solitaryInterventions() {
      return this.project.interventions.filter(
        (intervention) => intervention.isSolitary == true
      );
    },
    normalInterventions() {
      return this.project.interventions.filter(
        (intervention) => intervention.isSolitary == false
      );
    },
  },
  methods: {
    restore_intervention(id) {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا التدخل ؟")) {
        this.$inertia.put(`/interventions/${id}/restore`);
        this.deleted_intervention_id = null;
      }
    },
    destroy_intervention(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا التدخل ")) {
        this.$inertia.delete(`/interventions/${id}`);
        this.deleted_intervention_id = id;
      }
    },
    store_intervention() {
      this.intervention_form.post("/interventions", {
        preserveScroll: true,
        onSuccess: () => {
          this.intervention_form.reset();
          this.show_intervention_modal = false;
        },
      });
    },
    update() {
      this.form.post(`/projects/${this.project.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا المشروع ؟")) {
        this.$inertia.delete(`/projects/${this.project.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا المشروع ؟")) {
        this.$inertia.put(`/projects/${this.project.id}/restore`);
      }
    },
  },
};
</script>
