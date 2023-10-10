<template>
  <div>
    <Head :title="form.name" />
    <trashed-message v-if="family.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المنتفع .
    </trashed-message>
    <h1 class="mb-8 text-xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/families">المنتفع</Link>
      <span class="text-blue-400 font-medium"> : </span>
      {{ form.name }}
    </h1>

    <!--  -->
    <div class="bg-white rounded-md shadow overflow-hidden md:rounded-lg">
      <form @submit.prevent="update">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الاسم"
            placeholder="الإسم هنا"
          />
          <select-input
            v-model="form.status"
            :error="form.errors.status"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الحالة"
          >
            <option hidden disabled :value="null">إختر الحالة</option>
            <option value="active">نشط</option>
            <option value="disabled">محضور</option>
            <option value="inactive">غير نشط</option>
          </select-input>
          <select-input
            v-model="form.social_status"
            :error="form.errors.social_status"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label=" حالة المنتفع "
          >
            <option value="family">عائلة معوزة</option>
            <option value="elderly">مسن</option>
            <option value="widow">أرملة</option>
            <option value="single_mother">أم عزباء</option>
            <option value="divorced">مطلقة</option>
          </select-input>

          <text-input
            v-model="form.phone"
            :error="form.errors.phone"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="الهاتف"
            placeholder="الهاتف هنا"
          />
          <text-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="العنوان"
            placeholder="العنوان هنا"
          />
          <file-input
            v-model="form.photo"
            :error="form.errors.photo"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="file"
            accept="image/*"
            label="الصورة"
          />
        </div>
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button
            v-if="!family.deleted_at"
            class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-red-500 hover:bg-red-600 rounded focus:outline-none focus:ring-red-600 focus:ring-opacity-50"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            حذف المنتفع
          </button>

          <loading-button
            :loading="form.processing"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
          >
            تعديل المنتفع
          </loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الأفراد</h2>
    <br />
    <div ref="members" class="bg-white rounded-md shadow overflow-hidden">
      <br />
      <div class="flex flex-row items-center justify-around pr-4 w-full">
        <Link :href="`/members/${family.id}/create_new_one`" class="btn-indigo">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;الفرد</span>
        </Link>
        <p v-if="form.name == null" class="px-6 text-red-600">
          يجب تعيين معيل أسرة لهذا المنتفع
        </p>
      </div>
      <div class="mt-6 bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-nowrap">
          <thead>
            <tr class="text-right font-bold">
              <th class="pb-4 pt-6 px-6">الاسم</th>
              <th class="pb-4 pt-6 px-6">القرابة</th>
              <th class="flex flex-row justify-around pb-4 pt-6 px-6 w-full">
                معيل الأسرة
                <icon v-if="form.name == null" name="error" class="mr-2 w-4 h-4" />
              </th>
              <th></th>
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
                  :href="`/members/${member.id}/edit`"
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
                  :href="`/members/${member.id}/edit`"
                  tabindex="-1"
                >
                  <p v-if="member.kinship == 'husband'">زوج</p>
                  <p v-if="member.kinship == 'wife'">زوجة</p>
                  <p v-if="member.kinship == 'child'">إبن</p>
                  <p v-if="member.kinship == 'elderly'">مسن</p>
                  <p v-if="member.kinship == 'other_member'">فرد إضافي</p>
                  <p v-if="member.kinship == 'single_mother'">أم عزباء</p>
                </Link>
              </td>
              <td class="border-t">
                <Link
                  class="flex items-center px-6 py-4"
                  :href="`/members/${member.id}/edit_caregiver`"
                  tabindex="-1"
                >
                  <div v-if="member.caregiver">
                    <ToggleCheckbox_caregiver
                      :id="'sanitaion'"
                      :class="'border-0'"
                      :label="''"
                      :isChecked="true"
                    />
                  </div>
                  <div v-else>
                    <ToggleCheckbox_caregiver
                      :id="'sanitaion'"
                      :class="'border-0'"
                      :label="''"
                      :isChecked="false"
                    />
                  </div>
                </Link>
              </td>
              <td class="w-px border-t">
                <Link
                  class="flex items-center px-4"
                  :href="`/members/${member.id}/edit`"
                  tabindex="-1"
                >
                  <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                </Link>
              </td>
              <td class="w-px border-t">
                <Link
                  class="flex items-center px-4"
                  :href="`/members/${member.id}/show`"
                  tabindex="-1"
                >
                  <icon name="eye" class="block w-6 h-6" />
                </Link>
              </td>
            </tr>
            <tr v-if="family.members.length === 0">
              <td class="px-6 py-4 border-t" colspan="4">لا يوجد على الأفراد</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div
    class="mt-6 rounded shadow overflow-x-auto"
  :class="{ 'bg-white': show_note_modal, 'bg-transparent': !show_note_modal }"
  ref="note_modal"
  v-if="show_note_modal"
    >
    <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
