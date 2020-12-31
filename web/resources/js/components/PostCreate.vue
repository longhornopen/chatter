<script>
export default {
    data() {
        return {
            'post_title': '',
            'post_body': '',
        };
    },
    methods: {
        submit_new_post: function() {
            console.log("new post submitted")
            console.log("post title is " + this.post_title)
        },
        close_post_editor: function() {
            this.$emit('close_post_editor')
        }
    }
}
</script>

<template>
    <div class="new-post">
        <!-- <form> -->
            <div class="form-group">
                <label for="post-title">Post Title</label>
                <input class="form-control" id="post-title" v-model="post_title">
            </div>
            <div class="form-group">
                <label for="post-body">Post Body</label>
                <!-- placeholder for wysiwyg editor -->
                <textarea 
                    class="form-control" 
                    style="width:100%"
                    placeholder="This is a placeholder for WYSIWYG editor"></textarea>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="postAnonymously">
                <label class="form-check-label" for="postAnonymously">Post Anonymously</label>
            </div>
            <div class="editor-btn-group">
                <button 
                    class="btn btn-gray"
                    @click="close_post_editor()">Cancel</button>
                <button 
                    class="btn btn-blue"
                    @click="submit_new_post()">Post</button>
            </div>
            
        <!-- </form> -->
        
    </div>
    
</template>
