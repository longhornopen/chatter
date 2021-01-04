<script>
export default {
    props: ['dateIso'],
    data() {
        return {
        };
    },
    computed: {
        formatted_date() {
            return this.dateIso + "**";
        }
    },
}
</script>

<template>
    <span>
        {{formatted_date}}
    </span>
</template>