</div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_note">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
                العنوان
              </label>
             
              <text-input
              id="title"
                v-model="notes_form.title"
                :error="notes_form.errors.title"
                placeholder="اكتب عنوان الملاحظة "
                class="w-full"
          />
              <label
                for="message"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                التفاصيل
              </label>
              <TextareaInput
                v-model="notes_form.value"
                :error="notes_form.errors.value"

                id="message"
                name="message"
                rows="5"
                placeholder="اكتب تفاصيل الملاحظة "
                class="w-full"
              ></TextareaInput>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_note_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button type="submit" class="btn-indigo">تسجيل</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="note_modal_update"
      v-if="show_note_modal_update"
    >
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="update_note">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
                العنوان
              </label>
              <input
                id="title"
                v-model="notes_form_update.title"
                :error="notes_form_update.errors.title"
                placeholder="اكتب عنوان الملاحظة "
                class="w-full"
              />
              <label
                for="message"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                التفاصيل
              </label>
              <textarea
                v-model="notes_form_update.value"
                id="message"
                name="message"
                rows="5"
                placeholder="اكتب تفاصيل الملاحظة "
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_note_modal_update = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button type="submit" class="btn-indigo">تسجيل التعديلات</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
<br />
<div ref="home card" class="bg-white rounded shadow overflow-hidden">
  <br />
  <div class="flex flex-col md:flex-row items-center pr-4">
    <button v-if="family.home.length == 0" class="btn-indigo md:mr-2" @click="openHomeModal">
      <span>إنشاء</span>
      <span class="hidden md:inline">&nbsp;المسكن</span>
    </button>
    <button v-else class="btn-indigo md:mr-2" @click="edit_home">
      <span>تعديل</span>
      <span class="hidden md:inline">&nbsp;المسكن</span>
    </button>
  </div>
  <br />
  <div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">
  <div>
    <div v-for="home in family.home" :key="home.id">
      <div class="mb-4">
  <strong>الوضعية القانونية   : </strong>
  <span v-if="home.status == 'Ownership'">ملك</span>
  <span v-if="home.status == 'without compensation'">بدون مقابل</span>
  <span v-if="home.status == 'inherited'">ورثة</span>
  <span v-if="home.status == 'lease'">إيجار</span>
</div>

      <div class="mb-4">
      </div>
      <div  v-if="home.status == 'lease'">
        <strong>سعر الكراء:  </strong>       <span>{{ home.allocation_price }}</span>

      </div>
      <div>
        <p >{{ home.desciption }}</p>

      </div>
    </div>
  </div>

  <div v-if="family.home.length === 0" class="px-6 py-4 border-t">لا يوجد معطيات المسكن</div>
</div>

</div>


    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="home_modal"
      v-if="show_home_modal"
    >    <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
