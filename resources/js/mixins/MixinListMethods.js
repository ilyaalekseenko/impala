export default {
    data() {
        return {
            msg: 'Hello World',
        }
    },
  //  created: function () {
  //      console.log('Printing from the Mixin')
  //  },
    methods: {
        mix_mark_as_important(list,model)
        {
            this.checked_all=false
            for( let i = 0; i < list.length; i++ )
            {
                if(list[i].checked_order==true)
                {
                    list[i].checked_order=false
                    if(list[i].important==1)
                    {
                        list[i].important=null
                    }
                    else
                    {
                        list[i].important=1
                    }
                    this.delete_arr.push(list[i].id)
                }
            }
            //перерисуем массив
            let imp_arr=[];
            let not_imp_arr=[];
            for( let i = 0; i < list.length; i++ )
            {
                if(list[i].important==1)
                {
                    imp_arr.push(list[i])
                }
                else
                {
                    not_imp_arr.push(list[i])
                }
            }
            list=[];
            for( let i = 0; i < imp_arr.length; i++ )
            {
                list.push(imp_arr[i])
            }
            for( let i = 0; i < not_imp_arr.length; i++ )
            {
                list.push(not_imp_arr[i])
            }

            axios
                .post('/important_mark',{
                    orders_id:this.delete_arr,
                    model:model,
                })
            this.delete_arr=[]
        },

    },
}
