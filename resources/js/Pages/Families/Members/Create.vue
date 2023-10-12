<template>
  <div>
    <Breadcrumb :Family_id="Family.id" :beneficial_type="null" :current_form_title="current_form_title" :active_step="active_step" :HorW="HorW" @update-H-or-W="updateHorW" @update-active-step="updateActiveStep" @update-current-form-title="updateCurrentFormTitle" />
    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'home'">
      <form @submit.prevent="save_home">
        <div class="p-6">
          <label for="homeStatus" class="block mb-2 text-gray-700 text-sm font-bold"> الوضعية القانونية </label>
          <select-input :error="home_form.errors.status" v-model="home_form.status" class="form-input"
            ><option :value="null" disabled hidden>إختر وضعية المسكن</option>
            <option value="Ownership">ملك</option>
            <option value="without compensation">بدون مقابل</option>
            <option value="inherited">ورثة</option>
            <option value="lease">إيجار</option>
          </select-input>
          <div v-if="this.home_form.status == 'lease'">
            <label for="allocation_price" class="block mb-2 mt-6 text-gray-700 text-sm font-bold"> سعر الكراء </label>
            <text-input v-model="home_form.allocation_price" id="allocation_price" name="allocation_price" rows="5" placeholder="اكتب سعر الكراء" class="w-full" />
          </div>
          <label for="homeDescription" class="block mb-2 mt-6 text-gray-700 text-sm font-bold"> وصف المسكن </label>
          <TextareaInput v-model="home_form.desciption" :error="home_form.errors.desciption" id="homeDescription" name="homeDescription" rows="5" placeholder="اكتب وصف المسكن" class="w-full"></TextareaInput>
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button type="submit" class="btn-indigo">تسجيل</button>
        </div>
      </form>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'facilities'">
      <form @submit.prevent="define_facilities">
        <div class="p-6">
          <ToggleCheckbox :id="'sanitaion'" :active_value="'نعم'" :inactive_value="'لا'" :label="'الصرف الصحي'" :isChecked="facilities_form.Sanitation" @toggle="toggle_sanitation" />
          <ToggleCheckbox :id="'electricity'" :active_value="'نعم'" :inactive_value="'لا'" :label="'الكهرباء'" :isChecked="facilities_form.electricity" @toggle="toggle_electricity" />
          <ToggleCheckbox :id="'water'" :active_value="'نعم'" :inactive_value="'لا'" :label="'الماء'" :isChecked="facilities_form.water" @toggle="toggle_water" />
          <ToggleCheckbox :id="'ventilation'" :active_value="'نعم'" :inactive_value="'لا'" :label="'التكييف '" :isChecked="facilities_form.ventilation" @toggle="toggle_ventilation" />
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button type="submit" class="btn-indigo">تسجيل</button>
        </div>
      </form>
    </div>

    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'notes'">
      <form @submit.prevent="save_note">
        <div class="p-6">
          <label for="title" class="block mb-2 text-gray-700 text-sm font-bold"> العنوان </label>
          <text-input id="title" v-model="notes_form.title" :error="notes_form.errors.title" placeholder="اكتب عنوان الملاحظة" class="w-full" />
          <label for="message" class="block mb-2 mt-6 text-gray-700 text-sm font-bold"> التفاصيل </label>
          <TextareaInput v-model="notes_form.value" :error="notes_form.errors.value" id="message" name="message" rows="5" placeholder="اكتب تفاصيل الملاحظة" class="w-full"></TextareaInput>
        </div>
        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button type="submit" class="btn-indigo">تسجيل</button>
        </div>
      </form>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden" v-if="active_step == 'files'">
      <form @submit.prevent="save_files">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="files_form.title" :error="files_form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" id="file_title" name="file_title" placeholder="اكتب عنوان الملف" label="العنوان" />
          <file-input id="file" v-model="files_form.file" :error="files_form.errors.file" class="pb-8 pr-6 w-full lg:w-1/2" type="file" label="الملف" />
        </div>

        <div class="flex justify-end px-4 py-3 bg-gray-50">
          <button type="submit" class="btn-indigo">إضافة</button>
          <button @click="back" type="button" class="inline-flex items-center justify-center px-4 py-2 text-gray-700 text-sm font-medium bg-gray-200 hover:bg-gray-300 focus:bg-gray-300 rounded focus:outline-none">عودة</button>
        </div>
      </form>
    </div>
    <div class="bg-white rounded-md shadow overflow-hidden">
      <!--husband form-->
      <form @submit.prevent="store_husband" v-if="active_step == 'part1' && HorW == 'husband'">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="husband_form.name" :error="husband_form.errors.name" placeholder=" الإسم هنا" class="pb-8 pr-6 w-full lg:w-1/2" label="الإسم" />

            <text-input v-model="husband_form.address" :error="husband_form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="العنوان" placeholder=" العنوان هنا" />
            <text-input v-model="husband_form.cin" :error="husband_form.errors.cin" class="pb-8 pr-6 w-full lg:w-1/2" label="بطاقة التعريف الوطنية" placeholder=" بطاقة التعريف الوطنية هنا" />
            <text-input v-model="husband_form.phone" :error="husband_form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="الهاتف" placeholder="الهاتف هنا" />
            <select-input v-model="husband_form.birth_city" :error="husband_form.errors.birth_city" class="pb-8 pr-6 w-full lg:w-1/2" label="مدينة الولادة">
              <option :value="null" disabled hidden>إختر المدينة</option>
              <option value="مدنين">مدنين</option>
              <option value="باجة">باجة</option>
              <option value="تونس">تونس</option>
              <option value="صفاقس">صفاقس</option>
              <option value="سوسة">سوسة</option>
              <option value="أريانة">أريانة</option>
              <option value="بن عروس">بن عروس</option>
              <option value="القصرين">القصرين</option>
              <option value="الكاف">الكاف</option>
              <option value="المهدية">المهدية</option>
              <option value="منوبة">منوبة</option>
              <option value="المنستير">المنستير</option>
              <option value="نابل">نابل</option>
              <option value="سيدي بوزيد">سيدي بوزيد</option>
              <option value="سليانة">سليانة</option>
              <option value="قابس">قابس</option>
              <option value="جندوبة">جندوبة</option>
              <option value="تطاوين">تطاوين</option>
              <option value="توزر">توزر</option>
              <option value="زغوان">زغوان</option>
              <option value="قبلي">قبلي</option>
              <option value="بنزرت">بنزرت</option>
              <option value="القيروان">القيروان</option>
              <option value="قفصة">قفصة</option>
            </select-input>
            <text-input class="pb-8 pr-6 w-full lg:w-1/2" type="date" id="birth_date" v-model="husband_form.birth_date" :error="husband_form.errors.birth_date" label="تاريخ الولادة" placeholder=" تاريخ الولادة هنا" />

            <text-input v-model="husband_form.job_place" :error="husband_form.errors.job_place" class="pb-8 pr-6 w-full lg:w-1/2" label="مكان العمل " placeholder="  مكان العمل هنا" />
            <text-input v-model="husband_form.job" :error="husband_form.errors.job" class="pb-8 pr-6 w-full lg:w-1/2" label="العمل" placeholder="   العمل هنا" />
          </div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input v-model="husband_form.social_status" :error="husband_form.errors.social_status" class="pb-8 pr-6 w-full lg:w-1/2" label="الحالة المدنية ">
              <option disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <select-input v-model="husband_form.education_level" :error="husband_form.errors.education_level" class="pb-8 pr-6 w-full lg:w-1/2" label=" المستوى الدراسي ">
              <option hidden disabled selected :value="null">إختر المستوى الدراسي</option>
              <option value="illiterate ">لا شيء</option>
              <option value="primary ">إبتدائي</option>
              <option value="preparatory">إعدادي</option>
              <option value="secondary">ثاناوي</option>
              <option value="university ">جامعي</option>
            </select-input>
            <file-input v-model="husband_form.photo" :error="husband_form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="الصورة " />

            <ToggleCheckbox :id="'grant'" :class="'lg:w-1/2'" :isChecked="husband_form.grant" :label="'منحة إجتماعية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_husband_grant" />

            <div v-if="husband_form.grant" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input class="pb-8 pr-6 w-full" id="source" :error="husband_form.errors.grant_source" v-model="husband_form.grant_source" label="المصدر" placeholder="المصدر هنا" />
                <text-input class="pb-8 pr-6 w-full" id="value" v-model="husband_form.grant_value" :error="husband_form.errors.grant_value" label="القيمة" placeholder="القيمة بالدينار هنا" />
              </div>
            </div>

            <p class="text-18 pb-8 pr-6 w-full text-black font-bold">البيانات الصحة</p>
            <div class="flex flex-col flex-wrap w-full lg:flex-row">
              <ToggleCheckbox :id="'health_insurance'" :class="'lg:w-1/2'" :isChecked="husband_form.health_insurance" :label="'التغطية الصحية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_husband_health_insurance" />

              <ToggleCheckbox :id="'good'" :class="'lg:w-1/2'" :isChecked="husband_form.good" :label="'الحالة الصحية'" :active_value="'جيدة'" :inactive_value="'عليلة '" @toggle="toggle_husband_health_Status" />
            </div>

            <div v-if="husband_form.good == false" class="w-full">
              <div class="flex flex-col flex-wrap w-full lg:flex-row">
                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disease" :error="husband_form.errors.disease" v-model="husband_form.disease" label="مرض مزمن" placeholder="  المرض المزمن هنا" />

                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disability" v-model="husband_form.disability" :error="husband_form.errors.disability" label=" إعاقة" placeholder="الإعاقة هنا" />

                <text-input v-model="husband_form.disability_card_number" :error="husband_form.errors.disability_card_number" class="pb-8 pr-6 w-full lg:w-1/3" label="رقم بطاقة الإعاقة" placeholder="الرقم هنا" />
              </div>
            </div>
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <!-- Add a spacer element to create space between the buttons -->
            <div class="w-4"></div>
            <loading-button :loading="husband_form.processing" class="btn-indigo" type="submit"> إضافة </loading-button>
          </div>
        </div>
      </form>
      <!--wife form-->
      <form @submit.prevent="store_wife" v-if="active_step == 'part1' && HorW == 'wife'">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="wife_form.name" :error="wife_form.errors.name" placeholder=" الإسم هنا" class="pb-8 pr-6 w-full lg:w-1/2" label="الإسم" />

            <text-input v-model="wife_form.address" :error="wife_form.errors.addraess" class="pb-8 pr-6 w-full lg:w-1/2" label="العنوان" placeholder=" العنوان هنا" />
            <text-input v-model="wife_form.cin" :error="wife_form.errors.cin" class="pb-8 pr-6 w-full lg:w-1/2" label="بطاقة التعريف الوطنية" placeholder=" بطاقة التعريف الوطنية هنا" />
            <text-input v-model="wife_form.phone" :error="wife_form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="الهاتف" placeholder="الهاتف هنا" />
            <select-input v-model="wife_form.birth_city" :error="wife_form.errors.birth_city" class="pb-8 pr-6 w-full lg:w-1/2" label="مدينة الولادة">
              <option :value="null" disabled hidden>إختر المدينة</option>
              <option value="مدنين">مدنين</option>
              <option value="باجة">باجة</option>
              <option value="تونس">تونس</option>
              <option value="صفاقس">صفاقس</option>
              <option value="سوسة">سوسة</option>
              <option value="أريانة">أريانة</option>
              <option value="بن عروس">بن عروس</option>
              <option value="القصرين">القصرين</option>
              <option value="الكاف">الكاف</option>
              <option value="المهدية">المهدية</option>
              <option value="منوبة">منوبة</option>
              <option value="المنستير">المنستير</option>
              <option value="نابل">نابل</option>
              <option value="سيدي بوزيد">سيدي بوزيد</option>
              <option value="سليانة">سليانة</option>
              <option value="قابس">قابس</option>
              <option value="جندوبة">جندوبة</option>
              <option value="تطاوين">تطاوين</option>
              <option value="توزر">توزر</option>
              <option value="زغوان">زغوان</option>
              <option value="قبلي">قبلي</option>
              <option value="بنزرت">بنزرت</option>
              <option value="القيروان">القيروان</option>
              <option value="قفصة">قفصة</option>
            </select-input>
            <text-input class="pb-8 pr-6 w-full lg:w-1/2" type="date" id="birth_date" v-model="wife_form.birth_date" :error="wife_form.errors.birth_date" label="تاريخ الولادة" placeholder=" تاريخ الولادة هنا" />

            <text-input v-model="wife_form.job_place" :error="wife_form.errors.job_place" class="pb-8 pr-6 w-full lg:w-1/2" label="مكان العمل " placeholder="  مكان العمل هنا" />
            <text-input v-model="wife_form.job" :error="wife_form.errors.job" class="pb-8 pr-6 w-full lg:w-1/2" label="العمل" placeholder="   العمل هنا" />
          </div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input v-model="wife_form.social_status" :error="wife_form.errors.social_status" class="pb-8 pr-6 w-full lg:w-1/2" label="الحالة المدنية ">
              <option disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <select-input v-model="wife_form.education_level" :error="wife_form.errors.education_level" class="pb-8 pr-6 w-full lg:w-1/2" label=" المستوى الدراسي ">
              <option hidden disabled selected :value="null">إختر المستوى الدراسي</option>
              <option value="illiterate ">لا شيء</option>
              <option value="primary ">إبتدائي</option>
              <option value="preparatory">إعدادي</option>
              <option value="secondary">ثاناوي</option>
              <option value="university ">جامعي</option>
            </select-input>
            <file-input v-model="wife_form.photo" :error="wife_form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="الصورة " />

            <ToggleCheckbox :id="'grant'" :class="'lg:w-1/2'" :isChecked="wife_form.grant" :label="'منحة إجتماعية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_wife_grant" />

            <div v-if="wife_form.grant" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input class="pb-8 pr-6 w-full" id="source" :error="wife_form.errors.grant_source" v-model="wife_form.grant_source" label="المصدر" placeholder="المصدر هنا" />
                <text-input class="pb-8 pr-6 w-full" id="value" v-model="wife_form.grant_value" :error="wife_form.errors.grant_value" label="القيمة" placeholder="القيمة بالدينار هنا" />
              </div>
            </div>

            <p class="text-18 pb-8 pr-6 w-full text-black font-bold">البيانات الصحة</p>
            <div class="flex flex-col flex-wrap w-full lg:flex-row">
              <ToggleCheckbox :id="'health_insurance'" :class="'lg:w-1/2'" :isChecked="wife_form.health_insurance" :label="'التغطية الصحية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_wife_health_insurance" />

              <ToggleCheckbox :id="'good'" :class="'lg:w-1/2'" :isChecked="wife_form.good" :label="'الحالة الصحية'" :active_value="'جيدة'" :inactive_value="'عليلة '" @toggle="toggle_wife_health_Status" />
            </div>

            <div v-if="wife_form.good == false" class="w-full">
              <div class="flex flex-col flex-wrap w-full lg:flex-row">
                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disease" :error="wife_form.errors.disease" v-model="wife_form.disease" label="مرض مزمن" placeholder="  المرض المزمن هنا" />

                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disability" v-model="wife_form.disability" :error="wife_form.errors.disability" label=" إعاقة" placeholder="الإعاقة هنا" />

                <text-input v-model="wife_form.disability_card_number" :error="wife_form.errors.disability_card_number" class="pb-8 pr-6 w-full lg:w-1/3" label="رقم بطاقة الإعاقة" placeholder="الرقم هنا" />
              </div>
            </div>
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <!-- Add a spacer element to create space between the buttons -->
            <div class="w-4"></div>
            <loading-button :loading="wife_form.processing" class="btn-indigo" type="submit"> التالي </loading-button>
          </div>
        </div>
      </form>

      <!--member form-->
      <form @submit.prevent="store_member" v-if="active_step == 'part1' && HorW == 'none'">
        <div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="member_form.name" :error="member_form.errors.name" placeholder=" الإسم هنا" class="pb-8 pr-6 w-full lg:w-1/2" label="الإسم" />

            <text-input v-model="member_form.address" :error="member_form.errors.address" class="pb-8 pr-6 w-full lg:w-1/2" label="العنوان" placeholder=" العنوان هنا" />
            <text-input v-model="member_form.cin" :error="member_form.errors.cin" class="pb-8 pr-6 w-full lg:w-1/2" label="بطاقة التعريف الوطنية" placeholder=" بطاقة التعريف الوطنية هنا" />
            <text-input v-model="member_form.phone" :error="member_form.errors.phone" class="pb-8 pr-6 w-full lg:w-1/2" label="الهاتف" placeholder="الهاتف هنا" />
            <select-input v-model="member_form.kinship" :error="member_form.errors.kinship" class="pb-8 pr-6 w-full lg:w-1/2" label="الفرد" ref="memberKinship">
              <option :value="null" disabled hidden>إختر نوع الفرد</option>

              <option value="child">إبن</option>
              <option value="elderly">مسن</option>
              <option value="other_member">فرد آخر</option>
              <option value="single_mother">أم عزباء</option>
            </select-input>
            <select-input v-model="member_form.birth_city" :error="member_form.errors.birth_city" class="pb-8 pr-6 w-full lg:w-1/2" label="مدينة الولادة" ref="memberBirthCity">
              <option :value="null" disabled hidden>إختر المدينة</option>
              <option value="مدنين">مدنين</option>
              <option value="باجة">باجة</option>
              <option value="تونس">تونس</option>
              <option value="صفاقس">صفاقس</option>
              <option value="سوسة">سوسة</option>
              <option value="أريانة">أريانة</option>
              <option value="بن عروس">بن عروس</option>
              <option value="القصرين">القصرين</option>
              <option value="الكاف">الكاف</option>
              <option value="المهدية">المهدية</option>
              <option value="منوبة">منوبة</option>
              <option value="المنستير">المنستير</option>
              <option value="نابل">نابل</option>
              <option value="سيدي بوزيد">سيدي بوزيد</option>
              <option value="سليانة">سليانة</option>
              <option value="قابس">قابس</option>
              <option value="جندوبة">جندوبة</option>
              <option value="تطاوين">تطاوين</option>
              <option value="توزر">توزر</option>
              <option value="زغوان">زغوان</option>
              <option value="قبلي">قبلي</option>
              <option value="بنزرت">بنزرت</option>
              <option value="القيروان">القيروان</option>
              <option value="قفصة">قفصة</option>
            </select-input>
            <text-input class="pb-8 pr-6 w-full lg:w-1/2" type="date" id="birth_date" v-model="member_form.birth_date" :error="member_form.errors.birth_date" label="تاريخ الولادة" placeholder=" تاريخ الولادة هنا" />

            <text-input v-model="member_form.job_place" :error="member_form.errors.job_place" class="pb-8 pr-6 w-full lg:w-1/2" label="مكان العمل " placeholder="  مكان العمل هنا" />
            <text-input v-model="member_form.job" :error="member_form.errors.job" class="pb-8 pr-6 w-full lg:w-1/2" label="العمل" placeholder="   العمل هنا" />
          </div>
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <select-input v-model="member_form.social_status" :error="member_form.errors.social_status" class="pb-8 pr-6 w-full lg:w-1/2" label="الحالة المدنية " ref="memberSocialStatus">
              <option disabled hidden :value="null">إختر الحالة</option>
              <option value="single">أعزب/عزباء</option>
              <option value="married">متزوج/متزوجة</option>
              <option value="divorced">مطلق/مطلقة</option>
              <option value="widower">أرمل/أرملة</option>
            </select-input>

            <select-input v-model="member_form.education_level" :error="member_form.errors.education_level" class="pb-8 pr-6 w-full lg:w-1/2" label=" المستوى الدراسي " ref="memberEducation">
              <option hidden disabled selected :value="null">إختر المستوى الدراسي</option>
              <option value="illiterate ">لا شيء</option>
              <option value="primary ">إبتدائي</option>
              <option value="preparatory">إعدادي</option>
              <option value="secondary">ثاناوي</option>
              <option value="university ">جامعي</option>
            </select-input>
            <file-input v-model="member_form.photo" :error="member_form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="الصورة " />

            <ToggleCheckbox :id="'grant'" :class="'lg:w-1/2'" :isChecked="member_form.grant" :label="'منحة إجتماعية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_member_grant" />

            <div v-if="member_form.grant" class="w-full">
              <div class="flex flex-row flex-nowrap w-full">
                <text-input class="pb-8 pr-6 w-full" id="source" :error="member_form.errors.grant_source" v-model="member_form.grant_source" label="المصدر" placeholder="المصدر هنا" />
                <text-input class="pb-8 pr-6 w-full" id="value" v-model="member_form.grant_value" :error="member_form.errors.grant_value" label="القيمة" placeholder="القيمة بالدينار هنا" />
              </div>
            </div>

            <p class="text-18 pb-8 pr-6 w-full text-black font-bold">البيانات الصحة</p>
            <div class="flex flex-col flex-wrap w-full lg:flex-row">
              <ToggleCheckbox :id="'health_insurance'" :class="'lg:w-1/2'" :isChecked="member_form.health_insurance" :label="'التغطية الصحية'" :active_value="'نعم'" :inactive_value="'لا'" @toggle="toggle_member_health_insurance" />

              <ToggleCheckbox :id="'good'" :class="'lg:w-1/2'" :isChecked="member_form.good" :label="'الحالة الصحية'" :active_value="'جيدة'" :inactive_value="'عليلة '" @toggle="toggle_member_health_Status" />
            </div>

            <div v-if="member_form.good == false" class="w-full">
              <div class="flex flex-col flex-wrap w-full lg:flex-row">
                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disease" :error="member_form.errors.disease" v-model="member_form.disease" label="مرض مزمن" placeholder="  المرض المزمن هنا" />

                <text-input class="pb-8 pr-6 w-full lg:w-1/3" id="disability" v-model="member_form.disability" :error="member_form.errors.disability" label=" إعاقة" placeholder="الإعاقة هنا" />

                <text-input v-model="member_form.disability_card_number" :error="member_form.errors.disability_card_number" class="pb-8 pr-6 w-full lg:w-1/3" label="رقم بطاقة الإعاقة" placeholder="الرقم هنا" />
              </div>
            </div>
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <!-- Add a spacer element to create space between the buttons -->
            <div class="w-4"></div>
            <loading-button :loading="member_form.processing" class="btn-indigo" type="submit"> التالي </loading-button>
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
import ToggleCheckbox from '@/Shared/ToggleCheckbox.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import Breadcrumb from '@/Shared/Breadcrumb'
import FileInput from '@/Shared/FileInput'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    ToggleCheckbox,
    TextareaInput,
    Breadcrumb,
    FileInput,
  },
  layout: Layout,
  remember: 'form',
  props: {
    Family: Object,
  },
  data() {
    return {
      isFormDisabled: false,
      HorW: 'husband',
      active_step: 'part1',
      current_form_title: 'أضف بيانات الزوج',
      current_form: 'childrens',
      notes_form: this.$inertia.form({
        title: null,
        value: null,
      }),

      files_form: this.$inertia.form({
        title: null,
        file: null,
      }),
      facilities_form: this.$inertia.form({
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
      member_form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
        health_insurance: false,
        photo: null,
        kinship: null,
        caregiver: false,
        education_level: null,
        job: null,
        job_place: null,
        good: true,
        disease: null,
        disability: null,
        disability_card_number: null,
        grant: false,
        grant_source: null,
        grant_value: null,
      }),
      husband_form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
        health_insurance: false,
        photo: null,
        kinship: null,
        caregiver: true,
        education_level: null,
        job: null,
        job_place: null,
        good: true,
        disease: null,
        disability: null,
        disability_card_number: null,
        grant: false,
        grant_source: null,
        grant_value: null,
      }),
      wife_form: this.$inertia.form({
        name: null,
        family_id: this.Family.id,
        address: null,
        cin: null,
        phone: null,
        birth_date: null,
        birth_city: null,
        social_status: null,
        health_insurance: false,
        photo: null,
        kinship: null,
        caregiver: false,
        education_level: null,
        job: null,
        job_place: null,
        good: true,
        disease: null,
        disability: null,
        disability_card_number: null,
        grant: false,
        grant_source: null,
        grant_value: null,
      }),
    }
  },

  methods: {
    back() {
      this.$inertia.get('/beneficials')
    },
    updateActiveStep(step) {
      this.active_step = step // Update the active_step prop in the parent component
    },
    updateHorW(HorW) {
      this.HorW = HorW
    },
    updateCurrentFormTitle(title) {
      this.current_form_title = title // Update the current_form_title prop in the parent component
    },

    toggle_sanitation() {
      this.facilities_form.Sanitation = !this.facilities_form.Sanitation
    },
    toggle_water() {
      this.facilities_form.water = !this.facilities_form.water
    },
    toggle_electricity() {
      this.facilities_form.electricity = !this.facilities_form.electricity
    },
    toggle_ventilation() {
      this.facilities_form.ventilation = !this.facilities_form.ventilation
    },

    edit_facilities() {
      this.Family.facilities.forEach((element) => {
        this.facilities_form.Sanitation = element.Sanitation
        this.facilities_form.ventilation = element.ventilation
        this.facilities_form.water = element.water
        this.facilities_form.electricity = element.electricity
      })
    },
    save_note() {
      this.notes_form.post(`/notes/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = 'files'

          this.notes_form.reset()
        },
      })
    },
    save_files() {
      this.files_form.post(`/files/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = 'files'
          this.files_form.reset()
        },
      })
    },

    define_facilities() {
      this.facilities_form.put(`/facilities/${this.Family.id}`)
      this.active_step = 'home'
      this.current_form_title = 'بيانات المسكن'
    },

    save_home() {
      this.home_form.post(`/home/${this.Family.id}`, {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = 'notes'
          this.current_form_title = ' أضف ملاحظة'
        },
      })
    },
    //toggle husband checkboxes
    toggle_husband_grant() {
      this.husband_form.grant = !this.husband_form.grant
    },
    toggle_husband_health_insurance() {
      this.husband_form.health_insurance = !this.husband_form.health_insurance
    },

    toggle_husband_health_Status() {
      this.husband_form.good = !this.husband_form.good
    },
    //toggle wife checkboxes

    toggle_wife_grant() {
      this.wife_form.grant = !this.wife_form.grant
    },
    toggle_wife_health_insurance() {
      this.wife_form.health_insurance = !this.wife_form.health_insurance
    },

    toggle_wife_health_Status() {
      this.wife_form.good = !this.wife_form.good
    },

    //toggle member checkboxes
    toggle_member_grant() {
      this.member_form.grant = !this.member_form.grant
    },
    toggle_member_health_insurance() {
      this.member_form.health_insurance = !this.member_form.health_insurance
    },

    toggle_member_health_Status() {
      this.member_form.good = !this.member_form.good
    },

    resetMembersForm() {
      this.member_form.reset()
      this.$refs.memberEducation.clearSelection()
      this.$refs.memberBirthCity.clearSelection()
      this.$refs.memberSocialStatus.clearSelection()
      this.$refs.memberKinship.clearSelection()
    },

    store_wife() {
      this.wife_form.kinship = 'wife'
      this.wife_form.post('/members', {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = 'part1'
          this.current_form_title = 'أضف بيانات الأفراد'
          this.HorW = 'none'
        },
      })
    },
    store_member() {
      this.member_form.post('/members', {
        preserveScroll: true,
        onSuccess: () => {
          this.resetMembersForm()
          this.active_step = 'part1'
          this.current_form_title = 'إضافة فرد آخر'
        },
      })
    },
    store_husband() {
      this.husband_form.kinship = 'husband'
      this.husband_form.post('/members', {
        preserveScroll: true,
        onSuccess: () => {
          this.active_step = 'part1'
          this.HorW = 'wife'
          this.current_form_title = 'أضف بيانات الزوجة'
        },
      })
    },
  },
}
</script>
