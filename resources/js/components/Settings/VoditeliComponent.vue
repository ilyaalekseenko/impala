<template>
    <div class="container">
        <modal-voditel-component
            ref="modalComponentforActionVoditel"
            vid="settings"
            :voditeliComponentAddVoditel='voditeliComponentAddVoditel'
        ></modal-voditel-component>
        <div>Список водителей
        <span class="col add_button_perevozchiki no_wrap_text" v-b-modal.voditelMod variant="primary" v-on:click="newVoditel()">Добавить</span>
        </div>
    <div class="row col-10 justify-content-start">
            <div class="col-8 row ad_pogr_marg1" v-for="(mainOne,key) in mainArr">
                <div class="col-10 voditeli_list_FIO" v-b-modal.voditelMod variant="primary" v-on:click="show_mod_edit(mainOne.id)">{{ mainArr[key]['FIO'] }}</div>
                <button type="button" class="btn btn-danger btn_del_in_ord col-2" v-on:click="deleteFromSettings(mainOne.id,key)">-</button>
            </div>
            <div class="col-2 add_settings_doc_button">
<!--            <button type="button" class=" btn btn-success" v-on:click="add_perevozka_settings()">+</button>-->
            </div>
    </div>
        <nav class="col d-flex justify-content-end"  aria-label=" Page navigation example">
            <pagination-component
                :countAllTypes="countAllTypes"
                :countLimit="limit"
                :dataFromPagination="dataFromPagination"
                ref="PaginationComponent"
            ></pagination-component>
        </nav>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.limit=10
           this.getTSlist(this.mainArr)

        },
        data() {
            return {
                mainArr:[],
                countAllTypes:0,
                offset_from_start:0,
                limit:0,
            }
        },
        methods: {
            getTSlist(inp)
            {
                axios
                    .post('/getVoditeliList',{
                        offset:this.offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (

                        this.countAllTypes=data.tipesCount,
                            data.voditelList.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    FIO:entry.FIO
                                });
                            })
                        )
                    );
            },
            newVoditel()
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionVoditel.newPerevozchik()
            },
            show_mod_edit(id)
            {
                //вызов метода дочернего компонента( модального окна )
                this.$refs.modalComponentforActionVoditel.get_modal_edit_data(id)
            },
            deleteFromSettings(id,key)
            {
                axios
                    .post('/deleteFromSettings',{
                        id:id,
                        filesModel:'VoditelFiles',
                        searchColumn:'voditel_id',
                        searchColumnGrade:'voditel',
                        mainModel:'Voditel',
                    })
                this.mainArr.splice(key,1)

            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.mainArr=[];
                this.getTSlist(this.mainArr)
            },
            //метод вызываемый из модального окна
            voditeliComponentAddVoditel(isEdit,id,FIO)
            {
                //если редактируем водителя
                if(isEdit)
                {
                    for (let i = 0; i < this.mainArr.length; i++)
                    {
                        if(this.mainArr[i]['id']==id)
                        {
                            this.mainArr[i]['FIO']=FIO
                        }
                    }
                }
                else
                {
                    let objToPush= {};
                    objToPush['id'] =id;
                    objToPush['FIO'] =FIO;
                    this.mainArr.push(objToPush);
                }

            }
        }

    }
</script>
