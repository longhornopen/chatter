<script>
export default {
    data() {
        return {
            search_term: '',
        };
    },
    computed: {
        course_name() {
            return this.$store.getters.course_summary.name;
        }
    },
    methods: {
        clear_search: function() {
            // reset search term
            this.search_term = '';
            this.$store.commit('setFilteredPosts', {
                filtered_posts: [],
                search_results_available: false,
            })

        },
        search: function() {
            // console.log("You searched for \'"+this.search_term+"\'. What should happen visually when we search?")
            this.search_term = this.search_term.toLowerCase();

            const posts = this.$store.getters.course_summary.posts
            var filtered_posts_res = []

            // loop through all posts and only display the ones searched
            posts.forEach(post => {
                if ((post.title.indexOf(this.search_term) > -1) 
                    || post.body.indexOf(this.search_term) > -1) {
                    filtered_posts_res.push(post)
                }
            })
            // console.log(filtered_posts)
            // this.$store.getters.course_summary.filtered_posts = filtered_posts
            // this.$store.getters.course_summary.search_results_available = true

            this.$store.dispatch('setFilteredPosts', {
                filtered_posts: filtered_posts_res,
                search_results_available: true,
            })
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
                <div class="input-group">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search..."
                        autocomplete="off"
                        v-model="search_term"
                    >
                    <button 
                        class="btn bg-transparent clear-search-icon"
                        @click="clear_search()">
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
            <div class="course_name">{{ course_name }}</div>
            <div
                class="settings-control"
                @click="open_settings()"
            >
                <font-awesome-icon color="white" icon="cog"/>
            </div>
        </div>
    </div>
</template>
