<template>
  <v-tailwind-modal v-model="showTSMod"
                    @click-outside='cancel_modal'
  >
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 mod_borders_top mod_new_cargo row header_grade_mod_bot">
          <div class="col-8">{{ headerNazvanie }}:</div>
          <div class="col-4 row d-flex justify-content-end">
            <div class="col add_ts_button8 text-center" v-on:click="save_gruzootpravitel()">Сохранить</div>
            <div class="col add_ts_button8 text-center" v-on:click="cancel_modal()">Отменить</div>
          </div>

          <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent"></alert-error-list-component>

        </div>

        <div class="col-12 no_padding_right no_padding_left row mod_borders_bottom">
          <!--                        начало левой колонки модалка-->
          <div class="col-12">
            <div class="container-fluid perevoz_m_bottom">
              <div class="col-12 row">
                <div class="col no_padding_left_form lit_marg_grade">
                  <div class="col-12 create_orders_date_title_1 lit_marg_grade">Тип:</div>
                  <!--                                        <input class="border_input col-12 TS_mod_tip"-->
                  <!--                                               v-model="tip"/>-->
                  <auto-input-tip-t-s-component class="col-12 TS_mod_tip"
                                                :vidTsFromParent="tipNazvanie"
                                                @childReturnMethod="parentMethodFromAutoinputVidts"
                                                ref="AutoSelectComponent_vid_TS"
                  ></auto-input-tip-t-s-component>


                </div>
                <div class="col no_padding_right ">
                  <div class="create_orders_date_title_1 lit_marg_grade col-12">Марка:</div>
                  <input class="border_input col-12 TS_mod_marka"
                         v-model="marka"/>
                </div>
                <div class="col  no_padding_right">
                  <div class="col-12 create_orders_date_title_1 lit_marg_grade">Номер:</div>
                  <input class="border_input TS_mod_nomer" type="text" v-model="nomer"/>
                </div>
                <div class="col no_padding_right">
                  <div class="col-12 create_orders_date_title_1 lit_marg_grade">СТС:</div>
                  <input class="border_input TS_mod_tonn" type="text" v-model="sts"/>
                </div>
                <div class="col no_padding_right">
                  <div class="col-12 create_orders_date_title_1 lit_marg_grade">ПТС:</div>
                  <input class="border_input TS_mod_nomer_doc" type="text" v-model="pts"/>
                </div>
                <div class="col no_padding_right">
                  <div class="col-12 create_orders_date_title_1 lit_marg_grade">Перевозчик:</div>
                  <!--                                        <input class="border_input TS_mod_kompaniya" type="text" v-model="kompaniya" />-->

                  <auto-input-perevozka-modal-component v-if="pogruzkaShowInp" class="select_width_grade"
                                                        :vidTsFromParent="kompaniyaNazvanie"
                                                        @childReturnMethod="parentMethodFromAutoinputPerevozka"
                  ></auto-input-perevozka-modal-component>
                </div>
              </div>
            </div>
          </div>
          <!--                        конец левой колонки модалка-->
          <!--                    строка загрузки файлов-->
          <div class="col-12 grade_title_lit cont_header_vod">Загрузка файлов:</div>
          <input hidden="true" type="file" id="files" ref="files" v-on:change="handleFilesUpload()"/>
          <div class="container-fluid ">
            <div class="col-12 row">
              <div v-for="(loc_file,key) in doc_files"
                   class="col-4 row inn_width no_padding_left_form inn_mar_r grade_marg_bot doc_vod_block">
                <div class="col-12 row create_orders_date_title_1 lit_marg_grade ">Название документа:</div>
                <div class="col-12 row" v-if="loc_file.file_name">
                  <div class="col-12 lit_marg_grade add_button_grade_modal no_wrap_text"><span
                      v-on:click="show_inp_doc(key)">{{ loc_file.file_name }}.{{ loc_file.ext }}</span>
                    <iconify-icon icon="ci:off-close" style="color: #c4c4c4;" width="20"
                                  v-if="loc_file.file_name" height="20"
                                  v-on:click="delete_one_file_modal(key)"></iconify-icon>
                  </div>
                </div>
                <div class="col-12 row">
                  <div class="col-6" v-if="loc_file.show_inp">
                    <input v-click-outside="focus_out_from_inp" class="col-12 lit_marg_grade border_input"
                           v-model="loc_file.file_name"/>
                    <span class="iconify" data-icon="fa-regular:list-alt" style="color: #4d4d4d;" data-width="24"
                          data-height="24"></span>
                  </div>
                  <div class="col-6">
                    <span class="choose_file_grade " v-on:click="addFiles(key)">Загрузить файл</span>
                    <span class="excel_set" v-if="loc_file.file_name" v-on:click="download_modal_file(key)">
                                            <span class="iconify"
                                                  data-icon="material-symbols:sim-card-download-outline-rounded"
                                                  style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                            </span>
                  </div>
                </div>
              </div>
              <div class="col-2 add_vod_button1 text-center" v-on:click="dobavit_doc()">Добавить</div>
            </div>
          </div>
          <!--                        конец строки загрузки файлов-->

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
  props: ['edit_flag', 'gruzootpravitel_id', 'get_gruzootpravitel_list', 'select_gruzootpravitel', 'change_one_gruzzotpravitel', 'addOnePerevozchik', 'vid', 'gradeAddVoditel', 'voditeliComponentAddVoditel', 'gradeAddTSModal'],
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
      showTSMod: ref(false),
      openDP: false,
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

      tip: '',
      tipNazvanie: '',
      marka: '',
      nomer: '',
      tonn: '',
      sts: '',
      pts: '',
      nomer_documenta: '',
      kompaniya: '',
      kompaniyaNazvanie: '',
      doc_files: [],
      pogruzkaShowInp: true,
      headerNazvanie: ''

    }
  },

  methods: {
    formatPhone() {
      this.phoneMain = this.phoneMain.replace(/\D/g, '').slice(0, 10);
    },
    //если кликнули на нового перевозчика
    newPerevozchik(vidGrade, idPErevozchik, nazvaniePerevozchik) {
      this.showTSMod = true
      this.headerNazvanie = 'Новое ТС'
      this.current_gruzootpravitel_id = ''
      this.tip = ''
      this.marka = ''
      this.nomer = ''
      this.tonn = ''
      this.sts = ''
      this.pts = ''
      this.nomer_documenta = ''
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
      this.showTSMod = true
      this.headerNazvanie = 'Редактирование ТС'
      this.current_gruzootpravitel_id = id
      this.tip = ''
      this.marka = ''
      this.nomer = ''
      this.tonn = ''
      this.sts = ''
      this.pts = ''
      this.nomer_documenta = ''
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
          .post('/getTSModal', {
            id: this.current_gruzootpravitel_id
          })
          .then(({data}) => (
                  this.tip = data.voditel.tip,
                      this.marka = data.voditel.marka,
                      this.nomer = data.voditel.nomer,
                      this.tonn = data.voditel.tonn,
                      this.sts = data.voditel.sts,
                      this.pts = data.voditel.pts,
                      this.nomer_documenta = data.voditel.nomer_documenta,
                      this.kompaniya = data.voditel.kompaniya,
                      this.kompaniyaNazvanie = data.kompaniyaNazvanie,
                      this.tipNazvanie = data.tipNazvanie,
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
      this.showTSMod = false
    },
    cancel_modal() {
      this.delete_files_modal()
      this.hideModal()
    },

    clear_data_modal() {
      this.tip = ''
      this.marka = ''
      this.nomer = ''
      this.tonn = ''
      this.sts = ''
      this.pts = ''
      this.nomer_documenta = ''
      this.kompaniya = ''
      this.kompaniyaNazvanie = ''
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
    },
    // конец работа с файлами


    save_gruzootpravitel() {
      //работа с алертом валидации
      this.$refs.AlertListComponent.hide_alert_list()
      this.$refs.AlertListComponent.clear_alert_list()
      axios
          .post('/saveTSModal', {
            TSModalId: this.current_gruzootpravitel_id,
            tip: this.tip,
            marka: this.marka,
            nomer: this.nomer,
            tonn: this.tonn,
            sts: this.sts,
            pts: this.pts,
            nomer_documenta: this.nomer_documenta,
            kompaniya: this.kompaniya,
            doc_files: this.doc_files
          })
          .then(response => {
            //если вид grade
            if (this.vid == 'grade') {
              const targetPerevozkaID = (response.data.state === 'new') ? response.data.perevozkaID : this.current_gruzootpravitel_id;
              this.gradeAddTSModal(targetPerevozkaID, this.nomer);
              // //вызвать метод из вида grade сохраняий название
              // this.gradeAddTSModal(response.data.perevozkaID,this.nomer)
            }
            if (this.vid == 'settings') {
              //если редактируем ТС номер
              if (this.current_gruzootpravitel_id) {
                this.voditeliComponentAddVoditel(true, this.current_gruzootpravitel_id, this.nomer)
              }
              //если добавляем нового
              else {
                this.voditeliComponentAddVoditel(false, response.data.perevozkaID, this.nomer)
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
    parentMethodFromAutoinputVidts(data) {
      this.tip = data.id
    }
    ,
    parentMethodFromAutoinputPerevozka(data) {
      this.kompaniya = data.id
    }
  }
}
</script>
