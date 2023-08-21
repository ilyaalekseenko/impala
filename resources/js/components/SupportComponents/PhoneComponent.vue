<template>
    <div class="container">
        <select class="selPhone" v-model="currentCountry" @change="handleCountryChange">
            <option v-for="country in maskArr" :key="country.value" :value="country.value">{{ country.label }}</option>
        </select>
        <input class="phoneInputV" type="text" v-mask="currentMask" v-model="phoneNumber" :placeholder="currentPlaceholder" ref="phoneInput" @blur="handleBlur">
    </div>
</template>

<script>
//
import MaskedInput from 'vue-masked-input'
import { VueMaskDirective } from 'v-mask'
Vue.directive('mask', VueMaskDirective);

    export default {
        props: ['initialPhoneNumber','setPhoneNumber'],
        mounted() {
        },
        components: {
            MaskedInput

        },
        data() {
            return {

                currentCountry:'ru',
                currentMask:'+7 (###) ###-##-##',
                currentPlaceholder:'+7 (###) ###-##-##',
                maskArr: [
                    { value: 'ru', label: 'RU', mask:'+7 (###) ###-##-##',placeholder:'+7 (###) ###-##-##', pref:'7' },
                    { value: 'by', label: 'BY', mask:'+375 (##) ###-##-##',placeholder:'+375 (##) ###-##-##', pref:'375' },
                    { value: 'oth', label: 'Другой', mask:'+#################',placeholder:'', pref:'' },
                    // Добавьте другие страны в массив по желанию
                ],
                phoneNumber:this.initialPhoneNumber,
            }
        },
        watch: {
            //метод отслеживающий изменение телефона и передающих его в родительский компонент
            // phoneNumber(newValue) {
            //     this.maskArr.forEach(country => {
            //         if (country.value === this.currentCountry) {
            //             this.setPhoneNumber(newValue)
            //         }
            //     });
            // },



        },
        methods: {
            handleBlur(){
                this.maskArr.forEach(country => {
                    if (country.value === this.currentCountry) {
                        this.setPhoneNumber(this.phoneNumber)
                    }
                });
            },
            handleCountryChange(){
                this.maskArr.forEach(country => {
                    if (country.value === this.currentCountry) {
                         this.phoneNumber='';
                         this.currentMask=country.mask
                         this.currentPlaceholder=country.placeholder
                         this.$refs.phoneInput.focus();
                    }
                });
            },
        },


    }



</script>
