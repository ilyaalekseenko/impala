<template>
  <div class="impala-input impala-input-phone" :class="{ 'impala-input-focused': isFocus }" @focusout="handleFocus(false)">
    <select class="selPhone" v-model="currentCountry" @change="handleCountryChange" @focus="handleFocus(true)">
      <option v-for="country in maskArr" :key="country.value" :value="country.value">{{ country.label }}</option>
    </select>
    <input class="phoneInputV" type="text" v-maska="currentMask" v-model="phoneNumber" :placeholder="currentPlaceholder"
           ref="phoneInput" @input="handleInput" @focus="handleFocus(true)">
  </div>
</template>

<script>
import {vMaska} from "maska/vue"

export default {
  props: ['initialPhoneNumber', 'setPhoneNumber', 'typeNumber', 'rowKey'],
  mounted() {
    // console.log('initialPhoneNumber')
    // console.log(this.initialPhoneNumber)
    //тут приходит адреса и контакты
    //  console.log('адреса и контакты')

    this.setMaskMount(this.initialPhoneNumber)
  },
  directives: {
    'maska': vMaska
  },
  data() {
    return {
      currentCountry: 'ru',
      currentMask: '+7 (###) ###-##-##',
      currentPlaceholder: '+7 (###) ###-##-##',
      maskArr: [
        {value: 'ru', label: 'RU', mask: '+7 (###) ###-##-##', placeholder: '+7 (###) ###-##-##', pref: '7'},
        {value: 'by', label: 'BY', mask: '+375 (##) ###-##-##', placeholder: '+375 (##) ###-##-##', pref: '375'},
        {value: 'oth', label: 'др', mask: '+#################', placeholder: '', pref: ''},
        // Добавьте другие страны в массив по желанию
      ],
      phoneNumber: this.initialPhoneNumber,
      startFlag: true,
      isFocus: false,
    }
  },
  watch: {
    initialPhoneNumber(newPhoneNumber) {
      if (newPhoneNumber) {
        //тут приходит основной и генеральный
        //   console.log('основной и генеральный')
        this.setMask(newPhoneNumber)
        this.phoneNumber = newPhoneNumber
      }
    }
  },
  methods: {
    setMask(newPhoneNumber) {
      // if(this.startFlag)
      // {
      this.startFlag = false
      if ((newPhoneNumber == null) || (newPhoneNumber === '')) {
        this.currentMask = this.maskArr[0]['mask']
        this.currentPlaceholder = this.maskArr[0]['placeholder']
        this.currentCountry = this.maskArr[0]['value']
      } else {
        const countryCodeRegex = /^(\+7|\+375|\+\d{1,3})/;
        const match = newPhoneNumber.match(countryCodeRegex);

        if (match) {
          let countryCode = match[1];
          if (countryCode === '+7') {
            this.currentMask = this.maskArr[0]['mask']
            this.currentPlaceholder = this.maskArr[0]['placeholder']
            this.currentCountry = this.maskArr[0]['value']
          }
          if (countryCode === '+375') {
            this.currentMask = this.maskArr[1]['mask']
            this.currentPlaceholder = this.maskArr[1]['placeholder']
            this.currentCountry = this.maskArr[1]['value']
          }
          if ((countryCode !== '+7') && (countryCode !== '+375')) {
            this.currentMask = this.maskArr[2]['mask']
            this.currentPlaceholder = this.maskArr[2]['placeholder']
            this.currentCountry = this.maskArr[2]['value']
          }
        }
      }
      //}
    },
    setMaskMount(newPhoneNumber) {
      this.startFlag = false
      if ((newPhoneNumber == null) || (newPhoneNumber === '')) {
        this.currentMask = this.maskArr[0]['mask']
        this.currentPlaceholder = this.maskArr[0]['placeholder']
        this.currentCountry = this.maskArr[0]['value']
      } else {
        const countryCodeRegex = /^(\+7|\+375|\+\d{1,3})/;
        const match = newPhoneNumber.match(countryCodeRegex);

        if (match) {
          let countryCode = match[1];
          if (countryCode === '+7') {
            this.currentMask = this.maskArr[0]['mask']
            this.currentPlaceholder = this.maskArr[0]['placeholder']
            this.currentCountry = this.maskArr[0]['value']
          }
          if (countryCode === '+375') {
            this.currentMask = this.maskArr[1]['mask']
            this.currentPlaceholder = this.maskArr[1]['placeholder']
            this.currentCountry = this.maskArr[1]['value']
          }
          if ((countryCode !== '+7') && (countryCode !== '+375')) {
            this.currentMask = this.maskArr[2]['mask']
            this.currentPlaceholder = this.maskArr[2]['placeholder']
            this.currentCountry = this.maskArr[2]['value']
          }
          this.phoneNumber = newPhoneNumber
        }
      }
    },
    handleInput() {
      this.maskArr.forEach(country => {
        if (country.value === this.currentCountry) {
          this.setPhoneNumber(this.phoneNumber, this.typeNumber, this.rowKey)
        }
      });
    },
    handleCountryChange() {
      this.maskArr.forEach(country => {
        if (country.value === this.currentCountry) {
          this.phoneNumber = '';
          this.currentMask = country.mask
          this.currentPlaceholder = country.placeholder
          //this.$refs.phoneInput.focus();
        }
      });
    },
    handleFocus(focus = false) {
      this.isFocus = focus;
    }
  },
}
</script>
