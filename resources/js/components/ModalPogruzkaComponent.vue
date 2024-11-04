<template>
  <v-tailwind-modal v-model="showPogruzka"
                    @click-outside='cancel_modal'
  >
    <div class="container mx-auto">
      <div class="flex flex-wrap -mx-2 /* mod_borders_top not found */ /* mod_new_cargo not found */ /* header_grade_mod_bot not found */">
        <div class="w-8/12 px-2" v-if="flagNewModal">Новый грузоотправитель/грузополучатель:</div>
        <div class="w-8/12 px-2" v-if="!flagNewModal">Редактирование грузоотправителя/грузополучателя:</div>
        <div class="w-4/12 px-2 flex flex-wrap -mx-2 flex justify-end">
          <div class="flex /* add_ts_button8 not found */ text-center" v-on:click="save_gruzootpravitel()">Сохранить</div>
          <div class="flex /* add_ts_button8 not found */ text-center" v-on:click="cancel_modal()">Отменить</div>
        </div>

        <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent" class=""></alert-error-list-component>

      </div>

      <div class="/* no_padding_right not found */ /* no_padding_left not found */ flex flex-wrap -mx-2 /* mod_borders_bottom not found */">
        <!--                        начало левой колонки модалка-->
        <div class="w-6/12 px-2">
          <div class="container mx-auto px-4">
            <div class="w-full px-2 flex flex-wrap -mx-2">
              <div class="w-3/12 px-2 /* no_padding_left_form not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Форма</div>
                <input class="impala-input w-full" v-model="forma">
                <!--                                        <select v-model="forma" class="col-12 create_orders_date_title_int_1 grade_marg_bot">-->
                <!--                                            <option v-for="(forma_one) in forma_list" v-bind:value=forma_one.id  class="sel_cust">{{ forma_one.forma_name }}</option>-->
                <!--                                        </select>-->
              </div>
              <div class="w-5/12 px-2 /* grade_naz not found */">
                <div class="/* create_orders_date_title_1 not found */ /* lit_marg_grade not found */ w-full px-2">Название</div>
                <input class="impala-input w-full" v-model="nazvanie">
              </div>
              <div class="w-3/12 px-2 /* date_width_col not found */ /* no_padding_left_form not found */ /* grade_marg_bot not found */">
                <div class="/* create_orders_date_title_1 not found */ /* lit_marg_grade not found */ w-full px-2 /* no_wrap_text not found */">Дата регистрации</div>
                <input class="impala-input /* date_width not found */ w-full" @click="openDB0" v-model="data_registracii">
                <date-picker ref="datepicker0" v-model:value="data_registracii" valuetype="format" format="DD.MM.YYYY" v-model:open="openDP" class=""></date-picker>

              </div>
            </div>
          </div>
          <div class="container mx-auto px-4">
            <div class="w-full px-2 flex flex-wrap -mx-2">
              <div class="w-3/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */ /* grade_marg_top not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Для автозаполнения введите ИНН {{
                    founded
                  }}
                </div>
                <input @blur="get_INN_api()" class="w-full impala-input" v-model="INN">
              </div>
              <div class="w-2/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */ /* no_wrap_text not found */">ОГРН(если есть)</div>
                <input class="w-full impala-input" v-model="OGRN">
              </div>
              <div class="w-3/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Телефон</div>
                <phone-component class="/* phone_row not found */" :initialphonenumber="telefon" typenumber="mainNumber" :setphonenumber="setPhoneNumber"></phone-component>
              </div>
              <div class="w-2/12 px-2 /* no_padding_left_form not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">email</div>
                <input class="w-full impala-input" v-model="email">
              </div>
            </div>
          </div>
          <div class="w-full px-2 flex flex-wrap -mx-2">
            <div class="w-6/12 px-2">
              <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Генеральный
                директор
              </div>
              <input class="w-full impala-input" v-model="generalnii_direktor">
            </div>
            <div class="/* offset-1 not found */ w-4/12 px-2">
              <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Телефон</div>
              <phone-component class="/* phone_row not found */" :initialphonenumber="telefon_gen_dir" typenumber="genDir" :setphonenumber="setPhoneNumber"></phone-component>
            </div>
          </div>
          <div class="w-full px-2 /* grade_title_lit not found */ /* cont_header not found */">Контакты:</div>
          <div class="container mx-auto px-4">
            <div class="w-full px-2 flex flex-wrap -mx-2" v-for="(oplata,key) in kontakty">
              <div class="w-3/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Должность</div>
                <input class="w-full impala-input" v-model="kontakty[key].dolznost">
              </div>
              <div class="w-3/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">ФИО</div>
                <input class="w-full impala-input" v-model="kontakty[key].FIO">
              </div>
              <div class="w-3/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Телефон</div>
                <phone-component class="/* phone_row not found */" :initialphonenumber="kontakty[key].telefon" :rowkey="key" typenumber="kontakty" :setphonenumber="setPhoneNumber"></phone-component>
              </div>
              <div class="w-2/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">email</div>
                <input class="w-full impala-input" v-model="kontakty[key].email">
              </div>
              <button type="button" class="w-1/12 px-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm bg-red-500 hover:bg-red-700 text-white /* btn_del_in_ord1 not found */" v-on:click="delete_adres_pogr(key)">-
              </button>
            </div>
          </div>
          <div class="w-full px-2"><span v-on:click="dobavit_kontank()" class="/* add_cont_grade not found */">Добавить контакт</span></div>

          <div class="w-full px-2 /* grade_title_lit not found */ /* cont_header not found */">Адреса:</div>
          <div class="container mx-auto px-4">
            <div class="w-full px-2 flex flex-wrap -mx-2" v-for="(oneAdres,key) in adresa">
              <div class="w-4/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Название</div>
                <input class="w-full impala-input" v-model="adresa[key].nazvanie">
              </div>
              <div class="w-4/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Адрес</div>
                <input class="w-full impala-input" v-model="adresa[key].adres">
              </div>
              <div class="w-4/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">ФИО</div>
                <input class="w-full impala-input" v-model="adresa[key].FIO">
              </div>
              <div class="w-4/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">телефон</div>
                <phone-component class="/* phone_row not found */" :initialphonenumber="adresa[key].telefon" :rowkey="key" typenumber="adresa" :setphonenumber="setPhoneNumber"></phone-component>
              </div>
              <div class="w-2/12 px-2 /* no_padding_left_form not found */ /* inn_mar_r not found */ /* grade_marg_bot not found */">
                <div class="w-full px-2 /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">email</div>
                <input class="w-full impala-input" v-model="adresa[key].email">
              </div>
              <button type="button" class="w-1/12 px-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm bg-red-500 hover:bg-red-700 text-white /* btn_del_in_ord1 not found */" v-on:click="delete_one_adres(key)">-
              </button>
            </div>
          </div>
          <div class="w-full px-2"><span v-on:click="dobavit_adres()" class="/* add_cont_grade not found */">Добавить адрес</span></div>


        </div>
        <!--                        конец левой колонки модалка-->
        <!--                        начало правой колонки модалка-->
        <div class="w-6/12 px-2">
          <div class="w-full px-2 /* grade_marg_bot not found */">
            <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Юридический адрес:</div>
            <input class="w-full impala-input" v-model="yridicheskii_adres">
          </div>
          <div class="w-full px-2 /* grade_marg_bot not found */">
            <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Почтовый адрес:</div>
            <input class="w-full impala-input" v-model="pochtovyi_adres">
          </div>
          <div class="w-full px-2 /* grade_title_lit not found */ /* cont_header not found */">Банковские реквизиты:</div>
          <span v-for="(bank,key) in bank_arr" class="">
              <div class="w-full px-2 flex flex-wrap -mx-2">
                  <div class="w-6/12 px-2 /* grade_marg_bot not found */">
                      <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">БИК банка:</div>
                      <input class="w-full impala-input" v-model="bank_arr[key].BIK" @blur="get_BIK_BANK_api(key)">
                  </div>
                  <div class="w-6/12 px-2 /* bank_grade_marg not found */ /* grade_marg_bot not found */">
                      <span class="/* bank_grade not found */">Банк:</span>
                      <span class="/* bank_grade_1 not found */">{{ bank_arr[key].bank }}</span>
                  </div>
              </div>
              <div class="w-full px-2 flex flex-wrap -mx-2">
                  <div class="w-6/12 px-2 /* grade_marg_bot not found */">
                      <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Расчётный счёт:</div>
                      <input class="w-full impala-input /* grade_marg_bot not found */" v-model="bank_arr[key].raschetnyi">
                      <div class="flex /* create_orders_date_title_1 not found */ /* lit_marg_grade not found */">Корсчёт:</div>
                      <div class="w-full px-2">
                          {{ bank_arr[key].korschet }}
                      </div>
                  <button type="button" class="w-1/12 px-2 inline-flex items-center justify-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm bg-red-500 hover:bg-red-700 text-white /* btn_del_in_ord1 not found */" v-on:click="delete_bank(key)">-</button>
                  </div>
                  <div class="w-6/12 px-2 /* grade_marg_bot not found */">
                      <span class="/* bank_grade not found */">Комментарий:</span>
                      <textarea class="/* comm_settings not found */" v-model="bank_arr[key].kommentarii" rows="6" name="text"></textarea>
                  </div>
              </div>
                  </span>
          <div class="w-full px-2"><span v-on:click="dobavit_bank()" class="/* add_cont_grade not found */">Добавить банковские реквизиты</span></div>
          <div class="w-full px-2 flex flex-wrap -mx-2 /* modal_underline_right not found */"></div>

          <input hidden="true" type="file" id="files" ref="files" v-on:change="handleFilesUpload()" class="">
          <div class="w-full px-2 /* grade_title_lit not found */ /* cont_header_1 not found */">Файлы:</div>
          <div class="/* cont_header_2 not found */" v-for="(loc_file,key) in doc_files">

            <div class="w-full px-2 flex flex-wrap -mx-2" v-if="loc_file.file_name">
              <div class="w-full px-2 /* lit_marg_grade not found */ /* add_button_grade_modal not found */ /* no_wrap_text not found */"><span v-on:click="show_inp_doc(key)" class="">{{ loc_file.file_name }}.{{ loc_file.ext }}</span>
                <iconify-icon icon="ci:off-close" style="color: #c4c4c4;" width="20" v-if="loc_file.file_name" height="20" v-on:click="delete_one_file_modal(key)" class=""></iconify-icon>
              </div>
            </div>

            <div class="w-full px-2 flex flex-wrap -mx-2">
              <div class="w-6/12 px-2" v-if="loc_file.show_inp">
                <input v-click-outside="focus_out_from_inp" class="w-full px-2 /* lit_marg_grade not found */ impala-input" v-model="loc_file.file_name">
              </div>
              <div class="w-6/12 px-2">
                <span class="/* choose_file_grade not found */" v-on:click="addFiles(key)">Загрузить файл</span>
                <span class="/* excel_set not found */" v-if="loc_file.file_name" v-on:click="download_modal_file(key)">
                      <span class="/* iconify not found */" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                      </span>
              </div>
            </div>
          </div>
          <div class="w-full px-2 /* add_cont_grade not found */ /* cont_header_2 not found */" v-on:click="dobavit_doc()">Добавить документ</div>
        </div>
      </div>
    </div>
  </v-tailwind-modal>
