<script>
import SplashPage from './SplashPage.vue';
import SettingsEditor from './SettingsEditor.vue';
import AppHeader from './AppHeader.vue';
import PostList from './PostList.vue';
import Modal from './Modal.vue';
import component_mixins from '../component_mixins'

export default {
    components: { SplashPage, SettingsEditor, AppHeader, PostList, Modal },
    mixins: [component_mixins.course_closed_mixin],
    data() {
        return {

        };
    },
    methods: {
        alert_stale() {
            this.$bvModal.show('course_stale');
        },
        handle_stale_ok() {
            let homepage = new URL(document.location.href);
            homepage.hash = "";
            document.location.href = homepage.href;
        }
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
        let alert_stale_minutes = 60;
        setTimeout(() => this.alert_stale(), alert_stale_minutes*60*1000);
    }
}
</script>

<template>
    <div style="height: 100%;">
        <modal id="course_closed" title="Chatter Closed" :ok-only="true">
            <p>Your teacher has closed this Chatter.  You can still view existing conversations.</p>
        </modal>
        <modal id="course_stale" title="Refresh?" @ok="handle_stale_ok">
            <p>Chatter has been open for a while, and you may not be looking at the most recent posts.  Click "OK" to refresh.</p>
        </modal>
        <div class="row g-0">
            <div class="app-title-bar">
                <img class="app-logo" src="/images/logo/logo.svg" alt="Chatter logo">
                <span class="app-title">CHATTER</span>
            </div>
        </div>
        <div class="row g-0">
            <div class="col-md-12"><app-header></app-header></div>
        </div>
        <div class="row main-app-area g-0">
            <div v-if="show_post_list" class="col-md-4"><post-list></post-list></div>
            <div v-if="show_post_display || show_post_create" class="col-md-8">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

.main-app-area {
    height: calc(#{$full-height} - #{$title-bar-height} - map-get($app-header-height, 1920));
    .col-md-4 {
        padding-right: 0;
    }
    .col-md-8 {
        padding-left: 0;
    }
}

.app-title-bar {
    .app-logo {
        width: $app-logo-size;
        height: $app-logo-size;
        margin: 0 10px 0 0;
    }
    .app-logo.large {
        width: $app-logo-size-large;
        height: $app-logo-size-large;
        margin: 0 20px 0 0;
    }

    .app-title {
        font-size: x-large;
        font-weight: 900;
    }
    .app-title.large {
        font-size: xxx-large;
    }
    margin: 10px 20px;
    display: flex;
    align-items: center;
}
.app-title-bar.large {
    margin: 10% 5% 30px 5%;
}

</style>
