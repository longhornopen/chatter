<script>
export default {
    data() {
        return {

        };
    },
    computed: {
        app_main_panel_mode() {
            return this.$store.getters.app_main_panel_mode;
        }
    },
    methods: {
    }
}
</script>

<template>
    <div style="height: 100%;">
        <div class="row no-gutters">
            <div class="app-title-bar">
                <img class="app-logo" src="/images/logo/logo.svg" alt="Chatter logo">
                <span class="app-title">CHATTER</span>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col-md-12"><app-header></app-header></div>
        </div>
        <div class="row main-app-area no-gutters">
            <div class="col-md-4"><post-list></post-list></div>
            <div class="col-md-8">
                <div v-if="app_main_panel_mode==='welcome'">
                    Welcome to chatter!  FIXME Add description of what Chatter is, that you should click a post to read it, etc....
                </div>
                <div v-if="app_main_panel_mode==='show_post'">
                    <post-display></post-display>
                </div>
                <div v-if="app_main_panel_mode==='new_post'">
                    <post-create></post-create>
                </div>
                <div v-if="app_main_panel_mode==='show_settings'">
                    FIXME settings controls here: change name/email, change email settings, get help (if help url set)
                </div>
            </div>
        </div>
    </div>
</template>
