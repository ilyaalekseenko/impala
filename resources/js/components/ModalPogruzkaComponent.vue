<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-10">
                <b-modal ref="my-modal" id="modal-xl" size="lg" hide-footer hide-header>
                    <div class="col-12 mod_borders_top mod_new_cargo row header_grade_mod_bot">
                        <div class="col-8">Новый грузоотправитель/грузополучатель:</div>
                        <div class="col-4 row d-flex justify-content-end">
                            <div class="col add_ts_button8 text-center" v-on:click="save_gruzootpravitel()">Сохранить</div>
                            <div class="col add_ts_button8 text-center" v-on:click="cancel_modal()">Отменить</div>
                        </div>

                        <alert-error-list-component :alert_list="alert_list" ref="AlertListComponent"></alert-error-list-component>

                    </div>

                    <div class="col-12 no_padding_right no_padding_left row mod_borders_bottom">
                        <!--                        начало левой колонки модалка-->
                        <div class="col-6">
                            <div class="container-fluid">
                                <div class="col-12 row">
                                    <div class="col-3 no_padding_left_form">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Форма</div>
                                        <input class="border_input col-12"
                                               v-model="forma"/>
<!--                                        <select v-model="forma" class="col-12 create_orders_date_title_int_1 grade_marg_bot">-->
<!--                                            <option v-for="(forma_one) in forma_list" v-bind:value=forma_one.id  class="sel_cust">{{ forma_one.forma_name }}</option>-->
<!--                                        </select>-->
                                    </div>
                                    <div class="col-5 grade_naz">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12">Название</div>
                                        <input class="border_input col-12"
                                               v-model="nazvanie"/>
                                    </div>
                                    <div class="col-3 date_width_col no_padding_left_form grade_marg_bot">
                                        <div class="create_orders_date_title_1 lit_marg_grade col-12 no_wrap_text">Дата регистрации</div>
                                        <input class="border_input date_width col-12" @click="openDB0"
                                               v-model="data_registracii"/>
                                        <date-picker ref="datepicker0"  v-model="data_registracii" valueType="format" format="DD.MM.YYYY" :open.sync="openDP" ></date-picker>

                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid ">
                                <div class="col-12 row">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot grade_marg_top">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade ">Для автозаполнения введите ИНН {{ founded }}</div>
                                        <input @blur="get_INN_api()" class="col-12 border_input inn_width"
                                               v-model="INN"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade no_wrap_text ">ОГРН(если есть)</div>
                                        <input class="col-12 border_input inn_width"
                                               v-model="OGRN"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input class="col-12 border_input inn_width"
                                               v-model="telefon"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Генеральный
                                        директор
                                    </div>
                                    <input  class="col-12 border_input "
                                           v-model="generalnii_direktor"/>
                                </div>
                                <div class="offset-1 col-4">
                                    <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                    <input  class="col-12 border_input"
                                           v-model="telefon_gen_dir"/>
                                </div>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Контакты:</div>
                            <div class="container-fluid ">
                                <div class="col-12 row" v-for="(oplata,key) in kontakty">
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Должность</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].dolznost"/>
                                    </div>
                                    <div class="col-3 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">ФИО</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].FIO"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">Телефон</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].telefon"/>
                                    </div>
                                    <div class="col-2 inn_width no_padding_left_form inn_mar_r grade_marg_bot">
                                        <div class="col-12 create_orders_date_title_1 lit_marg_grade">email</div>
                                        <input  class="col-12 border_input inn_width"
                                               v-model="kontakty[key].email"/>
                                    </div>
                                    <button type="button" class="col-1 btn btn-danger btn_del_in_ord1" v-on:click="delete_adres_pogr(key)">-</button>
                                </div>
                            </div>
                            <div class="col-12"><span v-on:click="dobavit_kontank()" class="add_cont_grade">Добавить контакт</span></div>
                        </div>
                        <!--                        конец левой колонки модалка-->
                        <!--                        начало правой колонки модалка-->
                        <div class="col-6">
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade ">Юридический адрес:</div>
                                <input  class="col-12 border_input"
                                       v-model="yridicheskii_adres"/>
                            </div>
                            <div class="col-12 grade_marg_bot">
                                <div class="col create_orders_date_title_1 lit_marg_grade">Почтовый адрес:</div>
                                <input  class="col-12 border_input"
                                       v-model="pochtovyi_adres"/>
                            </div>
                            <div class="col-12 grade_title_lit cont_header">Банковские реквизиты:</div>
                            <span v-for="(bank,key) in bank_arr">
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">БИК банка:</div>
                                    <input  class="col-12 border_input"
                                           v-model="bank_arr[key].BIK"
                                            @blur="get_BIK_BANK_api(key)"
                                    />
                                </div>
                                <div class="col-6 bank_grade_marg grade_marg_bot">
                                    <span class="bank_grade ">Банк:</span>
                                    <span class="bank_grade_1 ">{{ bank_arr[key].bank }}</span>
                                </div>
                            </div>
                            <div class="col-12 row">
                                <div class="col-6 grade_marg_bot">
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Расчётный счёт:</div>
                                    <input  class="col-12 border_input grade_marg_bot"
                                           v-model="bank_arr[key].raschetnyi"/>
                                    <div class="col create_orders_date_title_1 lit_marg_grade">Корсчёт:</div>
                                    <div  class="col-12 ">
                                        {{ bank_arr[key].korschet }}
                                    </div>
                                <button type="button" class="col-1 btn btn-danger btn_del_in_ord1" v-on:click="delete_bank(key)">-</button>
                                </div>
                                <div class="col-6 grade_marg_bot">
                                    <span class="bank_grade ">Комментарий:</span>
                                    <textarea class="comm_settings"  v-model="bank_arr[key].kommentarii"
                                              rows="6" name="text"></textarea>
                                </div>
                            </div>
                                </span>
                            <div class="col-12 " ><span v-on:click="dobavit_bank()" class="add_cont_grade">Добавить банковские реквизиты</span> </div>
                            <div class="col-12 row modal_underline_right"></div>

                            <input hidden="true" type="file" id="files" ref="files" v-on:change="handleFilesUpload()"/>
                            <div class="col-12 grade_title_lit cont_header_1">Файлы:</div>
                            <div class="cont_header_2" v-for="(loc_file,key) in doc_files">
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
                                </div>
                                <div class="col-6">
                                    <span class="choose_file_grade " v-on:click="addFiles(key)">Загрузить файл</span>
                                    <span class="excel_set" v-if="loc_file.file_name" v-on:click="download_modal_file(key)">
                                <span class="iconify" data-icon="material-symbols:sim-card-download-outline-rounded" style="color: #4d4d4d;" data-width="24" data-height="24"></span>
                                </span>
                                </div>
                            </div>
                            </div>
                            <div class="col-12 add_cont_grade cont_header_2" v-on:click="dobavit_doc()">Добавить документ</div>
                        </div>
                    </div>
                </b-modal>

            </div>
        </div>
    </div>
