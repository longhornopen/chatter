<script>
import { useMainStore } from '@/store'

export default {
    setup() {
        const store = useMainStore()
        return { store }
    },
    props: ['name', 'role', 'anonymous', 'userId'],
    data() {
        return {
            temp_shown: false,
            deanon_user_name: "",
        };
    },
    computed: {
        show_deanon_button() {
            return this.anonymous && this.store.user.role==='teacher';
        },
        should_show_teacher_icon() {
            return !this.anonymous && this.role==='teacher'
        },
        display_user_name() {
            return this.anonymous ? "Anonymous" : this.name;
        },
        deanon_label() {
            return this.temp_shown ? this.deanon_user_name : "";
        }
    },
    methods: {
        async handle_deanon_click() {
            let user_info = await this.store.deanonUserId( {
                user_id: this.userId,
            })
            this.deanon_user_name = user_info.name
            this.temp_shown = true
            window.setTimeout(() => {
                this.temp_shown = false;
                this.deanon_user_name = ""
            }, 3000);
        }
    }
}
</script>

<template>
    <span style="margin-right:7px;">
        <span v-if="!should_show_teacher_icon">{{display_user_name}}</span>
        <span v-if="should_show_teacher_icon"
              class="teacher-icon"
              title="This is an instructor"
        >
            <font-awesome-icon icon="chalkboard-teacher"/>&nbsp;{{display_user_name}}
        </span>

        <span class="anon" v-if="show_deanon_button"
              title="Click to show this person's name"
              @click.prevent="handle_deanon_click"
        >
            <font-awesome-icon
                icon="caret-square-right"
                class="icon"
                size="lg"
                v-if="this.temp_shown === false"/>
            <span :class="this.deanon_user_name === '' ? 'd-none' : 'deanon-label'">{{ deanon_label }}</span>
        </span>

    </span>
</template>

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

.teacher-icon {
    background-color: #b5c1c9;
    border-radius: 3px;
    padding: 3px;
}

.anon {
    .icon {
        margin: 0 2px;
        color: $tertiary;
        cursor: pointer;
    }
    .deanon-label {
        background-color: $tertiary;
        color: white;
        padding: 0 5px;
        border-radius: 2px;
    }
    .invisible {
        background-color: $white;
    }
}
</style>
