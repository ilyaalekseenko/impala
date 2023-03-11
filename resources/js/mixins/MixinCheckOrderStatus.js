export default {
    data() {
        return {
            msg: 'Hello World',
        }
    },

    methods: {
//проверка показывать ли полльзователю блок
        //users_permissions_list список id 1 - админ 2 - logist кому показывать блок
        checkOrderStatusNameMixin(orderId)
        {
            axios
                .post('/checkOrderStatusName',{
                    orderId:orderId,
                })
                .then(response => {
                    console.log(response)
                })
        },


    },
}
