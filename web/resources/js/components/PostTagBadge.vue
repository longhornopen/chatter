<script>
import { useMainStore } from '@/store'

export default {
    setup() {
        const store = useMainStore()
        return { store }
    },
    props: {
        post_tag_uuid: {
            type: String,
        },
    },
    computed: {
        _post_tag() {
            return this.store.course_summary.post_tags
                .find(t => t.uuid === this.post_tag_uuid)
        }
    },
}
</script>

<template>
    <span v-if="_post_tag">
        <span :style="'background-color:'+_post_tag.bgcolor" class="badge">{{_post_tag.name}}</span>
    </span>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
