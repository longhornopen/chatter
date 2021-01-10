<script>
import SplashPage from './SplashPage.vue';
export default {
    components: { SplashPage },
    data() {
        return {

        };
    },
    computed: {
        app_main_panel_mode() {
            return this.$store.getters.app_main_panel_mode;
        },
        show_post_list() {
            return this.$store.getters.show_post_list
        },
        show_post_display() {
            return this.$store.getters.show_post_display
        }
    },
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
            <div v-if="show_post_list" class="col-md-4"><post-list></post-list></div>
            <div v-if="show_post_display" class="col-md-8">
                {{app_main_panel_mode}}
                <div v-if="app_main_panel_mode==='welcome'">
                    <splash-page></splash-page>
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
