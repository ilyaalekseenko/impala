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
       // displayMessage: function () {
        //    console.log('Now printing from a mixin function')
      //  },
        search_gruzootpravitel_autocomplite(input)
        {
            return new Promise(resolve => {
                if (input.length < 3) {
                    return resolve([])
                }
                axios
                    .post('/get_gruzootpravitel_list_atocomplite',{
                        req:input
                    }).then(response => {
                    resolve(response.data)
                })
            })
        },
        getResultValue_autocomplite(result) {
            return result.nazvanie
        },
        handleSubmit_autocomplite(result)
        {

            //результат забирать отсюда
            this.adres_pogruzke= result.nazvanie
            this.adres_pogruzke=result.id
        }

    },
}
