<template>
  <div class="impala-autoinput">
    <!--            <textarea type="text" class="auto_input_height" ref="auto_input" :style="{ height: inputHeight + 'px' }" v-model="MainVarInInput" @blur="focus_out_from_select()" @input="searchInpNew()" @click="clickSearchInp()"/>-->
    <input readonly type="text" class="impala-input" ref="auto_input" v-model="MainVarInInput"
           @blur="focus_out_from_select" @input="searchInpNew()" @click="clickSearchInp()"/>
    <div class="impala-box:dropdown" v-if="showList">
      <ul class="select_list_gruzoot" ref="scrollContainer">
        <li v-for="(item, index) in filteredList" :key="index">
          <div class="impala-text-link:normal" @click.capture="select(item)">{{ item.nazvanie }}</div>
        </li>
      </ul>
    </div>
  </div>
</template>


<!--метод search back срабатывает при клике и ищет совпадения с тем что в инпуте-->
<!--метод focus_out_from_select срабатывает при потере инпутом фокуса, так же срабатывает на лист-->
<script>
    export default {
        props: ['vidTsFromParent','key_in_arr','id_ts','elem1','tip'],
        data(){
            return {
                selectedValue: "",
                showList: false,
                filteredList: [],
                MainVarInInput:this.vidTsFromParent,
                searchOffset:0,
                inputHeight: 30,
                mainId:0
            }
        },
        mounted() {
            this.setHeightTextarea()
        },
        watch: {
            // отслеживаем изменения значений в родителе

            // vidTsFromParent: function () {
            //     alert('fine')
            //         this.MainVarInInput=this.vidTsFromParent
            // },
            MainVarInInput: function () {
                setTimeout(this.waitScrollTextarea, 1);
            }
        },
        methods: {
            //методы отображения скролла
            waitScrollTextarea()
            {
                setTimeout(this.waitScrollTextareaSec, 1);
            },
            waitScrollTextareaSec()
            {
                if(this.inputHeight!=this.$refs.auto_input.scrollHeight)
                {
                    this.inputHeight = this.$refs.auto_input.scrollHeight
                    this.waitScrollTextarea()
                }

            },
            select(item) {

                this.showList = false;
                this.MainVarInInput = item.nazvanie;
                this.mainId=item.id;
                this.returnDataToParent()

            },
            //использую задержку в выполнении для проверки клика по листу
            focus_out_from_select(event)
            {
                setTimeout(() => {

                if(this.showList)
                {
                      this.showList = false;
                        //проверяем есть ли впринципе такой вид ТС
                        let isset_gruz =this.checkIfVidTSIsset_in_db()
                        isset_gruz.then((value) => {
                            //если такое тс есть то записать результат
                            if(value.isset_flag=="yes")
                            {
                                this.mainId=value.idTSBack;
                                this.returnDataToParent();
                                //присвоим id грузоотправителя
                              //  this.adres_pogruzke=value.adres_pogruzke
                              //  this.up_gruzoot_from_select_grade()
                            }
                        });
                }
                }, 200);
            },

            //метод поиска на бэке
            searchBack(inp)
            {
                let searchArrTemp=[];
                let searchWord=this.MainVarInInput;
                axios
                    .post('/searchBackTS',{
                        searchWord:searchWord,
                        model:'VidTS',
                        fieldToSearch:'ts_name',
                        searchOffset:this.searchOffset
                    })
                    .then(response => {
                        response.data.res.forEach(function(entry) {
                            inp.push({
                                id:entry.id,
                                nazvanie:entry.ts_name,
                            });
                        })

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
                this.searchBack(this.filteredList)
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
                    //возвращаем обратно id
                    vid_TS:this.mainId,
                    ts_name:this.MainVarInInput,
                    elem1:this.elem1,
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
                        .post('/getVidTSNazvanie', {
                            ts_name: MainVarInInputLOC,
                        })
                     return res.data
                }
                return getData(this.MainVarInInput)
            }
        }
    }
</script>
