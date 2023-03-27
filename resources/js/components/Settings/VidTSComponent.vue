<template>
    <div class="container">
        <div>Виды ТС</div>
        <div class="row col-10 justify-content-start">
            <div class="col-8 ad_pogr_marg1" v-for="(mainOne,key) in mainArr">
                <textarea class="textar_settings border_input" @blur="updateOne(mainOne.id,key)" v-model="mainArr[key]['ts_name']"  />
                <button type="button" class="btn btn-danger btn_del_in_ord bottom_marg" v-on:click="delete_perevozka_settings(mainOne.id,key)">-</button>

            </div>
            <div class="col-2 add_settings_doc_button">
            <button type="button" class=" btn btn-success" v-on:click="add_perevozka_settings()">+</button>
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
                    .post('/get_vid_TS_list',{
                        offset:this.offset_from_start,
                        limit:this.limit,
                    })
                    .then(({ data }) => (

                        this.countAllTypes=data.tipesCount,
                            data.ts.forEach(function(entry) {
                                inp.push({
                                    id:entry.id,
                                    ts_name:entry.ts_name
                                });
                            })
                        )
                    );
            },
            updateOne(vidTSId,key)
            {
                if(this.mainArr[key]['ts_name']!=='')
                {
                axios
                    .post('/update_vid_TS',{
                        vidTSId:vidTSId,
                        ts_name:this.mainArr[key]['ts_name'],
                    })
                }
            },
            delete_perevozka_settings(vidTSId,key)
            {
                axios
                    .post('/delete_vid_TS',{
                        vidTSId:vidTSId,
                    })
                this.mainArr.splice(key,1)

            },
            add_perevozka_settings()
            {
                let objToPush= {};
                objToPush['id'] ='';
                objToPush['ts_name'] ='';
                this.mainArr.push(objToPush);
                axios
                    .post('/create_vid_TS',{
                    })
                    .then(({ data }) => (
                        this.mainArr[this.mainArr.length-1].id=data.id
                        )
                    );

            },
            dataFromPagination(offset)
            {
                this.offset_from_start=offset
                this.mainArr=[];
                this.getTSlist(this.mainArr)
            }
        }

    }
</script>
