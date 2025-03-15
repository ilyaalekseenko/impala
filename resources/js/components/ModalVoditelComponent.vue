<template>
  <v-tailwind-modal v-model="showVoditelMod"
                    @click-outside='cancel_modal'
  >

    <div class="w-full p-4 border-b border-[#e3eaea] justify-start items-center gap-4 flex">
      <div class="impala-block-title"
           v-html="headerNazvanie"></div>
      <div class="grow shrink basis-0 justify-end items-center gap-4 flex">
        <div class="impala-btn impala-btn-small impala-btn-primary" v-on:click="save_gruzootpravitel()">Сохранить</div>
        <div class="impala-btn impala-btn-small impala-btn-gray" v-on:click="cancel_modal()">Отменить</div>
      </div>
    </div>

    <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent"></alert-error-list-component>

    <div class="w-full p-4 justify-between items-start gap-4 flex">
      <div class="grid grid-cols-2 gap-6 w-full">
        <div class="w-full flex-col justify-start items-start gap-6 flex">
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input grow min-w-[320px]">
              <div class="impala-label">ФИО</div>
              <input class="impala-input" v-model="FIO">
            </div>
            <div class="impala-row-input w-[240px]">
              <div class="impala-label">
                Телефон
              </div>
              <phone-component :initialPhoneNumber="telefon" typeNumber="mainNumber"
                               :setPhoneNumber="setPhoneNumber"></phone-component>
            </div>
            <div class="impala-row-input">
              <div class="impala-label">Дата рождения</div>
              <date-picker ref="datepicker0" v-model:value="dataRozdeniya" type="date" valueType="format"
                           format="DD.MM.YY"
                           v-model:open="openDP" :editable="false"></date-picker>
            </div>
          </div>
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input">
              <div class="impala-label">Номер вод.удостоверения</div>
              <input class="impala-input" v-model="nomer_vod_ud">
            </div>
            <div class="impala-row-input">
              <div class="impala-label">Кем выдано</div>
              <input class="impala-input" v-model="kemVydan">
            </div>
            <div class="impala-row-input">
              <div class="impala-label">Когда выдан</div>
              <date-picker ref="datepicker0" v-model:value="kogdaVydan" type="date" valueType="format"
                           format="DD.MM.YY"
                           v-model:open="openDP1" :editable="false"></date-picker>
            </div>
          </div>
        </div>
        <div class="w-full flex-col justify-start items-start gap-6 flex">
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input">
              <div class="impala-label">Серия и номер паспорта</div>
              <input class="impala-input" v-model="seriyaPassporta">
            </div>
          </div>
          <div class="self-stretch justify-between items-center inline-flex gap-4">
            <div class="impala-row-input">
              <div class="impala-label">Прописка</div>
              <input class="impala-input" v-model="propiska">
            </div>
            <div class="impala-row-input grow shrink basis-0">
              <div class="impala-label">Перевозчик</div>
              <auto-input-perevozka-modal-component v-if="pogruzkaShowInp"
                                                    :vidTsFromParent="kompaniyaNazvanie"
                                                    @childReturnMethod="parentMethodFromAutoinputPerevozka"
              ></auto-input-perevozka-modal-component>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full p-4 flex-col justify-start items-start gap-4 flex">
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
  </v-tailwind-modal>
</template>

<script>
import {ref} from 'vue';
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import 'vue-datepicker-next/locale/ru';