</template>

<script>
import {ref} from 'vue';
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import 'vue-datepicker-next/locale/ru';

export default {
  props: ['edit_flag',
    'gruzootpravitel_id',
    'get_gruzootpravitel_list',
    'select_gruzootpravitel',
    'select_Manager',
    'change_one_gruzzotpravitel',
    'addRowGruzoot',
    'allNew',
    'vid'
  ],
  components: {
    DatePicker
  },
  emits: ['confirm', 'cancel'],
  mounted() {
    // this.get_gruzootpravitel_modal();
    // this.dobavit_kontank();
    // this.dobavit_bank();
    // this.get_BIK_BANK_api();
    //  this.get_forma_list(this.forma_list);
    //  console.log(this.edit_flag)

  },
  created() {
    // if(this.edit_flag)
    // {

    // }
  },
  data() {
    return {
      showPogruzka: ref(false),
      forma: '',
      forma_list: [],
      nazvanie: '',
      data_registracii: '',
      INN: '',
      OGRN: '',
      telefon: '',
      email: '',
      generalnii_direktor: '',
      telefon_gen_dir: '',
      yridicheskii_adres: '',
      pochtovyi_adres: '',
      menedzer_zakazchik: '',
      kontakty: [],
      adresa: [],
      openDP: false,
      bank_arr: [],
      files: [],
      doc_files: [],
      alert_arr: [],
      alert: false,
      //совпадает с ключём в массиве,аккуратно
      temp_file_id: '',
      current_opened_inp: '',
      founded: '',
      //переменные редактирования
      current_gruzootpravitel_id: '',

      alert_list: [],
      show_alert: false,
      flagNewModal: false

    }
  },
  methods: {
    setPhoneNumber(newPhoneNumber, type, key) {
      if (type == 'mainNumber') {
        this.telefon = newPhoneNumber
      }
      if (type == 'genDir') {
        this.telefon_gen_dir = newPhoneNumber
      }
      if (type == 'kontakty') {
        this.kontakty[key].telefon = newPhoneNumber
      }
      if (type == 'adresa') {
        this.adresa[key].telefon = newPhoneNumber
      }

    },
    setNewModalStatusFalse() {
      this.flagNewModal = false
    },
    //если новая погрузка
    newModal() {
      this.showPogruzka = true
      this.flagNewModal = true
      this.forma = ''
      this.nazvanie = ''
      this.data_registracii = ''
      this.INN = ''
      this.OGRN = ''
      this.telefon = ''
      this.email = ''
      this.generalnii_direktor = ''
      this.telefon_gen_dir = ''
      this.yridicheskii_adres = ''
      this.pochtovyi_adres = ''
      this.menedzer_zakazchik = ''
      this.kontakty = []
      this.adresa = []
      this.openDP = false
      this.bank_arr = []
      this.files = []
      this.doc_files = []
      this.alert_arr = []
      this.alert = false
      this.temp_file_id = ''
      this.current_opened_inp = ''
      this.current_gruzootpravitel_id = ''
    },
    //методы редактирования
    //общий метод загрузки cтартовых данных
    //передаем из предыде вида
    get_modal_edit_data(id) {
      console.log('id')
      console.log(id)
      if (this.edit_flag) {
        this.showPogruzka = true
        this.forma = ''
        this.nazvanie = ''
        this.data_registracii = ''
        this.INN = ''
        this.OGRN = ''
        this.telefon = ''
        this.email = ''
        this.generalnii_direktor = ''
        this.telefon_gen_dir = ''
        this.yridicheskii_adres = ''
        this.pochtovyi_adres = ''
        this.menedzer_zakazchik = ''
        this.kontakty = []
        this.adresa = []
        this.openDP = false
        this.bank_arr = []
        this.files = []
        this.doc_files = []
        this.alert_arr = []
        this.alert = false
        this.temp_file_id = ''
        this.current_opened_inp = ''

        this.current_gruzootpravitel_id = id
        this.get_gruzootpravitel_modal(this.kontakty, this.adresa, this.bank_arr, this.doc_files)

      }
    },
    //получаем стартовые данные
    get_gruzootpravitel_modal(kontakty, adresa, bank_arr, doc_files) {
      axios
          .post('/get_gruzootpravitel_modal', {
            id: this.current_gruzootpravitel_id
          })
          .then(({data}) => (
                  this.forma = data.gruzootpravitel.forma_id,
                      this.nazvanie = data.gruzootpravitel.nazvanie,
                      this.data_registracii = data.gruzootpravitel.data_registracii,
                      this.INN = data.gruzootpravitel.INN,
                      this.OGRN = data.gruzootpravitel.OGRN,
                      this.telefon = data.gruzootpravitel.telefon,
                      this.email = data.gruzootpravitel.email,
                      this.generalnii_direktor = data.gruzootpravitel.generalnii_direktor,
                      this.telefon_gen_dir = data.gruzootpravitel.telefon_gen_dir,
                      this.yridicheskii_adres = data.gruzootpravitel.YR_adres,
                      this.pochtovyi_adres = data.gruzootpravitel.pochtovyi_adres,

                      data.gruzootpravitel_contact.forEach(function (entry) {
                        kontakty.push({
                          id: entry.id,
                          dolznost: entry.dolznost,
                          FIO: entry.FIO,
                          telefon: entry.telefon,
                          email: entry.email,
                        });
                      }),
                      data.gruzootpravitel_adresa.forEach(function (entry) {
                        adresa.push({
                          id: entry.id,
                          nazvanie: entry.nazvanie,
                          adres: entry.adres,
                          FIO: entry.FIO,
                          telefon: entry.telefon,
                          email: entry.email
                        });
                      }),
                      data.gruzootpravitel_banks.forEach(function (entry) {
                        bank_arr.push({
                          BIK: entry.BIK,
                          raschetnyi: entry.raschetnyi,
                          korschet: entry.korschet,
                          kommentarii: entry.kommentarii,
                          bank: entry.bank,

                        });
                      }),
                      data.gruzootpravitel_files.forEach(function (entry) {
                        doc_files.push({
                          id: entry.id,
                          doc_name: entry.doc_name,
                          doc_path: entry.server_name,
                          file_name: entry.file_name,
                          ext: entry.ext,
                          show_inp: entry.show_inp
                        });
                      })

              )
          );
    },
    //конец методы редактирования
    get_INN_api() {
      this.founded = ''
      axios
          .post('/get_INN_api', {
            INN: this.INN
          })
          .then(response => {
            // console.log(response.data.status);
            if (response.data.status === 'success') {
              this.forma = response.data.forma,
                  this.nazvanie = response.data.nazvanie,
                  this.data_registracii = response.data.data_registracii,
                  this.OGRN = response.data.ogrn,
                  this.telefon = response.data.telefon,
                  this.email = response.data.email,
                  this.generalnii_direktor = response.data.generalnii_direktor,
                  this.yridicheskii_adres = response.data.yridicheskii_adres,
                  this.pochtovyi_adres = response.data.pochtovyi_adres

            } else {
              console.log(response.data.status);

              this.founded = 'Юр лицо не найдено'
              //  this.bank_arr[key]['korschet']=''
            }
          })
    },
    hideModal() {
      this.clear_data_modal()
      this.showPogruzka = false
    },
    cancel_modal() {
      this.delete_files_modal()
      this.hideModal()
    },
    get_forma_list(inp) {
      axios
          .post('/get_forma_list', {})
          .then(({data}) => (
                  data.ts.forEach(function (entry) {
                    inp.push({
                      id: entry.id,
                      forma_name: entry.forma_name,
                    });
                  })
              )
          );
    },
    get_BIK_BANK_api(key) {
      axios
          .post('/get_BIK_BANK_api', {
            bank: this.bank_arr[key]['BIK']
          })
          .then(response => {
            // console.log(response.data.status);
            if (response.data.status === 'success') {
              this.bank_arr[key]['bank'] = response.data.bank_name,
                  this.bank_arr[key]['korschet'] = response.data.korschet
            } else {
              this.bank_arr[key]['bank'] = 'Банк не найден',
                  this.bank_arr[key]['korschet'] = ''
            }
          })
    },
    clear_data_modal() {
      this.forma = ''
      this.nazvanie = ''
      this.data_registracii = ''
      this.INN = ''
      this.OGRN = ''
      this.telefon = ''
      this.email = ''
      this.generalnii_direktor = ''
      this.telefon_gen_dir = ''
      this.yridicheskii_adres = ''
      this.pochtovyi_adres = ''
      this.menedzer_zakazchik = ''
      this.kontakty = []
      this.adresa = []
      this.openDP = false
      this.bank_arr = []
      this.files = []
      this.doc_files = []
      this.alert_arr = []
      this.alert = false
      this.temp_file_id = ''
      this.current_opened_inp = ''
    },
    async delete_one_file_modal(key) {
      const result = await this.confirmMethodMixin();
      if (result) {
        this.doc_files.splice(key, 1);
      }
    },
    delete_files_modal() {
      axios
          .post('/delete_files_modal', {
            doc_files: this.doc_files
          })
    },

    openDB0() {
      this.openDP = true
    },
    async delete_adres_pogr(key) {
      const result = await this.confirmMethodMixin();
      if (result) {
        this.kontakty.splice(key, 1)
      }
    },
    async delete_one_adres(key) {
      const result = await this.confirmMethodMixin();
      if (result) {
        this.adresa.splice(key, 1)
      }
    },
    async delete_bank(key) {
      const result = await this.confirmMethodMixin();
      if (result) {
        this.bank_arr.splice(key, 1)
      }
    },
    dobavit_kontank() {
      let objToPush = {};
      objToPush['id'] = '';
      objToPush['dolznost'] = '';
      objToPush['FIO'] = '';
      objToPush['telefon'] = '';
      objToPush['email'] = '';
      this.kontakty.push(objToPush);
    },
    dobavit_adres() {
      let objToPush = {};
      objToPush['id'] = '';
      objToPush['nazvanie'] = '';
      objToPush['adres'] = '';
      objToPush['FIO'] = '';
      objToPush['telefon'] = '';
      objToPush['email'] = '';
      this.adresa.push(objToPush);
    },
    dobavit_bank() {
      let objToPush = {};
      objToPush['BIK'] = '';
      objToPush['raschetnyi'] = '';
      objToPush['korschet'] = '';
      objToPush['kommentarii'] = '';
      objToPush['bank'] = '';
      this.bank_arr.push(objToPush);
    },
    show_inp_doc(key) {
      this.current_opened_inp = key
      this.doc_files[key]['show_inp'] = true
    },
    focus_out_from_inp() {
      this.doc_files[this.current_opened_inp]['show_inp'] = false
      this.current_opened_inp = ''
    },


    // работа с файлами
    dobavit_doc() {

      let objToPush = {};
      objToPush['id'] = '';
      objToPush['doc_name'] = '';
      objToPush['doc_path'] = '';
      objToPush['file_name'] = '';
      objToPush['ext'] = '';
      objToPush['show_inp'] = false;

      // if(this.doc_files.length==0)
      // {
      //     objToPush['doc_id'] = 0;
      // }
      // else{
      //     objToPush['doc_id'] =Number( this.doc_files[this.doc_files.length-1]['doc_id'])+Number(1)
      //     }
      this.doc_files.push(objToPush);

    },
    download_modal_file(key) {
      window.location.href = '/download_modal_file/' + this.doc_files[key]['doc_path'];
    },
    addFiles(key) {
      this.temp_file_id = key
      this.$refs.files.click();
    },
    handleFilesUpload() {
      let uploadedFiles = this.$refs.files.files;
      let full_name = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)/))
      let file_name = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)\./))
      let extension = (uploadedFiles[0].name.slice((Math.max(0, (uploadedFiles[0].name.lastIndexOf(".")) || Infinity) + 1)));
      let formData = new FormData();
      let file = uploadedFiles;
      formData.append('file', file[0]);
      formData.append('file_name', file_name[1]);
      formData.append('full_name', full_name[0]);
      formData.append('extension', extension);
      axios.post('/store_modal_file_temp',
          formData,
          {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          }
      )
          .then(response => {
            if (response.data.status === 'success') {
              //server_name
              this.doc_files[this.temp_file_id].doc_path = response.data.path
              this.doc_files[this.temp_file_id].doc_name = full_name[0]
              this.doc_files[this.temp_file_id].file_name = file_name[1]
              this.doc_files[this.temp_file_id].ext = extension
            } else {
              alert('файл не сохранён')
            }
          })
      // }
      // else
      // {
      //     alert('Не верный формат файла')
      // }
    },
    // конец работа с файлами


    save_gruzootpravitel() {

      //работа с алертом валидации
      this.$refs.AlertListComponent.hide_alert_list()
      this.$refs.AlertListComponent.clear_alert_list()
      axios
          .post('/save_gruzootpravitel', {
            current_gruzootpravitel_id: this.current_gruzootpravitel_id,
            forma: this.forma,
            nazvanie: this.nazvanie,
            data_registracii: this.data_registracii,
            INN: this.INN,
            OGRN: this.OGRN,
            telefon: this.telefon,
            email: this.email,
            generalnii_direktor: this.generalnii_direktor,
            telefon_gen_dir: this.telefon_gen_dir,
            YR_adres: this.yridicheskii_adres,
            pochtovyi_adres: this.pochtovyi_adres,
            menedzer_zakazchik: this.menedzer_zakazchik,
            kontakty: this.kontakty,
            adresa: this.adresa,
            bank_arr: this.bank_arr,
            doc_files: this.doc_files
          })
          .then(response => {
            if (this.get_gruzootpravitel_list) {
              this.get_gruzootpravitel_list()
              //метод автообновления в виде
              // this.select_gruzootpravitel()
            }
            //если новая погрузка
            if ((this.flagNewModal) || (this.allNew)) {
              console.log('новая погрузка')
              //если добавляем новую из вида грузоотправителя
              if (this.vid == "GruzzotpravitelComponent") {
                //тут получить новый id перевозчика!
                this.addRowGruzoot(response.data.id, this.nazvanie, this.yridicheskii_adres, this.kontakty, this.forma)
                // this.change_one_gruzzotpravitel(this.current_gruzootpravitel_id,this.nazvanie,this.yridicheskii_adres,this.kontakty,this.forma)
              }
              if (this.vid == "GradeComponent") {
                // this.select_gruzootpravitel()
              }
            }
            //если редактируем погрузку
            else {

              //если редактируем из вида грузоотправителя
              if (this.vid == "GruzzotpravitelComponent") {
                this.change_one_gruzzotpravitel(this.current_gruzootpravitel_id, this.nazvanie, this.yridicheskii_adres, this.kontakty, this.forma)
              }

            }
            if (this.vid == "CreateOrdersComponent") {
              this.select_Manager(this.kontakty)
            }
            this.flagNewModal = false
            this.hideModal()
          })
          .catch(error => {
            if (error.response.status === 422) {
              this.alert_list = Object.values(error.response.data.errors).flat();
              this.$refs.AlertListComponent.show_alert_list()
            } else {
              console.error(error);
            }
          });

    }
  }
}
</script>
