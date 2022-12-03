<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link nav_menu_pointer" aria-current="page" v-bind:class="{ active : nav_menu_0}" v-on:click="nav_menu_show(0)">Документы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav_menu_pointer"  v-bind:class="{ active : nav_menu_1}" v-on:click="nav_menu_show(1)">Ещё чего-то</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav_menu_pointer"  v-bind:class="{ active : nav_menu_2}" v-on:click="nav_menu_show(2)">И ещё чего-то</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav_menu_pointer"  v-bind:class="{ active : nav_menu_3}" v-on:click="nav_menu_show(3)">Ну и тут чего-то</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="row justify-content-center" v-if="nav_menu_0">
            <div  class="col-10 row justify-content-end trio_but">
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('TH')">Загрузить шаблон ТН</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('TH')">Показать переменные ТН</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('TH')">Скачать шаблон ТН</div>
                <div class="doc_set_marg">{{ TH_name }}</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('DOV')">Загрузить шаблон Доверенность</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('DOV')">Показать переменные Доверенность</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('DOV')">Скачать шаблон Доверенность</div>
                <div class="doc_set_marg">{{ DOV_name }}</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="store_doc('ZAI')">Загрузить шаблон Заявка</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="show_vars_func('ZAI')">Показать переменные Заявка</div>
                    <div class="col add_ts_button5 text-center doc_set_button" v-on:click="download_current_doc('ZAI')">Скачать шаблон Заявка</div>
                <div class="doc_set_marg">{{ ZAI_name }}</div>
                <input hidden="true" type="file" id="files_doc" ref="files_doc"  v-on:change="handleFilesUploadDoc()"/>
            </div>
            <div>
                В формате xlsx переменную указывать в виде {переменная} без пробелов в скобках. Формат xlsx обязателен, xls устаревший и приводит к не корректной работе. Перевеодить прямо в excel редакторе, без посторонних сайтов.
            </div>
            <div>
                В формате docx переменную указывать в виде { переменная } .
            </div>
            <div class="col-12" v-if="show_TH_vars">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Переменная</th>
                        <th scope="col">Что означает</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(group, index) in arr_TH_vars">
                        <th scope="row">{{ index+1 }}</th>
                        <td>{{ group.var_name }}</td>
                        <td>{{ group.description }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12" v-if="show_DOV_vars">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Переменная</th>
                        <th scope="col">Что означает</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(group, index) in arr_DOV_vars">
                        <th scope="row">{{ index+1 }}</th>
                        <td>{{ group.var_name }}</td>
                        <td>{{ group.description }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-12" v-if="show_ZAI_vars">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Переменная</th>
                        <th scope="col">Что означает</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(group, index) in arr_ZAI_vars">
                        <th scope="row">{{ index+1 }}</th>
                        <td>{{ group.var_name }}</td>
                        <td>{{ group.description }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.get_templ_names()
            this.get_template_vars(this.arr_TH_vars,this.arr_DOV_vars,this.arr_ZAI_vars)
        },
        data() {
            return {
                doc_type:'',
                TH_name:'',
                DOV_name:'',
                ZAI_name:'',
                arr_TH_vars:new Array(),
                arr_DOV_vars:new Array(),
                arr_ZAI_vars:new Array(),
                show_TH_vars:false,
                show_DOV_vars:false,
                show_ZAI_vars:false,
                nav_menu_0:false,
                nav_menu_1:false,
                nav_menu_2:false,
                nav_menu_3:false,
            }
        },
        methods: {
            download_current_doc(doc_type)
            {
                this.current_doc=doc_type

                axios
                    .post('/download_current_doc',{
                        doc_type:this.current_doc

                    })
                    .then(response => {

                        window.location.assign('/get_finall_doc_pdf_file/templates/'+response.data.file) ;

                    })
            },
            nav_menu_show(numb)
            {
               if(numb==0)
               {
                 this.all_nav_false()
                 this.nav_menu_0=true
               }
                if(numb==1)
                {
                    this.all_nav_false()
                    this.nav_menu_1=true
                }
                if(numb==2)
                {
                    this.all_nav_false()
                    this.nav_menu_2=true
                }
                if(numb==3)
                {
                    this.all_nav_false()
                    this.nav_menu_3=true
                }

            },
            all_nav_false()
            {
                this.nav_menu_0=false,
                this.nav_menu_1=false,
                this.nav_menu_2=false,
                this.nav_menu_3=false
            },
            show_vars_func(type)
            {
                if(type=='TH')
                {
                    this.show_TH_vars=!this.show_TH_vars
                    this.show_DOV_vars=false
                    this.show_ZAI_vars=false
                }
                if(type=='DOV')
                {
                    this.show_DOV_vars=!this.show_DOV_vars
                    this.show_TH_vars=false
                    this.show_ZAI_vars=false
                }
                if(type=='ZAI')
                {
                    this.show_ZAI_vars=!this.show_ZAI_vars
                    this.show_DOV_vars=false
                    this.show_TH_vars=false
                }
            },
            get_template_vars(arr_TH_vars,arr_DOV_vars,arr_ZAI_vars)
            {
                axios
                    .post('/get_template_vars',{

                    })
                    .then(({ data }) => {
                        data.templ_vars1.forEach(function(entry) {
                            arr_TH_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                        data.templ_vars2.forEach(function(entry) {
                            arr_DOV_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                        data.templ_vars3.forEach(function(entry) {
                            arr_ZAI_vars.push({
                                id:entry.id,
                                var_name:entry.var_name,
                                description:entry.description,
                                doc_type:entry.doc_type,
                            });
                        })
                    })
            },
            get_templ_names()
            {
                axios.post( '/get_templ_names',

                )
                    .then(response => {
                    this.TH_name=response.data.TH
                    this.DOV_name=response.data.DOV
                    this.ZAI_name=response.data.ZAI
                    })
            },
            store_doc(type)
            {
                this.doc_type=type
                this.$refs.files_doc.click();
            },
            handleFilesUploadDoc() {
                let full_name = '';
                let ext = '';
                let uploadedFiles = this.$refs.files_doc.files;
                let flag = 0;
                ext = (uploadedFiles[0].name.match(/\.[0-9a-z]+$/i))
                if (this.doc_type == 'ZAI') {
                    if (ext[0] == '.doc' || ext[0] == '.docx' || ext[0] == '.txt') {
                        flag = 1;
                    }
                }
                if ((this.doc_type == 'DOV') || (this.doc_type == 'TH')) {
                    if (ext[0] == '.xls' || ext[0] == '.xlsx') {
                        flag = 1;
                    }

                }
                if (flag == 1)
                {
                    full_name = (uploadedFiles[0].name.match(/([A-Za-zа-яА-Я0-9Ёё\W]+)/))

                    if(this.doc_type == 'TH')
                    {
                        if((full_name[0]==this.DOV_name)||(full_name[0]==this.ZAI_name))
                        {
                            flag=0;
                        }
                    }
                    if(this.doc_type == 'DOV')
                    {
                        if((full_name[0]==this.TH_name)||(full_name[0]==this.ZAI_name))
                        {
                            flag=0;
                        }
                    }
                    if(this.doc_type == 'ZAI')
                    {
                        if((full_name[0]==this.DOV_name)||(full_name[0]==this.TH_name))
                        {
                            flag=0;
                        }
                    }

                    if (flag == 1)
                    {
                    let formData = new FormData();
                let file = uploadedFiles;
                formData.append('file_xlsx', file[0]);
                formData.append('doc_type', this.doc_type);

                formData.append('full_name', full_name[0]);
                axios.post('/store_doc_templ',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                    .then(response => {
                        if(response.data.status=='success')
                        {
                            if(this.doc_type== 'TH')
                            {
                                this.TH_name=full_name[0]
                            }
                            if(this.doc_type== 'DOV')
                            {
                                this.DOV_name=full_name[0]
                            }
                            if(this.doc_type== 'ZAI')
                            {
                                this.ZAI_name=full_name[0]
                            }
                        }
                    })
                    }
                    else {
                        alert('Такое имя файла уже существует')
                    }
                 }
                else
                {
                    alert('не верный формат файла')
                }
            },
        }

    }
</script>
