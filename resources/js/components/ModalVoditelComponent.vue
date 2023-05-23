<template>
    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-10">
                <b-modal ref="my-modal"  id="voditelMod" size="sm" hide-footer hide-header>
                    <div class="col-12 mod_borders_top mod_new_cargo row header_grade_mod_bot">
                        <div class="col-8">Новый водитель:</div>
                        <div class="col-4 row d-flex justify-content-end">
                            <div class="col add_ts_button8 text-center" v-on:click="save_gruzootpravitel()">Сохранить</div>
                            <div class="col add_ts_button8 text-center" v-on:click="cancel_modal()">Отменить</div>
                        </div>

                        <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent"></alert-error-list-component>

                    </div>

                    <div class="col-12 no_padding_right no_padding_left row mod_borders_bottom">
                        <!--                        начало левой колонки модалка-->
                        <div class="col-6">
                            <div class="container-fluid perevoz_m_bottom">
                                <div class="col-12 row">
                                    <div class="col-3 no_padding_left_form vod_fio">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">ФИО:</div>
                                        <input class="border_input col-12 "
                                               v-model="FIO"/>
                                    </div>
                                    <div class="col-2 grade_naz vod_telefon">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12">Телефон:</div>
                                        <input class="border_input col-12"
                                               v-model="telefon"/>
                                    </div>
                                    <div class="col-3 date_width_col no_padding_left_form ">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12 no_wrap_text">Дата рождения:</div>
                                        <input class="border_input date_width col-12" @click="openDB0"
                                               v-model="dataRozdeniya"/>
                                        <date-picker ref="datepicker0"  v-model="dataRozdeniya" valueType="format" format="DD.MM.YYYY" :open.sync="openDP" ></date-picker>
                                    </div>
                                    <div class="col-4 grade_naz no_padding_left_form vod_passport">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Серия и номер паспорта:</div>
                                        <input class="border_input" type="text" v-model="seriyaPassporta" />
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--                        конец левой колонки модалка-->
                        <!--                        начало правой колонки модалка-->
                        <div class="col-6">
                            <div class="container-fluid perevoz_m_bottom">
                                <div class="col-12 row">
                            <div class="col-4 vod_mar">
                                <div class="col create_orders_date_title_1 lit_marg_grade ">Кем выданс:</div>
                                <input  class="col-12 border_input"
                                       v-model="kemVydan"/>
                            </div>
                            <div class="col-3 ">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Когда выдан:</div>
                                <input  class="col-12 border_input"
                                       v-model="kogdaVydan"/>
                            </div>
                            <div class="col-5 ">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Прописка:</div>
                                <input  class="col-12 border_input"
                                        v-model="propiska"/>
                            </div>
                            <div class="col-4 ">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Перевозчик:</div>
                                <!--       <input  class="col-12 border_input"
                                               v-model="kompaniya"/>-->

                                       <auto-input-perevozka-modal-component v-if="pogruzkaShowInp" class="select_width_grade"
                                                                       :vidTsFromParent="kompaniyaNazvanie"
                                                                       @childReturnMethod="parentMethodFromAutoinputPerevozka"
                                       ></auto-input-perevozka-modal-component>

                                   </div>
                                   </div>
                                   </div>
                               </div>


       <!--                    строка загрузки файлов-->
                        <div class="col-12 grade_title_lit cont_header_vod">Загрузка файлов:</div>
                        <input hidden="true" type="file" id="files" ref="files" v-on:change="handleFilesUpload()"/>
                        <div class="container-fluid ">
                            <div class="col-12 row">
                                <div v-for="(loc_file,key) in doc_files" class="col-4 row inn_width no_padding_left_form inn_mar_r grade_marg_bot doc_vod_block">
                                    <div class="col-12 row create_orders_date_title_1 lit_marg_grade ">Название документа:</div>
                                    <div class="col-12 row" v-if="loc_file.file_name">
                                        <div class="col-12 lit_marg_grade add_button_grade_modal no_wrap_text"><span v-on:click="show_inp_doc(key)">{{ loc_file.file_name }}.{{ loc_file.ext }}</span>
                                            <iconify-icon icon="ci:off-close" style="color: #c4c4c4;" width="20"
                                                          v-if="loc_file.file_name"    height="20" v-on:click="delete_one_file_modal(key)"></iconify-icon>
                                        </div>
                                    </div>
                                    <div class="col-12 row">
                                        <div class="col-6" v-if="loc_file.show_inp">
                                            <input v-click-outside="focus_out_from_inp" class="col-12 lit_marg_grade border_input"
                                                   v-model="loc_file.file_name"/>
                                            <span class="iconify" data-icon="fa-regular:list-alt" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                        </div>
                                        <div class="col-6">
                                            <span class="choose_file_grade " v-on:click="addFiles(key)">Загрузить файл</span>
                                            <span class="excel_set" v-if="loc_file.file_name" v-on:click="download_modal_file(key)">
                                                    <span class="iconify" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                                    </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 add_vod_button1 text-center" v-on:click="dobavit_doc()">Добавить</div>
                            </div>
                        </div>
