<script>
export default {
    props: {
        comment: {
            type: Object,
            default: function () { return {}; },
        }
    },
    data() {
        return {
            this_comment: this.comment,
            show_editor: false,
        };
    },
    computed: {
        comment_is_endorsed() {
            return this.comment.endorsed;
        },
        
    },
    methods: {
        endorse(endorse_action) {
            // this.comment.endorsed = endorse_action;
            this.$store.dispatch('endorseComment', {
                comment_id: this.comment.id,
                endorsed: endorse_action
            })
        },
        toggle_comment_editor: function(action) {
            this.show_editor = action
        }
    }
}
</script>

<template>
    <div class="single-comment">
        <!-- <div 
            class="endorse-icon"
            :class="comment_is_endorsed ? 'endorsed' : 'can-endorse'">
            <font-awesome-icon icon="award" size="lg"/>
        </div> -->
        <div>
            <div style="font-style:italic;font-size:80%;">
                {{ this_comment.author_user_name }}
                {{ this_comment.created_at }}
            </div>
            <div class="row">
                <div 
                    class="endorse-icon col-1"
                    @click="endorse(!comment_is_endorsed)">
                    <font-awesome-icon 
                    :class="comment_is_endorsed ? 'endorsed' : 'can-endorse'" icon="award" size="2x" />
                </div>
                <div class="col-11">
                    <div class="comment-body">
                        {{ this_comment.body }}
                        <div 
                            class="reply-icon"
                            @click="toggle_comment_editor(!show_editor)">
                            <font-awesome-icon icon="reply" size="lg" color="#7d7d7d"/>
                        </div>
                    </div>
                    <comment-create v-if="show_editor" @close_comment_editor="  toggle_comment_editor(false)"></comment-create>
                    <div style="padding-left:20px;">
                        <div v-for="child_comment in this_comment.child_comments">
                            <comment-display :comment="child_comment"></comment-display>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
    </div>
</template>
