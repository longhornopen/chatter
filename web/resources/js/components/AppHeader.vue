<script>
import HelpViewer from './HelpViewer.vue'
import SettingsEditor from './SettingsEditor.vue'
import Modal from './Modal.vue'

export default {
    components: { SettingsEditor, HelpViewer, Modal },
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
            this.$refs['settings-modal'].show()
        },
        open_help() {
            this.$refs['help-modal'].show()
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

        <modal
            id="settings-modal"
            ref="settings-modal"
            size="xl"
            title="Settings"
            ok-only
        >
            <settings-editor />
        </modal>

        <modal
            id="help-modal"
            ref="help-modal"
            size="xl"
            title="Help"
            ok-only
        >
            <help-viewer />
        </modal>

        <modal
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
        </modal>

        <div class="d-flex justify-content-between">
            <div class="header-col">
                <b-form inline
                    @submit.stop.prevent="search()"
                >
                    <b-input-group class="input-group">
                        <b-form-input
                            class="search-input"
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

<style lang="scss" scoped>
@import '../../sass/_variables.scss';

.app-header-bar {
    background-color: $bg-dark;
    padding: 10px;
    position: relative;
    .justify-content-between {
        align-items: center;
        .header-col {
            flex: 1;
        }
        .header-col.middle {
            flex: 2;
        }
        .header-col.right {
            display: flex;
            justify-content: flex-end;
        }
    }

    .settings-control:hover {
        cursor: pointer;
    }
    .course-name {
        color: $white;
        font-size: large;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        line-height: 1.2em;
    }
    .course-name.mobile {
        display: none;
    }
    @media screen and (max-width: 1077px) {
        .course-name {
            height: 70px;
        }
    }
    .form-inline {
        flex-flow: nowrap;
        .form-control, .form-control:focus {
            background-color: $darkgray;
            border-radius: 6px;
            border: none;
            color: $white;
            margin: 0 10px 0 0;
        }

        .btn-search-submit {
            background-color: $darkgray;
            color: $white;
            border: none;
            border-radius: 6px;
            margin-right: 6px;
            white-space: nowrap;
        }
        .btn-search-submit:hover {
            background-color: $primary;
        }
    }
    .settings-control {
        font-size: 1.5rem;
        margin-left: 5px;
        margin-right: 5px;
    }
    .clear-search-icon {
        display: flex;
        align-items: center;
        margin-left: -50px;
        z-index: 100;
        box-shadow: none;
        border: 0;
        .times-icon {
            color: $bg-dark;
        }
    }
    .clear-search-icon:focus {
        box-shadow: none;
    }
}

@media screen and (max-width: 1077px) {
    .app-header-bar {
        display: flex;
        flex-direction: column-reverse;
        .course-name.mobile {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 10px 10px;
        }
        .header-col.middle {
            display: none;
        }
    }
}

@media screen and (max-width: 575px) {
    .app-header-bar {
        .input-group {
            width: auto;
        }
    }
}
</style>
