<script>
import { useMainStore } from '@/store'

export default {
    setup() {
        const store = useMainStore()
        return { store }
    },
    props: {
        post_tag_name: {
            type: String,
        },
        post_tag: {
            type: Object,
        }
    },
    computed: {
        _post_tag() {
            if (this.post_tag) {
                return this.post_tag
            }
            return this.store.course_summary.post_tags
                .find(t => t.name === this.post_tag_name)
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
