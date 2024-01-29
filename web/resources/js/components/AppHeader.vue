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
            post_tags.unshift({ value: null, text: "(any tag)" });
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
            this.$refs['advanced-search-modal'].show()
        },
        do_advanced_search() {
            this.search_term = this.advanced_search_text
            if (this.advanced_search_tag) {
                this.search_term += ' tag:' + this.advanced_search_tag
            }
            this.search()
            this.$refs['advanced-search-modal'].hide()
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
            <template v-slot:body>
            <settings-editor />
            </template>
        </modal>

        <modal
            id="help-modal"
            ref="help-modal"
            size="xl"
            title="Help"
            ok-only
        >
            <template v-slot:body>
            <help-viewer />
            </template>
        </modal>

        <modal
            id="advanced-search-modal"
            ref="advanced-search-modal"
            size="xl"
            title="Advanced Search"
            ok-title="Search"
            @ok="do_advanced_search()"
        >
            <template v-slot:body>
                <form>
                    <div class="mb-3">
                        <label for="advanced-search-text" class="form-label">Search Text:</label>
                        <input type="text" class="form-control" id="advanced-search-text" v-model="advanced_search_text">
                        <div id="advanced-search-group-text" class="form-text">Search for posts containing these words.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Search Tag:</label>
                        <div id="advanced-search-group-tag" class="form-text">Search for posts with these tags.</div>
                        <div class="d-flex flex-row">
                            <div class="form-check form-check-inline" v-for="tag in advanced_search_tag_options" :key="tag.value">
                                <input class="form-check-input" type="radio" :id="tag.value" :value="tag.value" v-model="advanced_search_tag">
                                <label class="form-check-label" :for="tag.value">{{tag.text}}</label>
                            </div>
                        </div>
                    </div>
                </form>
            </template>
        </modal>

        <div class="d-flex justify-content-between">
            <div class="header-col">
                <form class="row search-form" @submit.prevent="search()">
                    <div class="col-12 d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" placeholder="Search posts..."
                                autocomplete="off"
                                v-model="search_term"
                                aria-label="Search"
                            >
                            <button class="btn btn-secondary clear-search-icon"
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
                        ><font-awesome-icon color="white" icon="search"/> Search</button>

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
                    </div>
                </form>
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
    .search-form {
        flex-flow: nowrap;
        .search-input, .search-input:focus {
            background-color: $darkgray;
            border-radius: 6px;
            border: none;
            color: $white;
        }
        .search-input::placeholder {
            color: #6c757d;
            opacity: 1;
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
        .clear-search-icon {
            background-color: $darkgray;
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
    .settings-control {
        font-size: 1.5rem;
        margin-left: 5px;
        margin-right: 5px;
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