</div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_home">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <select-input v-model="home_form.status" class="form-input">
                <option disabled hidden selected :value="null">الوضعية القانونية</option>
                <option value="Ownership">ملك</option>
                <option value="without compensation">بدون مقابل</option>
                <option value="inherited">ورثة</option>
                <option value="lease">إيجار</option>
              </select-input>
              <div v-if="this.home_form.status == 'lease'">
                <label
                  for="allocation_price"
                  class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
                >
                  سعر الكراء
                </label>
                <text-input
                  v-model="home_form.allocation_price"
                  id="allocation_price"
                  name="allocation_price"
                  rows="5"
                  placeholder="اكتب سعر الكراء  "
                  class="w-full"
                />
              </div>
              <label
                for="homeDescription"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                وصف المسكن
              </label>
              <TextareaInput
                v-model="home_form.desciption"
                :error="home_form.errors.desciption"
                id="homeDescription"
                name="homeDescription"
                rows="5"
                placeholder="اكتب وصف المسكن "
                class="w-full"
              ></TextareaInput>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_home_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button type="submit" class="btn-indigo">تسجيل</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
    <br />
    <div ref="facilities" class="bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center pr-4">
        <button class="btn-indigo" @click="edit_facilities">
          <span>تعديل</span>
          <span class="hidden md:inline">&nbsp;المرافق</span>
        </button>
      </div>
      <br />
      <div
        class="mt-6 bg-white rounded shadow overflow-x-auto"
        ref="facility_modal_update"
        v-if="show_facility_modal_update"
      >
      <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
