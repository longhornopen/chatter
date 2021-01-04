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
                return "(anonymous)";
            }
            return this.name;
        },
        deanon_label() {
            return this.temp_shown ? this.deanon_user_name : "?";
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
                }, 3000);
            });
        }
    }
}
</script>

<template>
    <span>
        {{display_user_name}}
        <button
            class="btn btn-sm btn-secondary"
            v-if="show_deanon_button"
            @click.prevent="handle_deanon_click"
            title="Click to show this person's name"
        >{{ deanon_label }}</button>
    </span>
</template>
