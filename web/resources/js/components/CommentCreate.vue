<script>
export default {
    data() {
        return {
            //
        };
    },
    methods: {
        close_comment_editor: function() {
            this.$emit('close_comment_editor')
        }
    }
}
</script>

<template>
    <div>
        <!-- placeholder for wysiwyg editor -->
        <textarea style="width:100%"></textarea>
        <div class="editor-btn-group">
            <button class="btn btn-gray" @click="close_comment_editor()">Cancel</button>
            <button class="btn btn-orange">Submit</button>
        </div>
    </div>
    
</template>
