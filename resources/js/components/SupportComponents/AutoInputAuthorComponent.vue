<template>
  <div class="impala-autoinput" v-click-outside="focus_out_from_select">
    <input id="inp1" type="text" class="impala-input" ref="auto_input" v-model="adres_pogruzke_show_local"
           @input="searchInpNew()" @click="clickSearchInp()"/>
    <div class="impala-box:dropdown" v-if="showList">
      <ul class="select_list_gruzoot" ref="scrollContainer">
        <li v-for="(item, index) in filteredList" :key="index" class="flex flex-col gap-1">
          <div v-if="index===0 || item.avtor !== filteredList[index - 1].avtor"
               class="flex gap-2 items-center justify-between">
            <div class="grow text-sm font-bold impala-text-gray-500 uppercase">{{ item.avtor }}</div>
            <span class="impala-btn-none" v-on:click="show_mod_edit(item.gruzootpravitel_id)"
                  v-tooltip="'Добавить новый адрес к перевозчику'">
              <i class="iconsax-add-circle impala-text-primary text-lg cursor-pointer"></i>
            </span>
          </div>
          <div class="impala-text-link:normal" @click="select(item)"><i
              class="iconsax-location impala-text-gray-500"></i> {{ (item.nazvanie) ? item.nazvanie : 'Не заполнено' }}
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  props: ['gruzootpravitel_arr', 'order_id', 'adres_pogruzke_show', 'inp_type', 'key_in_arr',
    'adres_pogruzke_show_edit', 'id_ts', 'showHideText', 'firstClick',
    'changePogrVygrAllShow'
  ],
  data() {
    return {
      selectedValue: "",
      showList: false,
      filteredList: [],
      adres_pogruzke_show_local: this.adres_pogruzke_show_edit,
      order_id_local: '',
      searchOffset: 0,
      inputHeight: 30,
      firstClick_local: false,
      searchOffsetPogruzchiki: 0
    }
  },
  mounted() {
  },
  //убираем для предотвращения утечки памяти
  // beforeUnmount() {
  //     this.$refs.scrollContainer.removeEventListener('scroll', this.handleScroll);
  // },
  watch: {
    // отслеживаем изменения значений в родителе
    order_id: function () {
      this.order_id_local = this.order_id
    },
    adres_pogruzke_show_edit: function () {
      this.adres_pogruzke_show_local = this.adres_pogruzke_show_edit
    },
    firstClick: function () {
      // alert('ЗИМЕНЕНО')
      this.firstClick_local = this.firstClick
    },
    adres_pogruzke_show: function () {
      if (this.inp_type !== 'add_pogruzka_edit') {
        this.adres_pogruzke_show_local = this.adres_pogruzke_show
      }
    },
    adres_pogruzke_show_local: function () {
      setTimeout(this.waitScrollTextarea, 1);
    }
  },
  methods: {
    select_gruzootpravitel(current_gruzootpravitel_id, nazvanie, forma, adresa) {
      // console.log(adresa);
      // console.log(this.filteredList);

      let flag = false;
      this.filteredList.forEach((element, index) => {

        if (element.gruzootpravitel_id === current_gruzootpravitel_id) {
          element.avtor = forma + ' ' + nazvanie
          if (flag === false) {
            console.log(index);
            flag = true;
          }
        }
      });

    },
    waitScrollTextarea() {
      setTimeout(this.waitScrollTextareaSec, 1);
    },
    waitScrollTextareaSec() {
      if (this.inputHeight !== this.$refs.auto_input.scrollHeight) {
        this.inputHeight = this.$refs.auto_input.scrollHeight
        this.waitScrollTextarea()
      }

    },
    //клик в не списка
    focus_out_from_select() {
      //сделать название грузоравителя уникальным
      if (this.showList) {
        this.showList = false;
        //если добавление новой пустой погрузки или выгрузки
        if ((this.inp_type === 'add_pogruzka_empty') || (this.inp_type === 'add_vygruzka_empty') || (this.inp_type === 'add_pogruzka_edit') || (this.inp_type === 'add_vygruzka_edit')) {
          this.check_if_name_gruz_isset()
          this.firstClick_local = false
        }
        //если просто погрузка или выгрузка
        if ((this.inp_type === 'adres_vygruski') || (this.inp_type === 'adres_pogruzke')) {

          if (this.firstClick_local) {
            this.check_if_name_gruz()
            this.firstClick_local = false
          } else {
            this.firstClick_local = true
          }

        }
        //если вид grade
        if ((this.inp_type === 'grade_pogruzka') || (this.inp_type === 'grade_vygruzka')) {
          //проверяем есть ли впринципе такое название грузоотправителя
          let isset_gruz = this.check_if_name_gruz_isset_in_db()
          isset_gruz.then((value) => {
            if (value.isset_flag === "yes") {
              //присвоим id грузоотправителя
              this.adres_pogruzke = value.adres_pogruzke
              this.up_gruzoot_from_select_grade()
            }
          });
        }
      }
    },
    search() {
      let f = this.adres_pogruzke_show_local;
      this.filteredList = this.gruzootpravitel_arr.filter(function (item) {
        // let searchVar=forma_id+nazvanie+
        return item.nazvanie.toLowerCase().includes(f.toLowerCase());
        //nazvanie где искать
        // return item.nazvanie.toLowerCase().includes(f.toLowerCase());
      });
      if (this.filteredList.length != 0) {
        this.showList = true;
      } else {
        this.showList = false;
      }

    },
    show_mod_edit(id) {
      this.changePogrVygrAllShow(id)
      //вызов метода дочернего компонента( модального окна )
      //    this.$refs.modalComponentforActionAuthor.get_modal_edit_data(id)
    },
    //метод поиска на бэке
    searchBack(inp) {
      let searchArrTemp = [];
      let searchWord = this.adres_pogruzke_show_local;
      axios
          .post('/searchBackAvtor', {
            searchWord: searchWord,
            model: 'GruzootpravitelAdresa',
            fieldToSearch: 'full_name',
            searchOffset: this.searchOffset,
            searchOffsetPogruzchiki: this.searchOffsetPogruzchiki
          })
          .then(response => {
            response.data.res.forEach(function (entry) {
              inp.push({
                id: entry.id,
                gruzootpravitel_id: entry.gruzootpravitel_id,
                nazvanie: entry.full_name,
                avtor: entry.forma_id + ' ' + entry.nazvGruz
              });
            })
            this.searchOffsetPogruzchiki = response.data.searchOffsetPogruzchiki
          })
      // return searchArrTemp

    },
    clickSearchInp() {

      if (this.adres_pogruzke_show_local === undefined) {
        this.adres_pogruzke_show_local = ''
        this.searchInp()

      } else {

        this.searchInpNew()
        this.showList = true;
      }
    },
    //метод при вводе нового значения в инпут
    searchInp() {
      this.inputHeight = this.$refs.auto_input.scrollHeight
      this.searchBack(this.filteredList)
      this.showList = true;
      // ждём появления скролла и если он появился вызываем метод searchInpNext
      this.waitScroll()
    },
    searchInpNew() {
      setTimeout(this.waitScrollTextarea, 300);
      this.filteredList = [];
      this.searchOffset = 0;
      this.searchOffsetPogruzchiki = 0;
      this.searchBack(this.filteredList)
      this.showList = true;
      // ждём появления скролла и если он появился вызываем метод searchInpNext
      this.waitScroll()
    },
    searchInpNext() {
      const scrollContainer = this.$refs.scrollContainer;
      //добавляем слушатель к скроллу
      scrollContainer.addEventListener('scroll', this.handleScroll);

    },
    handleScroll() {
      const scrollContainer = this.$refs.scrollContainer;
      if (scrollContainer.scrollTop + scrollContainer.clientHeight >= scrollContainer.scrollHeight) {
        this.searchOffset += 10
        this.searchBack(this.filteredList)

      }
    },
    waitScroll() {
      if (!this.$refs.scrollContainer) {
        setTimeout(this.waitScroll, 300); // try again in 300 milliseconds
      } else {
        this.searchInpNext()
      }
    },

    select(item) {
      this.adres_pogruzke_show_local = item.nazvanie;
      this.adres_pogruzke = item.id;
      this.showList = false;

      //если вид grade
      if ((this.inp_type == 'grade_pogruzka') || (this.inp_type == 'grade_vygruzka')) {
        this.up_gruzoot_from_select_grade()
      }
      //если вид create_orders
      else {
        this.up_gruzoot_from_select()
        this.firstClick_local = false
      }

    },
    //click по грузоотправителю из вида grade при выборе
    up_gruzoot_from_select_grade() {
      let pogr_or_vygr_temp = ''
      if (this.inp_type == 'grade_pogruzka') {
        pogr_or_vygr_temp = 1
      }
      if (this.inp_type == 'grade_vygruzka') {
        pogr_or_vygr_temp = 2
      }
      //отправляем назад в главный вид
      this.$emit('add_pogruzka_new', {
        //id грузоотправителя
        inp_pog_id: this.adres_pogruzke,
        //ключ , это номер по порядку в данном ТС
        key: this.key_in_arr,
        //погрузка - 1 или выгрузка - 2
        pogr_or_vygr: pogr_or_vygr_temp,
        //то что в инпуте
        nazvanie: this.adres_pogruzke_show_local,
        //id грузоотправителя
        id_ts: this.id_ts
      })
    },

    //если добавление грузоотправителя из ФОРМЫ ТС из модального окна
    up_gruz_from_modal(nazvanie_from_modal) {
      //если новая погрузка в ТС
      this.adres_pogruzke_show_local = nazvanie_from_modal
    },
    //обновим из модального окна
    up_gruzoot_from_select_modal_grade() {

    },
    //обновим заявку
    //type_inp это погрузка или выгрузка из вида create_orders тоесть из модального окна
    up_gruzoot_from_select(adres_pogruzke_from_orders, type_inp) {

      let adres = '';
      let column_name = '';
      //если выбираем локально пустую погрузку то передаём id погрузки во временный массив добавления погрузки
      if (this.inp_type == 'add_pogruzka_empty') {
        adres = this.adres_pogruzke
        this.$emit('add_pogruzka_new', {
          inp_pog_id: adres,
          key: this.key_in_arr,
          pogr_or_vygr: 'pogruzka',
          nazvanie: this.adres_pogruzke_show_local
        })
      }
      //если выбираем локально пустую выгрузку то передаём id выгрузки во временный массив добавления выгрузки
      if (this.inp_type == 'add_vygruzka_empty') {
        adres = this.adres_pogruzke
        this.$emit('add_pogruzka_new', {
          inp_pog_id: adres,
          key: this.key_in_arr,
          pogr_or_vygr: 'vygruzka',
          nazvanie: this.adres_pogruzke_show_local
        })
      }
      //если выбираем локально редактирование погрузки то передаём id погрузки во временный массив редактирования погрузки
      if (this.inp_type == 'add_pogruzka_edit') {
        adres = this.adres_pogruzke
        this.$emit('add_pogruzka_new', {
          inp_pog_id: adres,
          key: this.key_in_arr,
          pogr_or_vygr: 'pogruzka',
          nazvanie: this.adres_pogruzke_show_local
        })
      }
      //если выбираем локально редактирование выгрузки то передаём id выгрузки во временный массив редактирования выгрузки
      if (this.inp_type == 'add_vygruzka_edit') {
        adres = this.adres_pogruzke
        this.$emit('add_pogruzka_new', {
          inp_pog_id: adres,
          key: this.key_in_arr,
          pogr_or_vygr: 'vygruzka',
          nazvanie: this.adres_pogruzke_show_local
        })
      }
      //если выбираем погрузку или разгрузку в левой колонке
      if ((this.inp_type == 'adres_vygruski') || (this.inp_type == 'adres_pogruzke')) {

        //если обновляем из вида create_orders тоесть из модального окна
        if (adres_pogruzke_from_orders) {
          adres = adres_pogruzke_from_orders
          column_name = type_inp
        }
        //если обновляем локально
        else {
          adres = this.adres_pogruzke
          column_name = this.inp_type
        }
        //покажем текст вместо инпута
        this.$emit('showHideText', {
          type: this.inp_type,
          inputText: this.adres_pogruzke_show_local,
          adres_id: adres,
        })


        axios
            .post('/up_gruzoot_from_select', {
              order_id: this.order_id,
              adres_id: adres,
              column_name: column_name
            })
      }
    },
    //метод проверки по названию когда клика пр списку не было а ввод был
    check_if_name_gruz() {
      // console.log('this.inp_type')
      // console.log(this.inp_type)
      axios
          .post('/check_if_name_gruz', {
            order_id: this.order_id,
            adres_pogruzke_show: this.adres_pogruzke_show_local,
            column_name: this.inp_type
          })
          .then(response => {

            if (response.data.isset_flag == 'yes') {
              this.adres_pogruzke = response.data.adres_pogruzke
              //покажем текст вместо инпута
              this.$emit('showHideText', {
                type: this.inp_type,
                inputText: this.adres_pogruzke_show_local
              })
            } else {
              //покажем текст вместо инпута
              this.$emit('showHideText', {
                type: this.inp_type,
                inputText: ''
              })
            }
          })
    },
    check_if_name_gruz_isset() {
      let pogr_vygr_temp = ''
      if ((this.inp_type == 'add_pogruzka_empty') || (this.inp_type == 'add_pogruzka_edit')) {
        pogr_vygr_temp = 'pogruzka'
      }
      if ((this.inp_type == 'add_vygruzka_empty') || (this.inp_type == 'add_vygruzka_edit')) {
        pogr_vygr_temp = 'vygruzka'
      }
      axios
          .post('/check_if_name_gruz_isset', {
            adres_pogruzke_show: this.adres_pogruzke_show_local,
          })
          .then(response => {
            if (response.data.isset_flag == 'yes') {
              this.adres_pogruzke = response.data.adres_pogruzke
              this.$emit('add_pogruzka_new', {
                inp_pog_id: this.adres_pogruzke,
                key: this.key_in_arr,
                pogr_or_vygr: pogr_vygr_temp,
                nazvanie: this.adres_pogruzke_show_local
              })
            }
          })
    },
    //метод проверки существует ли название такого ТС вообще
    //возвращает data с полями типа
    // adres_pogruzke: 162
    // isset_flag: "yes"
    // message: "Грузоотправитель получен
    // status: "success"
    check_if_name_gruz_isset_in_db() {
      async function getData(ad_pogr) {
        let res = await axios
            .post('/check_if_name_gruz_isset', {
              adres_pogruzke_show: ad_pogr,
            })
        return res.data
      }

      return getData(this.adres_pogruzke_show_local)
    }
  }
}
</script>
