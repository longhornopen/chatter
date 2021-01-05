<script>
import moment from 'moment'
export default {
    props: ['dateIso'],
    data() {
        return {
        };
    },
    computed: {
        formatted_date() {
            return this.dateIso + "**";
        },
        convert_date() {
            // return moment(this.dateIso).format('YYYY-MM-DD hh:mm A')
            let date = new Date(this.dateIso).toLocaleDateString()
            let time = new Date(this.dateIso).toLocaleTimeString([], {
                timeStyle: 'short'
            })

            return date + ' ' + time
        }
    },
}
</script>

<template>
        <span>
            {{ convert_date }}  
        </span>
</template>
