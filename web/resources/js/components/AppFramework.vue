<script>
export default {
    data() {
        return {
            show_new_post: false,
        };
    },
    methods: {
        toggle_post_editor: function(action) {
            // console.log(this.show_new_post)
            this.show_new_post = action;
        }
    }
}
</script>

<template>
    <div>
        <div class="row">
            <div class="col-md-12 app-title-bar">
                <img class="app-logo" src="/images/logo/logo.svg" alt="Chatter logo">
                <span class="app-title">CHATTER</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"><app-header></app-header></div>
        </div>
        <div class="row">
            <div class="col-md-4"><post-list @open_post_editor="toggle_post_editor(true)"></post-list></div>
            <div class="col-md-8" v-if="!show_new_post"><post-display ></post-display></div>
            <div class="col-md-8" v-if="show_new_post" ><post-create @close_post_editor="toggle_post_editor(false)"></post-create></div>
        </div>
    </div>
</template>
