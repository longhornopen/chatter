<script>
import HelpViewer from './HelpViewer'
import SettingsEditor from './SettingsEditor'

export default {
    components: { SettingsEditor, HelpViewer },
    data() {
        return {
            search_term: "",
            advanced_search_text: "",
            advanced_search_tag: null,
        };
    },
    computed: {
        course_name() {
            return this.$store.getters.course_summary.name
        },
        advanced_search_tag_options() {
            let post_tags = this.$store.getters.course_summary.post_tags
                .map(t => {return { value: t.name, text: t.name }})
            post_tags.unshift({ value: null, text: '(any tag)' })
            return post_tags
        },
    },
    methods: {
        clear_search() {
            this.search_term = ''
            this.search()
        },
        search() {
            this.$store.dispatch('setSearchString', {search_string: this.search_term})
            this.$store.dispatch('search')
        },
        open_settings() {
            this.$bvModal.show('settings-modal')
        },
        open_help() {
            this.$bvModal.show('help-modal')
        },
        open_advanced_search() {
            this.advanced_search_text = ""
            this.advanced_search_tag = null
            this.$bvModal.show('advanced-search-modal')
        },
        do_advanced_search() {
            this.search_term = this.advanced_search_text
            if (this.advanced_search_tag) {
                this.search_term += ' tag:' + this.advanced_search_tag
            }
            this.search()
            this.$bvModal.hide('advanced-search-modal')
        }
    }
}
</script>

<template>
    <div class="app-header-bar">

        <b-modal
            id="settings-modal"
            size="xl"
            title="Settings"
            ok-only
        >
            <settings-editor />
        </b-modal>

        <b-modal
            id="help-modal"
            size="xl"
            title="Help"
            ok-only
        >
            <help-viewer />
        </b-modal>

        <b-modal
            id="advanced-search-modal"
            size="xl"
            title="Advanced Search"
            ok-title="Search"
            @ok="do_advanced_search()"
        >
            <b-form>
                <b-form-group
                    id="advanced-search-group-text"
                    label="Search Text:"
                    description="Search for posts containing these words."
                >
                    <b-form-input
                        id="advanced-search-text"
                        v-model="advanced_search_text"
                    ></b-form-input>
                </b-form-group>
                <b-form-group
                    label="Search Tag:"
                    description="Search for posts with these tags."
                >
                    <b-form-radio-group
                        id="advanced-search-group-tag"
                    >
                        <b-form-radio-group
                            id="advanced-search-tag"
                            v-model="advanced_search_tag"
                            :options="advanced_search_tag_options"
                        ></b-form-radio-group>
                    </b-form-radio-group>
                </b-form-group>
            </b-form>
        </b-modal>

        <div class="d-flex justify-content-between">
            <div class="header-col">
                <b-form inline
                    @submit.stop.prevent="search()"
                >
                    <b-input-group class="input-group">
                        <b-form-input
                            placeholder="Search posts..."
                            autocomplete="off"
                            v-model="search_term"
                            aria-label="Search"
                        ></b-form-input>
                        <b-input-group-append>
                        <b-button
                            class="bg-transparent clear-search-icon"
                            @click="clear_search()"
                            aria-label="Clear">
                            <font-awesome-icon
                                class="times-icon"
                                icon="times-circle"
                                size="lg"/>
                        </b-button>
                        </b-input-group-append>
                    </b-input-group>

                    <b-button
                        type="submit"
                        class="btn-search-submit"
                    ><font-awesome-icon color="white" icon="search"/> Search</b-button>

                    <button
                        class="btn btn-search-submit"
                        title="Advanced Search"
                        aria-label="Advanced Search"
                        @click="open_advanced_search()"
                    >
                        <font-awesome-icon
                            icon="search-plus"
                        />
                    </button>
                </b-form>
            </div>
            <div class="header-col middle">
                <div class="course-name">{{ course_name }}</div>
            </div>
            <div class="header-col right">
                <div
                    class="settings-control"
                    @click="open_settings()"
                    title="Settings"
                >
                    <font-awesome-icon color="white" icon="cog"/>
                </div>
                <div
                    class="settings-control"
                    @click="open_help()"
                    title="Help"
                >
                    <font-awesome-icon color="white" icon="question"/>
                </div>
            </div>
        </div>
        <div class="course-name mobile">{{ course_name }}</div>

    </div>
</template>
