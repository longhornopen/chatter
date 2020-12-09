<script>
export default {
    data() {
        return {
            //
        };
    }
}
</script>

<template>
    <div>
        <div class="d-flex justify-content-between">
            <div>Topics</div>
            <div>Sort by <select><option>pinned</option><option>newest</option><option>unread</option></select></div>
            <div><button class="btn btn-secondary">Add Topic</button></div>
        </div>
        <hr>
        <div style="margin-bottom:10px;">
            <div style="font-size:80%;">Chris <i>12/08/2020 11:14 AM</i> <span class="badge badge-danger" title="2 unread posts">2</span><span class="badge badge-secondary" title="17 total posts">17</span></div>
            <div>What is Chatter?</div>
        </div>
        <div style="margin-bottom:10px;">
            <div style="font-size:80%;">Chris <i>12/08/2020 11:17 AM</i> <span class="badge badge-secondary" title="3 total posts">3</span></div>
            <div>Discussion Tools</div>
        </div>
        <div style="margin-bottom:10px;">
            <div style="font-size:80%;">Chris <i>12/08/2020 11:21 AM</i> <span class="badge badge-danger" title="1 unread posts">1</span><span class="badge badge-secondary" title="2 total posts">2</span></div>
            <div>Who wants to talk?</div>
        </div>
    </div>
</template>