export default {
  props: ['edit_flag', 'gruzootpravitel_id', 'get_gruzootpravitel_list', 'select_gruzootpravitel', 'change_one_gruzzotpravitel', 'addOnePerevozchik', 'vid', 'gradeAddVoditel', 'voditeliComponentAddVoditel'],
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
      showVoditelMod: ref(false),
      openDP: false,
      openDP1: false,
      files: [],
      alert_arr: [],
      alert: false,
      //совпадает с ключём в массиве,аккуратно
      temp_file_id: '',
      current_opened_inp: '',
      //переменные редактирования
      current_gruzootpravitel_id: '',

      alert_list: [],
      show_alert: false,
      FIO: '',
      nomer_vod_ud: '',
      telefon: '',
      dataRozdeniya: '',
      seriyaPassporta: '',
      kemVydan: '',
      kogdaVydan: '',
      propiska: '',
      kompaniya: '',
      kompaniyaNazvanie: '',
      doc_files: [],
      pogruzkaShowInp: true,
      headerNazvanie: ''
    }
  },

  methods: {
    setPhoneNumber(newPhoneNumber, type, key) {
      if (type == 'mainNumber') {
        this.telefon = newPhoneNumber
      }

    },
    formatPhone() {
      this.phoneMain = this.phoneMain.replace(/\D/g, '').slice(0, 10);
    },
    //если кликнули на нового перевозчика
    newPerevozchik(vidGrade, idPErevozchik, nazvaniePerevozchik) {
      this.showVoditelMod = true
      this.headerNazvanie = 'Новый водитель'
      this.current_gruzootpravitel_id = ''
      this.FIO = ''
      this.nomer_vod_ud = ''
      this.telefon = ''
      this.dataRozdeniya = ''
      this.seriyaPassporta = ''
      this.kemVydan = ''
      this.kogdaVydan = ''
      this.propiska = ''

      this.doc_files = []
      this.files = []
      this.openDP = false
      this.alert_arr = []
      this.alert = false
      this.temp_file_id = ''

      //если кликаем  из вида settings ну тоесть совсем новый водитель и название перевозчика(компании) не надо
      if (!vidGrade) {
        this.kompaniya = ''
        this.kompaniyaNazvanie = ''
      } else {
        this.kompaniya = idPErevozchik
        this.kompaniyaNazvanie = nazvaniePerevozchik
      }
    },
    //методы редактирования
    //общий метод загрузки cтартовых данных
    //передаем из предыдущего вида
    get_modal_edit_data(id) {
      this.showVoditelMod = true
      this.headerNazvanie = 'Редактирование водителя'
      this.current_gruzootpravitel_id = id
      this.FIO = ''
      this.nomer_vod_ud = ''
      this.telefon = ''
      this.dataRozdeniya = ''
      this.seriyaPassporta = ''
      this.kemVydan = ''
      this.kogdaVydan = ''
      this.propiska = ''
      this.kompaniya = ''
      this.kompaniyaNazvanie = ''
      this.doc_files = []
      this.files = []
      this.openDP = false
      this.alert_arr = []
      this.alert = false
      this.temp_file_id = ''
      this.getVoditeliModal(this.doc_files)
    },
    //получаем стартовые данные
    getVoditeliModal(doc_files) {
      axios
          .post('/getVoditeliModal', {
            id: this.current_gruzootpravitel_id
          })
          .then(({data}) => (
                  this.FIO = data.voditel.FIO,
                      this.nomer_vod_ud = data.voditel.udostoverenie,
                      this.telefon = data.voditel.telefon,
                      this.dataRozdeniya = data.voditel.dataRozdeniya,
                      this.seriyaPassporta = data.voditel.seriyaPassporta,
                      this.kemVydan = data.voditel.kemVydan,
                      this.kogdaVydan = data.voditel.kogdaVydan,
                      this.propiska = data.voditel.propiska,
                      this.kompaniya = data.voditel.kompaniya,
                      this.kompaniyaNazvanie = data.kompaniyaNazvanie,
                      data.voditel_files.forEach(function (entry) {
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
    hideModal() {
      this.clear_data_modal()
      this.showVoditelMod = false
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
    clear_data_modal() {
      this.forma = '',
          this.nazvanie = '',
          this.data_registracii = '',
          this.INN = '',
          this.OGRN = '',
          this.telefon = '',
          this.email = '',
          this.generalnii_direktor = '',
          this.telefon_gen_dir = '',
          this.yridicheskii_adres = '',
          this.pochtovyi_adres = '',
          this.menedzer_zakazchik = '',
          this.kontakty = [],
          this.adresa = [],
          this.openDP = false,
          this.bank_arr = [],
          this.files = [],
          this.doc_files = [],
          this.alert_arr = [],
          this.alert = false,
          this.temp_file_id = '',
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
    openDB1() {
      this.openDP1 = true
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
          .post('/saveVoditel', {
            voditelId: this.current_gruzootpravitel_id,
            FIO: this.FIO,
            nomer_vod_ud: this.nomer_vod_ud,
            telefon: this.telefon,
            dataRozdeniya: this.dataRozdeniya,
            seriyaPassporta: this.seriyaPassporta,
            kemVydan: this.kemVydan,
            kogdaVydan: this.kogdaVydan,
            propiska: this.propiska,
            kompaniya: this.kompaniya,
            doc_files: this.doc_files,

          })
          .then(response => {
            //если вид grade
            if (this.vid == 'grade') {
              //если сохраняем нового то в ответе будет state=new
              //если редактируем то в ответе будет state=old
              const targetPerevozkaID = (response.data.state === 'new') ? response.data.perevozkaID : this.current_gruzootpravitel_id;
              this.gradeAddVoditel(targetPerevozkaID, this.FIO);
            }
            if (this.vid == 'settings') {
              //если редактируем водителя
              if (this.current_gruzootpravitel_id) {
                this.voditeliComponentAddVoditel(true, this.current_gruzootpravitel_id, this.FIO)
              }
              //если добавляем нового
              else {
                this.voditeliComponentAddVoditel(false, response.data.perevozkaID, this.FIO)
              }

            }
            //если другой вид, не grade
            // else
            // {
            //     //если редактируем уже существующего перевозчка то в виде выше меняем его данные
            // if(this.current_gruzootpravitel_id)
            // {
            //     this.change_one_gruzzotpravitel(this.current_gruzootpravitel_id,this.nazvanie,this.yridicheskii_adres,this.kontakty)
            // }
            // //если добавляем нового перевозчика
            // else
            // {
            //     this.addOnePerevozchik(response.data.perevozkaID,this.nazvanie,this.yridicheskii_adres,this.kontakty);
            // }
            // }
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

    },
    parentMethodFromAutoinputPerevozka(data) {
      this.kompaniya = data.id
    }
  }
}
</script>