<!--                        конец строки загрузки файлов-->

                    </div>
                </b-modal>

            </div>
        </div>
    </div>
</template>

<script>

import vClickOutside from 'v-click-outside'
 import VueMask from 'v-mask'
Vue.use(VueMask)
    export default {
        props: ['edit_flag','gruzootpravitel_id','get_gruzootpravitel_list','select_gruzootpravitel','change_one_gruzzotpravitel','addOnePerevozchik','vid','gradeAddVoditel','voditeliComponentAddVoditel'],
        directives: {
            vClickOutside: vClickOutside.directive
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
                openDP:false,
                files:[],
                alert_arr:[],
                alert:false,
                //совпадает с ключём в массиве,аккуратно
                temp_file_id:'',
                current_opened_inp:'',
                //переменные редактирования
                current_gruzootpravitel_id:'',

                alert_list: [],
                show_alert:false,
                FIO:'',
                telefon:'',
                dataRozdeniya:'',
                seriyaPassporta:'',
                kemVydan:'',
                kogdaVydan:'',
                propiska:'',
                kompaniya:'',
                kompaniyaNazvanie:'',
                doc_files:[],
                pogruzkaShowInp:true
            }},

        methods: {
            formatPhone() {
                this.phoneMain = this.phoneMain.replace(/\D/g, '').slice(0, 10);
            },
            //если кликнули на нового перевозчика
            newPerevozchik(vidGrade,idPErevozchik,nazvaniePerevozchik)
            {
                this.current_gruzootpravitel_id='',
                this.FIO='',
                this.telefon='',
                this.dataRozdeniya='',
                this.seriyaPassporta='',
                this.kemVydan='',
                this.kogdaVydan='',
                this.propiska='',

                this.doc_files=[],
                this.files=[],
                this.openDP=false,
                this.alert_arr=[],
                this.alert=false,
                this.temp_file_id=''

                //если кликаем  из вида settings ну тоесть совсем новый водитель и название перевозчика(компании) не надо
                    if(!vidGrade)
                    {
                        this.kompaniya='',
                        this.kompaniyaNazvanie=''
                    }
                    else
                    {

                        this.kompaniya=idPErevozchik,
                        this.kompaniyaNazvanie=nazvaniePerevozchik

                    }


            },
            //методы редактирования
            //общий метод загрузки cтартовых данных
            //передаем из предыде вида
            get_modal_edit_data(id)
            {
                    this.current_gruzootpravitel_id=id,
                    this.FIO='',
                    this.telefon='',
                    this.dataRozdeniya='',
                    this.seriyaPassporta='',
                    this.kemVydan='',
                    this.kogdaVydan='',
                    this.propiska='',
                    this.kompaniya='',
                    this.kompaniyaNazvanie='',
                    this.doc_files=[],
                    this.files=[],
                    this.openDP=false,
                    this.alert_arr=[],
                    this.alert=false,
                    this.temp_file_id=''
                    this.getVoditeliModal( this.doc_files)

            },
            //получаем стартовые данные
            getVoditeliModal(doc_files) {
                axios
                    .post('/getVoditeliModal',{
                        id:this.current_gruzootpravitel_id
                    })
                 .then(({ data }) => (
                         this.FIO=data.voditel.FIO,
                         this.telefon=data.voditel.telefon,
                         this.dataRozdeniya=data.voditel.dataRozdeniya,
                         this.seriyaPassporta=data.voditel.seriyaPassporta,
                         this.kemVydan=data.voditel.kemVydan,
                         this.kogdaVydan=data.voditel.kogdaVydan,
                         this.propiska=data.voditel.propiska,
                         this.kompaniya=data.voditel.kompaniya,
                         this.kompaniyaNazvanie=data.kompaniyaNazvanie,
                             data.voditel_files.forEach(function(entry) {
                                 doc_files.push({
                                     id:entry.id,
                                     doc_name : entry.doc_name,
                                     doc_path : entry.server_name,
                                     file_name : entry.file_name,
                                     ext : entry.ext,
                                     show_inp : entry.show_inp
                                 });
                             })
                     )
                 );
            },
            //конец методы редактирования
            hideModal() {
                this.clear_data_modal()
                this.$refs['my-modal'].hide()
            },
            cancel_modal()
            {
                this.delete_files_modal()
                this.hideModal()
            },
            get_forma_list(inp)
            {
                axios
                    .post('/get_forma_list',{
                    })
                    .then(({ data }) => (
                            data.ts.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    forma_name:entry.forma_name,
                                });
                            })
                        )
                    );
            },
            clear_data_modal()
            {
                this.forma='',
                this.nazvanie='',
                this.data_registracii='',
                this.INN='',
                this.OGRN='',
                this.telefon='',
                this.email='',
                this.generalnii_direktor='',
                this.telefon_gen_dir='',
                this.yridicheskii_adres='',
                this.pochtovyi_adres='',
                this.menedzer_zakazchik='',
                this.kontakty=[],
                this.adresa=[],
                this.openDP=false,
                this.bank_arr=[],
                this.files=[],
                this.doc_files=[],
                this.alert_arr=[],
                this.alert=false,
                this.temp_file_id='',
                this.current_opened_inp=''
            },
            delete_one_file_modal(key)
            {
                this.doc_files.splice(key, 1);

            },
            delete_files_modal()
            {
                axios
                    .post('/delete_files_modal',{
                        doc_files:this.doc_files
                    })
            },

            openDB0()
            {
                this.openDP=true
            },


            show_inp_doc(key)
            {
                this.current_opened_inp=key
                this.doc_files[key]['show_inp']=true
            },
            focus_out_from_inp()
            {
                this.doc_files[this.current_opened_inp]['show_inp']=false
                this.current_opened_inp=''
            },


            // работа с файлами
            dobavit_doc()
            {
                let objToPush= {};
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
            download_modal_file(key)
            {
                window.location.href = '/download_modal_file/'+this.doc_files[key]['doc_path'];
            },
            addFiles(key){
                this.temp_file_id=key
                this.$refs.files.click();
            },
            handleFilesUpload()
            {
                let uploadedFiles = this.$refs.files.files;
                let full_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)/))
                let file_name=(uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё@ : , ' '' ; - _ = < > % # ~ `& !\W]+)\./))
                let extension=(uploadedFiles[0].name.slice((Math.max(0, (uploadedFiles[0].name.lastIndexOf(".")) || Infinity) + 1)));
                let formData = new FormData();
                let file = uploadedFiles;
                formData.append('file', file[0]);
                formData.append('file_name',file_name[1]);
                formData.append('full_name',full_name[0]);
                formData.append('extension',extension);
                axios.post( '/store_modal_file_temp',
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
                            this.doc_files[this.temp_file_id].doc_path=response.data.path
                            this.doc_files[this.temp_file_id].doc_name=full_name[0]
                            this.doc_files[this.temp_file_id].file_name=file_name[1]
                            this.doc_files[this.temp_file_id].ext=extension
                        }
                        else
                        {
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


            save_gruzootpravitel()
            {
                //работа с алертом валидации
                this.$refs.AlertListComponent.hide_alert_list()
                this.$refs.AlertListComponent.clear_alert_list()
                axios
                    .post('/saveVoditel',{
                        voditelId:this.current_gruzootpravitel_id,
                        FIO:this.FIO,
                        telefon:this.telefon,
                        dataRozdeniya:this.dataRozdeniya,
                        seriyaPassporta:this.seriyaPassporta,
                        kemVydan:this.kemVydan,
                        kogdaVydan:this.kogdaVydan,
                        propiska:this.propiska,
                        kompaniya:this.kompaniya,
                        doc_files:this.doc_files,

                    })
                    .then(response => {
                        //если вид grade
                        if(this.vid=='grade')
                        {
                            //вызвать метод из вида grade сохраняий название
                            this.gradeAddVoditel(response.data.perevozkaID,this.FIO)
                        }
                        if(this.vid=='settings')
                        {
                            //если редактируем водителя
                            if(this.current_gruzootpravitel_id)
                            {
                                this.voditeliComponentAddVoditel(true,this.current_gruzootpravitel_id,this.FIO)
                            }
                            //если добавляем нового
                            else
                            {
                                this.voditeliComponentAddVoditel(false,response.data.perevozkaID,this.FIO)
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
            parentMethodFromAutoinputPerevozka(data)
            {
                this.kompaniya=data.id
            }
        }
    }
</script>
