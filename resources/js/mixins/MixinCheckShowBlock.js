export default {
    data() {
        return {
            msg: 'Hello World',
        }
    },

    methods: {
//проверка показывать ли полльзователю блок
        //users_permissions_list список id 1 - админ 2 - logist кому показывать блок
        checkRolePermissionMixin(users_permissions_list)
        {
            let permission=2;

            //перебор юзеров
            let role=0;
            let flag=false;
            for(var j = 0; j < users_permissions_list.length; j++) {
                role=users_permissions_list[j]
                if((role===this.role)&&((this.permissions.includes(permission))||(this.permissions.includes(1)))) {
                    flag = true;
                }
            }
            return flag
        },


    },
}
