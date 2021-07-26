<script>
import SplashPage from './SplashPage.vue';
import SettingsEditor from './SettingsEditor.vue';
import component_mixins from '../component_mixins'

export default {
    components: { SplashPage, SettingsEditor },
    mixins: [component_mixins.course_closed_mixin],
    data() {
        return {

        };
    },
    computed: {
        show_post_list() {
            return this.$store.getters.show_post_list
        },
        show_post_display() {
            return this.$store.getters.show_post_display
        },
        show_post_create() {
            return this.$store.getters.show_post_create
        }
    },
    mounted() {
        if (this.course_is_closed) {
            this.$bvModal.show('course_closed');
        }
    }
}
</script>

<template>
    <div style="height: 100%;">
        <b-modal id="course_closed" title="Chatter Closed" :ok-only="true">
            <p>Your teacher has closed this Chatter.  You can still view existing conversations.</p>
        </b-modal>
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
            <div v-if="show_post_display || show_post_create" class="col-md-8">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>