</template>

<script>

import vClickOutside from 'v-click-outside'
    export default {
        props: ['edit_flag','gruzootpravitel_id','get_gruzootpravitel_list','select_gruzootpravitel','change_one_gruzzotpravitel'],
        directives: {
            vClickOutside: vClickOutside.directive
        },
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
                forma:'',
                forma_list:[],
                nazvanie:'',
                data_registracii:'',
                INN:'',
                OGRN:'',
                telefon:'',
                email:'',
                generalnii_direktor:'',
                telefon_gen_dir:'',
                yridicheskii_adres:'',
                pochtovyi_adres:'',
                menedzer_zakazchik:'',
                kontakty:[],
                openDP:false,
                bank_arr:[],
                files:[],
                doc_files:[],
                alert_arr:[],
                alert:false,
                //совпадает с ключём в массиве,аккуратно
                temp_file_id:'',
                current_opened_inp:'',
                founded:'',
                //переменные редактирования
                current_gruzootpravitel_id:'',

                alert_list: [],
                show_alert:false,

            }},
        methods: {
            //методы редактирования
            //общий метод загрузки тартовы данны
            //передаем из предыде ида
            get_modal_edit_data(id)
            {
                if(this.edit_flag)
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
                        this.openDP=false,
                        this.bank_arr=[],
                        this.files=[],
                        this.doc_files=[],
                        this.alert_arr=[],
                        this.alert=false,
                        this.temp_file_id='',
                        this.current_opened_inp=''

                    this.current_gruzootpravitel_id=id
                    this.get_gruzootpravitel_modal(this.kontakty, this.bank_arr, this.doc_files)

                }
            },
            //получаем стартовые данные
            get_gruzootpravitel_modal(kontakty,bank_arr,doc_files) {
                axios
                    .post('/get_gruzootpravitel_modal',{
                        id:this.current_gruzootpravitel_id
                    })
                 .then(({ data }) => (
                         this.forma=data.gruzootpravitel.forma_id,
                         this.nazvanie=data.gruzootpravitel.nazvanie,
                         this.data_registracii=data.gruzootpravitel.data_registracii,
                         this.INN=data.gruzootpravitel.INN,
                         this.OGRN=data.gruzootpravitel.OGRN,
                         this.telefon=data.gruzootpravitel.telefon,
                         this.email=data.gruzootpravitel.email,
                         this.generalnii_direktor=data.gruzootpravitel.generalnii_direktor,
                         this.telefon_gen_dir=data.gruzootpravitel.telefon_gen_dir,
                         this.yridicheskii_adres=data.gruzootpravitel.YR_adres,
                         this.pochtovyi_adres=data.gruzootpravitel.pochtovyi_adres,

                             data.gruzootpravitel_contact.forEach(function(entry) {
                                     kontakty.push({
                                         dolznost:entry.dolznost,
                                         FIO : entry.FIO,
                                         telefon : entry.telefon,
                                         email : entry.email,
                                 });
                             }),
                             data.gruzootpravitel_banks.forEach(function(entry) {
                                 bank_arr.push({
                                     BIK:entry.BIK,
                                     raschetnyi : entry.raschetnyi,
                                     korschet : entry.korschet,
                                     kommentarii : entry.kommentarii,
                                     bank : entry.bank,

                                 });
                             }),
                             data.gruzootpravitel_files.forEach(function(entry) {
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
            get_INN_api()
            {
                this.founded=''
                axios
                    .post('/get_INN_api',{
                        INN:this.INN
                    })
                    .then(response => {
                        // console.log(response.data.status);
                        if (response.data.status === 'success') {
                            this.forma=response.data.forma,
                            this.nazvanie=response.data.nazvanie,
                            this.data_registracii=response.data.data_registracii,
                            this.OGRN=response.data.ogrn,
                            this.telefon=response.data.telefon,
                            this.email=response.data.email,
                            this.generalnii_direktor=response.data.generalnii_direktor,
                            this.yridicheskii_adres=response.data.yridicheskii_adres,
                            this.pochtovyi_adres=response.data.pochtovyi_adres

                        }
                        else
                        {
                            console.log(response.data.status);

                            this.founded='Юр лицо не найдено'
                              //  this.bank_arr[key]['korschet']=''
                        }
                    })
            },
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
            get_BIK_BANK_api(key)
            {
                axios
                    .post('/get_BIK_BANK_api',{
                            bank:this.bank_arr[key]['BIK']
                    })
                    .then(response => {
                        // console.log(response.data.status);
                        if (response.data.status === 'success') {
                            this.bank_arr[key]['bank']=response.data.bank_name,
                            this.bank_arr[key]['korschet']=response.data.korschet
                        }
                        else
                        {
                            this.bank_arr[key]['bank']='Банк не найден',
                            this.bank_arr[key]['korschet']=''
                        }
                    })
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
                // axios
                //     .post('/delete_one_file_modal',{
                //         doc:this.doc_files[key]
                //     })
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
            delete_adres_pogr(key)
            {
                this.kontakty.splice(key,1)
            },
            delete_bank(key)
            {
                this.bank_arr.splice(key,1)
            },
            dobavit_kontank()
            {
                let objToPush= {};
                objToPush['id'] = '';
                objToPush['dolznost'] = '';
                objToPush['FIO'] = '';
                objToPush['telefon'] = '';
                objToPush['email'] = '';
                this.kontakty.push(objToPush);
            },
            dobavit_bank()
            {
                let objToPush= {};
                objToPush['BIK'] = '';
                objToPush['raschetnyi'] = '';
                objToPush['korschet'] = '';
                objToPush['kommentarii'] = '';
                objToPush['bank'] = '';
                this.bank_arr.push(objToPush);
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
                    .post('/save_gruzootpravitel',{
                        current_gruzootpravitel_id:this.current_gruzootpravitel_id,
                        forma:this.forma,
                        nazvanie:this.nazvanie,
                        data_registracii:this.data_registracii,
                        INN:this.INN,
                        OGRN:this.OGRN,
                        telefon:this.telefon,
                        email:this.email,
                        generalnii_direktor:this.generalnii_direktor,
                        telefon_gen_dir:this.telefon_gen_dir,
                        yridicheskii_adres:this.yridicheskii_adres,
                        pochtovyi_adres:this.pochtovyi_adres,
                        menedzer_zakazchik:this.menedzer_zakazchik,
                        kontakty:this.kontakty,
                        bank_arr:this.bank_arr,
                        doc_files:this.doc_files
                    })
                    .then(response => {
                        if(this.get_gruzootpravitel_list)
                        {
                            this.get_gruzootpravitel_list()

                            this.select_gruzootpravitel()
                        }
                        if(this.change_one_gruzzotpravitel)
                        {
                            this.change_one_gruzzotpravitel(this.current_gruzootpravitel_id,this.nazvanie,this.yridicheskii_adres,this.kontakty)
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
