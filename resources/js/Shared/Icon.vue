<template>
  <svg v-if="name === 'cheveron-down'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
  </svg>
  <svg v-else-if="name === 'cheveron-up'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
    <path d="M24,21a1,1,0,0,1-.71-.29L16,13.41l-7.29,7.3a1,1,0,1,1-1.42-1.42l8-8a1,1,0,0,1,1.42,0l8,8a1,1,0,0,1,0,1.42A1,1,0,0,1,24,21Z" />
  </svg>

  <svg v-else-if="name === 'restore'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
  </svg>

  <svg v-else-if="name === 'delete'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-4">
    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
  </svg>
  <svg v-else-if="name === 'close'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <svg v-else-if="name === 'add'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <svg v-else-if="name === 'cheveron-right'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120">
    <path d="M39.3756,48.0022l30.47-25.39a6.0035,6.0035,0,0,0-7.6878-9.223L26.1563,43.3906a6.0092,6.0092,0,0,0,0,9.2231L62.1578,82.615a6.0035,6.0035,0,0,0,7.6878-9.2231Z" />
  </svg>
  <svg v-else-if="name === 'cheveron-left'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <polygon points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707" />
  </svg>
  <svg v-else-if="name === 'dashboard'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm-5.6-4.29a9.95 9.95 0 0 1 11.2 0 8 8 0 1 0-11.2 0zm6.12-7.64l3.02-3.02 1.41 1.41-3.02 3.02a2 2 0 1 1-1.41-1.41z" />
  </svg>
  <svg v-else-if="name === 'office'" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
    <path fill-rule="evenodd" d="M7 0h86v100H57.108V88.418H42.892V100H7V0zm9 64h11v15H16V64zm57 0h11v15H73V64zm-19 0h11v15H54V64zm-19 0h11v15H35V64zM16 37h11v15H16V37zm57 0h11v15H73V37zm-19 0h11v15H54V37zm-19 0h11v15H35V37zM16 11h11v15H16V11zm57 0h11v15H73V11zm-19 0h11v15H54V11zm-19 0h11v15H35V11z" />
  </svg>
  <svg v-else-if="name === 'printer'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <path d="M4 16H0V6h20v10h-4v4H4v-4zm2-4v6h8v-6H6zM4 0h12v5H4V0zM2 8v2h2V8H2zm4 0v2h2V8H6z" />
  </svg>

  <svg v-else-if="name === 'contact'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <path d="M7 8a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0 1c2.15 0 4.2.4 6.1 1.09L12 16h-1.25L10 20H4l-.75-4H2L.9 10.09A17.93 17.93 0 0 1 7 9zm8.31.17c1.32.18 2.59.48 3.8.92L18 16h-1.25L16 20h-3.96l.37-2h1.25l1.65-8.83zM13 0a4 4 0 1 1-1.33 7.76 5.96 5.96 0 0 0 0-7.52C12.1.1 12.53 0 13 0z" />
  </svg>
  <svg v-else-if="name === 'edit'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
  </svg>
  <svg v-else-if="name === 'group'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
  </svg>
  <svg v-else-if="name === 'indiv'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
  </svg>

  <svg v-else-if="name === 'users'" style="enable-background: new 0 0 24 24" version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g id="info" />
    <g id="icons">
      <g id="user">
        <ellipse cx="12" cy="8" rx="5" ry="6" />
        <path d="M21.8,19.1c-0.9-1.8-2.6-3.3-4.8-4.2c-0.6-0.2-1.3-0.2-1.8,0.1c-1,0.6-2,0.9-3.2,0.9s-2.2-0.3-3.2-0.9    C8.3,14.8,7.6,14.7,7,15c-2.2,0.9-3.9,2.4-4.8,4.2C1.5,20.5,2.6,22,4.1,22h15.8C21.4,22,22.5,20.5,21.8,19.1z" />
      </g>
    </g>
  </svg>
  <svg v-else-if="name === 'waiting_user'" style="enable-background: new 0 0 32 32" version="1.1" viewBox="0 0 32 32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g>
      <g>
        <path d="M27,3H5C4.4477539,3,4,2.5527344,4,2s0.4477539-1,1-1h22c0.5522461,0,1,0.4472656,1,1S27.5522461,3,27,3z" />
      </g>
      <g>
        <path d="M27,31H5c-0.5522461,0-1-0.4472656-1-1s0.4477539-1,1-1h22c0.5522461,0,1,0.4472656,1,1S27.5522461,31,27,31z" />
      </g>
      <g>
        <path d="M24.3999023,31H7.6000977c-0.5507813,0-0.9980469-0.4453125-1-0.9970703    C6.5996094,29.8125,6.6049805,25.2753906,8.3828125,21.4013672C9.5849609,18.78125,10.9506836,16.9355469,11.7231445,16    c-0.7724609-0.9355469-2.1381836-2.78125-3.340332-5.4013672C6.6049805,6.7246094,6.5996094,2.1875,6.6000977,1.9970703    C6.6020508,1.4453125,7.0493164,1,7.6000977,1h16.7998047c0.5507813,0,0.9980469,0.4453125,1,0.9970703    c0.0004883,0.1904297-0.0048828,4.7275391-1.7827148,8.6015625C22.4150391,13.21875,21.0493164,15.0644531,20.2768555,16    c0.7724609,0.9355469,2.1381836,2.78125,3.340332,5.4013672c1.777832,3.8740234,1.7832031,8.4111328,1.7827148,8.6015625    C25.3979492,30.5546875,24.9506836,31,24.3999023,31z M8.6420898,29h14.7158203    c-0.0996094-1.4716797-0.4345703-4.3154297-1.5581055-6.7646484c-1.6044922-3.4960938-3.550293-5.5166016-3.5693359-5.5371094    c-0.3779297-0.3876953-0.3779297-1.0087891,0-1.3964844c0.019043-0.0205078,1.9648438-2.0410156,3.5693359-5.5371094    C22.9233398,7.3154297,23.2583008,4.4716797,23.3579102,3H8.6420898c0.0996094,1.4716797,0.4345703,4.3154297,1.5581055,6.7646484    c1.6044922,3.4960938,3.550293,5.5166016,3.5693359,5.5371094c0.3779297,0.3876953,0.3779297,1.0087891,0,1.3964844    c-0.019043,0.0205078-1.9648438,2.0410156-3.5693359,5.5371094C9.0766602,24.6845703,8.7416992,27.5283203,8.6420898,29z" />
      </g>
      <g>
        <path d="M11.2239609,26.980032c0,0-0.0082922-2.5372696,0.9618406-4.6516628S14.7377195,19.019968,16,19.019968    s2.8440647,1.1940098,3.8141975,3.3084011s0.9618416,4.6516628,0.9618416,4.6516628H11.2239609z" />
      </g>
    </g>
  </svg>

  <svg v-else-if="name === 'accepet'" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
    <title />
    <polygon points="106 304 106 250 160 250 160 214 106 214 106 160 70 160 70 214 16 214 16 250 70 250 70 304 106 304" />
    <circle cx="288" cy="144" r="112" />
    <path d="M288,288c-69.42,0-208,42.88-208,128v64H496V416C496,330.88,357.42,288,288,288Z" />
  </svg>
  <svg v-else-if="name === 'block'" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
    <title />
    <rect height="36" width="144" x="16" y="214" />
    <circle cx="288" cy="144" r="112" />
    <path d="M288,288c-69.42,0-208,42.88-208,128v64H496V416C496,330.88,357.42,288,288,288Z" />
  </svg>
  <svg v-else-if="name === 'error'" height="32" style="overflow: visible; enable-background: new 0 0 32 32" viewBox="0 0 32 32" width="32" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g>
      <g id="Error_1_">
        <g id="Error">
          <circle cx="16" cy="16" id="BG" r="16" style="fill: #d72828" />
          <path d="M14.5,25h3v-3h-3V25z M14.5,6v13h3V6H14.5z" id="Exclamatory_x5F_Sign" style="fill: #e6e6e6" />
        </g>
      </g>
    </g>
  </svg>
  <svg v-else-if="name === 'home'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
  </svg>

  <svg v-else-if="name === 'beneficials'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
  </svg>
  <svg v-else-if="name === 'li'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
  </svg>

  <svg v-else-if="name === 'check'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="green" class="p-1 w-10 h-10 bg-white rounded-full shadow-md">
    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>
  <svg v-else-if="name === 'minus'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="p-1 w-10 h-10 bg-white rounded-full shadow-md">
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
  </svg>

  <svg v-else-if="name === 'interventions'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
  </svg>
  <svg v-else-if="name === 'eye'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-4">
    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
  </svg>
</template>

<script>
export default {
  props: {
    name: String,
  },
}
</script>
