<script>
export default {
    data() {
        return {
        };
    },
    computed: {
        course_name() {
            return this.$store.getters.course_summary.name;
        },
        search_term: {
            get() {
                return this.$store.state.search_string
            },
            set(value) {
                this.$store.dispatch('setSearchString', {search_string: value});
            }
        }
    },
    methods: {
        clear_search() {
            this.search().then(() => {
                this.search_term = '';
            })
        },
        search() {
            this.$store.dispatch('search');
        },
        open_settings() {
            this.$store.dispatch('setAppMainPanelMode', {mode: 'show_settings'});
        }
    }
}
</script>

<template>
    <div class="app-header-bar">
        <div class="d-flex justify-content-between">
            <div class="header-col">
                <form
                    class="form-inline"
                    @submit.prevent="search()"
                >
                    <div class="input-group">
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Search..."
                            autocomplete="off"
                            v-model="search_term"
                            aria-label="Search"
                        >
                        <button
                            class="btn bg-transparent clear-search-icon"
                            @click="clear_search()"
                            aria-label="Clear">
                            <font-awesome-icon
                                class="times-icon"
                                icon="times-circle"
                                size="lg"/>
                        </button>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-search-submit"
                    >Search</button>
                </form>
            </div>
            <div class="header-col middle">
                <div class="course-name">{{ course_name }}</div>
            </div>
            <div class="header-col right">
                <div
                    class="settings-control"
                    @click="open_settings()"
                >
                    <font-awesome-icon color="white" icon="cog"/>
                </div>
            </div>
        </div>
        <div class="course-name mobile">{{ course_name }}</div>
       
    </div>
</template>
