export default {
    data() {
        return {

        }
    },

    methods: {
        //миксин для преобразования чисел в вид понятный компьютеру
        //в этом методе указывать переменную
            setToNumberFormat(field)
        {
            //проверяем если запятых больше чем 1 тогда выкидываем предупреждение
            let countCom=this.countCommas(this[field])
            if(countCom>1)
            {
                alert('Вы ввели число в не правильном формате. Правильный формат числа с точкой, без запятых')
                this[field]=0;
            }
            else
            {
                //убираем все буквы и пробелы, заменяем запятые на точку, убираем две и более точки подряд
                this[field] = this.replaceMultipleDots(this.removeLettersSpaces(this.replaceCommaWithDot(this[field])));
            }
            return this[field];
        },
        //в этом методе указывать данные
        setToNumberFormatData(data)
        {
            //проверяем если запятых больше чем 1 тогда выкидываем предупреждение
            let countCom=this.countCommas(data)
            if(countCom>1)
            {
                alert('Вы ввели число в не правильном формате. Правильный формат числа с точкой, без запятых')
                data=0;
            }
            else
            {
                //убираем все буквы и пробелы, заменяем запятые на точку
                data = this.replaceMultipleDots(this.removeLettersSpaces(this.replaceCommaWithDot(data)));

            }
            return data;
        },
        countCommas(str) {
            return str.match(/,/g) ? str.match(/,/g).length : 0;
        },
        removeLettersSpaces(str) {
            return str.replace(/[^0-9.]/g, ""); // заменяем все символы, не являющиеся цифрами или точкой, на пустую строку
        },
        replaceCommaWithDot(str) {
            return str.replace(/,/g, "."); // заменяем все запятые на точки
        },
        updateNumberToFormat(field,data)
        {
            data=this.setToNumberFormat(field)
        },
        replaceMultipleDots(str) {
            // Используйте регулярное выражение для поиска двух и более точек
            // и замените их на одну точку
            return str.replace(/\.{2,}/g, '.');
        },



    },
}
