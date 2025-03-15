<template>
  <v-tailwind-modal v-model="showPerevozchiki"
                    @click-outside='cancel_modal'
  >
    <div class="w-full p-4 border-b border-[#e3eaea] justify-start items-center gap-4 flex">
      <div class="impala-block-title"
           v-html="headerName"></div>
      <div class="grow shrink basis-0 justify-end items-center gap-4 flex">
        <div class="impala-btn impala-btn-small impala-btn-primary" v-on:click="save_gruzootpravitel()">Сохранить</div>
        <div class="impala-btn impala-btn-small impala-btn-gray" v-on:click="cancel_modal()">Отменить</div>
      </div>
    </div>

    <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent"></alert-error-list-component>

    <div class="w-full p-4 justify-between items-start gap-4 flex">
      <div class="grid grid-cols-1 gap-6 w-full">
        <div class="w-full flex-col justify-start items-start gap-6 flex">
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input w-[66px]">
              <div class="impala-label">Форма</div>
              <input class="impala-input" v-model="forma">
            </div>
            <div class="impala-row-input grow min-w-[320px]">
              <div class="impala-label">Название</div>
              <input class="impala-input" v-model="nazvanie">
            </div>
            <div class="impala-row-input">
              <div class="impala-label">Дата регистрации</div>
              <date-picker ref="datepicker0" v-model:value="data_registracii" type="date" valueType="format"
                           format="DD.MM.YY"
                           v-model:open="openDP" :editable="false"></date-picker>
            </div>
            <div class="impala-row-input w-[125px]">
              <div class="impala-label">ИНН {{ founded }}</div>
              <input @input="get_INN_api()" class="impala-input" v-model="INN">
            </div>
            <div class="impala-row-input w-[155px]">
              <div class="impala-label">ОГРН (если есть)</div>
              <input class="impala-input" v-model="OGRN">
            </div>
            <div class="impala-row-input w-[240px]">
              <div class="impala-label">Телефон</div>
              <phone-component :initialphonenumber="telefon" typenumber="mainNumber"
                               :setphonenumber="setPhoneNumber"></phone-component>
            </div>
            <div class="impala-row-input w-[245px]">
              <div class="impala-label">E-mail</div>
              <input class="impala-input" v-model="email">
            </div>
          </div>
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input grow min-w-[320px]">
              <div class="impala-label">
                Генеральный директор
              </div>
              <input class="w-full impala-input" v-model="generalnii_direktor">
            </div>
            <div class="impala-row-input w-[240px]">
              <div class="impala-label">
                Телефон директора
              </div>
              <phone-component :initialphonenumber="telefon_gen_dir"
                               typenumber="genDir" :setphonenumber="setPhoneNumber"></phone-component>
            </div>
            <div class="impala-row-input w-[245px]">
              <div class="impala-label">E-mail директора</div>
              <input class="w-full impala-input"
                     v-model="email_gen_dir"/>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 w-full">
            <div class="impala-row-input">
              <div class="impala-label">Юридический
                адрес:
              </div>
              <textarea class="w-full impala-input" v-model="yridicheskii_adres" rows="2"></textarea>
            </div>
            <div class="impala-row-input">
              <div class="impala-label">Почтовый
                адрес:
              </div>
              <textarea class="w-full impala-input" v-model="pochtovyi_adres" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="w-full flex-col justify-start items-start gap-4 flex">
          <div class="impala-label:base">Контакты:</div>
          <div v-if="kontakty.length > 0" class="self-stretch flex flex-col gap-4">
            <div class="impala-box:dashed self-stretch p-2 justify-between items-center gap-4 inline-flex"
                 v-for="(oplata, key) in kontakty">
              <div class="impala-row-input">
                <div class="impala-label">Должность</div>
                <input class="w-full impala-input" v-model="kontakty[key].dolznost">
              </div>
              <div class="impala-row-input grow min-w-[320px]">
                <div class="impala-label">ФИО</div>
                <input class="w-full impala-input" v-model="kontakty[key].FIO">
              </div>
              <div class="impala-row-input w-[240px]">
                <div class="impala-label">Телефон</div>
                <phone-component :initialphonenumber="kontakty[key].telefon"
                                 :rowkey="key" typenumber="kontakty" :setphonenumber="setPhoneNumber"></phone-component>
              </div>
              <div class="impala-row-input">
                <div class="impala-label">E-mail</div>
                <input class="w-full impala-input" v-model="kontakty[key].email">
              </div>
              <div class="impala-row-input">
                <div class="impala-label">&nbsp;</div>
                <span v-on:click="delete_adres_pogr(key)"
                      class="impala-text-link impala-text-link:red" v-tooltip="'Удалить контакт'"><i
                    class="iconsax-trash"></i></span>
              </div>
            </div>
          </div>
          <div v-on:click="dobavit_kontank()" class="impala-add-link">Добавить контакт</div>
        </div>
        <div class="w-full flex-col justify-start items-start gap-4 flex">
          <div v-if="bank_arr.length > 0" class="self-stretch flex flex-col gap-4">
            <div v-for="(bank,key) in bank_arr" class="self-stretch justify-between items-center gap-4 flex-col inline-flex">
              <div class="impala-label:base">Банковские реквизиты #{{key+1}}</div>
              <div class="self-stretch justify-between items-center gap-4 inline-flex">
                <div class="impala-row-input w-[125px]">
                  <div class="impala-label">БИК банка:</div>
                  <input class="w-full impala-input" v-model="bank_arr[key].BIK" @blur="get_BIK_BANK_api(key)">
                </div>
                <div class="impala-row-input grow min-w-[320px]">
                  <div class="impala-label">Банк:</div>
                  <input class="impala-input" disabled v-model="bank_arr[key].bank">
                </div>
                <div class="impala-row-input w-[220px]">
                  <div class="impala-label">Расчётный счёт:</div>
                  <input class="w-full impala-input" v-model="bank_arr[key].raschetnyi">
                </div>
                <div class="impala-row-input w-[220px]">
                  <div class="impala-label">Корсчёт:</div>
                  <input class="impala-input" disabled v-model="bank_arr[key].korschet">
                </div>
                <div class="impala-row-input">
                  <div class="impala-label">&nbsp;</div>
                  <span v-on:click="delete_bank(key)"
                        class="impala-text-link impala-text-link:red" v-tooltip="'Удалить реквизиты'"><i
                      class="iconsax-trash"></i></span>
                </div>
              </div>
              <div class="impala-row-input w-full">
                <div class="impala-label">Комментарий:</div>
                <textarea class="impala-input" v-model="bank_arr[key].kommentarii" rows="2"
                          name="text"></textarea>
              </div>
            </div>
          </div>
          <div v-on:click="dobavit_bank()" class="impala-add-link">Добавить банковские реквизиты</div>
        </div>
        <div class="w-full flex-col justify-start items-start gap-4 flex">
          <input hidden="hidden" type="file" id="files" ref="files" v-on:change="handleFilesUpload()">
          <div class="impala-label:base">Файлы:</div>
          <template v-if="doc_files.length > 0">
            <div class="self-stretch flex gap-4">
              <div v-for="(loc_file,key) in doc_files" class="impala-row-input w-[220px]">
                <div class="impala-label">#{{ key + 1 }} Файл:
                  <span class="impala-btn-none" v-if="loc_file.file_name===''" v-on:click="addFiles(key)"
                        v-tooltip="'Загрузить файл'"><i class="iconsax-document-upload"></i></span>
                </div>
                <div v-if="loc_file.file_name!==''"
                     class="impala-box:dashed flex flex-col gap-2 self-stretch p-2">

                  <div class="flex gap-2">
                    <span class="">{{ loc_file.file_name }}.{{ loc_file.ext }}</span>

                    <span v-if="loc_file.file_name" v-on:click="download_modal_file(key)" v-tooltip="'Скачать файл'"><i
                        class="iconsax-document-download"></i></span>
                    <span v-if="loc_file.file_name!==''"
                          v-on:click="delete_one_file_modal(key)"
                          class="impala-text-link impala-text-link:red" v-tooltip="'Удалить файл'"> <i
                        class="iconsax-trash"></i></span>
                  </div>

                </div>
              </div>
            </div>
          </template>
          <div v-on:click="dobavit_doc()" class="impala-add-link">Добавить документ</div>
        </div>
        <div class="w-full flex-col justify-start items-start gap-4 flex">
          <div class="impala-label:base">Комментарий</div>
          <textarea class="impala-input" v-model="kommentariy" rows="6" name="text"></textarea>
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
  props: ['edit_flag', 'gruzootpravitel_id', 'get_gruzootpravitel_list', 'select_gruzootpravitel', 'change_one_gruzzotpravitel', 'addOnePerevozchik', 'vid', 'gradeAddPerevozchik'],
  components: {
    DatePicker
  },
  mounted() {
  },
  created() {
  },
  computed: {
    formattedPhone() {
      return this.phoneMain.replace(/^(\d{1,3})(\d{1,3})?(\d{1,2})?(\d{1,2})?$/, '+7($1) $2 $3 $4');
    },
  },
  data() {
    return {
      showPerevozchiki: ref(false),
      forma: '',
      forma_list: [],
      nazvanie: '',
      gorod_bazirovania: '',
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
      kod_ATI: '',
      kommentariy: '',
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
      email_gen_dir: '',
      //переменные редактирования
      current_gruzootpravitel_id: '',
      alert_list: [],
      show_alert: false,
      phoneMain: '',
      headerName: 'Новый перевозчик'
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
    formatPhone() {
      this.phoneMain = this.phoneMain.replace(/\D/g, '').slice(0, 10);
    },
    //если кликнули на нового перевозчика
    newPerevozchik() {
      this.showPerevozchiki = true
      this.headerName = 'Новый перевозчик'
      this.forma = ''
      this.nazvanie = ''
      this.gorod_bazirovania = ''
      this.data_registracii = ''
      this.INN = ''
      this.kod_ATI = ''
      this.kommentariy = ''
      this.OGRN = ''
      this.telefon = ''
      this.email = ''
      this.generalnii_direktor = ''
      this.telefon_gen_dir = ''
      this.email_gen_dir = ''
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
      this.showPerevozchiki = true
      this.headerName = 'Редактирование перевозчика'
      this.forma = ''
      this.nazvanie = ''
      this.gorod_bazirovania = ''
      this.data_registracii = ''
      this.INN = ''
      this.kod_ATI = ''
      this.kommentariy = ''
      this.OGRN = ''
      this.telefon = ''
      this.email = ''
      this.generalnii_direktor = ''
      this.telefon_gen_dir = ''
      this.email_gen_dir = ''
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
      this.getPerevozkaModal(this.kontakty, this.adresa, this.bank_arr, this.doc_files)

    },
    //получаем стартовые данные
    getPerevozkaModal(kontakty, adresa, bank_arr, doc_files) {
      axios
          .post('/getPerevozkaModal', {
            id: this.current_gruzootpravitel_id
          })
          .then(({data}) => (
                  this.forma = data.perevozka.forma_id,
                      this.nazvanie = data.perevozka.nazvanie,
                      this.gorod_bazirovania = data.perevozka.gorod_bazirovania,
                      this.data_registracii = data.perevozka.data_registracii,
                      this.INN = data.perevozka.INN,
                      this.kod_ATI = data.perevozka.kod_ATI,
                      this.kommentariy = data.perevozka.kommentariy,
                      this.OGRN = data.perevozka.OGRN,
                      this.telefon = data.perevozka.telefon,
                      this.email = data.perevozka.email,
                      this.generalnii_direktor = data.perevozka.generalnii_direktor,
                      this.telefon_gen_dir = data.perevozka.telefon_gen_dir,
                      this.email_gen_dir = data.perevozka.email_gen_dir,
                      this.yridicheskii_adres = data.perevozka.YR_adres,
                      this.pochtovyi_adres = data.perevozka.pochtovyi_adres,

                      data.perevozka_contact.forEach(function (entry) {
                        kontakty.push({
                          id: entry.id,
                          dolznost: entry.dolznost,
                          FIO: entry.FIO,
                          telefon: entry.telefon,
                          email: entry.email,
                        });
                      }),
                      data.perevozka_banks.forEach(function (entry) {
                        bank_arr.push({
                          id: entry.id,
                          BIK: entry.BIK,
                          raschetnyi: entry.raschetnyi,
                          korschet: entry.korschet,
                          kommentarii: entry.kommentarii,
                          bank: entry.bank,

                        });
                      }),
                      data.perevozka_files.forEach(function (entry) {
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
    //надо ли тут добавлять kod_ATI
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
                  this.gorod_bazirovania = response.data.gorod_bazirovania,
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
      this.showPerevozchiki = false
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
      this.gorod_bazirovania = ''
      this.data_registracii = ''
      this.INN = ''
      this.kod_ATI = ''
      this.kommentariy = ''
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
      objToPush['id'] = '';
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
          .post('/savePerevozchik', {
            current_gruzootpravitel_id: this.current_gruzootpravitel_id,
            forma: this.forma,
            nazvanie: this.nazvanie,
            gorod_bazirovania: this.gorod_bazirovania,
            data_registracii: this.data_registracii,
            telefon: this.telefon,
            INN: this.INN,
            kod_ATI: this.kod_ATI,
            kommentariy: this.kommentariy,
            OGRN: this.OGRN,
            email: this.email,
            generalnii_direktor: this.generalnii_direktor,
            telefon_gen_dir: this.telefon_gen_dir,
            email_gen_dir: this.email_gen_dir,
            yridicheskii_adres: this.yridicheskii_adres,
            pochtovyi_adres: this.pochtovyi_adres,
            // menedzer_zakazchik:this.menedzer_zakazchik,
            kontakty: this.kontakty,
            //  adresa:this.adresa,
            bank_arr: this.bank_arr,
            doc_files: this.doc_files
          })
          .then(response => {
            //если вид grade
            if (this.vid == 'grade') {
              //вызвать метод из вида grade сохраняий название
              this.gradeAddPerevozchik(response.data.perevozkaID, this.forma + ' ' + this.nazvanie)
            }
            //если другой вид, не grade
            else {
              //если вид create_orders
              if (this.vid == 'CreateOrdersComponent') {
                if (this.current_gruzootpravitel_id) {
                  this.gradeAddPerevozchik(this.current_gruzootpravitel_id, this.nazvanie)
                } else {
                  //вызвать метод из вида grade сохраняий название
                  this.gradeAddPerevozchik(response.data.perevozkaID, this.nazvanie)
                }

              } else {
                //если редактируем уже существующего перевозчка то в виде выше меняем его данные
                if (this.current_gruzootpravitel_id) {
                  this.change_one_gruzzotpravitel(this.current_gruzootpravitel_id, this.nazvanie, this.yridicheskii_adres, this.kontakty)
                }
                //если добавляем нового перевозчика
                else {
                  this.addOnePerevozchik(response.data.perevozkaID, this.nazvanie, this.yridicheskii_adres, this.kontakty);
                }
              }
            }
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
