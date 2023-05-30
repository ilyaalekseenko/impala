<template>
    <div class="gruz_auto_select" v-my-click-outside="focus_out_from_select">

        <div class="input-container inp_show" >
            <div @click="clickMainHeader()" class="cust_pointer">{{ headerName }}</div>
            <div v-if="!showInput">{{ MainVarInInput }}</div>
            <textarea v-if="showInput" type="text" class="auto_input_height" ref="auto_input" :style="{ height: inputHeight + 'px' }" @input="searchInpNew()" v-model="MainVarInInput" @click="clickSearchInp()"/>
<!--            <textarea v-if="showInput" type="text" class="auto_input_height" ref="auto_input" :style="{ height: inputHeight + 'px' }" v-model="MainVarInInput" @blur="focus_out_from_select" @input="searchInpNew()" @click="clickSearchInp()"/>-->
            <div class="dropdown" v-if="showList" >
                <ul class="select_list_gruzoot" ref="scrollContainer">
                    <li v-for="(item, index) in filteredList" :key="index" @click.capture="select(item)">
                        {{ item.nazvanie }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>


<!--метод search back срабатывает при клике и ищет совпадения с тем что в инпуте-->
<!--метод focus_out_from_select срабатывает при потере инпутом фокуса, так же срабатывает на лист-->
<script>
    export default {
        props: ['vidTsFromParent','key_in_arr','id_ts','tip','modelSearch','fieldToSearch','fieldToSearchFinalGrade','pogrVygrInp','headerName'],
        data(){
            return {
                selectedValue: "",
                MainVarInInput:this.vidTsFromParent,
                searchOffset:0,
                inputHeight: 30,
                mainId:0,
                debounceTime: 200, // Задержка debounce в миллисекундах
                timeoutId: null,

                filteredList: [],
                showList: false,
                showInput:false,
                tempMainVarInInput:'',
                clickHeaderVar:true
            }
        },
        mounted() {
          //  this.setHeightTextarea()
        },
        watch: {
            // отслеживаем изменения значений в родителе

             vidTsFromParent: function () {

                     this.MainVarInInput=this.vidTsFromParent
             },
            MainVarInInput: function () {
                setTimeout(this.waitScrollTextarea, 1);
            }
        },
        methods: {
            //клик по перевозчику
            clickMainHeader()
            {
                if(this.clickHeaderVar)
                {
                    this.clickHeaderVar=false
                    console.log('clickMainHeader')
                    this.tempMainVarInInput=this.MainVarInInput
                    this.MainVarInInput=''
                    this.showInput=!this.showInput
                    setTimeout(this.setHeightTextarea, 100);

                    this.filteredList=[];
                    this.searchOffset=0;
                    this.searchBack(this.filteredList)
                    this.showList = true;
                    // ждём появления скролла и если он появился вызываем метод searchInpNext
                    this.waitScroll()
                }


            },
            //методы отображения скролла
            waitScrollTextarea()
            {
                setTimeout(this.waitScrollTextareaSec, 1);
            },
            waitScrollTextareaSec()
            {
                if(this.showList)
                {
                    if(this.inputHeight!=this.$refs.auto_input.scrollHeight)
                    {
                        this.inputHeight = this.$refs.auto_input.scrollHeight
                        this.waitScrollTextarea()
                    }
                }


            },
            focus_out_from_select()
            {
                if(this.showList)
                {
                    this.clickHeaderVar=true
                    this.showInput=false
                    this.showList=false;
                    this.MainVarInInput=this.tempMainVarInInput

                }
            },
            select(item) {

                this.clickHeaderVar=true
                this.showList = false;
                this.MainVarInInput = item.nazvanie;
                this.mainId=item.id;
                this.showInput=false
                this.returnDataToParent()

            },
            //использую задержку в выполнении для проверки клика по листу
            // focus_out_from_select(event)
            // {
            //     setTimeout(() => {
            //
            //     if(this.showList)
            //     {
            //           this.showList = false;
            //     }
            //     }, 200);
            // },
            //метод поиска на бэке
            searchBack(inp)
            {
                let searchArrTemp=[];
                let searchWord=this.MainVarInInput;
                axios
                    .post('/searchBackStavkiInput',{
                        searchWord:searchWord,
                        model:this.modelSearch,
                        fieldToSearch:this.fieldToSearch,
                        fieldToSearchFinalGrade:this.fieldToSearchFinalGrade,
                        searchOffset:this.searchOffset,
                        pogrVygrInp:this.pogrVygrInp
                    })
                    .then(response => {
                        if(response.data.count!=0)
                        {
                            response.data.res.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    nazvanie:entry.ts_name,
                                });
                            })
                        }
                    })
                // return searchArrTemp

            },
            clickSearchInp()
            {
                if(this.MainVarInInput==undefined)
                {
                    this.MainVarInInput=''
                    this.searchInp()
                }
                else
                {
                    this.searchInpNew()
                    this.showList = true;
                }
            },
            //метод изначального выставления высоты
            setHeightTextarea()
            {
                this.inputHeight = this.$refs.auto_input.scrollHeight
                //this.searchBack(this.filteredList)
                // ждём появления скролла и если он появился вызываем метод searchInpNext
                this.waitScroll()
            },
            //метод при вводе нового значения в инпут
            searchInp()
            {
                this.inputHeight = this.$refs.auto_input.scrollHeight
                this.searchBack(this.filteredList)
                this.showList = true;
              // ждём появления скролла и если он появился вызываем метод searchInpNext
               this.waitScroll()
            },
            searchInpNew()
            {
                setTimeout(this.waitScrollTextarea, 300);
                this.filteredList=[];
                this.searchOffset=0;
                this.searchBack(this.filteredList)
                this.showList = true;
                // ждём появления скролла и если он появился вызываем метод searchInpNext
                this.waitScroll()
            },
            searchInpNext()
            {
                const scrollContainer = this.$refs.scrollContainer;
                //добавляем слушатель к скроллу
                scrollContainer.addEventListener('scroll', this.handleScroll);

            },
            handleScroll() {
                 const scrollContainer = this.$refs.scrollContainer;
                 if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight) {
                     this.searchOffset+=10
                     this.searchBack(this.filteredList)

                 }
            },
             waitScroll () {
                if (!this.$refs.scrollContainer) {
                    setTimeout(this.waitScroll, 300); // try again in 300 milliseconds
                }
                else
                {
                    this.searchInpNext()
                }
                  },


            returnDataToParent()
            {
                this.$emit('childReturnMethod', {
                    // id:this.mainId,
                     ts_name:this.MainVarInInput,
                     tip:this.tip
                })

            },
            //метод проверки существует ли название такого ТС вообще
            //возвращает data с полями типа
            // adres_pogruzke: 162
            // isset_flag: "yes"
            // message: "Грузоотправитель получен
            // status: "success"
            //переписать под ВИД ТС
            checkIfVidTSIsset_in_db()
            {

                 async function getData(MainVarInInputLOC) {
                     let res = await axios
                        .post('/getPerevozkaNazvanie', {
                            nazvanie: MainVarInInputLOC,
                        })
                     return res.data
                }
                return getData(this.MainVarInInput)
            },
            // handleInput() {
            //     clearTimeout(this.timeoutId);
            //     this.timeoutId = setTimeout(() => {
            //         this.returnDataToParent();
            //     }, 2000);
            // }
        }
    }
</script>
