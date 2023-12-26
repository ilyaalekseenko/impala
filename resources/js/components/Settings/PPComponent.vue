<template>
    <div class="container">
        <modal-p-p-component
            ref="modalComponentforActionVoditel"
            vid="settings"
            :voditeliComponentAddVoditel='voditeliComponentAddVoditel'
        ></modal-p-p-component>
        <div>Список номеров ПП
        <span class="col add_button_perevozchiki no_wrap_text" v-b-modal.PPMod variant="primary" v-on:click="newVoditel()">Добавить</span>
        </div>
    <div class="row col-10 justify-content-start">
            <div class="col-8 row ad_pogr_marg1" v-for="(mainOne,key) in mainArr">
                <div class="col-10 voditeli_list_FIO" v-b-modal.PPMod variant="primary" v-on:click="show_mod_edit(mainOne.id)">{{ mainArr[key]['nomer'] }}</div>
                <button type="button" class="btn btn-danger btn_del_in_ord col-2" v-on:click="delete_perevozka_settings(mainOne.id,key)">-</button>
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
                    .post('/getPPList',{
                        offset:this.offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (

                        this.countAllTypes=data.tipesCount,
                            data.TSModalList.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    nomer:entry.nomer
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
          async delete_perevozka_settings(id,key)
            {
                const result = await this.confirmMethodMixin();
                if (result) {
                    axios
                        .post('/deleteFromSettings', {
                            id: id,
                            filesModel: 'PPFiles',
                            searchColumn: 'PP_id',
                            searchColumnGrade: 'nomer_PP',
                            mainModel: 'PP',
                        })
                    this.mainArr.splice(key, 1)
                }
            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.mainArr=[];
                this.getTSlist(this.mainArr)
            },
            //метод вызываемый из модального окна
            voditeliComponentAddVoditel(isEdit,id,nomer)
            {
                //если редактируем ТС номер
                if(isEdit)
                {
                    for (let i = 0; i < this.mainArr.length; i++)
                    {
                        if(this.mainArr[i]['id']==id)
                        {
                            this.mainArr[i]['nomer']=nomer
                        }
                    }
                }
                //если добавляем нового
                else
                {
                    let objToPush= {};
                    objToPush['id'] =id;
                    objToPush['nomer'] =nomer;
                    this.mainArr.push(objToPush);
                }

            }
        }

    }
</script>