</div>
        <div class="fixed inset-0 flex items-center justify-center">
          <form @submit.prevent="update_facility">
            <div class="w-96 h-auto bg-white rounded shadow-xl">
              <div class="p-6">
                <ToggleCheckbox
                  :id="'sanitaion'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الصرف الصحي'"
                  :isChecked="facilities_form_update.Sanitation"
                  @toggle="toggle_sanitation"
                />
                <ToggleCheckbox
                  :id="'electricity'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الكهرباء'"
                  :isChecked="facilities_form_update.electricity"
                  @toggle="toggle_electricity"
                />
                <ToggleCheckbox
                  :id="'water'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'الماء'"
                  :isChecked="facilities_form_update.water"
                  @toggle="toggle_water"
                />
                <ToggleCheckbox
                  :id="'ventilation'"
                  :active_value="'نعم'"
                  :inactive_value="'لا'"
                  :label="'التكييف '"
                  :isChecked="facilities_form_update.ventilation"
                  @toggle="toggle_ventilation"
                />
              </div>
              <div class="flex justify-end px-4 py-3 bg-gray-50">
                <button
                  @click="show_facility_modal_update = false"
                  type="button"
                  class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                >
                  إلغاء
                </button>
                <button type="submit" class="btn-indigo">تسجيل التعديلات</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <h2 class="mt-12 text-2xl font-bold">التدخلات</h2>
    <br />
    <div class="bg-white rounded-md shadow overflow-hidden mt-6" v-if="show_intervention_modal">
  <div class="mt-6 bg-white rounded shadow overflow-x-auto">
    <div>
      <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
        <div class="relative bg-white p-6 sm:p-8 rounded-md shadow max-h-full overflow-y-auto w-full sm:w-2/3 md:w-1/2 lg:w-1/3">
          <button class="absolute top-0 right-0 p-4" @click="show_intervention_modal = false">
            &times;
          </button>
          <form @submit.prevent="store_intervention">
            <div>
              <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <select-input
                  v-model="intervention_form.type"
                  class="pb-8 pr-6 lg:w-1/2 w-full md:w-1/2"
                  label="نوع التدخل"
                  :error="intervention_form.errors.type"
                  ref="typeSelect"
                >
                <option :value="null" selected disabled hidden>
                  إختر نوع التدخل
                </option>
                <option value="shipments">عيني</option>
                <option value="cash">نقدي</option>
                <option value="other">آخر</option>                </select-input>

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
                <!-- Other input fields... -->
                <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                type="date"
                id="date"
                v-model="intervention_form.date"
                :error="intervention_form.errors.date"
                label="تاريخ التدخل"
                placeholder=" تاريخ التدخل هنا"
              />
              <select-input
                v-model="intervention_form.project"
                :error="intervention_form.errors.project"
                class="pb-8 pr-6 lg:w-1/2 w-full md:w-1/2"
                ref="projectSelect"
              >
                <option hidden selected disabled :value="null">إختر المشروع</option>

                <option v-for="project in projects" :value="project.id">
                  {{ project.name }}
                </option>
                <option :value="null" disabled v-if="projects.length == 0">
                  قائمة فارغة
                </option>
              </select-input>
              <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="intervenor"
                v-model="intervention_form.intervenor"
                :error="intervention_form.errors.intervenor"
                placeholder="إسم المسؤل هنا"
                label="إسم المسؤل"
              />
              <text-input
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="intervenor"
                v-model="intervention_form.intervenor_phone"
                :error="intervention_form.errors.intervenor_phone"
                placeholder="هاتف المسؤل هنا"
                label="هاتف المسؤل"
              />
              <file-input
                v-model="intervention_form.file"
                :error="intervention_form.errors.file"
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                type="file"
                label="أضف ملف"
              />
              <text-input
                v-model="intervention_form.title"
                :error="intervention_form.errors.title"
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                label="عنوان الملف"
                placeholder="عنوان الملف هنا"
              />
              <TextAreaInput
                 v-bind:class="['pb-8', 'pr-6', 'w-full', 'lg:w-1/2']"
                id="notes"
                v-model="intervention_form.notes"
                :error="intervention_form.errors.notes"
                placeholder="اكتب ملاحظاتك هنا"
                label="ملاحظات"
              />

           
              </div>
            </div>

            <button class="btn-indigo w-full lg:w-auto" type="submit">إضافة</button>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

   
    <div class="mt-8 p-4 bg-white rounded-md shadow overflow-x-auto">
      <div class="flex items-center pr-4 mt-4">
        <button @click="show_intervention_modal = true" class="btn-indigo">
          إنشاء تدخل
        </button>
      </div>
        <table class="w-full">
        <thead class="text-right">
          <tr class="h-12 text-right font-bold">
            <th class="pr-4">النوع</th>
            <th class="pr-4">القيمة</th>
            <th class="pr-4">المتدخل</th>
            <th class="pr-4">المشروع</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="intervention in family.interventions"
            :key="intervention.id"
            class="h-12 hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="px-6 py-4 border-t">
              <Link
                class="flex items-center"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="whitespace-nowrap">
                  {{ intervention.type === "cash" ? "نقدي" : "عيني" }}
                </p>
              </Link>
            </td>
            <td class="px-6 py-4 border-t">
              <Link
                class="flex items-center"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="whitespace-nowrap">{{ intervention.value }}</p>
              </Link>
            </td>
            <td class="px-6 py-4 border-t">
              <Link
                class="flex items-center"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="whitespace-nowrap">{{ intervention.intervenor }}</p>
              </Link>
            </td>
            <td class="px-6 py-4 border-t">
              <Link
                v-if="intervention.project"
                class="flex items-center"
                :href="`/interventions/${intervention.id}/edit`"
                tabindex="-1"
              >
                <p class="whitespace-nowrap">{{ intervention.project.name }}</p>
              </Link>
            </td>
            <td class="w-px border-t">
              <Link
                class="flex items-center px-4"
                :href="`/interventions/${intervention.id}/show`"
                tabindex="-1"
              >
                <icon name="eye" class="block w-6 h-6" />
              </Link>
            </td>
            <td class="w-px border-t">
              <button
                @click="destroy_intervention(intervention.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                  />
                </svg>
              </button>
            </td>
          </tr>
          <tr v-if="family.interventions.length === 0">
            <td class="px-6 py-4 border-t" colspan="3">لا يوجد تدخلات</td>
          </tr>
        </tbody>
      </table>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div ref="members" class="bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center pr-4">
        <button class="btn-indigo" @click="openNoteModal">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;ملاحظة</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <tbody>
          <tr
            v-for="note in family.notes.data"
            :key="note.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                @click="edit_note(note.id)"
              >
                {{ note.title }}
                <icon
                  v-if="note.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="edit_note(note.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="destroy_note(note.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                  />
                </svg>
              </button>
            </td>
          </tr>
          <tr v-if="family.notes.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد ملاحظات</td>
          </tr>
        </tbody>
      </table>
    </div>

    <h2 class="mt-12 text-2xl font-bold">الملفات</h2>

    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="file_modal"
      v-if="show_file_modal"
    >
    <div class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
</div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_file">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <text-input
                id="title"
                v-model="files_form.title"
                :error="files_form.errors.title"
                placeholder="اكتب عنوان الملف "
                class="w-full"
                label="العنوان"
              />

              <file-input
                id="file"
                v-model="files_form.file"
                :error="files_form.errors.file"
                class="w-full"
                type="file"
                label="الملف"
              />
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_file_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button type="submit" class="btn-indigo">إضافة</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <br />
    <div ref="files" class="bg-white rounded shadow overflow-x-auto">
      <br />
      <div class="flex items-center pr-4">
        <button class="btn-indigo" @click="show_file_modal = true">
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;ملف</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <tbody>
          <tr
            v-for="file in family.files"
            :key="file.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-indigo-500"
                @click="edit_file(file.id)"
              >
                <a :href="getFileUrl(file.id)" target="_blank" class="text-blue-600">
                  {{ file.title }}
                </a>
                <icon
                  v-if="file.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>

            <td class="w-px border-t">
              <button
                @click="destroy_file(file.id)"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="w-5 h-5"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                  />
                </svg>
              </button>
            </td>
          </tr>
          <tr v-if="family.files.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد ملفات</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { Head, Link } from "@inertiajs/inertia-vue3";
import Icon from "@/Shared/Icon";
import Layout from "@/Shared/Layout";
import TextInput from "@/Shared/TextInput";
import LoadingButton from "@/Shared/LoadingButton";
import TrashedMessage from "@/Shared/TrashedMessage";
import ToggleCheckbox from "../../Shared/ToggleCheckbox.vue";
import ToggleCheckbox_caregiver from "../../Shared/ToggleCheckbox_caregiver.vue";
import FileInput from "@/Shared/FileInput";
import SelectInput from "@/Shared/SelectInput";
import TextareaInput from "@/Shared/TextareaInput.vue";

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
    ToggleCheckbox,
    ToggleCheckbox_caregiver,
    FileInput,
    TextareaInput,
  },
  layout: Layout,
  props: {
    family: Object,
    projects: Object,
  },
  remember: "form",

  data() {
    return {
      show_file_modal: false,
      show_note_modal: false,
      show_home_modal: false,
      show_intervention_modal: false,

      show_note_modal_update: false,
      show_facility_modal_update: false,
      note_id: null,

      intervention_form: this.$inertia.form({
        type: null,
        value: null,
        date: null,
        project: null,
        intervenor: null,
        intervenor_phone: null,
        file: null,
        title: null,
        notes: null,
        isSolitary: false,
        family: this.family.id,
      }),
      form: this.$inertia.form({
        name: this.family.name,
        is_family: this.family.is_family,
        phone: this.family.phone,
        status: this.family.status,
        social_status: this.family.social_status,
        address: this.family.address,
        photo: null,
        id: this.family.id,
      }),
      notes_form: this.$inertia.form({
        title: null,
        value: null,
      }),
      files_form: this.$inertia.form({
        title: null,
        file: null,
      }),
      notes_form_update: this.$inertia.form({
        title: null,
        value: null,
      }),

      facilities_form_update: this.$inertia.form({
        Sanitation: false,
        electricity: false,
        water: false,
        ventilation: false,
      }),

      home_form: this.$inertia.form({
        status: null,
        allocation_price: null,
        desciption: null,
      }),
    };
  },

  methods: {
    store_intervention() {
      this.intervention_form.post("/interventions", {
        preserveScroll: true,
        onSuccess: () => {
          this.intervention_form.reset();
          this.show_intervention_modal = false;
        },
      });
    },
    destroy_note(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذه الملاحظة؟")) {
        this.$inertia.delete(`/notes/${id}`);
      }
    },
    getFileUrl(id) {
      const fileUrl = `/openFile/${id}`;
      return fileUrl;
    },
    destroy_file(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا الملف ")) {
        this.$inertia.delete(`/files/${id}`);
      }
    },
    destroy_intervention(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا التدخل ")) {
        this.$inertia.delete(`/interventions/${id}`);
      }
    },
    toggle_sanitation() {
      this.facilities_form_update.Sanitation = !this.facilities_form_update.Sanitation;
    },
    toggle_water() {
      this.facilities_form_update.water = !this.facilities_form_update.water;
    },
    toggle_electricity() {
      this.facilities_form_update.electricity = !this.facilities_form_update.electricity;
    },
    toggle_ventilation() {
      this.facilities_form_update.ventilation = !this.facilities_form_update.ventilation;
    },

    edit_facilities() {
      this.show_facility_modal_update = true;
      this.family.facilities.forEach((element) => {
        this.facilities_form_update.Sanitation = element.Sanitation;
        this.facilities_form_update.ventilation = element.ventilation;
        this.facilities_form_update.water = element.water;
        this.facilities_form_update.electricity = element.electricity;
      });
    },

    update_facility() {
      this.show_facility_modal_update = false;

      this.facilities_form_update.put(`/facilities/${this.family.id}`);
    },

    edit_note(id) {
      const note_title = this.family.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.title);
      const note_value = this.family.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.value);

      this.show_note_modal_update = true;
      this.notes_form_update.title = note_title.toString();
      this.notes_form_update.value = note_value.toString();
      this.note_id = id;
    },

    save_file() {
      this.files_form.post(`/files/${this.family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.files_form.title = null;
      this.files_form.file = null;
      this.show_file_modal = false;
        },
      });
      
    },

    save_note() {

      this.notes_form.post(`/notes/${this.family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.show_note_modal = false;
          this.notes_form.title = null;
      this.notes_form.value = null;
        },
      });
     
    },
    update_note() {
      this.show_note_modal_update = false;

      this.notes_form_update.put(`/notes/${this.note_id}`);
    },
    openNoteModal() {
      this.show_note_modal = true;
    },

    edit_home() {
      this.show_home_modal = true;

      const home_status = this.family.home.map((home) => home.status);
      const home_allocation_price = this.family.home.map((home) => home.allocation_price);
      const home_desciption = this.family.home.map((home) => home.desciption);

      this.home_form.status = home_status.toString();
      this.home_form.allocation_price = home_allocation_price.toString();
      this.home_form.desciption = home_desciption.toString();
    },
    save_home() {
      if (this.home_form.status !== "lease") {
        this.home_form.allocation_price = null;
      }
      this.home_form.post(`/home/${this.family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.show_home_modal = false;
          this.home_form.reset();
        },
      });
    },

    openHomeModal() {
      this.show_home_modal = true;
    },

    update() {
      this.form.post(`/families_update/${this.family.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف  هذا المنتفع؟")) {
        this.$inertia.delete(`/families/${this.family.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة  هذا المنتفع؟")) {
        this.$inertia.put(`/families/${this.family.id}/restore`);
      }
    },
  },
};
</script>
