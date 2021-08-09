<script>
export default {
    props: ['name', 'anonymous', 'userId'],
    data() {
        return {
            temp_shown: false,
            deanon_user_name: "",
        };
    },
    computed: {
        show_deanon_button() {
            return this.anonymous && this.$store.state.user.role==='teacher';
        },
        display_user_name() {
            if (this.anonymous) {
                return "Anonymous";
            }
            return this.name;
        },
        deanon_label() {
            return this.temp_shown ? this.deanon_user_name : "";
        }
    },
    methods: {
        handle_deanon_click() {
            this.$store.dispatch('deanonUserId', {
                user_id: this.userId,
            }).then((user_info) => {
                this.deanon_user_name = user_info.name
                this.temp_shown = true
                window.setTimeout(() => {
                    this.temp_shown = false;
                    this.deanon_user_name = ""
                }, 3000);
            });
        }
    }
}
</script>

<template>
    <span>
        {{display_user_name}}
        <span class="anon" v-if="show_deanon_button">
            <font-awesome-icon
                icon="caret-square-right"
                class="icon"
                @click.prevent="handle_deanon_click"
                size="lg"
                title="Click to show this person's name"
                v-if="this.temp_shown === false"/>
            <span :class="this.deanon_user_name === '' ? 'd-none' : 'deanon-label'">{{ deanon_label }}</span>
        </span>

    </span>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

</style>
