<script>
export default {
    data() {
        return {
            course_name: this.$store.getters.course_summary.name,
            search_term: '',
        };
    },
    methods: {
        search: function() {
            console.log("You searched for \'"+this.search_term+"\'. What should happen visually when we search?")
            this.search_term = '';
        },
        open_settings: function() {
            console.log("You opened settings.")
        }
    }
}
</script>

<template>
    <div class="app-header-bar">
        <div class="d-flex justify-content-between">
            <div>
                <form
                    class="form-inline"
                    @submit.prevent="search()"
                >
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search..."
                        autocomplete="off"
                        v-model="search_term"
                    >
                    <button
                        type="submit"
                        class="btn btn-search-submit"
                    >Search</button>
                </form>
            </div>
            <div class="course_name">{{ course_name }}</div>
            <div
                class="settings-control"
                @click="open_settings()"
            >
                <font-awesome-icon icon="cog"/>
            </div>
        </div>
    </div>
</template>
