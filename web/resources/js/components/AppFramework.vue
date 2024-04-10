<script>
import AppHeader from './AppHeader.vue';
import PostList from './PostList.vue';
import Modal from './Modal.vue';
import component_mixins from '../component_mixins'

export default {
    components: { AppHeader, PostList, Modal },
    mixins: [component_mixins.course_closed_mixin],
    data() {
        return {

        };
    },
    methods: {
        alert_stale() {
            this.$refs['course_stale_modal'].show();
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
            this.$refs['course_closed_modal'].show();
        }
        let alert_stale_minutes = 60;
        setTimeout(() => this.alert_stale(), alert_stale_minutes*60*1000);
    }
}
</script>

<template>
    <div class="app-framework-container">
        <modal id="course_closed" ref="course_closed_modal" title="Chatter Closed" :ok-only="true">
            <template v-slot:body>
            <p>Your teacher has closed this Chatter.  You can still view existing conversations.</p>
            </template>
        </modal>
        <modal id="course_stale" ref="course_stale_modal" title="Refresh?">
            <template v-slot:body>
            <p>Chatter has been open for a while, and you may not be looking at the most recent posts.  Click "OK" to refresh.</p>
            </template>
            <template v-slot:footer>
                <button class="btn btn-primary" @click="handle_stale_ok()">Ok</button>
            </template>
        </modal>
        <div class="g-0">
            <div class="app-title-bar">
                <img class="app-logo" src="/images/logo/logo.svg" alt="Chatter logo">
                <span class="app-title">CHATTER</span>
            </div>
        </div>
        <div class="g-0">
            <div class="col-md-12"><app-header></app-header></div>
        </div>
        <div class="main-app-area g-0">
            <div v-if="show_post_list" class="post-list-area"><post-list></post-list></div>
            <div v-if="show_post_display || show_post_create" class="post-display-area">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

.app-framework-container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow-y: hidden;
}

.main-app-area {
    flex: 1;
    display: flex;
    overflow-y: hidden;
}

.post-list-area {
    height: 100%;
    width: 33.333%;
    overflow: hidden;
}

@media screen and (max-width: 1077px) {
    .post-list-area{
        width: 100%;
    }
}

.post-display-area {
    height: 100%;
    overflow-y: scroll;
    flex: 1;
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
    padding: 10px 20px;
    display: flex;
    align-items: center;
}
.app-title-bar.large {
    margin: 10% 5% 30px 5%;
}

</style>
