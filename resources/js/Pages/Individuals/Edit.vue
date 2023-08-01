<template>
  <div>
    <Head :title="form.name" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-blue-400 hover:text-blue-600" href="/individuals">
        المنتفعين الأفراد
      </Link>
      <span class="text-blue-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="individual.deleted_at" class="mb-6" @restore="restore"
      >تم حذف هذا المنتفع
    </trashed-message>
    <!--  -->
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
        <div ref="part1" v-if="active_step == 1">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.name"
              :error="form.errors.name"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الإسم"
            />
            <file-input
              id="photo"
              v-model="form.photo"
              :error="form.errors.photo"
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="file"
              accept="image/*"
              label="الصورة"
            />
            <text-input
              v-model="form.address"
              :error="form.errors.address"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العنوان"
            />
            <text-input
              v-model="form.cin"
              :error="form.errors.cin"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="بطاقة التعريف الوطنية"
            />
            <text-input
              v-model="form.phone"
              :error="form.errors.phone"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الهاتف"
            />
            <text-input
              class="pb-8 pr-6 w-full lg:w-1/2"
              type="date"
              id="birth_date"
              v-model="form.birth_date"
              :error="form.errors.birth_date"
              label="تاريخ الولادة"
            />
            <select-input
              v-model="form.birth_city"
              :error="form.errors.birth_city"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مدينة الولادة"
            >
              <option value="Medenine">مدنين</option>
              <option value="Beja">باجة</option>
              <option value="Tunis">تونس</option>
              <option value="Sfax">صفاقس</option>
              <option value="Sousse">سوسة</option>
              <option value="Ariana">أريانة</option>
              <option value="Ben Arous">بن عروس</option>
              <option value="Kasserine">القصرين</option>
              <option value="Le Kef">الكاف</option>
              <option value="Mahdia">المهدية</option>
              <option value="Manouba">منوبة</option>
              <option value="Monastir">المنستير</option>
              <option value="Nabeul">نابل</option>
              <option value="Sidi Bouzid">سيدي بوزيد</option>
              <option value="Siliana">سليانة</option>
              <option value="Gabes">قابس</option>
              <option value="Jendouba">جندوبة</option>
              <option value="Tataouine">تطاوين</option>
              <option value="Tozeur">توزر</option>
              <option value="Zaghouan">زغوان</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              v-if="!individual.deleted_at"
              class="bg-red-500 text-white hover:bg-red-600 focus:ring-red-600 focus:ring-opacity-50 ml-3 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded focus:outline-none"
              tabindex="-1"
              type="button"
              @click="destroy"
            >
              حذف
            </button>

            <button
              @click="active_step = 2"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="button"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-4"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M15.75 19.5L8.25 12l7.5-7.5"
                />
              </svg>
            </button>
            <div class="mr-3">
              <loading-button
                :loading="form.processing"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
                type="submit"
                >تسجيل التعديلات</loading-button
              >
            </div>
          </div>
        </div>
        <div ref="part2" v-if="active_step == 2">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
              v-model="form.job_place"
              :error="form.errors.job_place"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="مكان العمل "
            />
            <text-input
              v-model="form.job"
              :error="form.errors.job"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="العمل"
            />
            <select-input
              v-model="form.social_status"
              :error="form.errors.social_status"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الحالة المدنية "
            >
              <option :value="null" />
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>
            <text-input
              v-model="form.monthly_income"
              :error="form.errors.monthly_income"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="الدخل الشهري"
            />
            <text-input
              v-model="form.education_level"
              :error="form.errors.education_level"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label="المستوى الدراسي"
            />
            <select-input
              v-model="form.gender"
              :error="form.errors.gender"
              class="pb-8 pr-6 w-full lg:w-1/2"
              label=" الجنس "
            >
              <option :value="null" />
              <option value="male">ذكر</option>
              <option value="female">أنثى</option>
            </select-input>
          </div>
          <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
          >
            <button
              v-if="!individual.deleted_at"
              class="text-red-600 hover:underline"
              tabindex="-1"
              type="button"
              @click="destroy"
            >
              حذف
            </button>
            <button
              @click="active_step = 1"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="button"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="w-6 h-4"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8.25 4.5l7.5 7.5-7.5 7.5"
                />
              </svg>
            </button>
            <loading-button
              :loading="form.processing"
              class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              type="submit"
              >تسجيل التعديلات</loading-button
            >
          </div>
        </div>
      </form>
    </div>
    <h2 class="mt-12 text-2xl font-bold">الحالة الصحية</h2>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update_health_status">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <div class="w-full flex flex-row flex-nowrap">
            <ToggleCheckbox
              :id="'health_insurance'"
              :class="'lg:w-1/2'"
              :isChecked="health_status_form.health_insurance"
              :label="'التغطية الصحية'"
              :active_value="'نعم'"
              :inactive_value="'لا'"
              @toggle="toggle_health_insurance"
            />
            <ToggleCheckbox
              :id="'good'"
              :class="'lg:w-1/2'"
              :isChecked="health_status_form.good"
              :label="'الحالة الصحية'"
              :active_value="'جيدة'"
              :inactive_value="'عليلة '"
              @toggle="toggle_health_Status"
            />
          </div>
          <div v-if="health_status_form.good == false" class="w-full">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
              <div class="w-full flex flex-row flex-nowrap">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disease"
                  :error="health_status_form.errors.disease"
                  v-model="health_status_form.disease"
                  label=" المرض المزمن"
                />
              </div>
              <div class="w-full flex flex-row flex-nowrap">
                <text-input
                  class="pb-8 pr-6 w-full"
                  id="disability"
                  v-model="health_status_form.disability"
                  :error="health_status_form.errors.disability"
                  label=" الإعاقة"
                />
                <text-input
                  v-model="health_status_form.disability_card_number"
                  :error="health_status_form.errors.disability_card_number"
                  class="pb-8 pr-6 w-full lg:w-1/2"
                  label="رقم بطاقة الإعاقة"
                />
              </div>
            </div>
          </div>
        </div>
        <div
          class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
          <loading-button
            :loading="form.processing"
            class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
            type="submit"
            >تسجيل التعديلات
          </loading-button>
        </div>
      </form>
    </div>
    <!--  -->
    <h2 class="mt-12 text-2xl font-bold">المرافق الأساسية</h2>
    <br />
    <div ref="facilities" class="bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button
          class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          @click="edit_facilities"
        >
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
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
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
                  :label="'التهوئة'"
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
                <button
                  type="submit"
                  class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
                >
                  تسجيل التعديلات
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--  -->
    <h2 class="mt-12 text-2xl font-bold">الملاحظات</h2>
    <br />
    <div ref="members" class="bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button
          class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          @click="openNoteModal"
        >
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;ملاحظة</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">العنوان</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="note in individual.notes.data"
            :key="note.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-blue-500"
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
          <tr v-if="individual.notes.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد ملاحظات</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!--  -->
    <h2 class="mt-12 text-2xl font-bold">المسكن</h2>
    <br />
    <div ref="members" class="bg-white rounded shadow overflow-hidden">
      <br />
      <div class="flex items-center">
        <button
          class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
          @click="openHomeModal"
        >
          <span>إنشاء</span>
          <span class="hidden md:inline">&nbsp;المسكن</span>
        </button>
      </div>
      <br />
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-right font-bold">
            <th class="pb-4 pt-6 px-6">الوضعية القانونية</th>
            <th class="pb-4 pt-6 px-6">سعر الكراء</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="home in individual.home"
            :key="home.id"
            class="hover:bg-gray-100 focus-within:bg-gray-100"
          >
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-blue-500"
                @click="edit_home()"
              >
                <p v-if="home.status == 'Ownership'">ملك</p>
                <p v-if="home.status == 'without compensation'">بدون مقابل</p>
                <p v-if="home.status == 'inherited'">ورثة</p>
                <p v-if="home.status == 'lease'">إيجار</p>
                <icon
                  v-if="home.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="border-t">
              <button
                class="flex items-center px-6 py-4 focus:text-blue-500"
                @click="edit_home()"
              >
                <p v-if="home.status == 'lease'">{{ home.allocation_price }}</p>
                <icon
                  v-if="home.deleted_at"
                  name="trash"
                  class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                />
              </button>
            </td>
            <td class="w-px border-t">
              <button
                @click="edit_home()"
                type="button"
                class="flex items-center px-4"
                tabindex="-1"
              >
                <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
              </button>
            </td>
          </tr>
          <tr v-if="individual.home.length === 0">
            <td class="px-6 py-4 border-t" colspan="2">لا يوجد معطيات المسكن</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!--  HOME ADD-->
    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="home_modal"
      v-if="show_home_modal"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_home">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
                الوضعية القانونية
              </label>
              <select-input v-model="home_form.status" class="form-input">
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
                  placeholder="اكتب سعر الكراء  ..."
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
                placeholder="اكتب وصف المسكن ..."
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
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!--  HOME UPDATE-->
    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="home_modal_update"
      v-if="show_home_modal_update"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="update_home">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold">
                الوضعية القانونية
              </label>

              <select-input v-model="home_form_update.status" class="form-input">
                <option value="Ownership">ملك</option>
                <option value="without compensation">بدون مقابل</option>
                <option value="inherited">ورثة</option>
                <option value="lease">إيجار</option>
              </select-input>
              <div v-if="this.home_form_update.status == 'lease'">
                <label
                  for="allocation_price"
                  class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
                >
                  سعر الكراء
                </label>
                <text-input
                  v-model="home_form_update.allocation_price"
                  id="allocation_price"
                  name="allocation_price"
                  rows="5"
                  placeholder="اكتب سعر الكراء  ..."
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
                v-model="home_form_update.desciption"
                :error="home_form_update.errors.desciption"
                id="homeDescription"
                name="homeDescription"
                rows="5"
                placeholder="اكتب وصف المسكن ..."
                class="w-full"
              ></TextareaInput>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_home_modal_update = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل التعديلات
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- NOTS SHOW-->
    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="note_modal"
      v-if="show_note_modal"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
      <div class="fixed inset-0 flex items-center justify-center">
        <form @submit.prevent="save_note">
          <div class="w-96 h-auto bg-white rounded shadow-xl">
            <div class="p-6">
              <label for="title" class="block mb-2 text-gray-700 text-sm font-bold">
                العنوان
              </label>
              <input
                id="title"
                v-model="notes_form.title"
                :error="notes_form.errors.title"
                placeholder="اكتب عنوان الملاحظة ..."
                class="w-full"
              />
              <label
                for="message"
                class="block mb-2 mt-6 text-gray-700 text-sm font-bold"
              >
                التفاصيل
              </label>
              <textarea
                v-model="notes_form.value"
                id="message"
                name="message"
                rows="5"
                placeholder="اكتب تفاصيل الملاحظة ..."
                class="w-full"
              ></textarea>
            </div>
            <div class="flex justify-end px-4 py-3 bg-gray-50">
              <button
                @click="show_note_modal = false"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none"
              >
                إلغاء
              </button>
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
    <!-- NOTE UPDATE -->
    <div
      class="mt-6 bg-white rounded shadow overflow-x-auto"
      ref="note_modal_update"
      v-if="show_note_modal_update"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
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
                placeholder="اكتب عنوان الملاحظة ..."
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
                placeholder="اكتب تفاصيل الملاحظة ..."
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
              <button
                type="submit"
                class="inline-flex items-center justify-center ml-3 px-4 py-2 text-white text-sm font-medium bg-green-500 hover:bg-green-600 focus:bg-green-600 rounded focus:outline-none"
              >
                تسجيل التعديلات
              </button>
            </div>
          </div>
        </form>
      </div>
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
import SelectInput from "@/Shared/SelectInput";
import FileInput from "@/Shared/FileInput";
import ToggleCheckbox from "@/Shared/ToggleCheckbox.vue";
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
    FileInput,
    ToggleCheckbox,
    TextareaInput,
  },
  layout: Layout,
  props: {
    individual: Object,
  },
  remember: "form",
  created() {
    this.individual.healthStatus.forEach((element) => {
      this.health_status_form.health_insurance = element.health_insurance;
      this.health_status_form.good = element.good;
      this.health_status_form.disease = element.disease;
      this.health_status_form.disability = element.disability;
      this.health_status_form.disability_card_number = element.disability_card_number;
    });
  },

  data() {
    return {
      active_step: 1,

      show_note_modal: false,
      show_home_modal: false,

      show_note_modal_update: false,
      show_facility_modal_update: false,
      show_home_modal_update: false,

      note_id: null,

      healthStatusFields: [],
      health_status_form: this.$inertia.form({
        health_insurance: this.individual.healthStatus.health_insurance,
        good: this.individual.healthStatus.good,
        disease: this.individual.healthStatus.disease,
        disability: this.individual.healthStatus.disability,
        disability_card_number: this.individual.healthStatus.disability_card_number,
        individual_id: this.individual.id,
      }),
      form: this.$inertia.form({
        photo: this.individual.photo,
        phone: this.individual.phone,
        name: this.individual.name,
        address: this.individual.address,
        cin: this.individual.cin,
        gender: this.individual.gender,
        birth_date: this.individual.birth_date,
        birth_city: this.individual.birth_city,
        social_status: this.individual.social_status,
        monthly_income: this.individual.monthly_income,
        education_level: this.individual.education_level,
        job: this.individual.job,
        job_place: this.individual.job_place,
        id: this.individual.id,
      }),
      notes_form: this.$inertia.form({
        title: null,
        value: null,
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
      home_form_update: this.$inertia.form({
        status: null,
        allocation_price: null,
        desciption: null,
      }),
    };
  },
  methods: {
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
    openNoteModal() {
      this.show_note_modal = true;
    },

    toggle_health_Status() {
      this.health_status_form.good = !this.health_status_form.good;
    },

    toggle_health_insurance() {
      this.health_status_form.health_insurance = !this.health_status_form
        .health_insurance;
    },
    update() {
      this.form.put(`/individuals/${this.individual.id}`);
    },
    update_health_status() {
      this.health_status_form.put(`/healthStatus/${this.individual.id}`);
    },
    destroy() {
      if (confirm("هل أنت متأكد أنك تريد حذف هذا الفرد ؟")) {
        this.$inertia.delete(`/individuals/${this.individual.id}`);
      }
    },
    restore() {
      if (confirm("هل أنت متأكد أنك تريد استعادة هذا الفرد ؟")) {
        this.$inertia.put(`/individuals/${this.individual.id}/restore`);
      }
    },

    edit_facilities() {
      this.show_facility_modal_update = true;
      this.individual.facilities.forEach((element) => {
        this.facilities_form_update.Sanitation = element.Sanitation;
        this.facilities_form_update.ventilation = element.ventilation;
        this.facilities_form_update.water = element.water;
        this.facilities_form_update.electricity = element.electricity;
      });
    },
    update_facility() {
      this.show_facility_modal_update = false;

      this.facilities_form_update.put(
        `/facilities/${this.individual.id}/Individualsupdate`
      );
    },

    edit_note(id) {
      const note_title = this.individual.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.title);
      const note_value = this.individual.notes.data
        .filter((note) => note.id === id)
        .map((note) => note.value);

      this.show_note_modal_update = true;
      this.notes_form_update.title = note_title.toString();
      this.notes_form_update.value = note_value.toString();
      this.note_id = id;
    },
    save_note() {
      this.show_note_modal = false;

      this.notes_form.put(`/notes/${this.individual.id}/IndividualStoreNote`);
      this.notes_form.title = null;
      this.notes_form.value = null;
    },
    update_note() {
      this.show_note_modal_update = false;

      this.notes_form_update.put(`/notes/${this.note_id}`);
    },
    destroy_note(id) {
      if (confirm("هل أنت متأكد أنك تريد حذف هذه الملاحظة؟")) {
        this.$inertia.delete(`/notes/${id}`);
      }
    },
    update_home() {
      this.show_home_modal_update = false;

      this.home_form_update.put(`/home/${this.individual.id}`);
    },
    openHomeModal() {
      this.show_home_modal = true;
    },

    edit_home() {
      this.show_home_modal_update = true;

      const home_status = this.individual.home.map((home) => home.status);
      const home_allocation_price = this.individual.home.map(
        (home) => home.allocation_price
      );
      const home_desciption = this.individual.home.map((home) => home.desciption);

      this.home_form_update.status = home_status.toString();
      this.home_form_update.allocation_price = home_allocation_price.toString();
      this.home_form_update.desciption = home_desciption.toString();
    },
    save_home() {
      this.show_home_modal = false;

      this.home_form.put(`/home/${this.individual.id}/IndividualsStore`);
      this.home_form.status = null;
      this.home_form.allocation_price = null;
      this.home_form.desciption = null;
    },
    update_home() {
      this.show_home_modal_update = false;

      this.home_form_update.put(`/home/${this.individual.id}/IndividualsStore`);
    },
    openHomeModal() {
      this.show_home_modal = true;
    },
  },
};
</script>
